<?php

namespace EmailQueue\Shell;

use Cake\Console\Shell;

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
                'help' => 'only queue items in this status will be processed',
                'default' => 'pending',
                'choices' => ['pending', 'failed'],
                ]);
        return $parser;
    }
}
