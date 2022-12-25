<?php

declare(strict_types=1);

namespace Spiral\KnpMenu\Tests\Unit\Renderer;

use Knp\Menu\ItemInterface;
use Knp\Menu\Matcher\MatcherInterface;
use PHPUnit\Framework\TestCase;
use Spiral\KnpMenu\Config\KnpMenuConfig;
use Spiral\KnpMenu\Renderer\SpiralRenderer;
use Spiral\Views\ViewsInterface;

final class SpiralRendererTest extends TestCase
{
    private ItemInterface $item;
    private MatcherInterface $matcher;
    private ViewsInterface $views;

    protected function setUp(): void
    {
        $this->item = $this->createMock(ItemInterface::class);
        $this->matcher = $this->createMock(MatcherInterface::class);
        $this->views = $this->createMock(ViewsInterface::class);
    }

    public function testRenderWithoutOptions(): void
    {
        $this->matcher
            ->expects($this->once())
            ->method('clear');

        $this->views
            ->expects($this->once())
            ->method('render')
            ->with('foo', [
                'item' => $this->item,
                'options' => [],
                'matcher' => $this->matcher
            ])
            ->willReturn('string');

        $renderer = new SpiralRenderer($this->views, $this->matcher, new KnpMenuConfig(['template' => 'foo']));

        $this->assertSame('string', $renderer->render($this->item));
    }

    public function testRenderWithCustomTemplate(): void
    {
        $this->matcher
            ->expects($this->once())
            ->method('clear');

        $this->views
            ->expects($this->once())
            ->method('render')
            ->with('custom', [
                'item' => $this->item,
                'options' => ['template' => 'custom'],
                'matcher' => $this->matcher
            ])
            ->willReturn('string');

        $renderer = new SpiralRenderer($this->views, $this->matcher, new KnpMenuConfig(['template' => 'foo']));

        $this->assertSame('string', $renderer->render($this->item, ['template' => 'custom']));
    }

    public function testRenderWithTemplateOptions(): void
    {
        $this->matcher
            ->expects($this->once())
            ->method('clear');

        $this->views
            ->expects($this->once())
            ->method('render')
            ->with('foo', [
                'item' => $this->item,
                'options' => ['foo' => 'bar'],
                'matcher' => $this->matcher
            ])
            ->willReturn('string');

        $renderer = new SpiralRenderer($this->views, $this->matcher, new KnpMenuConfig(['template' => 'foo']));

        $this->assertSame('string', $renderer->render($this->item, ['foo' => 'bar']));
    }

    public function testRenderWithClearMatcherFalse(): void
    {
        $this->matcher
            ->expects($this->never())
            ->method('clear');

        $this->views
            ->expects($this->once())
            ->method('render')
            ->with('foo', [
                'item' => $this->item,
                'options' => ['clear_matcher' => false],
                'matcher' => $this->matcher
            ])
            ->willReturn('string');

        $renderer = new SpiralRenderer($this->views, $this->matcher, new KnpMenuConfig(['template' => 'foo']));

        $this->assertSame('string', $renderer->render($this->item, ['clear_matcher' => false]));
    }
}
