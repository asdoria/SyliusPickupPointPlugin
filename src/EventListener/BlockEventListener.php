<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Sylius Sp. z o.o.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Asdoria\SyliusPickupPointPlugin\EventListener;

use Sonata\BlockBundle\Event\BlockEvent;
use Sylius\Bundle\UiBundle\Block\BlockEventListener as BaseBlockEventListener;
use Sonata\BlockBundle\Model\Block;

/**
 * Class BlockEventListener
 * @package Asdoria\SyliusPickupPointPlugin\EventListener
 *
 * @author  Philippe Vesin <pve.asdoria@gmail.com>
 */
final class BlockEventListener
{

    public function onBlockEvent(BlockEvent $event): void
    {

    }
}
