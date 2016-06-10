<?php

namespace EmailQueue\Model\Table;

use Cake\ORM\Table as Table;
use Cake\Database\Schema\Table as Schema;

class EmailTemplatesTable extends Table{
      /**
    	 * Tell CakePHP to modify the data structure of the entity data types
    	 * @param  Schema $schema this table's schema
    	 * @return Schema the adjusted schema definition
    	 */
    	protected function _initializeSchema(Schema $schema) {
            $schema->columnType('from_addr', 'json');
            $schema->columnType('sender_addr', 'json');
            $schema->columnType('to_addr', 'json');
            $schema->columnType('cc_addr', 'json');
            $schema->columnType('bcc_addr', 'json');
            $schema->columnType('replyTo', 'json');
            $schema->columnType('readReceipt', 'json');
            $schema->columnType('returnPath', 'json');
            $schema->columnType('headers', 'json');
            $schema->columnType('viewVars', 'json');
            $schema->columnType('attachments', 'json');
            $schema->columnType('helpers', 'json');

            return $schema;
        }

  	/**
  	 * Initialize the table
  	 * @param  array  $options
  	 * @return void
  	 */
  	public function initialize(array $options){
  		parent::initialize($options);

  		# load the timestamp behavior so our created/modified fields are populated
  		$this->addBehavior('Timestamp');
  	}
}
