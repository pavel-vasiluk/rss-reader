# RSS reader web application

Simple RSS reader web application with multiple views 

## Prerequisites

Project has been built using following technologies:
##### PHP 7.3.13 
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
composer:install
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