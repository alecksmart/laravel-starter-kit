# laravel-starter-kit

Latest Laravel blog boilerplate starter kit application. A very basic set of features for easy start developing a new application.

# Features

* Laravel
* MySQL migrations
* Bootstrap, jQuery
* Users, auth and roles, gates and policies
* User profile page and avatar upload
* Posts, comments, users CRUD

# ToDo

- [ ] Beautiful users, posts, comments CRUD with AJAX
- [ ] Unit testing


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
$ mkdir -p bootstrap/cache/
$ vim config/database.php
$ cp .env.example .env
$ vim .env
# install
$ composer install
$ npm install
$ php artisan key:generate
$ php artisan config:clear
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