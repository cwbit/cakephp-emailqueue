<?php
# ./src/config/emailqueue.php
/**
 * Master configuration file for the EmailQueue Plugin
 *
 * This returns an array of all possible EmailQueue options
 *     EmailQueue
 *         master
 *             .. component configuration settings
 *         default,
 *         override,
 *         specific.type (settings can be any setting useable by Cake\Network\Email\Email)
 *            'from': Email or array of sender. See Email::from().
 *            'sender': Email or array of real sender. See Email::sender().
 *            'to': Email or array of destination. See Email::to().
 *            'cc': Email or array of carbon copy. See Email::cc().
 *            'bcc': Email or array of blind carbon copy. See Email::bcc().
 *            'replyTo': Email or array to reply the e-mail. See Email::replyTo().
 *            'readReceipt': Email address or an array of addresses to receive the receipt of read. See Email::readReceipt().
 *            'returnPath': Email address or and array of addresses to return if have some error. See Email::returnPath().
 *            'messageId': Message ID of e-mail. See Email::messageId().
 *            'subject': Subject of the message. See Email::subject().
 *            'message': Content of message. Do not set this field if you are using rendered content.
 *            'headers': Headers to be included. See Email::setHeaders().
 *            'viewRender': If you are using rendered content, set the view classname. See Email::viewRender().
 *            'template': If you are using rendered content, set the template name. See Email::template().
 *            'theme': Theme used when rendering template. See Email::theme().
 *            'layout': If you are using rendered content, set the layout to render. If you want to render a template without layout, set this field to null. See Email::template().
 *            'viewVars': If you are using rendered content, set the array with variables to be used in the view. See Email::viewVars().
 *            'attachments': List of files to attach. See Email::attachments().
 *            'emailFormat': Format of email (html, text or both). See Email::emailFormat().
 *            'transport': Transport configuration name. See Network\Email\Email::configTransport().
 *            'log': Log level to log the email headers and message. true will use LOG_DEBUG. See also CakeLog::write()
 *            'helpers': Array of helpers used in the email template.
 */
return [
    'EmailQueue' => [
        /**
         * Master configuration settings
         */
        'master' => [
            'alwaysRequireConfigFile' => true,    # true, will always require a config/emailqueue.php file to exist; false, will allow bootstrap to continue if Configure::check('EmailQueue') exists
            'deleteAfterSend'   => false,   # true, will delete after send; false, will just mark sent
            'testingModeOverride' => true,  # true, will load EmailQueue.override with highest priority
            ], # end of MASTER

        /**
         * OVERRIDE settings
         * Priority: HIGHEST (OVERRIDE)
         * These are intended only for use when doing something like testing or hotfixing until a legit patch is introduced
         * These will only be used if { testingmodeOverride : true } in MASTER settings
         */
        'override' => [
            'from'              => 'override_sender@email.com',
            'to'                => 'override_to@email.com',
            ], # end of OVERRIDE

        /**
         * DEFAULT settings
         * Priority: LOWEST
         * This array provides default settings for email templates that most likely will be overridden
         * Things like the default 'from' setting can be set in here and will apply to all emails unless replaced by values from the DATABASE, a SPECIFIC template setting, or the OVERRIDE
         */
        'default' => [
            'processor'         => ['EmailQueue\Processor\MustacheProcessor','EmailQueue\Processor\MarkdownProcessor'],
            'cc'                => ['default_cc@email.com'],
            'emailFormat'       => 'both',
            'from'              => 'default_sender@email.com',
            'layout'            => 'EmailQueue.default',
            'transport'         => 'default',
            ], # end of DEFAULT


        ] # end of EmailQueue configs
  ];
