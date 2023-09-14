<?php

declare(strict_types=1);

namespace Asdoria\SyliusPickupPointPlugin\Api\Soap;

/**
 * Class SoapClientInterface
 * @package Asdoria\SyliusPickupPointPlugin\Api\Soap
 *
 * @author  Philippe Vesin <pve.asdoria@gmail.com>
 */
interface SoapClientInterface
{
    /**
     * @return \SoapClient
     */
    public function getClient() :\SoapClient;
}
