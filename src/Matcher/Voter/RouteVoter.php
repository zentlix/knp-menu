<?php

declare(strict_types=1);

namespace Spiral\KnpMenu\Matcher\Voter;

use Knp\Menu\ItemInterface;
use Knp\Menu\Matcher\Voter\VoterInterface;
use Spiral\Http\Request\InputManager;
use Spiral\Router\Router;

final class RouteVoter implements VoterInterface
{
    public function __construct(
        private readonly InputManager $request
    ) {
    }

    public function matchItem(ItemInterface $item): ?bool
    {
        $route = $this->request->attributes->get(Router::ROUTE_NAME);
        $testedRoute = $item->getExtra('route');

        if (null === $route || null === $testedRoute) {
            return null;
        }

        if (!\is_string($testedRoute)) {
            throw new \InvalidArgumentException('Route extra item must be string.');
        }

        if ($route !== $testedRoute) {
            return false;
        }

        $parameters = $this->request->attributes->get(Router::ROUTE_MATCHES);
        foreach ($item->getExtra('routeParameters', []) as $name => $testedParameter) {
            if ((string) $parameters[$name] !== (string) $testedParameter) {
                return false;
            }
        }

        return true;
    }
}
