<?php namespace Anomaly\FedexShippingMethodExtension;

use Anomaly\FedexShippingMethodExtension\Command\GetQuote;
use Anomaly\ShippingModule\Method\Extension\MethodExtension;
use Anomaly\StoreModule\Contract\AddressInterface;
use Anomaly\StoreModule\Contract\ShippableInterface;

/**
 * Class FedexShippingMethodExtension
 *
 * @link   http://pyrocms.com/
 * @author PyroCMS, Inc. <support@pyrocms.com>
 * @author Ryan Thompson <ryan@pyrocms.com>
 */
class FedexShippingMethodExtension extends MethodExtension
{

    /**
     * This extension provides the UPS shipping
     * type for the shipping module.
     *
     * @var null|string
     */
    protected $provides = 'anomaly.module.shipping::method.fedex';

    /**
     * Get a shipping quote.
     *
     * @param ShippableInterface $shippable
     * @param AddressInterface   $address
     * @return float
     */
    public function quote(ShippableInterface $shippable, AddressInterface $address)
    {
        return $this->dispatch(new GetQuote($this, $shippable, $address));
    }

}
