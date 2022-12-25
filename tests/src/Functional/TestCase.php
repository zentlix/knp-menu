<?php

declare(strict_types=1);

namespace Spiral\KnpMenu\Tests\Functional;

use Spiral\KnpMenu\Bootloader\KnpMenuBootloader;
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
        ];
    }
}
