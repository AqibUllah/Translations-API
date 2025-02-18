# Introduction
Welcome to the Translation Management Service—a Laravel-based application designed to store, manage, and serve translations for multiple locales. The service focuses on clean architecture, performance, and scalability, leveraging SOLID principles and repository/service patterns. By integrating caching, API resources, and advanced testing, it ensures both robust functionality and a maintainable codebase.

![Demo Image](https://translations.lodhiui.com/demo.png)
### See [Live Demo](https://translations.lodhiui.com)

# Use official PHP + Apache base
```php
FROM php:8.3 -apache
```

# Clone repository
```php
git clone https://github.com/AqibUllah/Translations-API.git
```

# Install dependencies
```php
composer install
```

# Copy environment
```php
cp .env.example .env | copy .env.example .env
```
# Generate key
```php
php artisan key:generate
```

# Note: Make sure you have increased memory size limit because there is a lots of data for seeding
```php
php.ini >> memory_limit = 1024M
```
# Run migration + seed
```php
php artisan migrate --seed
```
# Run application
```php
php artisan serve
```
# API Endpoints
```php
GET     api/translations   query params = search,key,tag,locale,perPage
POST    api/translations
PUT     api/translations/translation_id
DELETE  api/translations/translation_id
GET     api/translations/export
```
