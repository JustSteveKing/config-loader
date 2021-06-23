# Config Loader

<!-- BADGES_START -->
[![Latest Version][badge-release]][packagist]
[![PHP Version][badge-php]][php]
![tests](https://github.com/JustSteveKing/config-loader/workflows/run-tests/badge.svg)
[![Total Downloads][badge-downloads]][downloads]

[badge-release]: https://img.shields.io/packagist/v/juststeveking/config-loader.svg?style=flat-square&label=release
[badge-php]: https://img.shields.io/packagist/php-v/juststeveking/config-loader.svg?style=flat-square
[badge-downloads]: https://img.shields.io/packagist/dt/juststeveking/config-loader.svg?style=flat-square&colorB=mediumvioletred

[packagist]: https://packagist.org/packages/juststeveking/config-loader
[php]: https://php.net
[downloads]: https://packagist.org/packages/juststeveking/config-loader
<!-- BADGES_END -->

A simple to use configuration loader for PHP.

A PHP package that will allow you to get all config files from a directory, or specific config files from a directory and combine them in an easy to access array.

**Note: Only PHP array config files are currently supported.**

## Installation

You can install this package using composer:

```bash
composer require juststeveking/config/loader
```

## Usage

To use this package all you need to do is create a new instance of `Loader` and pass in the path to your config files:

```php
use JustSteveKing\ConfigLoader\Loader;

$loader = new Loader(
    basePath: __DIR__ . '/../config',
);
```

You can optionally pass in default config options, but these will not be able to be overwritten.

```php
use JustSteveKing\ConfigLoader\Loader;

$loader = new Loader(
    basePath: __DIR__ . '/../config',
    config: [
        'keep' => [
            'this' => 'data',
        ]
    ]
);
```
Once you have a loader, you can start working with it:

### Checking the basePath and extending

```php
use JustSteveKing\ConfigLoader\Loader;

$loader = new Loader(
    basePath: __DIR__ . '/../config',
);

$loader->basePath(); // will return the passed in basePath

$loader->basePath(
    path: 'local',
); // will return the base path appended with /local
```

### Loading a specific config file

Sometimes you want to be explicit in how you load your configuration, use the `load` method for these times.

**WARNING** This could throw a `InvalidArgumentException` if the passed in name does not match a file name, or the base path is incorrect. This uses `file_exists` under the hood.

```php
use JustSteveKing\ConfigLoader\Loader;

$loader = new Loader(
    basePath: __DIR__ . '/../config',
);

$loader->load(
    name: 'app',
);
```

### Loading all config files from the base path

Sometimes you just want to load all files from your configuration path, using the `loadAll()` method for these times.

**WARNING** This will throw a `ConfigLoadingException` if config files could not be loaded, this usually means the base path is wrong.

```php
use JustSteveKing\ConfigLoader\Loader;

$loader = new Loader(
    basePath: __DIR__ . '/../config',
);

$loader->loadAll();
```

### Get all collected config

If you want to retrieve all the config that has been loaded, you can use the `config()` method.

```php
use JustSteveKing\ConfigLoader\Loader;

$loader = new Loader(
    basePath: __DIR__ . '/../config',
);

$loader->config(); // This will return an empty array

// This will load an array with the contents of app.php inside a keyed array
$loader->load(
    name: 'app',
);
```

## Going further

If you want to build something that is a little more than just a loader, and allows you to use dot notation follow these steps:

- Install [juststeveking/config](https://packagist.org/packages/juststeveking/config)
- Create a Config Repository and load in your config:

```php
use JustSteveKing\ConfigLoader\Loader;
use JustSteveKing\Config\Repository;

$loader = new Loader(
    basePath: __DIR__ . '/../config',
);

$loader->loadAll();

$config = Repository::build(
    items: $loader->config(),
);

// Access config:
$appName = $config->get(
    key: 'app.name',
    value: 'fall-back-value',
);
```

## Security

If you discover any security related issues, please email juststevemcd@gmail.com instead of using the issue tracker.

## Credits

- [Steve McDougall](https://github.com/JustSteveKing)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
