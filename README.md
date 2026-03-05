<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Trade



## ✅ Project Setup Instructions

1. Clone the repository

For Local Machine:

```sh
git clone https://github.com/Mirza-Md-Golam-Nabi/trade.git
```

For Live Server or cPanel:

```sh
git clone https://github.com/Mirza-Md-Golam-Nabi/trade.git .
```

> **Note:** If the clone command fails, the directory may not be empty.
> Run the following command to clean it:

```sh
rm -rf *
```

If the issue persists, hidden files may still exist. Remove them with: 

```sh
rm -rf .*
```

2. Goto project folder

```sh
cd trade
```

3. Install dependencies using Composer

```sh
composer install
```

If you want to install it in cPanel, first check **composer** is install or not. For checking:

```sh
composer -v
```

If you see "**Composer Not Found**", you need to install Composer on your system.
Follow this guide [Composer install in cPanel](https://github.com/Mirza-Md-Golam-Nabi/tips/blob/master/laravel/composer/README.md#composer-install-in-cpanel-%EF%B8%8F)

4. Create the **.env** file

Copy the example environment file:

```sh
cp .env.example .env
```

5. Run this command:

```sh
php artisan key:generate
```

6. Create the database

Create a database named:

```sh
trade
```

7. Run migrations and seeders

Run the following command to migrate and seed the database:

```sh
php artisan migrate --seed
```

8. Run the application

```sh
npm install
```

and

```sh
npm run build
```

and

```sh
php artisan serve
```

✅ When the seeder file is executed, a default admin & user are created in the users table.
The login credentials are:

> Admin credential
- Email: admin@example.com
- Password: password
- URL: http://127.0.0.1:8000/admin/login

> User credential
- Email: user1@example.com
- Password: password
- URL: http://127.0.0.1:8000/user/login



