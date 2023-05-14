<?php

declare(strict_types=1);

namespace Spiral\KnpMenu\Config;

use Knp\Menu\Matcher\Voter\VoterInterface;
use Spiral\Core\Container\Autowire;
use Spiral\Core\InjectableConfig;
use Spiral\KnpMenu\Matcher\Voter\RouteVoter;
use Spiral\KnpMenu\MenuInterface;

/**
 * @psalm-type TMenu = MenuInterface|class-string<MenuInterface>|Autowire<MenuInterface>
 * @psalm-type TVoter = VoterInterface|class-string<VoterInterface>|Autowire<VoterInterface>
 *
 * @property array{
 *     template: non-empty-string,
 *     template_options: array<array-key, mixed>,
 *     menus: TMenu[],
 *     voters: TVoter[]
 * } $config
 */
final class KnpMenuConfig extends InjectableConfig
{
    public const CONFIG = 'knp-menu';

    protected array $config = [
        'template' => '',
        'template_options' => [],
        'menus' => [],
        'voters' => [
            RouteVoter::class
        ]
    ];

    /**
     * @return TMenu[]
     */
    public function getMenus(): array
    {
        return $this->config['menus'];
    }

    /**
     * @return TVoter[]
     */
    public function getVoters(): array
    {
        return $this->config['voters'];
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
