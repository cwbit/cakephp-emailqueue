# CakePHP 3 - EmailQueue Plugin
This is a plugin for CakePHP 3 that let's you quickly Queue emails to be sent whenever a process function is called.

***WHY?***

It's not cool to bomb or hold up an order because you can't send an email confirmation. Better to queue the emails and process them in a batch later on, no?

***HOW?***

2. [Install the Plugin](#plugin-installation)
1. [Create the database table](#database-installation)
3. [Configure the plugin](#plugin-configuration)
4. [Use the plugin](#plugin-usage)
  5. Queue an email
  6. Process the queue

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
composer require cwbit/cakephp-emailqueue:dev-master
```

#### Manual Install

You can also manually load this plugin in your App

:warning: Manual installation of this plugin is not supported, but should work. Use at your own risk.

##### loading the plugin in your app
Add the source code in this project into `src/Plugins/EmailQueue`

Then configure your App to actually load this plugin

```php
	# in ../config/bootstrap.php
Plugin::load('EmailQueue', [
    'bootstrap' => true,        # let the plugin load its boostrap file
    'routes' => true,           # load the plugin routes file
    'ignoreMissing' => true,    # ignore missing routes or bootstrap file(s)
    'autoload' => true,      	# uncomment if you can't get composer to set the namespace/class location
    ]);
```

### Database Installation

Run the following migration command from inside your app directory to build the database for this plugin

```bash
 cd /path/to/your/app/root
 bin/cake migrations migrate --plugin EmailQueue
```

 You should now see a database table called `email_queues` and likely another called `email_queue_phinxlog` (used to store the current migration state, you can ignore this)

### Plugin Configuration
This section may seem complicated but it's really not, trust me.

***TLDR;*** **we dynamically build emails by combining a bunch of config settings that directly match up to the configurable settings in \Cake\Email\Email.**

##### Configuration Explanation
The EmailQueue needs to be given some basic configuration before it can be used. The idea is to set up config settings for each of the different types of emails you're going to be sending - the `_getConfig($emailType)` function will merge all the configuration options into a complete set of information for the `Email` library

The parameters used to send the actual email are built by combining the 5 different config arrays to

```
default <= specific <= database <= override <= master
   ^											  ^
  LOW											 HIGH
PRIORITY  									   PRIORITY
```


* `master`
  * settings for the plugin itself (no EMAIL-level settings)
* `default`
  * default settings applied to each email (with LOWEST priority)
  * e.g. to apply a standard `reply-to` or `bcc`
* `override`
  * override settings applied to each email (with HIGHEST priority if `master.testingModeOverride = true`)
  * e.g. to force all emails to send `to` while in dev
* `specific.type`
  * settings applied to email based on the `email->type` (with NORMAL priority)
  * keyed by `email->type`
  * e.g. to specify which `template` your `order-confirmation` email should be using, or that your `password-reset` email should send with `emailFormat => 'text'` instead of `both`
* `database`
  * database-level configuration settings are actually retrieved from the email entity in the queue table
  * e.g. the actual `to` address for your email, and the `orderId` to be used to load the order details  

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

### Plugin Usage
Using the EmailQueue is a two-step process

1. Queue the email
2. Process the email queue (CRON)

##### Queue an Email
Add the EmailQueue component to your controller

```php
	# ../src/Controller/DemoController.php
	
	public function initialize()
	{
		parent::initialize();
		
		# load the EmailQueue's EmailQueueComponent
		$this->loadComponent('EmailQueue.EmailQueue');
	}
```

And then to actually Queue an email, just specify the email **`type`**, who it's **`to`**, and any **`viewVars`** the Template (*set in the config `EmailQueue.specific.{$type}`*) will need when rendering itself.

```php
	# in your controller function
	public function someRandomFunction()
	{
		# ... do some stuff ...
		
        $this->EmailQueue->add('demo', 'test@user.com', ['name'=>'Test User']);
        
	}
```

##### Sending Queued Emails

Manually run, or add to CRON, the following commandline command

```bash
bin/cake EmailQueue.process
```

The shell has the following options:

* `--limit n` or `-l n` 
  * will set the query limit() to `n` where n is an integer.
  * default `20`
* `--status foo` or `-s foo`
  * will process only emails with status `foo`
  * choice(s) : `pending|failed|sent`
  * default `pending`
* `--type foo` or `-t foo`
  * will only process emails of type `foo`
  * choice(s) are built from `Configure::read('EmailQueue.specific');`
  * default `all`
* `--id foo`
  * will only process emails with id `foo` (as long as it also matched the other filters/config settings)

All the options can be chained together.

### CLI Examples

To send all pending emails, run the following

```bash
bin/cake EmailQueue.process
```

To explicitly send all `pending` emails, run the following

```bash
bin/cake EmailQueue.process -s pending
```
To send all emails of type `order-confirmation`, run the following

```bash
bin/cake EmailQueue.process -t order-confirmation
```
To send up to `100` emails at once, run the following

```bash
bin/cake EmailQueue.process -l 100
```
To send a specific email, run the following

```bash
bin/cake EmailQueue.process --id "5869e7fd-ccf3-46c2-9b15-844335b9a86d"
```

To send up to `100`, `failed`, `user-resetpw` emails, run the following

```bash
bin/cake EmailQueue.process -l 100 -s failed -t user-resetpw
```