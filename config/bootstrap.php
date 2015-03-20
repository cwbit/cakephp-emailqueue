<?

use Cake\Core\Configure;


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