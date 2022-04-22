<?php
namespace BooklyStripe\Backend\Components\Notices;

use Bookly\Lib as BooklyLib;

/**
 * Class ScaUpdate
 * @package BooklyPro\Backend\Components\Notices
 */
class ScaUpdate extends BooklyLib\Base\Component
{
    /**
     * Render SCA update notice
     */
    public static function render()
    {
        if ( BooklyLib\Utils\Common::isCurrentUserAdmin() && get_user_meta( get_current_user_id(), 'bookly_show_stripe_sca_update_notice', true ) ) {
            self::enqueueStyles( array(
                'alias' => array( 'bookly-backend-globals', ),
            ) );
            self::enqueueScripts( array(
                'module' => array( 'js/sca-update.js' => array( 'bookly-backend-globals' ), ),
            ) );

            self::renderTemplate( 'sca_update' );
        }
    }
}