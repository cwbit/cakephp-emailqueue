<?php

namespace EmailQueue\Lib;

use Cake\Core\Configure;
use Cake\Datasource\Exception\RecordNotFoundException;
use EmailQueue\Model\Entity\EmailQueue;
use Cake\Log\Log;
use Cake\Network\Email\Email;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;

class EmailQueueManager
{

    const STATUS_SENT = 'sent';
    const STATUS_ERROR = 'error';
    const STATUS_PENDING = 'pending';

    /**
     * List of usable email variables for Email::profile()
     *
     * This is identical to Entity::$_profileKeys with the exception that there is no '*' (wildcard) allowed
     * @var array
     */
    private $_profileKeys = [
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
        'message' => false,     # NOT SUPPORTED  #: Content of message. Do not set this field if you are using rendered content.
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
        'from_addr' => 'from',
        'sender_addr' => 'sender',
        ];

    /**
     * `_config*` hold the configuration arrays for the App settings
     * _configDefault holds the default settings to be applied to all emails at (lowest priority)
     * _configOverride holds override settings that override everything except master to allow for DEV env testing (2nd highest priority)
     * _configMaster holds the master config settings for the entire plugin (highest priority)
     * @var array
     */
    protected $_configDefault = [];
    protected $_configMaster = [];
    protected $_configOverride = [];

    /**
     * Constructor - sets up the Manager
     * Loads the Tables and reads the global configuration data (nonce)
     */
    public function __construct()
    {
        # Get the EmailQueues table
        $this->EmailQueues = TableRegistry::get('EmailQueue.EmailQueues');
        $this->EmailTemplates = TableRegistry::get('EmailQueue.EmailTemplates');
        $this->EmailLogs = TableRegistry::get('EmailQueue.EmailLogs');

        # load in the config data
        $this->_configMaster = Configure::read('EmailQueue.master');
        $this->_configDefault = Configure::read('EmailQueue.default');
        $this->_configOverride = ($this->_configMaster['testingModeOverride']) ? Configure::read('EmailQueue.override') : [];

    }

    /**
     * Queues an email for delivery by storing it in the database for processing
     * @param array $vars array of key => value pairs accepted by the EmailQueue entity objects
     * @return mixed results of save operation
     */
    public function add(array $vars)
    {
        $email = $this->EmailQueues->newEntity($vars);
        $this->EmailQueues->save($email);
        return $email;
    }

