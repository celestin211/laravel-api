<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Laravel\Set\LaravelSetList;

return RectorConfig::configure()
    ->withPaths([
        __DIR__.'/app',
        __DIR__.'/database',
        __DIR__.'/tests',
    ])
    ->withSkip([
        __DIR__.'/storage',
        __DIR__.'/bootstrap/cache',
        __DIR__.'/vendor',
    ])
    ->withSets([
        LevelSetList::UP_TO_PHP_82,
        LaravelSetList::LARAVEL_110,
    ])
    ->withPhpSets(php82: true);
