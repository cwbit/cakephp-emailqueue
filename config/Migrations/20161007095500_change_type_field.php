<?php
use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter as Adapter;

class ChangeTypeField extends AbstractMigration
{

    /**
     * Migrate Up.
     */
    public function up()
    {
        $table = $this->table('email_queues');
        $table->renameColumn('type', 'email_type');
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $table = $this->table('email_queues');
        $table->renameColumn('email_type', 'type');
    }
}
