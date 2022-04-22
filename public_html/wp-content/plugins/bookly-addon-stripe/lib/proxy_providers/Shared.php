<?php
namespace BooklyStripe\Lib\ProxyProviders;

use Bookly\Lib as BooklyLib;
use BooklyStripe\Backend\Components\Notices;

/**
 * Class Shared
 * @package BooklyStripe\Lib\ProxyProviders
 */
class Shared extends BooklyLib\Proxy\Shared
{
    /**
     * @inheritDoc
     */
    public static function showPaymentSpecificPrices( $show )
    {
        if ( ! $show && get_option( 'bookly_stripe_enabled' ) ) {
            return (float) get_option( 'bookly_stripe_increase' ) != 0 || (float) get_option( 'bookly_stripe_addition' ) != 0;
        }

        return $show;
    }

    /**
     * @inheritDoc
     */
    public static function applyGateway( BooklyLib\CartInfo $cart_info, $gateway )
    {
        if ( $gateway === BooklyLib\Entities\Payment::TYPE_STRIPE && get_option( 'bookly_stripe_enabled' ) ) {
            $cart_info->setGateway( $gateway );
        }

        return $cart_info;
    }

    /**
     * @inheritDoc
     */
    public static function prepareOutdatedUnpaidPayments( $payments )
    {
        $timeout = (int) get_option( 'bookly_stripe_timeout' );
        if ( $timeout ) {
            $payments = array_merge( $payments, BooklyLib\Entities\Payment::query()
                ->where( 'type', BooklyLib\Entities\Payment::TYPE_STRIPE )
                ->where( 'status', BooklyLib\Entities\Payment::STATUS_PENDING )
                ->whereLt( 'created_at', date_create( current_time( 'mysql' ) )->modify( sprintf( '- %s seconds', $timeout ) )->format( 'Y-m-d H:i:s' ) )
                ->fetchCol( 'id' )
            );
        }

        return $payments;
    }

    /**
     * @inheritDoc
     */
    public static function renderAdminNotices( $bookly_page )
    {
        Notices\ScaUpdate::render();
    }
}