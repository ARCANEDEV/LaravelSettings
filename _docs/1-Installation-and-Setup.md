# 1. Installation

## Table of contents

  1. [Installation and Setup](1-Installation-and-Setup.md)
  2. [Configuration](2-Configuration.md)
  3. [Usage](3-Usage.md)

## Version Compatibility

| LaravelSettings                               | Laravel                                                   |
|:----------------------------------------------|:----------------------------------------------------------|
| ![LaravelSettings v0.x][laravel_settings_0_x] | ![Laravel v5.2][laravel_5_2] ![Laravel v5.3][laravel_5_3] |
| ![LaravelSettings v1.x][laravel_settings_1_x] | ![Laravel v5.4][laravel_5_4]                              |
| ![LaravelSettings v2.x][laravel_settings_2_x] | ![Laravel v5.5][laravel_5_5]                              |
| ![LaravelSettings v3.x][laravel_settings_3_x] | ![Laravel v5.6][laravel_5_6]                              |
| ![LaravelSettings v4.x][laravel_settings_4_x] | ![Laravel v5.7][laravel_5_7]                              |
| ![LaravelSettings v5.x][laravel_settings_5_x] | ![Laravel v5.8][laravel_5_8]                              |
| ![LaravelSettings v6.x][laravel_settings_6_x] | ![Laravel v6.x][laravel_6_x]                              |

[laravel_5_2]:  https://img.shields.io/badge/v5.2-supported-brightgreen.svg?style=flat-square "Laravel v5.2"
[laravel_5_3]:  https://img.shields.io/badge/v5.3-supported-brightgreen.svg?style=flat-square "Laravel v5.3"
[laravel_5_4]:  https://img.shields.io/badge/v5.4-supported-brightgreen.svg?style=flat-square "Laravel v5.4"
[laravel_5_5]:  https://img.shields.io/badge/v5.5-supported-brightgreen.svg?style=flat-square "Laravel v5.5"
[laravel_5_6]:  https://img.shields.io/badge/v5.6-supported-brightgreen.svg?style=flat-square "Laravel v5.6"
[laravel_5_7]:  https://img.shields.io/badge/v5.7-supported-brightgreen.svg?style=flat-square "Laravel v5.7"
[laravel_5_8]:  https://img.shields.io/badge/v5.8-supported-brightgreen.svg?style=flat-square "Laravel v5.8"
[laravel_6_x]:  https://img.shields.io/badge/v6.x-supported-brightgreen.svg?style=flat-square "Laravel v6.x"

[laravel_settings_0_x]: https://img.shields.io/badge/version-0.x-blue.svg?style=flat-square "LaravelSettings v0.x"
[laravel_settings_1_x]: https://img.shields.io/badge/version-1.x-blue.svg?style=flat-square "LaravelSettings v1.x"
[laravel_settings_2_x]: https://img.shields.io/badge/version-2.x-blue.svg?style=flat-square "LaravelSettings v2.x"
[laravel_settings_3_x]: https://img.shields.io/badge/version-3.x-blue.svg?style=flat-square "LaravelSettings v3.x"
[laravel_settings_4_x]: https://img.shields.io/badge/version-4.x-blue.svg?style=flat-square "LaravelSettings v4.x"
[laravel_settings_5_x]: https://img.shields.io/badge/version-5.x-blue.svg?style=flat-square "LaravelSettings v5.x"
[laravel_settings_6_x]: https://img.shields.io/badge/version-6.x-blue.svg?style=flat-square "LaravelSettings v6.x"

## Composer

You can install this package via [Composer](http://getcomposer.org/) by running this command:

```bash
composer require arcanedev/laravel-settings
```

## Laravel

### Setup

> **NOTE :** The package will automatically register itself if you're using Laravel `>= v5.5`, so you can skip this section.

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

> **Note:** If you want to publish the laravel settings' migrations, you need to set the `Arcanedev\LaravelSettings\SettingsManager::$runsMigrations` value to `false` in your `ServiceProvider`.
