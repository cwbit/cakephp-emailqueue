<?php
# ./src/config/emailqueue.php
/**
 * Master configuration file for the EmailQueue Plugin
 *
 * This returns an array of all possible EmailQueue options
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
         * These will only be used if { testingmodeOverride === true } in MASTER settings
         */
        'override' => [
            //'from'              => 'override_sender@email.com',
            //'to'                => 'override_to@email.com',
            'transport'         => 'test',
            ], # end of OVERRIDE

        /**
         * DEFAULT settings
         * Priority: LOWEST
         * This array provides default settings for email templates that most likely will be overridden
         * Things like the default 'from' setting can be set in here and will apply to all emails unless replaced by values from the EmailQueue\Model\Entity\EmailQueue record, a SPECIFIC template setting, or the OVERRIDE
         */
        'default' => [
            'processor'         => ['EmailQueue\Processor\MustacheProcessor','EmailQueue\Processor\MarkdownProcessor'],
            //'cc'                => ['default_cc@email.com'=> 'Support'],
            'from'              => ['default_sender@email.com' => 'Support'],
            'emailFormat'       => 'both',
            'layout'            => 'EmailQueue.default',
            'transport'         => 'default',
            ], # end of DEFAULT

        ] # end of EmailQueue configs
  ];
