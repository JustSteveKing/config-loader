<?php

declare(strict_types=1);

namespace JustSteveKing\ConfigLoader;

use InvalidArgumentException;
use JustSteveKing\ConfigLoader\Exceptions\ConfigLoadingException;

class Loader
{
    /**
     * Loader constructor.
     *
     * @param string $basePath
     * @param array $config
     *
     * @return void
     */
    public function __construct(
        private string $basePath,
        private array $config = [],
    ) {}

    /**
     * Fetch the base path, or pass in a path to append to it.
     *
     * @param string|null $path
     *
     * @psalm-suppress PossiblyNullOperand
     *
     * @return string
     */
    public function basePath(null|string $path = null): string
    {
        return $this->basePath . (! is_null($path) ? '/' . $path : $path);
    }

    /**
     * Load a specific configuration file.
     *
     * @param string $name
     *
     * @throws InvalidArgumentException
     *
     * @psalm-suppress UnresolvableInclude
     *
     * @return void
     */
    public function load(string $name): void
    {
        if (array_key_exists($name, $this->config)) {
            return;
        }

        $path = $this->configPath(
            name: $name,
        );

        if ($path) {
            $this->config[$name] = require $path;
        }
    }

    /**
     * Load all config from a directory that matches the pattern: base_path / *.php
     *
     * @throws ConfigLoadingException
     *
     * @return void
     */
    public function loadAll(): void
    {
        $files = glob($this->basePath() . DIRECTORY_SEPARATOR . '*.php');

        if (empty($files)) {
            throw new ConfigLoadingException(
                message: "Could not load config files from [$this->basePath], please ensure they exist."
            );
        }

        array_map(function ($file) {
            $info = pathinfo(
                path: $file,
            );

            $this->load(
                name: $info['filename'],
            );
        }, $files);
    }

    /**
     * Retrieve the stored config.
     *
     * @return array
     */
    public function config(): array
    {
        return $this->config;
    }

    /**
     * Get the config path for a passed in file name.
     *
     * @param string $name
     *
     * @throws InvalidArgumentException
     *
     * @return string
     */
    private function configPath(string $name): string
    {
        $configPath = $this->basePath() . DIRECTORY_SEPARATOR . $name . '.php';

        if (! file_exists($configPath)) {
            throw new InvalidArgumentException(
                message: "Cannot load configuration file [$name] from path: [$configPath]",
            );
        }

        return $configPath;
    }
}
