<?php

declare(strict_types=1);

namespace Spiral\KnpMenu\Extension;

use Knp\Menu\Factory\ExtensionInterface;
use Knp\Menu\ItemInterface;
use Spiral\Router\RouterInterface;

final class RoutingExtension implements ExtensionInterface
{
    public function __construct(
        private readonly RouterInterface $router
    ) {
    }

    public function buildOptions(array $options): array
    {
        if (!empty($options['route'])) {
            $params = $options['routeParameters'] ?? [];

            $options['uri'] = (string) $this->router->uri($options['route'], $params);

            // adding the item route to the extras for the RouteVoter
            $options['extras']['route'] = $options['route'];
            $options['extras']['routeParameters'] = $params;
        }

        return $options;
    }

    public function buildItem(ItemInterface $item, array $options): void
    {
    }
}
