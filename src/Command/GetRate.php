<?php namespace Anomaly\FedexShippingMethodExtension\Command;

use Anomaly\ShippingModule\Method\Contract\MethodInterface;
use Anomaly\ShippingModule\Method\Extension\MethodExtension;
use FedEx\RateService\ComplexType\RateRequest;
use FedEx\RateService\ComplexType\WebAuthenticationCredential;
use FedEx\RateService\ComplexType\WebAuthenticationDetail;
use Illuminate\Contracts\Config\Repository;

/**
 * Class GetRate
 *
 * @link          http://pyrocms.com/
 * @author        PyroCMS, Inc. <support@pyrocms.com>
 * @author        Ryan Thompson <ryan@pyrocms.com>
 * @package       Anomaly\FedexShippingMethodExtension\Command
 */
class GetRate
{

    /**
     * The method interface.
     *
     * @var MethodInterface
     */
    protected $method;

    /**
     * The method extension.
     *
     * @var MethodExtension
     */
    protected $extension;

    /**
     * Handle the command.
     *
     * @param Repository $config
     * @return RateRequest
     */
    public function handle(Repository $config)
    {
        $rateRequest    = new RateRequest();
        $userCredential = new WebAuthenticationCredential();

        $userCredential
            ->setKey($config->get('anomaly.extension.fedex_shipping_method::config.access_key'))
            ->setPassword($config->get('anomaly.extension.fedex_shipping_method::config.password'));

        $rateRequest->setWebAuthenticationDetail(
            (new WebAuthenticationDetail())->setUserCredential($userCredential)
        );

        return $rateRequest;
    }
}
