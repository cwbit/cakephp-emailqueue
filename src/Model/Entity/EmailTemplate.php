<?php

namespace EmailQueue\Model\Entity;

use Cake\ORM\Entity;

class EmailTemplate extends Entity
{
  protected $_accessible = [
      'from_addr' => true,         #: Email or array of sender. See Email::from().
      'sender_addr' => true,       #: Email or array of real sender. See Email::sender().
      'to_addr' => true,           #: Email or array of destination. See Email::to().
      'cc_addr' => true,           #: Email or array of carbon copy. See Email::cc().
      'bcc_addr' => true,          #: Email or array of blind carbon copy. See Email::bcc().
      'replyTo' => true,      #: Email or array to reply the e-mail. See Email::replyTo().
      'readReceipt' => true,  #: Email address or an array of addresses to receive the receipt of read. See Email::readReceipt().
      'returnPath' => true,   #: Email address or and array of addresses to return if have some error. See Email::returnPath().
      'messageId' => true,    #: Message ID of e-mail. See Email::messageId().
      'subject' => true,      #: Subject of the message. See Email::subject().
      'message' => false,     #: Content of message. Do not set this field if you are using rendered content.
      'headers' => true,      #: Headers to be included. See Email::setHeaders().
      'viewRender' => true,   #: If you are using rendered content, set the view classname. See Email::viewRender().
      'template' => true,     #: If you are using rendered content, set the template name. See Email::template().
      'theme' => true,        #: Theme used when rendering template. See Email::theme().
      'layout' => true,       #: If you are using rendered content, set the layout to render. If you want to render a template without layout, set this field to null. See Email::template().
      'viewVars' => true,     #: If you are using rendered content, set the array with variables to be used in the view. See Email::viewVars().
      'attachments' => true,  #: List of files to attach. See Email::attachments().
      'emailFormat' => true,  #: Format of email (html, text or both). See Email::emailFormat().
      'transport' => true,    #: Transport configuration name. See Network\Email\Email::configTransport().
      'log' => true,          #: Log level to log the email headers and message. true will use LOG_DEBUG. See also CakeLog::write()
      'helpers' => true,      #: Array of helpers used in the email template.
      ];
}
