<?php

namespace EmailQueue\Lib;

use Cake\Core\Configure;
use Cake\Network\Email\Email;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Cake\Log\Log;

class EmailQueueManager
{

    /**
     * List of usable email variables
     *
     * This is identical to Entity::$_accessible with the exception that there is no '*' (wildcard) allowed
     * @var array
     */
    private $_accessible = [
        'from' => true,         #: Email or array of sender. See Email::from().
        'sender' => true,       #: Email or array of real sender. See Email::sender().
        'to' => true,           #: Email or array of destination. See Email::to().
        'cc' => true,           #: Email or array of carbon copy. See Email::cc().
        'bcc' => true,          #: Email or array of blind carbon copy. See Email::bcc().
        'replyTo' => true,      #: Email or array to reply the e-mail. See Email::replyTo().
        'readReceipt' => true,  #: Email address or an array of addresses to receive the receipt of read. See Email::readReceipt().
        'returnPath' => true,   #: Email address or and array of addresses to return if have some error. See Email::returnPath().
        'messageId' => true,    #: Message ID of e-mail. See Email::messageId().
        'subject' => true,      #: Subject of the message. See Email::subject().
        'message' => false,     #: Content of message. Do not set this field if you are using rendered content.
        'headers' => true,      #: Headers to be included. See Email::setHeaders().
        'viewRender' => true,   #: If you are using rendered content, set the view classname. See Email::viewRender().
        'template' => true,     #: If you are using rendered content, set the template name. See Email::template().
        'theme' => true,        #: Theme used when rendering template. See Email::theme().
        'layout' => true,       #: If you are using rendered content, set the layout to render. If you want to render a template without layout, set this field to null. See Email::template().
        'viewVars' => true,     #: If you are using rendered content, set the array with variables to be used in the view. See Email::viewVars().
        'attachments' => true,  #: List of files to attach. See Email::attachments().
        'emailFormat' => true,  #: Format of email (html, text or both). See Email::emailFormat().
        'transport' => true,    #: Transport configuration name. See Network\Email\Email::configTransport().
        'log' => true,          #: Log level to log the email headers and message. true will use LOG_DEBUG. See also CakeLog::write()
        'helpers' => true,      #: Array of helpers used in the email template.
        ];

    /**
     * Used to re-map configuration keys into the parameters for \Network\Email\Email::profile(..)
     *
     * E.g. we need to remap 'to_addr' (from the config) into 'to' (the Email class function) because `to` is a reserved word in MySQL so we can't use it as the database column
     * @var array
     */
    private $_map = [
        'to_addr' => 'to',      # change key 'to_addr' to 'to'
        'cc_addr' => 'cc',      # change key 'cc_addr' to 'cc'
        'bcc_addr' => 'bcc',    # change key 'bcc_addr' to 'bcc'
        ];

    /**
     * Constructor - sets up the Manager
     */
    public function __construct()
    {
        # Get the EmailQueues table
        $this->EmailQueue = TableRegistry::get('EmailQueue.EmailQueues');
    }


    /**
     * Queues and email for delivery by storing it in the database for processing
     * @param array $vars array of key => value pairs accepted by the EmailQueue entity objects
     * @return mixed results of save operation
     */
    public function add($vars)
    {
        $email = $this->EmailQueue->newEntity($vars);
        $this->EmailQueue->save($email);
        return $email;
    }

    /**
     * QuickAdd function that accepts basic params; email type, to address, and an array of view variables needed by the email template function
     * @param string $type   email type as defined in the SPECIFIC configuration array
     * @param string $to_addr     email address of recipient
     * @param array $viewVars array of viewVars expected by the email $type's template (as specified in configuration file)
     *
     * @return void
     */
    public function quickAdd($type, $to_addr, $viewVars)
    {
        return $this->add(compact('type', 'to_addr', 'viewVars') + ['status'=>'pending']);
    }

    /**
     * Looks through the database and sends all the emails that need to be sent
     *
     * Emails are built by combining all the configuration settings from the plugin config file and the settings in the email table itself
     * See the hash::merge line for the order in which the configuration settings are implemented
     *
     * Once sent, emails will either be removed or marked 'sent' based on the master configuration setting `deleteAfterSend`
     *
     * @return array of results from each email send
     */
    public function process($options = [])
    {
        $result = [];

        # find all the emails we need to send
        $emails = $this->EmailQueue->find();

        # apply filters if set
        if (isset($options['limit'])) :
            $emails->limit($options['limit']);
        endif;
        if (isset($options['type'])) :
            $emails->where(['EmailQueues.type' => $options['type']]);
        endif;
        if (isset($options['status'])) :
            $emails->where(['EmailQueues.status' => $options['status']]);
        endif;
        if (isset($options['id'])) :
            $emails->where(['EmailQueues.id' => $options['id']]);
        endif;

        # build and send each email
        foreach ($emails as $email) :
            # get the config settings for this email
            $config = $this->_getConfig($email);

            # build and send the email
            $e = new Email('default');

            # set the profile() by merging the $config'd settings with what we've deemed $_accessible (similar to Entity::$_accessible, ie. where [.., 'property' => true, ..])
            $e->profile(array_intersect_key($config, array_filter($this->_accessible)));

            try {
                # now that the email is built, send it
                $e->send();
                $email->status = 'sent';
                $email->sent_on = date('Y-m-d H:i:s');

            } catch (\Exception $e) {
                Log::error($e->getMessage());
                $email->status = 'failed';
                $email->error = $e->getMessage();
            }

            # either delete or update the email depending on Configure::read(EmailQueue.master.deleteAfterSend)
            if ($config['deleteAfterSend'] && $email->status === 'sent') :
                $this->EmailQueue->delete($email);
            else :
                $this->EmailQueue->save($email);
            endif;

            $result[] = $email;
                
        endforeach;

        # return the results from each email
        return $result;
    }

    /**
     * Builds a complete set of configuration settings by reading in several config arrays and merging them according to priority
     *
     * @param Entity $email email entity
     * @return array complete configuration array
     */
    protected function _getConfig($email)
    {
        # get the master configuration details for the plugin itself
        $master = Configure::read('EmailQueue.master');

        # get the specific details for the email.type
        $specific = Configure::read('EmailQueue.specific.'.$email->type);
        
        # get the default email settings
        $default = Configure::read('EmailQueue.default');

        # if in `testingModeOverride` then load the override settings, otherwise just use a blank array (will have no effect)
        $override = ($master['testingModeOverride']) ? Configure::read('EmailQueue.override') : [];

        # merge all the configs into one final complete array
        $config = Hash::merge($default, $specific, $email->toArray(), $override, $master);

        return $this->_formatConfig($config);

    }

    /**
     * Applies required format modifications to the config array
     * @param  array $config configuration array
     * @return array         formatted configuration array
     */
    private function _formatConfig($config)
    {
        # remap keys (if any)
        $config = $this->_remapKeys($config);

        return $config;
    }

    /**
     * Re-maps the configuration keys based on $this::_map
     *
     * This was added because the \Network\Email\Email::profile needs to set `to` which is a reserved word in MySQL. So we store it as `to_addr` in the database and re-map it as `to` before we try and use it instead of dealing with the overhead of quoting all the SQL statements
     * @param  array $config configuration array
     * @return array formatted configuration array
     */
    private function _remapKeys($config)
    {
        # remap keys
        foreach ($this->_map as $key => $remap) :
            if (isset($config[$key])) :
                $config[$remap] = $config[$key];
                unset($config[$key]);
            endif;
        endforeach;

        return $config;
    }
}
