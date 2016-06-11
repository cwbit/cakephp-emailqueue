<?php

namespace EmailQueue\Model\Table;

use Cake\ORM\Table as Table;
use Cake\Database\Schema\Table as Schema;

/**
 * TL;DR The EmailTemplate is where you would define the settings for each type of email your system can send.
 *
 * These used to be stored in the EmailQueue configuration array and where moved into a database table to make the system more dynamic.
 * The EmailTemplate essentially holds a bunch of Cake\Email\Email::profile() fields and configuration settings that are used to build and send the emails
 * For example you might set up a 'support' email-type that has the `to_addr` preconfigured to send to your Zendesk account, or a `order-confirmation` email-type
 * that runs a special EmailQueue\Processor to get the order data and load it into the data array to get picked up by the MustacheProcessor
 * The EmailTemplate is where you would define the settings for each type of email your system can send.
 */
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
            $schema->columnType('processor', 'json');
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
