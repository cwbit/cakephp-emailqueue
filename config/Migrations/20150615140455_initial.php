<?php
use Phinx\Migration\AbstractMigration;

class Initial extends AbstractMigration
{

    public function change()
    {
        $table = $this->table('email_queues', ['id'=>false, 'primary_key'=>'id']);
        $table
            ->addColumn('id', 'uuid')
            ->addColumn('type', 'string')
            ->addColumn('to_addr', 'text')
            ->addColumn('viewVars', 'text')
            ->addColumn('status', 'string')
            ->addColumn('sent_on', 'datetime')
            ->addColumn('error', 'text', ['default' => null, 'limit' => null, 'null' => true, ])
            ->addColumn('created', 'datetime')
            ->addColumn('modified', 'datetime')
            ->create();
    }

    public function down()
    {
        $this->drop('email_queues');
    }
}
