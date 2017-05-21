# 1. Installation

## Table of contents

  1. [Installation and Setup](1-Installation-and-Setup.md)
  2. [Configuration](2-Configuration.md)
  3. [Usage](3-Usage.md)
  
## Server Requirements

The LaravelSettings package has a few system requirements:

    - PHP >= 5.6

## Version Compatibility

| LaravelSettings                               | Laravel                                                   |
|:----------------------------------------------|:----------------------------------------------------------|
| ![LaravelSettings v0.x][laravel_settings_0_x] | ![Laravel v5.2][laravel_5_2] ![Laravel v5.3][laravel_5_3] |
| ![LaravelSettings v1.x][laravel_settings_1_x] | ![Laravel v5.4][laravel_5_4]                              |

[laravel_5_2]:  https://img.shields.io/badge/v5.2-supported-brightgreen.svg?style=flat-square "Laravel v5.2"
[laravel_5_3]:  https://img.shields.io/badge/v5.3-supported-brightgreen.svg?style=flat-square "Laravel v5.3"
[laravel_5_4]:  https://img.shields.io/badge/v5.4-supported-brightgreen.svg?style=flat-square "Laravel v5.4"

[laravel_settings_0_x]: https://img.shields.io/badge/version-0.*-blue.svg?style=flat-square "LaravelSettings v0.*"
[laravel_settings_1_x]: https://img.shields.io/badge/version-1.*-blue.svg?style=flat-square "LaravelSettings v1.*"

## Composer

You can install this package via [Composer](http://getcomposer.org/) by running this command: 

```bash
composer require arcanedev/laravel-settings
```

## Laravel

### Setup

Once the package is installed, you can register the service provider in `config/app.php` in the `providers` array:

```php
'providers' => [
    ...
    Arcanedev\LaravelSettings\SettingsServiceProvider::class,
],
```

### Artisan commands

To publish the config file by running this command:

```bash
php artisan vendor:publish --provider="Arcanedev\LaravelSettings\SettingsServiceProvider"
```
