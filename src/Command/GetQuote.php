<?php namespace Anomaly\FedexShippingMethodExtension\Command;

use Anomaly\ConfigurationModule\Configuration\Contract\ConfigurationRepositoryInterface;
use Anomaly\ShippingModule\Method\Extension\MethodExtension;
use Anomaly\StoreModule\Contract\AddressInterface;
use Anomaly\StoreModule\Contract\ShippableInterface;
use Illuminate\Foundation\Bus\DispatchesJobs;

/**
 * Class GetQuote
 *
 * @link          http://pyrocms.com/
 * @author        PyroCMS, Inc. <support@pyrocms.com>
 * @author        Ryan Thompson <ryan@pyrocms.com>
 * @package       Anomaly\FedexShippingMethodExtension\Command
 */
class GetQuote
{

    use DispatchesJobs;

    /**
     * The shippable interface.
     *
     * @var ShippableInterface
     */
    protected $shippable;

    /**
     * The shipping extension.
     *
     * @var MethodExtension
     */
    protected $extension;

    /**
     * The parameter array.
     *
     * @var array
     */
    protected $address;

    /**
     * Create a new GetQuote instance.
     *
     * @param MethodExtension    $extension
     * @param ShippableInterface $shippable
     * @param AddressInterface   $address
     */
    public function __construct(MethodExtension $extension, ShippableInterface $shippable, AddressInterface $address)
    {
        $this->shippable = $shippable;
        $this->extension = $extension;
        $this->address   = $address;
    }

    /**
     * Handle the command.
     *
     * @param ConfigurationRepositoryInterface $configuration
     */
    public function handle(ConfigurationRepositoryInterface $configuration)
    {
        $method = $this->order->getShippingMethod();

        /* @var Rate $rate */
        $rate = $this->dispatch(new GetRate($method));

        $code = $configuration->value('anomaly.extension.fedex_shipping_method::service', $method->getSlug());

        $shipment = new Shipment();

        /**
         * Set the shipping service.
         */
        $service = new Service();
        $service->setCode($code);

        $shipment->setService($service);

        /**
         * Set the shipper's information.
         */
        $shipperAddress = $shipment
            ->getShipper()
            ->getAddress();
        $shipperAddress->setPostalCode('61241');

        /**
         * Set the origin information.
         */
        $fromAddress = new Address();
        $fromAddress->setPostalCode('61241');
        $shipFrom = new ShipFrom();
        $shipFrom->setAddress($fromAddress);

        $shipment->setShipFrom($shipFrom);

        /**
         * Set the destination information.
         */
        $shipTo = $shipment->getShipTo();
        $shipTo->setCompanyName('Test Ship To');
        $shipToAddress = $shipTo->getAddress();
        $shipToAddress->setPostalCode('99205');

        /**
         * Add a package to the shipment.
         */
        $package = new Package();
        $package->getPackagingType()->setCode(PackagingType::PT_PACKAGE);
        $package->getPackageWeight()->setWeight(10);

        $dimensions = new Dimensions();
        $dimensions->setHeight(10);
        $dimensions->setWidth(10);
        $dimensions->setLength(10);

        $unit = new UnitOfMeasurement();
        $unit->setCode(UnitOfMeasurement::UOM_IN);

        $dimensions->setUnitOfMeasurement($unit);
        $package->setDimensions($dimensions);

        $shipment->addPackage($package);

        /* @var RateResponse $response */
        $response = $rate->getRate($shipment);

        /* @var RatedShipment $quote */
        $quote = $response->RatedShipment[0];

        return $quote->TotalCharges->MonetaryValue;
    }
}
