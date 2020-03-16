# RSS reader web application

Simple RSS reader web application with multiple views 

## Prerequisites

Project has been built using following technologies:
##### PHP 7.3.13
##### Symfony 5.0.5
##### Node.js 12.14.0 
##### NPM 6.13.4
##### Javascript ECMAScript 6

## Installing

First create your own database for the project.  
Afterwards proceed to .env file and edit following parameter:

```
DATABASE_URL=mysql://db_user:db_password@db_host:db_port/db_name?serverVersion=5.7
```
Providing your own configuration values.

Once it is done, install PHP dependencies with: 

```
composer install
```

Afterwards execute Doctrine migrations with: 

```
php bin/console doctrine:migrations:migrate
```

Next install all of the NPM packages by executing:

```
npm install
```

Once done build webpack modules by running:

```
npm run dev
```

That's all! Project should be up & running for you (╯°□°)╯

## Tests

Project functionality is covered with automation tests using Behat Framework.
To be able to run these tests, it's recommended that you should first: 1) update Chrome browser to the latest version 2) Install Chromedriver.
You can download Chromedriver here: https://chromedriver.chromium.org/

Once installation is done, Chromedriver should start listening for a connection.
If you are using Windows, the command to execute is:
```
./chromedriver.exe --whitelisted-ips=""
```
It should be similar in the other systems as well, just make sure you provide the same arguments to the command.

Then proceed to behat.yml project file and edit your local parameters:
```
base_url: 'http://your_local_base_url'
wd_host: "http://your_local_wd_host:9515"
```

Make sure you are using test database for Behat tests. To configure test database params, proceed to .env.test file and edit following parameter:

```
DATABASE_URL=mysql://db_user:db_password@db_host:db_port/db_name?serverVersion=5.7
```

Afterwards execute Doctrine migrations for test env: 

```
php bin/console doctrine:migrations:migrate -e test
```

Then switch site env to test: proceed to .env file and change param value
```
APP_ENV=dev
```
to 
```
APP_ENV=test
```

Now you should be able to run behats. Execute 
```
APP_ENV=test ./vendor/bin/behat
```

And make sure that tests pass.