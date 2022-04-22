<?php
namespace BooklyCustomerInformation\Lib;

use Bookly\Lib as BooklyLib;
use BooklyCustomerInformation\Backend;
use BooklyCustomerInformation\Frontend;

/**
 * Class Plugin
 * @package BooklyCustomerInformation\Lib
 */
abstract class Plugin extends BooklyLib\Base\Plugin
{
    protected static $prefix;
    protected static $title;
    protected static $version;
    protected static $slug;
    protected static $directory;
    protected static $main_file;
    protected static $basename;
    protected static $text_domain;
    protected static $root_namespace;
    protected static $embedded;

    /**
     * @inheritdoc
     */
    protected static function init()
    {
        // Init ajax.
        Backend\Modules\CustomerInformation\Ajax::init();

        // Init proxy.
        Backend\Components\Dialogs\Customer\ProxyProviders\Local::init();
        Backend\Components\Dialogs\Customer\ProxyProviders\Shared::init();
        Backend\Modules\Appearance\ProxyProviders\Local::init();
        Backend\Modules\Appearance\ProxyProviders\Shared::init();
        Backend\Modules\Customers\ProxyProviders\Local::init();
        Backend\Modules\Customers\ProxyProviders\Shared::init();
        if ( get_option( 'bookly_customer_information_enabled' ) ) {
            Frontend\Modules\Booking\ProxyProviders\Local::init();
        }
        ProxyProviders\Local::init();
        ProxyProviders\Shared::init();
    }
}