<?php

namespace EmailQueue\Controller\Component;

use Cake\Controller\Component;
use EmailQueue\Lib\EmailQueueManager;

class EmailQueueComponent extends Component
{

    /**
     * wrapper for manager function quickAdd
     */
    public function add($email_type, $to_addr, $viewVars)
    {
        $mgr = new EmailQueueManager();
        return $mgr->quickAdd($email_type, $to_addr, $viewVars);
    }
}
