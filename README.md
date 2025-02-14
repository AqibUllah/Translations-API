# Introduction
Welcome to the Translation Management Service—a Laravel-based application designed to store, manage, and serve translations for multiple locales. The service focuses on clean architecture, performance, and scalability, leveraging SOLID principles and repository/service patterns. By integrating caching, API resources, and advanced testing, it ensures both robust functionality and a maintainable codebase.

# Use official PHP + Apache base
```php
FROM php:8.3 -apache
```

# clone repository
```php
git clone https://github.com/AqibUllah/Translations-API.git
```

# Install dependencies
```php
composer install
```

# copy environment
```php
cp .env.example .env | copy .env.example .env
```
# generate key
```php
php artisan key:generate
```

# make sure you have increased memory size limit because there is a lots of data for seeding
```php
php.ini >> memory_limit = 1024M
```
# running migration + seed
```php
php artisan migrate --seed
```
# run application
```php
php artisan serve
```
