<?php

use Phinx\Migration\AbstractMigration;

class FixQueues extends AbstractMigration{
  public function change(){
    $this->table('email_queues')
      ->changeColumn('status', 'string', ['default'=>'pending'])
      ->changeColumn('to_addr', 'text', ['null'=>true,'default'=>null])
      ->changeColumn('sent_on', 'datetime', ['null'=>true,'default'=>null])
      ->save();
  }
}
