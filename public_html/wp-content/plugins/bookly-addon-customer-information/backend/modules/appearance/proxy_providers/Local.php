<?php
namespace BooklyCustomerInformation\Backend\Modules\Appearance\ProxyProviders;

use Bookly\Backend\Modules\Appearance\Proxy;

/**
 * Class Proxy
 * @package BooklyCustomerInformation\Backend\Modules\Appearance\ProxyProviders
 */
class Local extends Proxy\CustomerInformation
{
    /**
     * @inheritdoc
     */
    public static function renderShowCustomerInformation()
    {
        self::renderTemplate( 'show_customer_information' );
    }

    /**
     * @inheritdoc
     */
    public static function renderCustomerInformation()
    {
        self::renderTemplate( 'customer_information' );
    }
}