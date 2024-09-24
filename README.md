create .env file by copying .env.example

    cp .env.example .env

Add This lines to .env file

    ADMIN_EMAIL=admin@example.com
    ADMIN_PASSWORD=password

run npm install

    npm install

create database

    create database blog_api;

or run migrates

    php artisan migrate

run migrates with seeders

    php artisan migrate:fresh --seed


run the application

    php artisan serve


the postman api collection is

    BlogApplication.postman_collection.json
