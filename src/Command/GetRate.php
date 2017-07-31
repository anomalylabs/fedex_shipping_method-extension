<?php namespace Anomaly\FedexShippingMethodExtension\Command;

use Anomaly\ShippingModule\Method\Contract\MethodInterface;
use Anomaly\ShippingModule\Method\Extension\MethodExtension;
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
     * @return Rate
     */
    public function handle(Repository $config)
    {
        return new Rate(
            $config->get('anomaly.extension.fedex_shipping_method::config.access_key'),
            $config->get('anomaly.extension.fedex_shipping_method::config.username'),
            $config->get('anomaly.extension.fedex_shipping_method::config.password')
        );
    }
}
