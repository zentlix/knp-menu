<?php

declare(strict_types=1);

namespace Spiral\KnpMenu;

use Knp\Menu\ItemInterface;
use Knp\Menu\Provider\MenuProviderInterface;
use Spiral\KnpMenu\Exception\MenuException;

final class MenuRegistry implements MenuProviderInterface
{
    /**
     * @var array<string, MenuInterface>
     */
    private array $items = [];

    public function add(string $name, MenuInterface $menu): void
    {
        if (!$this->has($name)) {
            $this->items[$name] = $menu;
        }
    }

    public function get(string $name, array $options = []): ItemInterface
    {
        if ($this->has($name)) {
            return $this->items[$name]->create($options);
        }

        throw new MenuException(sprintf('Menu with name `%s` not found.', $name));
    }

    public function has(string $name, array $options = []): bool
    {
        return isset($this->items[$name]);
    }
}
