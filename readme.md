# CakePHP 3 - EmailQueue Plugin 

### Database Installation
Run the following script to set up the database for the plugin

```sql
CREATE TABLE IF NOT EXISTS `email_queues` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(255) NOT NULL,
  `to` varchar(1020) NOT NULL,
  `cc` TEXT NOT NULL,
  `viewVars` varchar(1020) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `sent_on` timestamp NULL DEFAULT NULL,
  `created` timestamp NULL DEFAULT NULL,
  `modified` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 ;
```

### Plugin Installation

#### loading the plugin in your app
Add the source code in this project into `src/Plugins/EmailQueue`

If you are unable to get composer autoloading to work, uncomment the `'autoload' => true` line in your `bootstrap.php` `Plugin::load(..)` command (see previous)

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
#### setting up the namespace / autoloader
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

### Using the EmailQueue
Add the EmailQueue component to your controller

```php
	# ../src/Controller/DemoController.php
	
	public function initialize(){
		parent::initialize();
		
		# load the EmailQueue's EmailQueueComponent
		$this->loadComponent('EmailQueue.EmailQueue');
	}
```

Next, to actually queue an email

```php
	# in your controller function
	public function someRandomFunction(){
		# ...
        $data = [
            'email_template' => 'invoice', // type of email (see config file)
            'to_mail' => 'ruchir.kakkad@gmail.com', 
            'cc_mail' => '["ruchir.kakkad@gmail.com","chintanggor@gmail.com"]', 
            'parametrs' => '{ "order_no":"007", "product_name" : "mobile", "qty" : "10", "total" : "$100" }' //viewVars should be in this json format only..
        ];
	}
```

### Demo Controller

**warning: the demo controller is only available while in { debug : true }**

Load the following url (while in debug mode) `../email_queue/demo/test`
or view the file `src/Controller/DemoController.php`