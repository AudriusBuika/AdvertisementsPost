# AdvertisementsPost Test Solution

LIVE: https://ads-post.herokuapp.com/

## Requirements
 - PHP 5.6+
 - MySQL
 - Apache

## Install

Clone repository and create database, tables:

```
$ git clone https://github.com/AudriusBuika/AdvertisementsPost.git ads_post
$ cd ads_post
$ php bin/console doctrine:database:create
$ php bin/console doctrine:schema:update
```
*and final run command*
```
$ php bin/console server:run
```
_____________________

## If you want deploy Heroku
Source: https://coderwall.com/p/qpitzq/deploing-symfony-project-using-mysql-to-heroku (Written by _Mateusz Książek_)

## Requirements
 - Heroku(https://heroku.com/)
 - Git
 
Open console and go to your project:
```
$ cd ~/yourSymfonyProject
```

Initialize new Git repository and commit the current state of your code.
```
$ git init
Initialized empty Git repository in ~/yourProjectSymfony/.git/
$ git add .
$ git commit -m "First commit"
[master (root-commit) f432531] First commit 89 files changed, 7865 insertions(+)
```

Now create Procfile where you can set you web/ directory.
```
$ touch Procfile
$ echo "web: bin/heroku-php-apache2 web/" > Procfile
$ git add .
$ git commit -m "Procfile for Apache and PHP"
[master 45343as] Procfile for Apache and PHP 1 file changed, 1 insertion(+)
```

Next you should login to heroku toolbelt, so type:
```
$ heroku login
```

And use your credentials.

After login success you can create new Heroku application:
```
$ heroku create
Creating rusty-server-1211 in organization heroku... done, stack is xakar-15 http://rusty-server-1211.herokuapp.com/ | git@heroku.com:rusty-server-1211.git
Git remote heroku added
```

Success? Awesome! You are ready to deploy your application:
```
$ git push heroku master
```

If you had any problems related with SSH Fingerprints then this article should be helpful: https://devcenter.heroku.com/articles/git-repository-ssh-fingerprints.

If you didn't see any errors then your project was deployed successfully.

This was short guide about deploy Symfony project to Heroku, if you need more information then you can see documents on start of this protip.

##### My problem
In my Symfony project I use MySQL and Doctrine ORM, after deploying process I saw blank screen and on logs I found errors related with database connection. It was logic, I didn't set up any database...

##### My solution
Go to your project in console and add new Heroku addon: ClearDB (MySQL Database). Free version have a 5MB storage (more info: https://addons.heroku.com/cleardb).

##### Add ClearDB addon to your Heroku App
Type in console:
```
$ cd ~/yourSymfonyProject
$ heroku addons:add cleardb:ignite
```

Check that environment variable for database was set:
```
$ heroku config:get CLEARDB_DATABASE_URL
mysql://suausya5443:adf4252@us-cdbr-east.cleardb.com/heroku_db?reconnect=true
```

##### Set config files in your project
You added database and next step is configure you config files.

##### EDIT *CONFIG.YML*
If you use yml files for configuration then open *~/yourSymfonyProject/config/config.yml*

In first lines you should see imports section:
```
imports:
   - { resource: parameters.yml }
   - { resource: security.yml }
```

From this section remove import of *parameters.yml* (you can copy this line before delete). Result:
```
imports:
   - { resource: security.yml }
```

##### EDIT *CONFIG_DEV.YML*
Open *~/yourSymfonyProject/config/config_dev.yml* and find similar section (imports) in this file:
```
imports:
    - { resource: config.yml }
```

Add removed line from config.yml to the section before - *{ resource: config.yml }* line. Result:
```
imports:
    - { resource: parameters.yml }
    - { resource: config.yml }
```

##### CREATE PARAMETERS FILE FOR PRODUCTION
Your developer environment is ready to use, but we need to set up production environment.

Create new PHP file for parameters in config directory. I created file named parameters_production.php, full path: *~/yourSymfonyProject/config/parameters_production.php*
```php
<?php
    $db = parse_url(getenv('CLEARDB_DATABASE_URL'));

    $container->setParameter('database_driver', 'pdo_mysql');
    $container->setParameter('database_host', $db['host']);
    $container->setParameter('database_port', $db['port']);
    $container->setParameter('database_name', substr($db["path"], 1));
    $container->setParameter('database_user', $db['user']);
    $container->setParameter('database_password', $db['pass']);
    $container->setParameter('secret', getenv('SECRET'));
    $container->setParameter('locale', 'en');
    $container->setParameter('mailer_transport', null);
    $container->setParameter('mailer_host', null);
    $container->setParameter('mailer_user', null);
    $container->setParameter('mailer_password', null);
```

All lines above are required to start application, and I think all is clear.

Function *getenv('CLEARDB_DATABASE_URL')* get a environment variable from your server (we checked that this variable is exists above in Add ClearDB addon to your Heroku App section).

Second interesting line is *$container->setParameter('secret', getenv('SECRET'));* here you can see that you need set a new environment variable in server. 
Go to console and type:
```
$ heroku config:set SECRET=your_super_token
Setting config vars and restarting rusty-server-1211... done, v19
SECRET: your_super_token
```

##### APPEND file with parameters
When you have new parameters file then you can append this to config.

Open *~/yourSymfonyProject/config/config_prod.yml* and add your new file to import section in first position.
```
imports:
    - { resource: parameters_production.php }
    - { resource: config.yml }
```

##### DEPLOY
I guess that all is fine, so you can deploy your changes.

Go to console, and commit changes.
```
$ git add .
$ git commit -m "Updated production config"
[master 46462ab] Updated production config 4 files changed, 24 insertions (+++-)
```

##### *And...*
```
$ git push heroku master
```

Now probably your database is clear, so you'll create database and migrate your schema.
```
$ heroku run php bin/console doctrine:database:create
$ heroku run php bin/console doctrine:schema:update --force
```

If all things is well done then you see install required libraries and other dependencies. 
After deploying process check results of your work. Type in console:
```
$ heroku open
```

I guess that you see your website on live, well done!

**Author: _Mateusz Książek_**
_____________________
# Screenshot

###### 1 screenshot
![alt text](https://github.com/AudriusBuika/AdvertisementsPost/blob/master/screenshot/1.png)
###### 2 screenshot
![alt text](https://github.com/AudriusBuika/AdvertisementsPost/blob/master/screenshot/2.png)
###### 3 screenshot
![alt text](https://github.com/AudriusBuika/AdvertisementsPost/blob/master/screenshot/3.png)
###### 4 screenshot
![alt text](https://github.com/AudriusBuika/AdvertisementsPost/blob/master/screenshot/4.png)
###### 5 screenshot
![alt text](https://github.com/AudriusBuika/AdvertisementsPost/blob/master/screenshot/5.png)

_____________________
#### Training assignment for a PHP developer
Create a small web application that displays advertisements posted by users on its home page. It should somewhat resemble websites such as craigslist.org or skelbiu.lt, but have much less features.


##### Requirements for the Application
Both registered and unregistered users should see a list of all posted advertisements on the home page
Each advertisement in the list should display a posting date, title, detailed description and username of the poster
New users should be able to register by clicking "New User Registration" link on the homepage
Existing users should be able to log in
Logged in users should be able to post new advertisements. Posted advertisements should immediately appear on the home page
Logged in users should be able to see their own advertisements list
Logged in users should be able to log out
Deployment instructions


##### Requirements
 - Framework - Symfony
 - Frontend - Bootstrap
 - Database - MySQL
 - Version control - GIT
 - Deployment - project must be placed on the internet and publicly available (free hosting available at heroku.com)
