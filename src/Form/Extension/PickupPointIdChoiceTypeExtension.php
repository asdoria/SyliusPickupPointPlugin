<?php

declare(strict_types=1);


namespace Asdoria\SyliusPickupPointPlugin\Form\Extension;

use Setono\SyliusPickupPointPlugin\Form\Type\PickupPointIdChoiceType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;


/**
 * Class ShippingMethodTypeExtension
 *
 * @author Philippe Vesin <pve.asdoria@gmail.com>
 */
class PickupPointIdChoiceTypeExtension extends AbstractTypeExtension
{
    public function __construct(protected RouterInterface $router)
    {
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'attr' => ['data-route-path' => $this->router->generate('setono_sylius_pickup_point_shop_ajax_pickup_points_search_by_cart_address')]
        ]);
    }
    public static function getExtendedTypes(): iterable
    {
        return [PickupPointIdChoiceType::class];
    }
}
