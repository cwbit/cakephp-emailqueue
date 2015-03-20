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
        $Emailqueue = TableRegistry::get('Emailqueue');
        $emails = $Emailqueue->find('all', array("conditions" => array("status <>" => "sent"))); // Call the query on the model

        foreach ($emails as $val)
        {
            $email_specific = Configure::read('EmailQueue.specific');
            $master = Configure::read('EmailQueue.master');
            $default = Configure::read('EmailQueue.default');
            $overriede = Configure::read('EmailQueue.overriede');
            $subject = $email_specific[$val['email_template']]['subject'];
            $template = $email_specific[$val['email_template']]['template'];
            $emailFormate = $email_specific[$val['email_template']]['emailFormate'];
            $layout = @$email_specific[$val['email_template']]['layout'];
            $viewVars = $email_specific[$val['email_template']]['viewVars'];

            $database_parameters = json_decode($val['parametrs'], true); /* DATABASE Parameters */
            $parameters = [];
            foreach ($viewVars as $key => $value)
            {
                if (isset($database_parameters[$key]))
                {
                    $parameters[$key] = $database_parameters[$key];
                }
                else
                {
                    $parameters[$key] = $value;
                }
            }

            if ($master['testingmodeOverride'] == true)
            {
                $to = $overriede['to'];
                $from = $overriede['from'];
            }
            else
            {
                $to = $val['to_mail'];
                $from = $default['from'];
            }
            $cc = json_decode($val['cc_mail'],true);

            $email = new Email('default');
            $email->template($template, $layout)
                ->emailFormat($emailFormate)
                ->viewVars($parameters)
                ->from($from)
                ->to($to)
                ->cc($cc)
                ->subject($subject)
                ->send();

            if ($master['deleteAfterSend'])
            {
                $Emailqueue = TableRegistry::get('Emailqueue');
                $emailqueue_id = $Emailqueue->get($val['id']);
                $Emailqueue->delete($emailqueue_id);
            }
            else
            {
                $Emailqueue = TableRegistry::get('Emailqueue');
                $emailqueue_data = $Emailqueue->get($val['id']);

                $emailqueue_data['status'] = 'sent';
                $emailqueue_data['sent_on'] = date('Y-m-d H:i:s');

                $emailqueue_data = $Emailqueue->patchEntity($emailqueue_data, array());
                $Emailqueue->save($emailqueue_data);
            }
        }
    }
}