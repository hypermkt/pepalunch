# pepalunch

## Requirements

* PHP 7.1
    * Composer
* Node.js 9.3
    * npm

## Setup

Install PHP and JavaScript libraries via packaging manager.

```sh
$ composer install
$ npm install
```

Copy .env file and edit on database and Slack section. To create the Slack Client ID and Client Secret, please see below.

* [Hello World Slack OAuth \| Slack](https://api.slack.com/tutorials/app-creation-and-oauth)

```sh
$ cp .env.example .env
```

Run the DB migration.

```sh
$ php artisan db:migrate
```

Create the application key and JWTAuth key.

```sh
$ php artisan key:generate
$ php artisan jwt:secret
```

To start the application, run the command as below.

```sh
$ php artisan serve
$ npm run watch
```
