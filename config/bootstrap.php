<?php

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
    // Configure::load('EmailQueue.emailqueue', 'default', false);	# plugin
    Configure::load('emailqueue', 'default', true);				# app overrides
} catch (\Exception $e) {
    # if EmailQueue is already set through the bootstrap process, the php file is technically NOT required.
    if (!Configure::check('EmailQueue.master.alwaysRequireConfigFile') || Configure::read('EmailQueue.master.alwaysRequireConfigFile') !== false) :
      die('Fatal Error. Either the EmailQueue settings file MUST EXIST in `config/emailqueue.php`, or the normal bootstrap process MUST return a full `EmailQueue` options array AND set `EmailQueue.master.alwaysRequireConfigFile` === false. For a sample file please see EmailQueue.config/emailqueue_sample.php. Error:' . $e->getMessage() . "\n");
    endif;
}
