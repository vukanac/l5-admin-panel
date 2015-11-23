# l5-admin-panel

<!-- START doctoc generated TOC please keep comment here to allow auto update -->
<!-- DON'T EDIT THIS SECTION, INSTEAD RE-RUN doctoc TO UPDATE -->
**Table of Contents**  *generated with [DocToc](https://github.com/thlorenz/doctoc)*

- [Goal](#goal)
- [Parts](#parts)
- [Install](#install)
  - [While in Dev](#while-in-dev)
- [Authentication](#authentication)
- [Why Laravel](#why-laravel)
  - [Alternatives](#alternatives)

<!-- END doctoc generated TOC please keep comment here to allow auto update -->


Administration panel for managing Software Licences of clients

Why it is in Laravel, read section below [Why Laravel](#why-laravel)


## Goal

To do anything user must be logged.


## Parts

1. Login page
2. Register page
3. Dashboard
4. Logout
5. Companies (List, Add, Delete, Show Details)
6. Prepare Master Layout for Localization
7. Prepare Companies part layout for Localization


## Install

1. create db
2. change .env
3. composer install
4. php artisan key:generate
5. php artisan migrate

Read section below [While in Dev](#while-in-dev).



## Authentication

1. Add Auth routes
2. Add views: register, login
3. `$php artisan migrate`
4. - test is it working: Register new user "John Doe, ...."
5. Add missing `home`
6. Instead of `home` set `dashboard` to be first page after login
7. Add functional tests: Register, LoginLogout, Logout redirect.




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



## Workflow

### While in Dev

#### Install


Install tools:

1. npm install


Awesome feature is to use Elixir to run PhpUnit on every file save!

Add to gulpfile.js inside :

    elixir(function(mix) {
        // other actions
        mix.phpUnit();
    });

Just from terminal run:

    $ gulp tdd


### Jenkins

Tricky part is how to set all required to run and successfully pass all test on some remote machine.

Most of required steps are written in `build.xml` - an Ant Build script.

1. Add job to jenkins
2. git repo:
	* !remember the url used!, <- will be needed for hook
	* set credentials (add new SSH user/pass)
	* set remote (origin, in my case p1 or demo),
	* set branch (master or develop)
3. Trigger: SCM, empty params <- should be empty, triggered by hook
4. Build:
	4.1. shell script: `php -r copy('.env.example', '.env');`
	4.2. ant target: `get.composer vendor`
	4.3. shell script:
		4.3.1 `php artisan key:generate`
		4.3.2 `php artisan migrate`
	4.4. ant target: `full-build-parallel`


Test it you can run it from terminal on local dev or on remote machine, and result should be the same:

    $ ant

or specific target only:

    $ ant phpunit


#### Database on Jenkins

On build server login with jenkins user, and login to mysql, and create new user 'homestead'.

    mysql -u root -pPASSWORD
    
    CREATE USER 'homestead'@'localhost' IDENTIFIED BY 'secret';
    GRANT ALL PRIVILEGES ON homestead.* TO 'homestead'@'localhost';
    FLUSH PRIVILEGES;

    CREATE DATABASE homestead;


This `homestead` user and database schema will be used for testing and build on Jenkins CI.

Other solution, may be better and faster, for this problem is to instead of mysql use **in memmory SqLite**.
