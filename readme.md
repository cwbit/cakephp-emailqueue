# Email Queue Component in Cakephp 3.0 #

### Database Installation ##
Run the following script to set up the database for the plugin

```sql
CREATE TABLE IF NOT EXISTS `emailqueue` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email_template` varchar(255) NOT NULL,
  `to_mail` varchar(1020) NOT NULL,
  `cc_mail` varchar(1020) NOT NULL,
  `parametrs` varchar(1020) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `sent_on` timestamp NULL DEFAULT NULL,
  `created` timestamp NULL DEFAULT NULL,
  `modified` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 ;
```

### Steps to Configure a component: ###

* Put emailqueue_config.php file in config directory
* Write Configure::load(‘emailqueue_config’); in config/bootstrap.php
* Put EmailQueueComponent.php in src/Controller/Component directory
* Import the emailqueue.sql file in your database

You have now successfully configured the component.

### Now check the DemoController.php file for example to use in any controller in application ###

* Insert emailqueue entry in database :- Check function add_email_entry()

* Fire emails from database:- Check function cron_emails()
