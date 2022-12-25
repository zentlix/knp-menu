<?php

declare(strict_types=1);

namespace Spiral\KnpMenu\Config;

use Spiral\Core\Container\Autowire;
use Spiral\Core\InjectableConfig;
use Spiral\KnpMenu\MenuInterface;

final class KnpMenuConfig extends InjectableConfig
{
    public const CONFIG = 'knp-menu';

    protected array $config = [
        'template' => '',
        'template_options' => [],
        'menus' => [],
    ];

    /**
     * @return array<MenuInterface|class-string|Autowire>
     */
    public function getMenus(): array
    {
        return $this->config['menus'];
    }

    /**
     * @return non-empty-string
     */
    public function getTemplate(): string
    {
        return $this->config['template'];
    }

    public function getTemplateOptions(): array
    {
        return $this->config['template_options'];
    }
}
