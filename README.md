# l5-admin-panel

Administration panel for managing Software Licences of clients


## Why Laravel

[http://taylorotwell.com/on-community/](http://taylorotwell.com/on-community/)
 - This week, Laravel became the most popular PHP project on Github - Jan 5th 2014.

We want to develop company and ourselves with the latest technology used
and with community offered - **Best practices**.

Laravel provides nice already built structure for every developer to be on
same page. We don't have to discover hot-water, nor America :)

Authentication, Sessions, Testing, ORM, Queue, Emailer, ... all are
already there!

Laravel is proclaimed to be RAD tool. - **Build fast, refactor later**.

**Has Database migrations and seeders out of the box**

- track DB changes in VCS (git)!
- don't have to use anything else but php to develop app.

For fast RAD start with Laravel's Eloquent ORM that implements Active Record pattern
and later you can use Doctrine 2 that implements Data Mapper pattern.

Blade template engine is nice and clean (~twig).

By default it will return JSON response.


### Alternatives

* ZF2 - waiting for ZF3
* Symphony 2
* CakePhp - no migration out of the box


## Goal

To do anything user must be logged.


## Parts

1. Login page
2. Register page
3. Dashboard
4. Logout


## Install

1. create db
2. change .env
3. composer install
4. npm install

### While in Dev

Awesome feature is to use Elixir to run PhpUnit on every file save!
Just from terminal run:

    $ gulp tdd


## Authentication

1. Add Auth routes
2. Add views: register, login
3. `$php artisan migrate`
4. - test is it working: Register new user "John Doe, ...."
5. Add missing `home`
6. Instead of `home` set `dashboard` to be first page after login
7. Add functional tests: Register, LoginLogout, Logout redirect.
