# laravel-starter-kit

Latest Laravel blog boilerplate starter kit application. A very basic set of features for easy start developing a new application.

# Features

* Laravel
* MySQL migrations
* Bootstrap, jQuery
* Users, auth and roles
* User profile page and avatar upload
* Posts, comments, users CRUD

# ToDo

- [ ] Unit testing
- [ ] rocketeer

# Pre-requisites

 * npm
 * composer
 * gulp

# Install

```bash
# get it
$ git clone https://github.com/alecksmart/laravel-starter-kit.git
# edit config
$ cd laravel-starter-kit
$ vim config/database.php
# install
$ composer install
$ npm install
# run
$ php artisan migrate --seed
#   or
$ php artisan migrate:refresh --seed
# for development, in another console
$ gulp watch
# finally:
$ php artisan serve
```

# Notes

We will try to keep this up-to-date. At the moment of writing, the latest Laravel version is 5.4.