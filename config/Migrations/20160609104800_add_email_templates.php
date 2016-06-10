<?php
use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter as Adapter;

class AddEmailTemplates extends AbstractMigration
{

    public function change()
    {
        $table = $this->table('email_templates', ['id'=>false, 'primary_key'=>'id']);
        $table
            ->addColumn('id', 'uuid')
            ->addColumn('email_type', 'string', ['null'=>false])
            ->addColumn('from_addr', 'string', ['null'=>true])        #: Email or array of sender. See Email::from().
            ->addColumn('sender_addr', 'string', ['null'=>true])       #: Email or array of real sender. See Email::sender().
            ->addColumn('to_addr', 'string', ['null'=>true])          #: Email or array of destination. See Email::to().
            ->addColumn('cc_addr', 'string', ['null'=>true])           #: Email or array of carbon copy. See Email::cc().
            ->addColumn('bcc_addr', 'string', ['null'=>true])          #: Email or array of blind carbon copy. See Email::bcc().
            ->addColumn('replyTo', 'string', ['null'=>true])      #: Email or array to reply the e-mail. See Email::replyTo().
            ->addColumn('readReceipt', 'string', ['null'=>true])  #: Email address or an array of addresses to receive the receipt of read. See Email::readReceipt().
            ->addColumn('returnPath', 'string', ['null'=>true])   #: Email address or and array of addresses to return if have some error. See Email::returnPath().
            ->addColumn('messageId', 'string', ['null'=>true])    #: Message ID of e-mail. See Email::messageId().
            ->addColumn('subject', 'string', ['null'=>false])     #: Subject of the message. See Email::subject().
            ->addColumn('message_html', 'text', ['null'=>true, 'limit'=>Adapter::TEXT_REGULAR])   #: Message body for HTML messages (OPTIONAL)
            ->addColumn('message_text', 'text', ['null'=>false, 'limit'=>Adapter::TEXT_REGULAR])  #: Message body for TEXT-ONLY messages (REQUIRED)
            ->addColumn('headers', 'string', ['null'=>true])      #: Headers to be included. See Email::setHeaders().
            ->addColumn('viewRender', 'string', ['null'=>true])   #: If you are using rendered content, set the view classname. See Email::viewRender().
            ->addColumn('template', 'string', ['null'=>true])     #: If you are using rendered content, set the template name. See Email::template().
            ->addColumn('theme', 'string', ['null'=>true])        #: Theme used when rendering template. See Email::theme().
            ->addColumn('layout', 'string', ['default'=>"EmailQueue.default"])       #: If you are using rendered content, set the layout to render. If you want to render a template without layout, set this field to null. See Email::template().
            ->addColumn('viewVars', 'string', ['null'=>true, 'limit'=>Adapter::TEXT_REGULAR])     #: If you are using rendered content, set the array with variables to be used in the view. See Email::viewVars().
            ->addColumn('attachments', 'string', ['null'=>true])  #: List of files to attach. See Email::attachments().
            ->addColumn('emailFormat', 'string', ['null'=>true,'default'=>'both'])  #: Format of email (html, text or both). See Email::emailFormat().
            ->addColumn('transport', 'string', ['null'=>true, 'default'=>'default'])    #: Transport configuration name. See Network\Email\Email::configTransport().
            ->addColumn('log', 'string', ['null'=>true])          #: Log level to log the email headers and message. true will use LOG_DEBUG. See also CakeLog::write()
            ->addColumn('helpers', 'string', ['null'=>true, 'default' => '["Markdown.Markdown"]'])      #: Array of helpers used in the email template.            ->addColumn('created', 'datetime')
            ->addColumn('created', 'datetime')
            ->addColumn('modified', 'datetime')
            ->create();
    }

    public function down()
    {
        $this->drop('email_templates');
    }
}
