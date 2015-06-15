<?php

namespace EmailQueue\Controller\Component;

use Cake\Controller\Component;
use EmailQueue\Lib\EmailQueueManager;

class EmailQueueComponent extends Component
{

    /**
     * wrapper for manager function quickAdd
     */
    public function add($type, $to_addr, $viewVars)
    {
        $mgr = new EmailQueueManager();
        return $mgr->quickAdd($type, $to_addr, $viewVars);
    }
}
