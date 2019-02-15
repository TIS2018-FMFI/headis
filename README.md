# Headis
Projekt TIS 2018/2019 - Headis


## How to install

1. clone repo
2. create database
3. duplicate/rename '.env.example' file to '.env'
4. enter DB_DATABASE, DB_USERNAME, DB_PASSWORD, APP_URL, MAIL setup into '.env' file
5. run 'composer install' command
6. import database from folder sql/
7. write access for folders storage/ and bootstrap/cache/
8. setup queue on server (https://laravel.com/docs/5.7/queues#supervisor-configuration)
9. setup cron (https://laravel.com/docs/5.7/scheduling)
