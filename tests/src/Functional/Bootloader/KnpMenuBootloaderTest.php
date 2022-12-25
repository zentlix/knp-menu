<?php

declare(strict_types=1);

namespace Spiral\KnpMenu\Tests\Functional\Bootloader;

use Knp\Menu\FactoryInterface;
use Knp\Menu\Matcher\Matcher;
use Knp\Menu\Matcher\MatcherInterface;
use Knp\Menu\MenuFactory;
use Knp\Menu\Provider\MenuProviderInterface;
use Knp\Menu\Renderer\PsrProvider;
use Knp\Menu\Renderer\RendererProviderInterface;
use Knp\Menu\Twig\MenuExtension;
use Spiral\Boot\DirectoriesInterface;
use Spiral\KnpMenu\Config\KnpMenuConfig;
use Spiral\KnpMenu\MenuRegistry;
use Spiral\KnpMenu\Renderer\SpiralRenderer;
use Spiral\KnpMenu\Tests\Functional\TestCase;
use Spiral\Twig\Config\TwigConfig;
use Spiral\Twig\Extension\ContainerExtension;
use Spiral\Views\Bootloader\ViewsBootloader;
use Spiral\Views\Config\ViewsConfig;

final class KnpMenuBootloaderTest extends TestCase
{
    public function testViewsBootloaderShouldBeBooted(): void
    {
        $this->assertBootloaderRegistered(ViewsBootloader::class);
    }

    public function testFactoryShouldBeBoundAsSingleton(): void
    {
        $this->assertContainerBoundAsSingleton(FactoryInterface::class, MenuFactory::class);
        $this->assertContainerBoundAsSingleton(MenuFactory::class, MenuFactory::class);
    }

    public function testMatcherShouldBeBoundAsSingleton(): void
    {
        $this->assertContainerBoundAsSingleton(MatcherInterface::class, Matcher::class);
    }

    public function testMenuProviderShouldBeBoundAsSingleton(): void
    {
        $this->assertContainerBoundAsSingleton(MenuProviderInterface::class, MenuRegistry::class);
        $this->assertContainerBoundAsSingleton(MenuRegistry::class, MenuRegistry::class);
    }

    public function testRendererProviderShouldBeBoundAsSingleton(): void
    {
        $this->assertContainerBoundAsSingleton(RendererProviderInterface::class, PsrProvider::class);
        $this->assertContainerBoundAsSingleton(SpiralRenderer::class, SpiralRenderer::class);
    }

    public function testTwigViewsShouldBeRegistered(): void
    {
        $container = $this->getApp()->getContainer();
        $dirs = $container->get(DirectoriesInterface::class);

        $this->assertConfigHasFragments(
            ViewsConfig::CONFIG,
            [
                'namespaces' => [
                    'default' => [
                        \rtrim($dirs->get('app'), '/') . '/views/'
                    ],
                    'knpMenu' => [
                        \rtrim($dirs->get('vendor'), '/') . '/zentlix/knp-menu/views/twig'
                    ]
                ]
            ]
        );
    }

    public function testDefaultConfigShouldBeDefined(): void
    {
        $this->assertConfigMatches(KnpMenuConfig::CONFIG, [
            'template' => 'knpMenu:knp_menu',
            'template_options' => [],
            'menus' => []
        ]);
    }

    public function testTwigExtensionShouldBeAdded(): void
    {
        $this->assertConfigHasFragments(
            TwigConfig::CONFIG,
            [
                'extensions' => [
                    ContainerExtension::class,
                    MenuExtension::class
                ]
            ]
        );
    }
}
