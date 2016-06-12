<?php

namespace EmailQueue\Model\Entity;

use Cake\ORM\Entity;

class EmailQueue extends Entity {

	/**
	 * Array of database fields that can be mass-assigned by newEntities or patchEntities
	 * @var array
	 */
	protected $_accessible = [
			'type' => true,
			'to_addr' => true,
			'status' => true,
			'sent_on' => true,
			'viewVars' => true,
		];

}
