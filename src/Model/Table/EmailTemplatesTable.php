<?php

namespace EmailQueue\Model\Table;

use App\Table\AppTable as Table;

class EmailTemplatesTable extends Table{
      /**
    	 * Tell CakePHP to modify the data structure of the entity data types
    	 * @param  Schema $schema this table's schema
    	 * @return Schema the adjusted schema definition
    	 */
    	protected function _initializeSchema(Schema $schema) {
            $schema->columnType('from', 'json')
            ->columnType('type', 'json')
            ->columnType('from', 'json')
            ->columnType('sender', 'json')
            ->columnType('to', 'json')
            ->columnType('cc', 'json')
            ->columnType('bcc', 'json')
            ->columnType('replyTo', 'json')
            ->columnType('readReceipt', 'json')
            ->columnType('returnPath', 'json')
            ->columnType('headers', 'json')
            ->columnType('viewVars', 'json')
            ->columnType('attachments', 'json')
            ->columnType('helpers', 'json');

            return $schema;
        }

}
