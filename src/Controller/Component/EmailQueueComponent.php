<?php

namespace EmailQueue\Controller\Component;

use Cake\Core\Configure;
use Cake\Controller\Component;
use Cake\Network\Email\Email;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;

class EmailQueueComponent extends Component{
    
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

        $this->EmailQueue = TableRegistry::get('EmailQueue.EmailQueues');
        
        # find all the emails we need to send
        $emails = $this->EmailQueue
                            ->find('pending')
                            ->all();

        foreach ($emails as $email):

            # get the config settings for this email
            $config = $this->_getConfig($email);

            # build and send the email
            $e = new Email('default');
            $result[] = $e->template($config['template'], $config['layout'])
                ->emailFormat($config['emailFormat'])
                ->viewVars($config['viewVars'])
                ->from($config['from'])
                ->to($config['to_addr'])
                ->cc($config['cc_addr'])
                ->subject($config['subject'])
                ->send();

            # if we want to remove the email after it's sent, do so
            if ($master['deleteAfterSend']):
                $this->EmailQueue->delete($email);

            # otherwise, just mark it sent and leave it there
            else:
                $email->status = 'sent';
                $email->sent_on = date('Y-m-d H:i:s');
                $this->EmailQueue->save($email);
            endif;
        endforeach;

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