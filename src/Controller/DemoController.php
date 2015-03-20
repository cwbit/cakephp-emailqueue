<?
namespace EmailQueue\Controller;

use EmailQueue\Controller\AppController;
use Cake\Core\Configure;

class DemoController extends AppController{

    public function initialize(){
        parent::initialize();

        # if Auth is loaded and we're in debug mode, allow the following functions to run
        if(isset($this->Auth) && Configure::read('debug')):
            $this->Auth->allow([
                'test',
                'cron_emails',
                ]);
        endif;
    }

    public function cron_emails() {
        $this->loadComponent('EmailQueue.EmailQueue');
        debug($this->EmailQueue->process());
        die;
    }

    /**
     * Sample function showing how to add and use the EmailQueue component
     * @return void
     */
    public function test(){
        #load the component - can be done in self::initialize, too
        $this->loadComponent('EmailQueue.EmailQueue');

        # queue an email of type 'test' to be sent to a random email with some view variables used by the 'test' template 
        debug($this->EmailQueue->add($type = 'demo', $to = 'test@example.com', ['name'=>'Warren T Wicket'] ));

        die;
    }

}