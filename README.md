# KnpMenu

[![PHP Version Require](https://poser.pugx.org/zentlix/knp-menu/require/php)](https://packagist.org/packages/zentlix/knp-menu)
[![Latest Stable Version](https://poser.pugx.org/zentlix/knp-menu/v/stable)](https://packagist.org/packages/zentlix/knp-menu)
[![phpunit](https://github.com/zentlix/knp-menu/actions/workflows/phpunit.yml/badge.svg)](https://github.com/zentlix/knp-menu/actions)
[![psalm](https://github.com/zentlix/knp-menu/actions/workflows/static-analysis.yml/badge.svg)](https://github.com/zentlix/knp-menu/actions)
[![Codecov](https://codecov.io/gh/zentlix/knp-menu/branch/master/graph/badge.svg)](https://codecov.io/gh/zentlix/knp-menu)
[![Total Downloads](https://poser.pugx.org/zentlix/knp-menu/downloads)](https://packagist.org/packages/zentlix/knp-menu)
[![type-coverage](https://shepherd.dev/github/zentlix/knp-menu/coverage.svg)](https://shepherd.dev/github/zentlix/knp-menu)
[![psalm-level](https://shepherd.dev/github/zentlix/knp-menu/level.svg)](https://shepherd.dev/github/zentlix/knp-menu)

The package provides object-oriented menus for projects based on the Spiral Framework.

## Requirements

Make sure that your server is configured with following PHP version and extensions:

- PHP 8.1+
- Spiral framework 3.5+

## Installation

You can install the package via composer:

```bash
composer require zentlix/knp-menu
```

To enable the package, you just need to add `Spiral\KnpMenu\Bootloader\KnpMenuBootloader`
to the bootloaders list, which is located in the class of your application.

```php
protected const LOAD = [
    // ...
    \Spiral\KnpMenu\Bootloader\KnpMenuBootloader::class,
];
```

> **Note**
> If you are using [`spiral-packages/discoverer`](https://github.com/spiral-packages/discoverer),
> you don't need to register bootloader by yourself.

## Configuration

The configuration file for this package should be located at `app/config/knp-menu.php`.
Within this file, you may configure default `template` and `template_options` for all menus.
Also, you can register `menus`.

For example, the configuration file might look like this.

```php
use App\Menu\Sidebar;
use App\Menu\TopBar;
use App\Menu\OtherMenu;
use Spiral\Core\Container\Autowire;

return [
    /**
     * -------------------------------------------------------------------------
     *  Default template for all menus
     * -------------------------------------------------------------------------
     */
    'template' => 'menu.twig',

    /**
     * -------------------------------------------------------------------------
     *  Template options for all menus
     * -------------------------------------------------------------------------
     */
    'template_options' => [
        'foo' => 'bar'
    ],

    /**
     * -------------------------------------------------------------------------
     *  Application menus list
     * -------------------------------------------------------------------------
     *
     *  As a key, you can specify the name of the menu, using this key you can get the menu.
     *  If the key is not specified, the fully qualified name of the class will be used as the key.
     */
    'menus' => [
        'sidebar' => Sidebar::class,
        'top-bar' => new Autowire(TopBar::class),
        'other' => new OtherMenu()
    ]
];
```

## Creating a menu

To create a menu, create a class and implement interface `Spiral\KnpMenu\MenuInterface`.

```php
namespace App\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Spiral\KnpMenu\MenuInterface;

final class Sidebar implements MenuInterface
{
    public function __construct(
        private readonly FactoryInterface $factory
    ) {
    }

    public function create(array $options = []): ItemInterface
    {
        $menu = $this->factory->createItem('root');

        $menu->addChild('Home', ['route' => 'homepage']);
        $menu->addChild('Blog', ['uri' => '/posts']);
        $menu->addChild('Comments', ['uri' => '#comments']);
        $menu->addChild('Full URL', ['uri' => 'https://site.com/']);

        return $menu;
    }
}
```

After that, you can register the menu in the `config` file as shown above,
or register it using `Spiral\KnpMenu\MenuRegistry`.

```php
namespace App\Bootloader;

use App\Menu\Sidebar;
use Spiral\Boot\Bootloader\Bootloader;
use Spiral\KnpMenu\Bootloader\KnpMenuBootloader;
use Spiral\KnpMenu\MenuRegistry;

final class MenuBootloader extends Bootloader
{
    protected const DEPENDENCIES = [
        KnpMenuBootloader::class
    ];

    public function boot(MenuRegistry $registry, Sidebar $menu): void
    {
        $registry->add('sidebar', $menu);
    }
}
```

## Using

If you are using Twig, you can use the `knp_menu_render` extension to render the menu.

```php
{{ knp_menu_render('sidebar') }}
```

With custom template or other options.

```php
{{ knp_menu_render('sidebar', {'template': 'custom.twig'}) }}
```

> **Warning**
> To use other templating engines, need to implement the menu display mechanism yourself.

## Testing

```bash
composer test
```

```bash
composer psalm
```

```bash
composer cs
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
