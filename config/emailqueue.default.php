<?php
/**
 *
 * These options are used by EmailQueue.Lib\EmailQueueManager::_getConfig()
 *         .. any settings that can be used by \Cake\Email\Email::profile()
 */
return [
    'EmailQueue' => [
        'deleteAfterSend'   => false,   # true, will delete after send; false, will just mark sent
        'processor'         => ['EmailQueue\Processor\MustacheProcessor','EmailQueue\Processor\MarkdownProcessor'],
        'layout'            => 'EmailQueue.default',
        'emailFormat'       => 'both',
        'transport'         => 'test',  # typically change to 'default' for production
        // 'from'              => ['noreply@example.com' => 'Support'],
        // 'cc'              => ['noreply@example.com' => 'Support'],
    ] # end of EmailQueue configs
  ];
