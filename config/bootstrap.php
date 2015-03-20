<?

use Cake\Core\Configure;
use Cake\Database\Type;

/**
 * Load the json database type
 * @see EmailQueuesTable
 */
Type::map('json', 'EmailQueue\Database\Type\JsonType');

/** 
 * try loading the configuration files
 */
try {
    Configure::load('EmailQueue.emailqueue', 'default', false);	# plugin
    Configure::load('emailqueue', 'default', true);				# app overrides
} catch (\Exception $e) {
    die($e->getMessage() . "\n");
}


?>