    /**
     * QuickAdd function that accepts basic params; email type, to address, and an array of view variables needed by the email template function
     * @param string $type   email type as defined in the SPECIFIC configuration array
     * @param mixed $to_addr     email address of recipient
     * @param array $viewVars array of viewVars expected by the email $type's template (as specified in configuration file)
     *
     * @return void
     */
    public function quickAdd($type, $to_addr, array $viewVars)
    {
        return $this->add(compact('type', 'to_addr', 'viewVars') + ['status'=>self::STATUS_PENDING]);
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
    public function process(array $options = [])
    {
        $result = [];

        # find all the emails we need to send
        $emails = $this->EmailQueues->find();

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
            $config = $this->_getConfig($email);
            $profile = $this->_buildProfile($config, $email);
            $e = new Email($profile);

            # attempt to send the email
            try {
                # try and catch errors during transmission
                set_error_handler(function ($errno, $errstr, $errfile, $errline) {
                    throw new \ErrorException($errstr, $errno, 0, $errfile, $errline);
                });

                # now that the email is built, send it
                $e->send();
                $email->status = self::STATUS_SENT;
                $email->sent_on = date('Y-m-d H:i:s');

                restore_error_handler();

            } catch (\Exception $e) {
                Log::error($e->getMessage());
                $email->status = self::STATUS_ERROR;
                $email->error = $e->getMessage();
            }

            # log it - include as much relevant data as possible
            $e = $e->jsonSerialize();
            $log = $this->EmailLogs->newEntity([
              'email_id' => $email->id,
              'email_type' => $email->type,
              'email_data' => [
                'email' => $e,
                'config' => $config,
                'profile' => $email->toArray(),
              ],
              'sent_to' => $e['_to'],
              'sent_from' => $e['_from'],
              'sent_on' => $email->sent_on ?: null,
              'processed_on' => new \DateTime(),
              'status' => $email->status,
              'status_message' => $email->error,
            ]);
            $this->EmailLogs->save($log);

            # either delete or update the email depending on Configure::read(EmailQueue.master.deleteAfterSend)
            if ($config['deleteAfterSend'] && $email->status === self::STATUS_SENT) :
                $this->EmailQueues->delete($email);
            else :
                $this->EmailQueues->save($email);
            endif;

            $result[] = $email;

        endforeach;

        # return the results from each email
        return $result;
    }

    /**
     * Convert the configuration settings into something ingestible by Email::profile()
     *
     * @param array $config array of config values to be built and processed into an Email profile
     * @return array config array ready to be injected into Email::profile()
     */
    protected function _buildProfile(array $config)
    {
        # if `message_html` or `message_text` are set (in EmailTemplate), stuff them into viewVars with `_` (prefix) to avoid collisions
        foreach (['message_html', 'message_text'] as $message_type) :
          if (isset($config[$message_type]) && !is_null($config[$message_type])) :
              $config['viewVars']["_{$message_type}"] = $config[$message_type];
          endif;
        endforeach;

        # convert the processor list to an array if not already
        $config['processor'] = (is_string($config['processor'])) ? [$config['processor']] : $config['processor'];

        # load the processors (in order) and run the config thru them - SUPPORTS new BlahProcessor, or new BlahProcessor($settings)
        foreach ($config['processor'] as $processor => $settings) :
          $processor = (is_string($processor)) ? new $processor($settings) : new $settings;
          $processor->process($config);
        endforeach;

        # finally, pare down to values we can pass to Email::profile()
        $config = array_intersect_key($config, array_filter($this->_profileKeys));

        return $config;
    }

    /**
     * Builds a complete set of configuration settings by reading in several config arrays and merging them according to priority
     * This works by loading the configuration settings first for all emails, then the specific email type, then the email settings itself, and finally giving the testing-mode override and master settings the highest priority
     * This function supports the deprecated style of declaring the 'specific' email type settings in the Config array, but the preferred method is through an EmailTemplate
     * @param Entity $email email entity
     * @return array complete configuration array
     */
    protected function _getConfig(EmailQueue $email)
    {

        # get the specific details for the email.type
        # either from the config file (for backward-compat) or the database
        if (Configure::check("EmailQueue.specific.{$email->type}")) :
          $specific = Configure::read('EmailQueue.specific.'.$email->type); # DEPRECATED
        else :
          $specific = $this->EmailTemplates->find()->where(['EmailTemplates.email_type' => $email->type])->first();
          if (!$specific) :
            throw new RecordNotFoundException("Cannot find config template for EmailQueue type '{$email->type}' anywhere.");
          endif;
          $specific = $specific->toArray();
        endif;

        # merge all the configs into one final complete array
        $config = Hash::merge($this->_configDefault, $specific, $email->toArray(), $this->_configOverride, $this->_configMaster);

        # return a useable config array
        return $this->_formatConfig($config);

    }

    /**
     * Applies required format modifications to the config array
     * @param  array $config configuration array
     * @return array         formatted configuration array
     */
    private function _formatConfig(array $config)
    {
        $config = $this->_remapKeys($config);
        return $config;
    }

    /**
     * Re-maps the configuration keys based on $self::_map
     *
     * This was added because the \Network\Email\Email::profile needs to set `to` which is a reserved word in MySQL. So we store it as `to_addr` in the database and re-map it as `to` before we try and use it instead of dealing with the overhead of quoting all the SQL statements
     * @param  array $config configuration array
     * @return array formatted configuration array
     */
    private function _remapKeys(array $config)
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
