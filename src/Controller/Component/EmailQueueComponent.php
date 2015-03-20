<?php

namespace EmailQueue\Controller\Component;

use Cake\ORM\TableRegistry;
use Cake\Network\Email\Email;
use Cake\Core\Configure;
use Cake\Controller\Component;

class EmailQueueComponent extends Component{
    

    /**
     * Queues and email for delivery by storing it in the 
     * @param string $type   email type as defined in the SPECIFIC configuration array
     * @param string $to     email address of recipient
     * @param array $viewVars array of viewVars expected by the email $type's template (as specified in configuration file)
     */
    public function add($type, $to, $viewVars){
        $viewVars = json_encode($viewVars);

        $this->EmailQueue = TableRegistry::get('Emailqueue');
        $email = $this->EmailQueue->newEntity(compact('type', 'to', 'viewVars'));
        $this->EmailQueue->save($email);
    }


    // public function add_email_entry($data){
    //     $this->EmailQueue = TableRegistry::get('Emailqueue');
    //     $this->EmailQueue_data = $this->EmailQueue->newEntity();
    //     $this->EmailQueue_data = $this->EmailQueue->patchEntity($this->EmailQueue_data, $data);
    //     $this->EmailQueue->save($this->EmailQueue_data);

    // }
    
    public function cron_emails(){
        $this->EmailQueue = TableRegistry::get('Emailqueue');
        
        # find all the emails we need to send
        $emails = $this->EmailQueue
                            ->find()
                            ->where(["Emailqueues.status <>" => "sent"])
                            ->all();

        foreach ($emails as $email):
            $email_specific = Configure::read('EmailQueue.specific');
            $master = Configure::read('EmailQueue.master');
            $default = Configure::read('EmailQueue.default');
            $overriede = Configure::read('EmailQueue.overriede');
            $subject = $email_specific[$email->type]['subject'];
            $template = $email_specific[$email->type]['template'];
            $emailFormate = $email_specific[$email->type]['emailFormate'];
            $layout = (isset($email_specific[$email->type]['layout'])) ? $email_specific[$email->type]['layout'] : 'default';
            $viewVars = $email_specific[$email->type]['viewVars'];

            $database_viewVars = json_decode($email->viewVars, true); /* DATABASE viewVars */
            $viewVars = [];
            foreach ($viewVars as $key => $emailue)
            {
                if (isset($database_viewVars[$key]))
                {
                    $viewVars[$key] = $database_viewVars[$key];
                }
                else
                {
                    $viewVars[$key] = $emailue;
                }
            }

            if ($master['testingmodeOverride'] == true)
            {
                $to = $overriede['to'];
                $from = $overriede['from'];
            }
            else
            {
                $to = $email->to;
                $from = $default['from'];
            }
            $cc = json_decode($email->cc,true);

            # build and send the email
            $e = new Email('default');
            $e->template($template, $layout)
                ->emailFormat($emailFormate)
                ->viewVars($viewVars)
                ->from($from)
                ->to($to)
                ->cc($cc)
                ->subject($subject)
                ->send();

            if ($master['deleteAfterSend']):
                // $this->EmailQueue = TableRegistry::get('Emailqueue');
                // $this->EmailQueue_id = $this->EmailQueue->get($email['id']);
                $this->EmailQueue->delete($email);
            else:
                // $this->EmailQueue = TableRegistry::get('Emailqueue');
                // $this->EmailQueue_data = $this->EmailQueue->get($email['id']);

                $email->status = 'sent';
                $email->sent_on = date('Y-m-d H:i:s');

                // $this->EmailQueue_data = $this->EmailQueue->patchEntity($this->EmailQueue_data, array());
                $this->EmailQueue->save($email);

            endif;
        endforeach;
    }
}