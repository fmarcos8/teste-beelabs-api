# Teste Beelabs - API

## Project setup
```
composer update
```

### Run for development
```
php artisan serve
```

### Run Migrations
```
php artisan migrate
```

### Run Seeders
- For Students
  ```
  php artisan db:seed --class=StudentSeeder
  ```
- For Courses
  ```
  php artisan db:seed --class=CourseSeeder
  ```
- For Registrations
  ```
  php artisan db:seed --class=RegistrationSeeder
  ```
