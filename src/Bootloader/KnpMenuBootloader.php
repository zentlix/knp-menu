<?php

declare(strict_types=1);

namespace Spiral\KnpMenu\Bootloader;

use Knp\Menu\FactoryInterface;
use Knp\Menu\Matcher\Matcher;
use Knp\Menu\Matcher\MatcherInterface;
use Knp\Menu\MenuFactory;
use Knp\Menu\Provider\MenuProviderInterface;
use Knp\Menu\Renderer\PsrProvider;
use Knp\Menu\Renderer\RendererProviderInterface;
use Knp\Menu\Twig\MenuExtension;
use Psr\Container\ContainerInterface;
use Spiral\Boot\Bootloader\Bootloader;
use Spiral\Boot\DirectoriesInterface;
use Spiral\Config\ConfiguratorInterface;
use Spiral\Core\Container;
use Spiral\KnpMenu\Config\KnpMenuConfig;
use Spiral\KnpMenu\MenuInterface;
use Spiral\KnpMenu\MenuRegistry;
use Spiral\KnpMenu\Renderer\SpiralRenderer;
use Spiral\Twig\Bootloader\TwigBootloader;
use Spiral\Views\Bootloader\ViewsBootloader;

final class KnpMenuBootloader extends Bootloader
{
    protected const DEPENDENCIES = [
        ViewsBootloader::class,
    ];

    protected const SINGLETONS = [
        FactoryInterface::class => MenuFactory::class,
        MatcherInterface::class => Matcher::class,
        MenuProviderInterface::class => [self::class, 'initMenuProvider'],
        MenuRegistry::class => MenuProviderInterface::class,
        RendererProviderInterface::class => [self::class, 'initRenderer'],
        SpiralRenderer::class => SpiralRenderer::class,
    ];

    public function __construct(
        private readonly ConfiguratorInterface $config
    ) {
    }

    public function init(ViewsBootloader $views, DirectoriesInterface $dirs): void
    {
        $this->addViewsDirectory($views, $dirs);
        $this->initConfig();
    }

    public function boot(ContainerInterface $container): void
    {
        if (class_exists(TwigBootloader::class)) {
            $twig = $container->get(TwigBootloader::class);

            $twig->addExtension(MenuExtension::class);
        }
    }

    private function initConfig(): void
    {
        $this->config->setDefaults(
            KnpMenuConfig::CONFIG,
            [
                'template' => class_exists(TwigBootloader::class) ? 'knpMenu:knp_menu' : '',
                'template_options' => [],
                'menus' => [],
            ]
        );
    }

    private function addViewsDirectory(ViewsBootloader $views, DirectoriesInterface $dirs): void
    {
        switch (true) {
            case class_exists(TwigBootloader::class):
                $views->addDirectory(
                    'knpMenu',
                    rtrim($dirs->get('vendor'), '/').'/zentlix/knp-menu/views/twig'
                );
        }
    }

    private function initRenderer(Container $container): RendererProviderInterface
    {
        return new PsrProvider($container, SpiralRenderer::class);
    }

    private function initMenuProvider(KnpMenuConfig $config, Container $container): MenuRegistry
    {
        $registry = new MenuRegistry();

        foreach ($config->getMenus() as $name => $menu) {
            if (!$menu instanceof MenuInterface) {
                $menu = $container->get($menu);
            }

            \assert($menu instanceof MenuInterface);

            $registry->add(array_is_list($config->getMenus()) ? $menu::class : $name, $menu);
        }

        return $registry;
    }
}
