<?php

namespace EmailQueue\Test\TestCase\Lib;

use EmailQueue\Lib\EmailQueueManager;
use Cake\Core\Configure;
use Cake\TestSuite\TestCase;

class EmailQueueManagerTest extends TestCase
{

  public $fixtures = ['plugin.email_queue.email_templates'];

  public function testQuickAdd()
  {
    Configure::load('EmailQueue.emailqueue.default', false);

    $Q = new EmailQueueManager;

    pj($Q->quickAdd('contact', 'test@user.com', ['name'=>'Test']));

  }
}
