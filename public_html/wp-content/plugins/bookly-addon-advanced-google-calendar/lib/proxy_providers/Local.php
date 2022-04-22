<?php
namespace BooklyAdvancedGoogleCalendar\Lib\ProxyProviders;

use Bookly\Lib as BooklyLib;
use BooklyAdvancedGoogleCalendar\Lib;
use BooklyPro\Lib\Google;

/**
 * Class Local
 * @package BooklyAdvancedGoogleCalendar\Lib\ProxyProviders
 */
class Local extends BooklyLib\Proxy\AdvancedGoogleCalendar
{
    /**
     * @inheritdoc
     */
    public static function createApiCalendar( Google\Client $client )
    {
        return new Lib\Google\Calendar( $client );
    }

    /**
     * @inheritdoc
     */
    public static function reSync()
    {
        if ( BooklyLib\Proxy\Pro::getGoogleCalendarSyncMode() === '2-way' ) {
            // Re-sync calendars.
            $google = new Google\Client();
            foreach ( BooklyLib\Entities\Staff::query()->whereNot( 'visibility', 'archive' )->find() as $staff ) {
                if ( $google->auth( $staff ) && $google->calendar()->clearSyncToken()->sync() ) {
                    $google->calendar()->watch();
                }
            }
        }
    }
}