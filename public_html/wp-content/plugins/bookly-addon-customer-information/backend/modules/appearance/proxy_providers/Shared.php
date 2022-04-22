<?php
namespace BooklyCustomerInformation\Backend\Modules\Appearance\ProxyProviders;

use Bookly\Backend\Modules\Appearance\Proxy;
use BooklyCustomDuration\Lib;

/**
 * Class Shared
 * @package BooklyCustomerInformation\Backend\Modules\Appearance\ProxyProviders
 */
class Shared extends Proxy\Shared
{
    /**
     * @inheritdoc
     */
    public static function prepareOptions( array $options_to_save, array $options )
    {
        $options_to_save = array_merge( $options_to_save, array_intersect_key( $options, array_flip( array (
            'bookly_customer_information_enabled',
        ) ) ) );

        return $options_to_save;
    }
}