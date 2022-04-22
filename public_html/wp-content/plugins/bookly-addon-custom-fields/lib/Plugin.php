<?php
namespace BooklyCustomFields\Lib;

use Bookly\Lib as BooklyLib;
use BooklyCustomFields\Backend;
use BooklyCustomFields\Frontend;

/**
 * Class Plugin
 * @package BooklyCustomFields\Lib
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
        Backend\Modules\CustomFields\Ajax::init();
        if ( get_option( 'bookly_custom_fields_enabled' ) ) {
            Frontend\Modules\Booking\Ajax::init();
        }

        // Init proxy.
        Backend\Components\Dialogs\Appointment\CustomerDetails\ProxyProviders\Shared::init();
        Backend\Modules\Appearance\ProxyProviders\Local::init();
        Backend\Modules\Appearance\ProxyProviders\Shared::init();
        Backend\Modules\Calendar\ProxyProviders\Shared::init();
        Backend\Modules\Notifications\ProxyProviders\Shared::init();
        Backend\Modules\Settings\ProxyProviders\Shared::init();
        if ( get_option( 'bookly_custom_fields_enabled' ) ) {
            Frontend\Modules\Booking\ProxyProviders\Shared::init();
            Frontend\Modules\CustomerProfile\ProxyProviders\Local::init();
        }
        Notifications\Assets\Item\ProxyProviders\Shared::init();
        ProxyProviders\Local::init();
        ProxyProviders\Shared::init();
    }
}