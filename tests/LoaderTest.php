<?php

declare(strict_types=1);

use JustSteveKing\ConfigLoader\Exceptions\ConfigLoadingException;
use JustSteveKing\ConfigLoader\Loader;

it('can create a new loader, passing in the base path.', function () {
    $loader = new Loader(
        basePath: __DIR__ . DIRECTORY_SEPARATOR . '../config',
    );

    expect(
        value: $loader,
    )->toBeInstanceOf(
        class: Loader::class,
    );

    expect(
        value: $loader->basePath(),
    )->toEqual(
        expected: __DIR__ . DIRECTORY_SEPARATOR . '../config'
    );

    expect(
        value: $loader->config(),
    )->toBeEmpty()->toEqual(
        expected: [],
    );
});

it('can load config from a PHP file', function () {
    $loader = new Loader(
        basePath: __DIR__ . DIRECTORY_SEPARATOR . '../config',
    );

    $loader->load(
        name: 'app',
    );

    expect(
        value: $loader->config(),
    )->toHaveCount(
        count: 1,
    )->toContain(
        needle: [
            'name' => 'test'
        ],
    );
});

it('does not double load config', function () {
    $loader = new Loader(
        basePath: __DIR__ . DIRECTORY_SEPARATOR . '../config',
        config: ['app' => []],
    );

    expect(
        value: $loader->config(),
    )->toHaveCount(
        count: 1,
    )->toEqual(
        expected: ['app' => []],
    );

    $loader->load(
        name: 'app',
    );

    expect(
        value: $loader->config(),
    )->toHaveCount(
        count: 1,
    )->toEqual(
        expected: ['app' => []],
    );
});

it('can load all config files from a directory', function () {
    $loader = new Loader(
        basePath: __DIR__ . DIRECTORY_SEPARATOR . '../config',
    );

    expect(
        value: $loader->config(),
    )->toBeEmpty()->toEqual(
        expected: [],
    );

    $loader->loadAll();

    expect(
        value: $loader->config(),
    )->toHaveCount(
        count: 1,
    )->toContain(
        needle: [
            'name' => 'test'
        ],
    );
});

it('throws a ConfigLoadingException if it cannot find files in the directory to load', function () {
    $loader = new Loader(
        basePath: __DIR__ . DIRECTORY_SEPARATOR . '../empty',
    );

    expect(
        value: $loader->config(),
    )->toBeEmpty()->toEqual(
        expected: [],
    );

    $loader->loadAll();
})->throws(
    exceptionClass: ConfigLoadingException::class
);

it('throws an InvalidArgumentException when a passed in config option cannot be loaded.', function () {
    $loader = new Loader(
        basePath: __DIR__ . DIRECTORY_SEPARATOR . '../config',
    );

    $loader->load(
        name: 'app',
    );

    expect(
        value: $loader->config(),
    )->toHaveCount(
        count: 1,
    )->toContain(
        needle: [
            'name' => 'test'
        ],
    );

    $loader->load(
        name: 'testing',
    );
})->throws(
    exceptionClass: InvalidArgumentException::class
);
