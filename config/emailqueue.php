<?php
return [
    'EmailQueue' => [
        /** 
         * Master configuration settings
         */
        'master' => [
            'deleteAfterSend' => FALSE, // TRUE- to delete entry from database, FALSE- update entry in database
            'testingmodeOverride' => TRUE // TRUE- overrides the settings to $config['override'], FALSE- default settings
            ], # end of MASTER


        /**
         * OVERRIDE settings
         * Priority: HIGHEST (OVERRIDE)
         * These are intended only for use when doing something like testing or hotfixing until a legit patch is introduced
         * These will only be used if { testingmodeOverride : true } in MASTER settings
         */
        'override' => [
            'from' => 'override_sender@gmail.com',
            'to' => 'testing@email.com',
            ], # end of OVERRIDE


        /**
         * DEFAULT settings
         * Priority: LOWEST
         * This array provides default settings for email templates that most likely will be overridden
         * Things like the default 'from' setting can be set in here and will apply to all emails unless replaced by values from the DATABASE, a SPECIFIC template setting, or the OVERRIDE
         */
        'default' => [
            'from' => 'sender@gmail.com',
            'emailFormat' => 'both',
            ], # end of DEFAULT


        /**
         * This is an array of SPECIFIC settings for each email TYPE
         * format is
         *     invoice : {              # this is the 'type' of the email
         *         subject : 'test',    # this will set the email subject to 'test'
         *         template : 'invoice',# what (view) template file the Email will render
         *         layout : 'foo',      # what layout the `template` will be rendered on
         *         viewVars : [ .. ]    # and array of variables that the view needs
         *         ...                  # any other settings needed for an Email
         *     }
         */
        'specific' => [
            'test' => [                 
                'subject'       => 'This is just a test!', 
                'template'      => 'test',  
                'layout'        => 'default', 
                'emailFormat'   => 'html', 
                'viewVars'      => [
                    'version'   => '123',
                    'foo'       => 'bar',
                    ],
                ], # end of type `test`
            ], # end of SPECIFIC
        ] # end of EmailQueue configs

        
    ];
