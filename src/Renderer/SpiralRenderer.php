<?php

declare(strict_types=1);

namespace Spiral\KnpMenu\Renderer;

use Knp\Menu\ItemInterface;
use Knp\Menu\Matcher\MatcherInterface;
use Knp\Menu\Renderer\RendererInterface;
use Spiral\KnpMenu\Config\KnpMenuConfig;
use Spiral\Views\ViewsInterface;

final class SpiralRenderer implements RendererInterface
{
    public function __construct(
        private readonly ViewsInterface $views,
        private readonly MatcherInterface $matcher,
        private readonly KnpMenuConfig $config
    ) {
    }

    public function render(ItemInterface $item, array $options = []): string
    {
        $templateOptions = array_merge($this->config->getTemplateOptions(), $options);

        $html = $this->views->render(
            $options['template'] ?? $this->config->getTemplate(),
            ['item' => $item, 'options' => $templateOptions, 'matcher' => $this->matcher]
        );

        if ($options['clear_matcher'] ?? true) {
            $this->matcher->clear();
        }

        return $html;
    }
}
