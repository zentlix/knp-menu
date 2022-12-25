<?php

declare(strict_types=1);

namespace Spiral\KnpMenu\Tests\Unit\Config;

use Knp\Menu\ItemInterface;
use PHPUnit\Framework\TestCase;
use Spiral\Core\Container\Autowire;
use Spiral\KnpMenu\Config\KnpMenuConfig;
use Spiral\KnpMenu\MenuInterface;

final class KnpMenuConfigTest extends TestCase
{
    public function testGetMenus(): void
    {
        $instance = new class implements MenuInterface {
            public function create(array $options = []): ItemInterface
            {
            }
        };
        $autowire = new Autowire('');

        $config = new KnpMenuConfig(['menus' => ['binding', $autowire, $instance]]);

        $this->assertSame(['binding', $autowire, $instance], $config->getMenus());
    }

    public function testGetTemplate(): void
    {
        $config = new KnpMenuConfig(['template' => 'test']);

        $this->assertSame('test', $config->getTemplate());
    }

    public function testGetTemplateOptions(): void
    {
        $config = new KnpMenuConfig(['template_options' => ['a' => 'b']]);

        $this->assertSame(['a' => 'b'], $config->getTemplateOptions());
    }
}
