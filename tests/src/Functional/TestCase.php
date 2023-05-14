<?php

declare(strict_types=1);

namespace Spiral\KnpMenu\Tests\Functional;

use Spiral\Bootloader\Http\RouterBootloader;
use Spiral\KnpMenu\Bootloader\KnpMenuBootloader;
use Spiral\Nyholm\Bootloader\NyholmBootloader;
use Spiral\Twig\Bootloader\TwigBootloader;

abstract class TestCase extends \Spiral\Testing\TestCase
{
    public function rootDirectory(): string
    {
        return __DIR__ . '/../';
    }

    public function defineBootloaders(): array
    {
        return [
            TwigBootloader::class,
            KnpMenuBootloader::class,
            RouterBootloader::class,
            NyholmBootloader::class,
        ];
    }
}
