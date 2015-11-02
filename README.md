# l5-admin-panel

Administration panel for managing Software Licences of clients



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


## Authentication

1. Add Auth routes
2. Add views: register, login
3. `$php artisan migrate`
4. - test is it working: Register new user "John Doe, ...."
5. Add missing `home`
6. Instead of `home` set `dashboard` to be first page after login
7. Add functional tests: Register, LoginLogout, Logout redirect.
