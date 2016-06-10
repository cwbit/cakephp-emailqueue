<?php

namespace EmailQueue\Model\Table;

use Cake\ORM\Table as Table;
use Cake\Database\Schema\Table as Schema;

class EmailLogsTable extends Table{
      /**
    	 * Tell CakePHP to modify the data structure of the entity data types
    	 * @param  Schema $schema this table's schema
    	 * @return Schema the adjusted schema definition
    	 */
    	protected function _initializeSchema(Schema $schema) {
            $schema->columnType('sent_to', 'json');
            $schema->columnType('sent_from', 'json');
            $schema->columnType('email_data', 'json');
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
