<?php
# ./src/config/emailqueue.php
/**
 * Master configuration file for the EmailQueue Plugin
 *
 * This returns an array of all possible EmailQueue options
 * These options are merged together by EmailQueue.Lib\EmailQueueManager::_getConfig()
 *     EmailQueue
 *         master
 *            .. plugin-level configuration settings
 *         default,
 *            .. any settings that can be used by \Cake\Email\Email::profile()
 *         override,
 *            .. any settings that can be used by \Cake\Email\Email::profile()
 */
return [
    'EmailQueue' => [
        /**
         * Master configuration settings
         */
        'master' => [
            'alwaysRequireConfigFile' => true,    # true, will always require a config/emailqueue.php file to exist; false, will allow bootstrap to continue if Configure::check('EmailQueue') exists
            'deleteAfterSend'         => false,   # true, will delete after send; false, will just mark sent
            'testingModeOverride'     => true,  # true, will load EmailQueue.override with highest priority
            ], # end of MASTER

        /**
         * OVERRIDE settings
         * Priority: HIGHEST (OVERRIDE)
         * These are intended only for use when doing something like testing or hotfixing until a legit patch is introduced
         * These will only be used if { EmailQueue.master.testingModeOverride === true }
         */
        'override' => [
            'transport'         => 'test',
            // 'to'                => ['override@email.com'=> 'Support'], # e.g. override CC for all emails.
            ], # end of OVERRIDE

        /**
         * DEFAULT settings
         * Priority: LOWEST
         * This array provides default settings for email templates that most likely will be overridden
         * Things like the default 'from' setting can be set in here and will apply to all emails unless replaced by values from the EmailQueue\Model\Entity\EmailQueue record, a SPECIFIC template setting, or the OVERRIDE
         */
        'default' => [
            'processor'         => ['EmailQueue\Processor\MustacheProcessor','EmailQueue\Processor\MarkdownProcessor'],
            'layout'            => 'EmailQueue.default',
            'emailFormat'       => 'both',
            'transport'         => 'default',
            'from'              => ['noreply@example.com' => 'Support'],
            //'cc'                => ['default_cc@email.com'=> 'Support'],
            ], # end of DEFAULT

        ] # end of EmailQueue configs
  ];
