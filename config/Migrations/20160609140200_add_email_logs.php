<?php
use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter as Adapter;

class AddEmailLogs extends AbstractMigration
{

    public function change()
    {
        $table = $this->table('email_logs', ['id'=>false, 'primary_key'=>'id']);
        $table
            ->addColumn('id', 'uuid')
            ->addColumn('email_id', 'uuid')
            ->addColumn('email_type', 'string', ['null'=>true])
            ->addColumn('email_data', 'text', ['null'=>true])
            ->addColumn('sent_to', 'string', ['null'=>true])
            ->addColumn('sent_from', 'string', ['null'=>true])
            ->addColumn('sent_on', 'datetime', ['null'=>true])
            ->addColumn('processed_on', 'datetime')
            ->addColumn('status', 'string', ['null'=>true])
            ->addColumn('status_message', 'string', ['null'=>true])
            ->addColumn('created', 'datetime')
            ->addColumn('modified', 'datetime')
            ->create();
    }

    public function down()
    {
        $this->drop('email_logs');
    }
}
