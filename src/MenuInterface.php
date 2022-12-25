<?php

declare(strict_types=1);

namespace Spiral\KnpMenu;

use Knp\Menu\ItemInterface;

interface MenuInterface
{
    public function create(array $options = []): ItemInterface;
}
