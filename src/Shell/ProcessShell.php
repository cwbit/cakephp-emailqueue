<?php

namespace EmailQueue\Shell;

use Cake\Console\Shell;
use Cake\Core\Configure;
use EmailQueue\Lib\EmailQueueManager;
use Cake\Log\Log;

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

    public function initialize()
    {
        parent::initialize();
    }

    public function main()
    {
        $mgr = new EmailQueueManager();

        # if passed an option with OR pipes, build to an array
        # TODO doesn't seem to be support at the SHELL level
        # TODO extend ConsoleInputOption::validValue to handle exploded params, then $parser->addOption( new MultiConsoleInputOption(args..))
        // switch (true) :
        //     case isset($this->params['type']):
        //         $this->params['type'] = explode('|', $this->params['type']);
        //         break;
        //     case isset($this->params['status']):
        //         $this->params['status'] = explode('|', $this->params['status']);
        //         break;
        // endswitch;

        $result = $mgr->process($this->params);

        foreach ($result as $email) :
            $this->out("STATUS: {$email->status} ID: {$email->id}");
        endforeach;

        $this->out('Done.');

    }
}
