<?php

namespace EmailQueue\Shell;

use Cake\Console\Shell;
use Cake\Core\Configure;
use EmailQueue\Lib\EmailQueueManager;

class ProcessShell extends Shell
{
    public function getOptionParser()
    {
        $parser = parent::getOptionParser();
        $parser
            ->addOption('limit', [
                'short' => 'l',
                'help' => 'how many queue records to process at once',
                'default' => 1,
                ])
            ->addOption('status', [
                'short' => 's',
                'help' => 'only queue entities in this status will be processed',
                'default' => 'pending',
                'choices' => ['pending', 'failed', 'sent'],
                ])
            ->addOption('id', [
                'help' => 'supply an ID to send a specific email',
                ]);

        # build a list of possible types from the config file
        $types = array_keys(Configure::read('EmailQueue.specific')) ?: [];
        $parser
            ->addOption('type', [
                'short' => 't',
                'help' => 'only queue entities of this type will be processed',
                'choices' => $types,
                ]);
        return $parser;
    }

    public function initiliaze()
    {
        parent::initiliaze();

        $this->loadModel('EmailQueues');
    }

    public function main()
    {
        $mgr = new EmailQueueManager();

        $this->out(pj($mgr->process()));

    }
}
