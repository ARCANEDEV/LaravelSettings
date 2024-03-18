# 1. Installation

## Table of contents

  1. [Installation and Setup](1-Installation-and-Setup.md)
  2. [Configuration](2-Configuration.md)
  3. [Usage](3-Usage.md)

## Version Compatibility

| Laravel                        | LaravelSettings                                 |
|:-------------------------------|:------------------------------------------------|
| ![Laravel v10.x][laravel_10_x] | ![LaravelSettings v10.x][laravel_settings_10_x] |
| ![Laravel v9.x][laravel_9_x]   | ![LaravelSettings v9.x][laravel_settings_9_x]   |
| ![Laravel v8.x][laravel_8_x]   | ![LaravelSettings v8.x][laravel_settings_8_x]   |
| ![Laravel v7.x][laravel_7_x]   | ![LaravelSettings v7.x][laravel_settings_7_x]   |
| ![Laravel v6.x][laravel_6_x]   | ![LaravelSettings v6.x][laravel_settings_6_x]   |
| ![Laravel v5.8][laravel_5_8]   | ![LaravelSettings v5.x][laravel_settings_5_x]   |
| ![Laravel v5.7][laravel_5_7]   | ![LaravelSettings v4.x][laravel_settings_4_x]   |
| ![Laravel v5.6][laravel_5_6]   | ![LaravelSettings v3.x][laravel_settings_3_x]   |
| ![Laravel v5.5][laravel_5_5]   | ![LaravelSettings v2.x][laravel_settings_2_x]   |
| ![Laravel v5.4][laravel_5_4]   | ![LaravelSettings v1.x][laravel_settings_1_x]   |
| ![Laravel v5.3][laravel_5_3]   | ![LaravelSettings v0.x][laravel_settings_0_x]   |
| ![Laravel v5.2][laravel_5_2]   | ![LaravelSettings v0.x][laravel_settings_0_x]   |

[laravel_10_x]:  https://img.shields.io/badge/version-10.x-blue.svg?style=flat-square "Laravel v10.x"
[laravel_9_x]:  https://img.shields.io/badge/version-9.x-blue.svg?style=flat-square "Laravel v9.x"
[laravel_8_x]:  https://img.shields.io/badge/version-8.x-blue.svg?style=flat-square "Laravel v8.x"
[laravel_7_x]:  https://img.shields.io/badge/version-7.x-blue.svg?style=flat-square "Laravel v7.x"
[laravel_6_x]:  https://img.shields.io/badge/version-6.x-blue.svg?style=flat-square "Laravel v6.x"
[laravel_5_8]:  https://img.shields.io/badge/version-5.8-blue.svg?style=flat-square "Laravel v5.8"
[laravel_5_7]:  https://img.shields.io/badge/version-5.7-blue.svg?style=flat-square "Laravel v5.7"
[laravel_5_6]:  https://img.shields.io/badge/version-5.6-blue.svg?style=flat-square "Laravel v5.6"
[laravel_5_5]:  https://img.shields.io/badge/version-5.5-blue.svg?style=flat-square "Laravel v5.5"
[laravel_5_4]:  https://img.shields.io/badge/version-5.4-blue.svg?style=flat-square "Laravel v5.4"
[laravel_5_3]:  https://img.shields.io/badge/version-5.3-blue.svg?style=flat-square "Laravel v5.3"
[laravel_5_2]:  https://img.shields.io/badge/version-5.2-blue.svg?style=flat-square "Laravel v5.2"

[laravel_settings_10_x]: https://img.shields.io/badge/version-10.x-blue.svg?style=flat-square "LaravelSettings v10.x"
[laravel_settings_9_x]: https://img.shields.io/badge/version-9.x-blue.svg?style=flat-square "LaravelSettings v9.x"
[laravel_settings_8_x]: https://img.shields.io/badge/version-8.x-blue.svg?style=flat-square "LaravelSettings v8.x"
[laravel_settings_7_x]: https://img.shields.io/badge/version-7.x-blue.svg?style=flat-square "LaravelSettings v7.x"
[laravel_settings_6_x]: https://img.shields.io/badge/version-6.x-blue.svg?style=flat-square "LaravelSettings v6.x"
[laravel_settings_5_x]: https://img.shields.io/badge/version-5.x-blue.svg?style=flat-square "LaravelSettings v5.x"
[laravel_settings_4_x]: https://img.shields.io/badge/version-4.x-blue.svg?style=flat-square "LaravelSettings v4.x"
[laravel_settings_3_x]: https://img.shields.io/badge/version-3.x-blue.svg?style=flat-square "LaravelSettings v3.x"
[laravel_settings_2_x]: https://img.shields.io/badge/version-2.x-blue.svg?style=flat-square "LaravelSettings v2.x"
[laravel_settings_1_x]: https://img.shields.io/badge/version-1.x-blue.svg?style=flat-square "LaravelSettings v1.x"
[laravel_settings_0_x]: https://img.shields.io/badge/version-0.x-blue.svg?style=flat-square "LaravelSettings v0.x"

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
