<?php

namespace EmailQueue\Controller\Component;

use Cake\Core\Configure;
use Cake\Controller\Component;
use Cake\Network\Email\Email;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Cake\Log\Log;

class EmailQueueComponent extends Component{

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
     * Queues and email for delivery by storing it in the 
     * @param string $type   email type as defined in the SPECIFIC configuration array
     * @param string $to_addr     email address of recipient
     * @param array $viewVars array of viewVars expected by the email $type's template (as specified in configuration file)
     * 
     * @return void
     */
    public function add($type, $to_addr, $viewVars){
        $this->EmailQueue = TableRegistry::get('EmailQueue.EmailQueues');
        $email = $this->EmailQueue->newEntity(compact('type', 'to_addr', 'viewVars'));
        return $this->EmailQueue->save($email);
    }

    /**
     * Looks through the database and sends all the emails that need to be sent
     * 
     * Emails are built by combining all the configuration settings from the plugin config file and the settings in the email table itself
     * See the hash::merge line for the order in which the configuration settings are implemented
     * 
     * Once sent, emails will either be removed or marked 'sent' based on the master configuration setting `deleteAfterSend`
     * 
     * @return void
     */
    public function process(){
        $result = [];

        # Get the EmailQueues table
        $this->EmailQueue = TableRegistry::get('EmailQueue.EmailQueues');
        
        # find all the emails we need to send
        $emails = $this->EmailQueue
                            ->find('pending')
                            ->all();

        # build and send each email
        foreach ($emails as $email):
            try {
                # get the config settings for this email
                $config = $this->_getConfig($email);

                # build and send the email
                $e = new Email('default');

                # set the profile() by merging the $config'd settings with what we've deemed $_accessible (similar to Entity::$_accessible, ie. where [.., 'property' => true, ..])
                $e->profile(array_intersect_key($config, array_filter($this->_accessible)));

                # now that the email is built, send it
                $result[] = $e->send();

                # if we want to remove the email after it's sent, do so
                if ($config['deleteAfterSend']):
                    $this->EmailQueue->delete($email);

                # otherwise, just mark it sent and leave it there
                else:
                    $email->status = 'sent';
                    $email->sent_on = date('Y-m-d H:i:s');
                    $this->EmailQueue->save($email);
                endif;
                
            } catch (Exception $e) {
                Log::error($e->getMessage());
                continue;
            }
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
    protected function _getConfig($email){
        # get the master configuration details for the plugin itself
        $master = Configure::read('EmailQueue.master');

        # get the specific details for the email.type
        $specific = Configure::read('EmailQueue.specific.'.$email->type);
        
        # get the default email settings            
        $default = Configure::read('EmailQueue.default');

        # if in `testingModeOverride` then load the override settings, otherwise just use a blank array (will have no effect)
        $override = ($master['testingModeOverride']) ? Configure::read('EmailQueue.override') : [];
     
        # merge all the configs into one final complete array
        return Hash::merge($default, $specific, $email->toArray(), $override);
    }
}