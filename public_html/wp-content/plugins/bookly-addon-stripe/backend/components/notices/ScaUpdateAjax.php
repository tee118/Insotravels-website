<?php
namespace BooklyStripe\Backend\Components\Notices;

use Bookly\Lib;

/**
 * Class ScaUpdateAjax
 * @package BooklyStripe\Backend\Components\Notices
 */
class ScaUpdateAjax extends Lib\Base\Ajax
{
    /**
     * Dismiss SCA update notice.
     */
    public static function dismissScaUpdateNotice()
    {
        delete_user_meta( get_current_user_id(), 'bookly_show_stripe_sca_update_notice' );

        wp_send_json_success();
    }
}