# Laravel Make File Permissions

[![Latest Stable Version](https://img.shields.io/packagist/v/romanzipp/laravel-make-file-permissions.svg?style=flat-square)](https://packagist.org/packages/romanzipp/laravel-make-file-permissions)
[![Total Downloads](https://img.shields.io/packagist/dt/romanzipp/laravel-make-file-permissions.svg?style=flat-square)](https://packagist.org/packages/romanzipp/laravel-make-file-permissions)
[![License](https://img.shields.io/packagist/l/romanzipp/laravel-make-file-permissions.svg?style=flat-square)](https://packagist.org/packages/romanzipp/laravel-make-file-permissions)

This package automatically applies unix file permissions after the `artisan make:` command.

## Installation

```
composer require --dev romanzipp/laravel-make-file-permissions
```

**If you use Laravel 5.5+ you are already done, otherwise continue.**

```php
romanzipp\MakeFilePermissions\Providers\MakeFilePermissionsProvider::class,
```

Add Service Provider to your app.php configuration file.

## Configuration

Copy configuration to config folder:

```
$ php artisan vendor:publish --provider="romanzipp\MakeFilePermissions\Providers\MakeFilePermissionsProvider"
```

## Config File

```php
return [

    /**
     * Enable the permission service
     */
    'enabled' => env('MAKE_PERMISSIONS_ENABLED', true),

    /**
     * Apply the following permission
     */
    'permission' => env('MAKE_PERMISSIONS', '600'),

    /**
     * Ignore commands
     */
    'ignore' => [],
];
```
