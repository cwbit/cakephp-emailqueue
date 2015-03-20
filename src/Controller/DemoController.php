<?
namespace EmailQueue\Controller;

use EmailQueue\Controller\EmailQueueAppController;

class DemoController extends EmailQueueAppController{


    public function cron_emails() {
        $this->loadComponent('EmailQueue.EmailQueue');
        echo $this->EmailQueue->cron_emails();
        die;
    }

    /**
     * Sample function showing how to add and use the EmailQueue component
     * @return [type] [description]
     */
    public function test(){
        #load the component - can be done in self::initialize, too
        $this->loadComponent('EmailQueue.EmailQueue');

        # queue an email of type 'test' to be sent to a random email with some view variables used by the 'test' template 
        $this->EmailQueue->add($type = 'test', $to = 'test@example.com', ['name'=>'Warren T Wicket'] );

        die;
    }


    public function add_email_entry() {
        /*
         * $data is having demo values but the format of inserting the data should be same as mentioned in it..
         */
        $data = [
            'email_template' => 'invoice', //name of type of email mentioned in config file
            'to_mail' => 'ruchir.kakkad@gmail.com', // sending mail to email address
            'cc_mail' => '["ruchir.kakkad@gmail.com","chintanggor@gmail.com"]', //cc is multiple so should be in this format only
            'parametrs' => '{ "order_no":"007", "product_name" : "mobile", "qty" : "10", "total" : "$100" }' //viewVars should be in this json format only..
        ];
        $this->loadComponent('EmailQueue');
        echo $this->EmailQueue->add_email_entry($data);
        die;
    }

}