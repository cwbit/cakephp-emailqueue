<?php

namespace EmailQueue\Model\Table;

use Cake\Database\Schema\Table as Schema;
use Cake\ORM\Table;
use Cake\ORM\Query;
use Cake\Validation\Validator;
use Cake\ORM\Behavior\TimestampBehavior;

/**
 * The EmailQueueTable holds the emails that have been Queued by the EmailQueueManager and are awaiting processing.
 * The EmailQueueManager will run thru this table looking for emails to send and will update/delete them from the Queue as they are processed.
 */
class EmailQueuesTable extends Table{

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

	/**
	 * Tell CakePHP to modify the data structure of the entity data types
	 * @param  Schema $schema this table's schema
	 * @return Schema the adjusted schema definition
	 */
	protected function _initializeSchema(Schema $schema) {
        $schema->columnType('viewVars', 'json');
        $schema->columnType('to_addr', 'json');
        return $schema;
    }

	/**
	 * Add the default table validation rules
	 * @param  Validator $validator [description]
	 * @return Validator
	 */
	public function validationDefault( Validator $validator ) {
		$validator
			->requirePresence('email_type', 'create')
			->notEmpty('email_type');
			// ->requirePresence('to_addr', 'create')
			// ->notEmpty('to_addr');
			// ->requirePresence('viewVars', 'create')
			// ->notEmpty('viewVars');

		return $validator;
	}

	/**
	 * Filter our Query object to only show emails that are pending
	 *
	 * This will be exposed as EmailQueues->find('pending')
	 *
	 * @param  Query  $query   Cake Query object
	 * @param  array  $options array of query options (not currently used by this finder)
	 * @return Query
	 */
	public function findPending(Query $query, array $options){
		return $query->where(['EmailQueues.status' => 'pending']);
	}

}
