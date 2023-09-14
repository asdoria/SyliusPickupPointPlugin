<?php

declare(strict_types=1);

namespace Asdoria\SyliusPickupPointPlugin\Validator\Constraints;

use Setono\SyliusPickupPointPlugin\Model\PickupPointCode;
use Setono\SyliusPickupPointPlugin\Model\PickupPointInterface;
use Setono\SyliusPickupPointPlugin\Model\PickupPointProviderAwareInterface;
use Setono\SyliusPickupPointPlugin\Model\ShipmentInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

final class CheckPickupPointSelectedValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint): void
    {
        if (null === $value || '' === $value) {
            return;
        }

        if (!$constraint instanceof CheckPickupPointSelected) {
            throw new UnexpectedTypeException($constraint, CheckPickupPointSelected::class);
        }

        if (!$value instanceof ShipmentInterface) {
            return;
        }

        $method = $value->getMethod();

        if (!$method instanceof PickupPointProviderAwareInterface) {
            return;
        }

        if (!$method->hasPickupPointProvider()) {
            return;
        }

        if (!$value->hasPickupPointId()) {
            return;
        }

        $pickupPointId = PickupPointCode::createFromString($value->getPickupPointId());

        if ($pickupPointId->getProviderPart() !== $method->getPickupPointProvider()) {
            $this->context
                ->buildViolation($constraint->pickupPointNotValid)
                ->addViolation()
            ;
        }
    }
}
