# AMI BE By Laravel

AMI is a system used to perform audits on each user and can recap history. This system is integrated with the backend in the other repository.

## Authors

- [@dendik-creation](https://www.github.com/dendik-creation)

## Tech Stack

- Backend : Laravel 10
- Tools : Laravel Sanctum, ORA for Oracle Database (Optional)

## Instruction

1. Clone and open this repository to your local computer
2. Run this command for install all dependencies

```
composer install
```

3. Create a database such as mysql, or oracle
4. Copy .env.example file and rename to .env
5. open the .env file and change DB_DATABASE value to [database_name]
6. Run command

```
php artisan key:generate
```

```
php artisan migrate
```

Now you can run Laravel 10 use command

```
php artisan serve
```
