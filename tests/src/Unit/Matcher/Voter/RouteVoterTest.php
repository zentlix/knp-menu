<?php

declare(strict_types=1);

namespace Spiral\KnpMenu\Tests\Unit\Matcher\Voter;

use Knp\Menu\ItemInterface;
use Nyholm\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Spiral\Core\Container;
use Spiral\Http\Request\InputManager;
use Spiral\KnpMenu\Matcher\Voter\RouteVoter;
use Spiral\Router\Router;

final class RouteVoterTest extends TestCase
{
    public function testMatchItemRouteAndAttributeNull(): void
    {
        $serverRequest = new ServerRequest('GET', '/');
        $item = $this->createMock(ItemInterface::class);
        $item
            ->expects($this->once())
            ->method('getExtra')
            ->with('route')
            ->willReturn(null);

        $container = new Container();
        $container->bind(ServerRequestInterface::class, $serverRequest);

        $request = new InputManager($container);
        $voter = new RouteVoter($request);

        $this->assertNull($voter->matchItem($item));
    }

    public function testMatchItemAttributeNull(): void
    {
        $serverRequest = new ServerRequest('GET', '/');
        $item = $this->createMock(ItemInterface::class);
        $item
            ->expects($this->once())
            ->method('getExtra')
            ->with('route')
            ->willReturn('foo');

        $container = new Container();
        $container->bind(ServerRequestInterface::class, $serverRequest);

        $request = new InputManager($container);
        $voter = new RouteVoter($request);

        $this->assertNull($voter->matchItem($item));
    }

    public function testMatchItemRouteNull(): void
    {
        $serverRequest = new ServerRequest('GET', '/');
        $serverRequest = $serverRequest->withAttribute(Router::ROUTE_NAME, 'foo');
        $item = $this->createMock(ItemInterface::class);
        $item
            ->expects($this->once())
            ->method('getExtra')
            ->with('route')
            ->willReturn(null);

        $container = new Container();
        $container->bind(ServerRequestInterface::class, $serverRequest);

        $request = new InputManager($container);
        $voter = new RouteVoter($request);

        $this->assertNull($voter->matchItem($item));
    }

    public function testInvalidRoute(): void
    {
        $serverRequest = new ServerRequest('GET', '/');
        $serverRequest = $serverRequest->withAttribute(Router::ROUTE_NAME, 'foo');
        $item = $this->createMock(ItemInterface::class);
        $item
            ->expects($this->once())
            ->method('getExtra')
            ->with('route')
            ->willReturn(new \stdClass());

        $container = new Container();
        $container->bind(ServerRequestInterface::class, $serverRequest);

        $request = new InputManager($container);
        $voter = new RouteVoter($request);

        $this->expectException(\InvalidArgumentException::class);
        $voter->matchItem($item);
    }

    public function testDifferentRoutes(): void
    {
        $serverRequest = new ServerRequest('GET', '/');
        $serverRequest = $serverRequest->withAttribute(Router::ROUTE_NAME, 'foo');
        $item = $this->createMock(ItemInterface::class);
        $item
            ->expects($this->once())
            ->method('getExtra')
            ->with('route')
            ->willReturn('bar');

        $container = new Container();
        $container->bind(ServerRequestInterface::class, $serverRequest);

        $request = new InputManager($container);
        $voter = new RouteVoter($request);

        $this->assertFalse($voter->matchItem($item));
    }

    public function testSameRoutes(): void
    {
        $serverRequest = new ServerRequest('GET', '/');
        $serverRequest = $serverRequest->withAttribute(Router::ROUTE_NAME, 'foo');
        $item = $this->createMock(ItemInterface::class);
        $item
            ->expects($this->exactly(2))
            ->method('getExtra')
            ->willReturnOnConsecutiveCalls('foo', []);

        $container = new Container();
        $container->bind(ServerRequestInterface::class, $serverRequest);

        $request = new InputManager($container);
        $voter = new RouteVoter($request);

        $this->assertTrue($voter->matchItem($item));
    }

    public function testSameRoutesAndSameParameters(): void
    {
        $serverRequest = new ServerRequest('GET', '/');
        $serverRequest = $serverRequest->withAttribute(Router::ROUTE_NAME, 'foo');
        $serverRequest = $serverRequest->withAttribute(Router::ROUTE_MATCHES, ['id' => 1]);
        $item = $this->createMock(ItemInterface::class);
        $item
            ->expects($this->exactly(2))
            ->method('getExtra')
            ->willReturnOnConsecutiveCalls('foo', ['id' => 1]);

        $container = new Container();
        $container->bind(ServerRequestInterface::class, $serverRequest);

        $request = new InputManager($container);
        $voter = new RouteVoter($request);

        $this->assertTrue($voter->matchItem($item));
    }

    public function testSameRoutesAndSameParameterAndWrongParameter(): void
    {
        $serverRequest = new ServerRequest('GET', '/');
        $serverRequest = $serverRequest->withAttribute(Router::ROUTE_NAME, 'foo');
        $serverRequest = $serverRequest->withAttribute(Router::ROUTE_MATCHES, ['id' => 1, 'invalid' => 2]);
        $item = $this->createMock(ItemInterface::class);
        $item
            ->expects($this->exactly(2))
            ->method('getExtra')
            ->willReturnOnConsecutiveCalls('foo', ['id' => 1, 'invalid' => 3]);

        $container = new Container();
        $container->bind(ServerRequestInterface::class, $serverRequest);

        $request = new InputManager($container);
        $voter = new RouteVoter($request);

        $this->assertFalse($voter->matchItem($item));
    }

    public function testSameRoutesAndWrongParameter(): void
    {
        $serverRequest = new ServerRequest('GET', '/');
        $serverRequest = $serverRequest->withAttribute(Router::ROUTE_NAME, 'foo');
        $serverRequest = $serverRequest->withAttribute(Router::ROUTE_MATCHES, ['id' => 1]);
        $item = $this->createMock(ItemInterface::class);
        $item
            ->expects($this->exactly(2))
            ->method('getExtra')
            ->willReturnOnConsecutiveCalls('foo', ['id' => 3]);

        $container = new Container();
        $container->bind(ServerRequestInterface::class, $serverRequest);

        $request = new InputManager($container);
        $voter = new RouteVoter($request);

        $this->assertFalse($voter->matchItem($item));
    }
}
