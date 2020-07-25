# Laravel Shop Admin Panel
The project was created on the basis of the course "Laravel - creating an admin panel."
Course author: Alexander Batashov.
# Getting Started 
The requirements to application is:
*    **PHP - Supported Versions**: >= 7.1
*    **Webserver**: Nginx or Apache
*    **Database**: MySQL, or Maria DB
### Git Clone
```sh
$ git clone https://github.com/evgeniizab/laravel.shop.admin.git laravel.admin.panel
$ cd laravel.admin.panel
$ composer install
```
### Database
.env file

```sh
DB_CONNECTION=mysql
DB_HOST=XXXXXX
DB_PORT=3306
DB_DATABASE=XXXXX
DB_USERNAME=XXXX
DB_PASSWORD=XXXXX
```
Remember: Create the database before run artisan command.

```sh
$ php artisan migrate --seed
```

