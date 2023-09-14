<?php

declare(strict_types=1);

namespace Asdoria\SyliusPickupPointPlugin\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

final class CheckPickupPointSelected extends Constraint
{
    public string $pickupPointNotValid = 'asdoria_pickup_point.shipment.pickup_point.not_valid';

    public function validatedBy(): string
    {
        return 'asdoria_sylius_pickup_point_check_pickup_point_selected';
    }

    public function getTargets(): string
    {
        return Constraint::CLASS_CONSTRAINT;
    }
}
