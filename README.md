# Laravel Make File Permissions

[![Latest Stable Version](https://poser.pugx.org/romanzipp/laravel-make-file-permissions/version)](https://packagist.org/packages/romanzipp/laravel-make-file-permissions)
[![Total Downloads](https://poser.pugx.org/romanzipp/laravel-make-file-permissions/downloads)](https://packagist.org/packages/romanzipp/laravel-make-file-permissions)
[![License](https://poser.pugx.org/romanzipp/laravel-make-file-permissions/license)](https://packagist.org/packages/romanzipp/laravel-make-file-permissions)

This package automatically applies unix file permissions after the `artisan make:` command.

## Installation

```
composer require --dev romanzipp/laravel-make-file-permissions
```

Or add `romanzipp/laravel-make-file-permissions` to your `composer.json`

```
"romanzipp/laravel-make-file-permissions": "*"
```

Run composer update to pull the latest version.

**If you use Laravel 5.5+ you are already done, otherwise continue:**

```php
romanzipp\MakeFilePermissions\Providers\MakeFilePermissionsProvider::class,
```

Add Service Provider to your app.php configuration file.

## Configuration

Copy configuration to config folder:

```
$ php artisan vendor:publish --provider=romanzipp\MakeFilePermissions\Providers\MakeFilePermissionsProvider
```
