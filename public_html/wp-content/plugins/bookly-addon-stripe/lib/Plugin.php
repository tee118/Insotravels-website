<?php
namespace BooklyStripe\Lib;

use Bookly\Lib as BooklyLib;
use BooklyStripe\Backend\Modules as Backend;
use BooklyStripe\Backend\Components as BackendComponents;
use BooklyStripe\Frontend\Modules as Frontend;

/**
 * Class Plugin
 * @package BooklyStripe\Lib
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
     * @inheritDoc
     */
    protected static function init()
    {
        // Init ajax.
        if ( get_option( 'bookly_stripe_enabled' ) ) {
            Frontend\Stripe\Ajax::init();
        }

        // Init proxy.
        Backend\Payments\ProxyProviders\Shared::init();
        Backend\Settings\ProxyProviders\Shared::init();
        if ( get_option( 'bookly_stripe_enabled' ) ) {
            Backend\Appearance\ProxyProviders\Shared::init();
            Frontend\Booking\ProxyProviders\Shared::init();
        }
        BackendComponents\Notices\ScaUpdateAjax::init();
        ProxyProviders\Shared::init();
    }
}