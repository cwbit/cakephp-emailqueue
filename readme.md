# Email Queue Component in Cakephp 3.0 #

### Database Installation ##
Run the following script to set up the database for the plugin

```sql
CREATE TABLE IF NOT EXISTS `emailqueue` (
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

### Plugin Installation ###
Add the source code in this project into `src/Plugins/EmailQueue`

Then configure your App to actually load this plugin

```php
	# ../config/bootstrap.php
	Plugin::load('EmailQueue', [
		'bootstrap' => true, 		# let the plugin load its boostrap file(s)
		]);
```

### Using the EmailQueue ###
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

### Now check the DemoController.php file for example to use in any controller in application ###

* Insert emailqueue entry in database :- Check function add_email_entry()

* Fire emails from database:- Check function cron_emails()
