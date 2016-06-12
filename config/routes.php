<?php
use Cake\Routing\Router;
use Cake\Core\Configure;

/**
 * Expose the controllers within this app if we're running in debug mode
 */
if(Configure::read('debug')):
	Router::plugin('EmailQueue', function ($routes) {
	    $routes->fallbacks('InflectedRoute');
	});
endif;
