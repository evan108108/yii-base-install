#Yii Base Build With Bootstrap, etc...

###Yii Installation
- git clone --recursive [repo]

- **Standard [Yii](http://yiiframework.com) Install (see below for more info)**


##Yii Web Application
======================


###REQUIREMENTS
-----------------
- Apache 2 Web Server
- MySQL 5.1+
- PHP 5.3+ Configured with the following extensions:
- PDO
- PDO MySQL
- APC
- GD2
- Mcrypt
- Yii Framework v1.1.8 (Included in git project...no need to download)


###APACHE CHANGES
-----------------
Whichever apache site you use to run the app, you will need to add a few directives so that we can have friendly urls. 
Adding the following will get rid of the need to specify the root index.php file. 
Just add it to the Directory tag in your `httpd-vhosts.conf` file.
```
<VirtualHost *:80>
  <Directory "/Users/ben/Sites/yii/test/webapp">
    Options Indexes FollowSymLinks
    AllowOverride All
    Order deny,allow
    Allow from all
    Satisfy all

	IndexIgnore */*
	RewriteEngine on
	# if a directory or a file exists, use it directly
	RewriteCond %{REQUEST_FILENAME} !-f
	RewriteCond %{REQUEST_FILENAME} !-d

	# otherwise forward it to index.php
	RewriteRule . index.php

  </Directory>

  ServerAdmin info@webmaster.com
  DocumentRoot "/Users/admin/www/yii/test/webapp"
  ServerName test.local
  ServerAlias test.local
  ErrorLog "/private/var/log/apache2/yii-test-error_log"
  CustomLog "/private/var/log/apache2/yii-test-access_log" common
</VirtualHost>
```
Also, in your local environment you might want to update the `hosts`:
```
sudo nano /private/etc/hosts
```
Should open something like this:
```
##
# Host Database
#
# localhost is used to configure the loopback interface
# when the system is booting.  Do not change this entry.
##
127.0.0.1       localhost
255.255.255.255 broadcasthost
::1             localhost
fe80::1%lo0     localhost
```

Just add the `ServerAlias` you used in the `httpd-vhosts.conf` 
```
##Dev stuff
127.0.0.1       test.local
```

Now, you can access your app typing *test.local* in your browser.

####APPLICATION INSTALLATION
----------------------------
* Cloning this app from Github will result in the following:
test/
	README     [this file]
	Yii-1.1.8/ [Yii framework files]
	webapp/    [the web application files]

Create assets and runtime directory. From within application root, do the following:
```
$ mkdir webapp/assets

$ chmod 777 webapp/assets

$ mkdir webapp/protected/runtime

$ chmod 777 webapp/protected/runtime
```

File Permissions Note: You may also have to adjust some other directory permissions so that they are writable by the Web server process. 
At a minimum, the webapp/assets/ and webapp/protected/runtime/ directories needs to be writable by your Web server process.

* Create a new database and mysql user account, and configure with appropriate permissions:
```
CREATE DATABASE yii_base;
CREATE USER 'admin'@'localhost' IDENTIFIED BY '';
GRANT INSERT, SELECT, UPDATE, INDEX, DELETE, CREATE, DROP, ALTER, SHOW VIEW, CREATE VIEW ON yii_base.* TO 'admin'@'localhost';
```

* CREATE DATABASE yii_base_test;
```
GRANT INSERT, SELECT, UPDATE, INDEX, DELETE, CREATE, DROP, ALTER, SHOW VIEW, CREATE VIEW ON yii_base_test.* TO 'admin'@'localhost';
```

* Update `/webapp/protected/config/main.php` with the newly created mysql database name and credentials

* Run the data migration: _From the Shell_
 * ``$ cd to /your/project/path/webapp``
 * ``$ protected/yiic migrate``
 * ``$ protected/yiic migrate --connectionID=testdb``
