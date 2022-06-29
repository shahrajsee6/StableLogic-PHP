# StableLogic-PHP
## PHP version support

Currently the required PHP minimum version is PHP __8.1__.

See the `composer.json` for other requirements.

## Installation

Use [composer](https://getcomposer.org) to install All dependancies into your project:

Run
```sh
composer install
```
to ensure that the correct dependencies are retrieved to match your deployment environment.

then run
```sh
php bin/console doctrine:database:create
```
to create database

then do
```sh
php bin/console doctrine:migrations:migrate
```
to create tables.


## Run Project
```sh
symfony server:start
```
