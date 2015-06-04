# CakePHP 3 - EmailQueue Plugin
This is a plugin for CakePHP 3 that let's you quickly Queue emails to be sent whenever a process function is called.

***WHY?***

It's not cool to bomb or hold up an order because you can't send an email confirmation. Better to queue the emails and process them in a batch later on, no?

***HOW?***

1. Create the database table
2. Load the plugin
3. Configure the plugin
4. Use the plugin
  5. Queue an email
  6. Process the queue

### Database Installation
Run the following script to set up the database for the plugin

```sql
CREATE TABLE IF NOT EXISTS `email_queues` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(255) NOT NULL, -- TYPE of the email, e.g. order-confirm. This is used to get the set of configs EmailQueue.specific.{$type} see below
  `to_addr` varchar(1020) NOT NULL, -- will be mapped to CakeEmail::to()
  -- `cc_addr` TEXT NOT NULL, -- optional, generally you do a global CC in the config file. -- mapped to CakeEmail::cc()
  `viewVars` TEXT NOT NULL, -- will be mapped to CakeEmail::viewVars()
  `status` varchar(255) NOT NULL DEFAULT 'pending', -- by default only pending emails will be considered
  `sent_on` timestamp NULL DEFAULT NULL, -- when the email was sent
  `created` timestamp NULL DEFAULT NULL,
  `modified` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 ;
```

### Plugin Installation

1. Using Composer
2. Manually
  3. Loading the plugin in your app 
  4. Setting up the namespace / autoloader
3. CakePHP Bootstrapping
4. Configuring the Plugin

  
#### Composer Install

This plugin is on Packagist which means it can be easily installed with Composer.

```
composer require cwbit/cakephp-emailqueue:~1
```
#### Manual Install

You can also manually load this plugin in your App

##### loading the plugin in your app
Add the source code in this project into `src/Plugins/EmailQueue`

Then configure your App to actually load this plugin

```php
	# in ../config/bootstrap.php
Plugin::load('EmailQueue', [
    'bootstrap' => true,        # let the plugin load its boostrap file
    'routes' => true,           # load the plugin routes file
    'ignoreMissing' => true,    # ignore missing routes or bootstrap file(s)
    'autoload' => true,      # uncomment if you can't use composer to set the namespace/class location
    ]);
```

##### setting up the namespace / autoloader
Tell the autoloader where to find your namespace in your `composer.json` file

```json
	(..)
    "autoload": {
        "psr-4": {
           (..)
            "EmailQueue\\": "./plugins/EmailQueue/src"
        }
    },
    (..)
```
Then you need to issue the following command on the commandline
```
	php composer.phar dumpautoload
```
If you are unable to get composer autoloading to work, uncomment the `'autoload' => true` line in your `bootstrap.php` `Plugin::load(..)` command (see loading section)

### Configuration
This section may seem complicated but it's really not, trust me.

***TLDR;*** **we dynamically build emails by combining a bunch of config settings that directly match up to the configurable settings in \Cake\Email\Email.**

##### Configuration Explanation
The EmailQueue needs to be given some basic configuration before it can be used. The idea is to set up config settings for each of the different types of emails you're going to be sending - the `_getConfig($emailType)` function will merge all the configuration options into a complete set of information for the `Email` library

* master
  * settings for the component itself
* default
  * default settings applied to each email with lowest priority
* override
  * override settings applied to each email with HIGHEST priority iif `master.testingModeOverride = true`
* specific.type
  * settings applied to email based on the `email->type`
  * keyed by `email->type`
  * example: We can Queue an `order-confirmation` email in the database and have the `process()` determine what layout, to_addr, etc. we should use for an order-confirmation by looking in the `EmailQueue.specific.order-confirmation` array

##### Default Configuration
Here is what the default configuration file might look like. `demo` in this example would be an email type that we support - it would load the `EmailQueue.test` view file, passing it `viewVars = ['name' => .., 'version' => .., 'foo' => ..]` and the email would be sent to `to_addr` from `from` and so on

```php
# ./src/config/emailqueue.php
/**
 * Master configuration file for the EmailQueue Plugin
 */
return [
    'EmailQueue' => [
        'master' => [
            'deleteAfterSend'   => false,   
            'testingModeOverride' => false,  
            ], # end of MASTER
        'override' => [
            'from'              => 'override_sender@email.com',
            'to_addr'           => 'override_to@email.com',
            ], # end of OVERRIDE
        'default' => [
            'cc_addr'           => ['default_cc@email.com'],    
            'emailFormat'       => 'both',                      
            'from'              => 'default_sender@email.com',
            'layout'            => 'EmailQueue.default',
            ], # end of DEFAULT
        'specific' => [
        	# ..
            'demo' => [                 
                'subject'       => 'This is just a test!',
                'template'      => 'EmailQueue.test',  
                'emailFormat'   => 'html', 
                'viewVars'      => [
                    'name'      => 'User',
                    'version'   => '123',
                    'foo'       => 'bar',
                    ],
                ], # end of type `test`
            # ..
            ], # end of SPECIFIC
        ] # end of EmailQueue configs
    ];

```

### Using the EmailQueue
Using the EmailQueue is a two-step process

1. Queue the email
2. Process the email queue

##### Queue an Email
Add the EmailQueue component to your controller

```php
	# ../src/Controller/DemoController.php
	
	public function initialize(){
		parent::initialize();
		
		# load the EmailQueue's EmailQueueComponent
		$this->loadComponent('EmailQueue.EmailQueue');
	}
```
And then to actually Queue an email, just specify the email **`type`**, who it's **`to`**, and any **`viewVars`** the Template (*set in the config `EmailQueue.specific.{$type}`*) will need when rendering itself.

```php
	# in your controller function
	public function someRandomFunction(){
		
		# ...
        $this->EmailQueue->add('demo', 'test@user.com', ['name'=>'Test User']);
        
	}
```

##### Sending Queued Emails
Currently the Queued emails are processed by running the `EmailQueueComponent::process()` function (not optimal)

To automatically process the email queue (via CRON) just expose the `process` method thru a controller (see `DemoController::cron_emails()` for an example) and call it via URL

**TODO: this should a use Cake Shell**

### Demo Controller

**warning: the demo controller is only available while in { debug : true }**

Load the following url (while in debug mode) `../email_queue/demo/test`
or view the file `src/Controller/DemoController.php`