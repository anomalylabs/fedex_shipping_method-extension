<?php namespace Anomaly\FedexShippingMethodExtension\Command;

use Anomaly\ConfigurationModule\Configuration\Contract\ConfigurationRepositoryInterface;
use Anomaly\ShippingModule\Method\Extension\MethodExtension;
use Anomaly\StoreModule\Contract\AddressInterface;
use Anomaly\StoreModule\Contract\ShippableInterface;
use FedEx\RateService\ComplexType\Address;
use FedEx\RateService\ComplexType\ClientDetail;
use FedEx\RateService\ComplexType\Dimensions;
use FedEx\RateService\ComplexType\Party;
use FedEx\RateService\ComplexType\Payment;
use FedEx\RateService\ComplexType\Payor;
use FedEx\RateService\ComplexType\RateReply;
use FedEx\RateService\ComplexType\RateRequest;
use FedEx\RateService\ComplexType\RequestedPackageLineItem;
use FedEx\RateService\ComplexType\RequestedShipment;
use FedEx\RateService\ComplexType\TransactionDetail;
use FedEx\RateService\ComplexType\VersionId;
use FedEx\RateService\ComplexType\Weight;
use FedEx\RateService\Request;
use FedEx\RateService\SimpleType\DropoffType;
use FedEx\RateService\SimpleType\LinearUnits;
use FedEx\RateService\SimpleType\PaymentType;
use FedEx\RateService\SimpleType\RateRequestType;
use FedEx\RateService\SimpleType\WeightUnits;
use Illuminate\Contracts\Config\Repository;
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
     * @param Repository                       $config
     */
    public function handle(ConfigurationRepositoryInterface $configuration, Repository $config)
    {
        $method = $this->extension->getMethod();

        /* @var RateRequest $rateRequest */
        $rateRequest = $this->dispatch(new GetRate($method));

        $code = $configuration->value('anomaly.extension.fedex_shipping_method::service', $method->getId());

        $accountNumber = $config->get('anomaly.extension.fedex_shipping_method::config.account_number');
        $meterNumber   = $config->get('anomaly.extension.fedex_shipping_method::config.meter_number');

        //ClientDetail
        $clientDetail = new ClientDetail();
        $clientDetail
            ->setAccountNumber($accountNumber)
            ->setMeterNumber($meterNumber);

        $rateRequest->setClientDetail($clientDetail);

        //TransactionDetail
        $transactionDetail = new TransactionDetail();
        $transactionDetail->setCustomerTransactionId('Testing Rate Service request');
        $rateRequest->setTransactionDetail($transactionDetail);

        //VersionId
        $versionId = new VersionId();
        $versionId
            ->setServiceId('crs')
            ->setMajor(10)
            ->setIntermediate(0)
            ->setMinor(0);
        $rateRequest->setVersion($versionId);

        //OPTIONAL ReturnTransitAndCommit
        $rateRequest->setReturnTransitAndCommit(true);

        //RequestedShipment
        $requestedShipment = new RequestedShipment();
        $requestedShipment->setDropoffType(DropoffType::_REGULAR_PICKUP);
        $requestedShipment->setShipTimestamp(date('c'));
        $rateRequest->setRequestedShipment($requestedShipment);

        //RequestedShipment/Shipper
        $shipper        = new Party();
        $shipperAddress = new Address();
        $shipperAddress
            ->setStreetLines(array('10 Fed Ex Pkwy'))
            ->setCity('Memphis')
            ->setStateOrProvinceCode('TN')
            ->setPostalCode(38115)
            ->setCountryCode('US');
        $shipper->setAddress($shipperAddress);
        $requestedShipment->setShipper($shipper);

        //RequestedShipment/Recipient
        $recipient        = new Party();
        $recipientAddress = new Address();
        $recipientAddress
            ->setStreetLines(array($this->address->getStreetAddress()))
            ->setCity($this->address->getCity())
            ->setStateOrProvinceCode($this->address->getState())
            ->setPostalCode($this->address->getPostalCode())
            ->setCountryCode($this->address->getCountry());
        $recipient->setAddress($recipientAddress);
        $requestedShipment->setRecipient($recipient);

        //RequestedShipment/ShippingChargesPayment
        $shippingChargesPayment = new Payment();
        $shippingChargesPayment->setPaymentType(PaymentType::_SENDER);
        $payor = new Payor();
        $payor
            ->setAccountNumber($accountNumber)
            ->setCountryCode('US');
        $shippingChargesPayment->setPayor($payor);
        $requestedShipment->setShippingChargesPayment($shippingChargesPayment);

        //RequestedShipment/RateRequestType(s)
        $requestedShipment->setRateRequestTypes(
            [
                RateRequestType::_LIST,
                RateRequestType::_ACCOUNT,
            ]
        );

        //RequestedShipment/PackageCount
        $requestedShipment->setPackageCount(1);

        //RequestedShipment/RequestedPackageLineItem(s)
        $itemWeight = new Weight();
        $itemWeight
            ->setUnits(WeightUnits::_LB)
            ->setValue(2.0);
        $itemDimensions = new Dimensions();
        $itemDimensions
            ->setLength(10)
            ->setWidth(10)
            ->setHeight(3)
            ->setUnits(LinearUnits::_IN);
        $item = new RequestedPackageLineItem();
        $item
            ->setWeight($itemWeight)
            ->setDimensions($itemDimensions)
            ->setGroupPackageCount(1);
        $requestedShipment->setRequestedPackageLineItems([$item]);
        $rateRequest->setRequestedShipment($requestedShipment);
        $rateServiceRequest = new Request();

        /* @var RateReply $response */
        //$rateServiceRequest->getSoapClient()->__setLocation('https://ws.fedex.com:443/web-services/rate'); //use the production web service
        $response = $rateServiceRequest->getGetRatesReply($rateRequest);

        foreach ($response->RateReplyDetails as $detail) {
            if ($detail->ServiceType == $code) {
                return (float)$detail
                    ->RatedShipmentDetails[0]
                    ->ShipmentRateDetail
                    ->TotalNetChargeWithDutiesAndTaxes
                    ->Amount;
            }
        }

        return null;
    }
}
