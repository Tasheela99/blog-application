1.create .env file by copying .env.example

    cp .env.example .env
2.give the database credentials

3.Add This lines to .env file

    ADMIN_EMAIL=admin@example.com
    ADMIN_PASSWORD=password

4.run npm install and composer

    npm install
    composer install

5.create database

    create database blog_api;

or run migrates

    php artisan migrate

6.run migrates with seeders

    php artisan migrate:fresh --seed


7.run the application

    php artisan serve


8.the postman api collection is

    BlogApplication.postman_collection.json
    and inside of the PostmanCollection Folder
