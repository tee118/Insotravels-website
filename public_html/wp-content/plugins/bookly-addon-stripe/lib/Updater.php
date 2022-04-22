<?php
namespace BooklyStripe\Lib;

/**
 * Class Updates
 * @package BooklyStripe\Lib
 */
class Updater extends \Bookly\Lib\Base\Updater
{
    public function update_1_7()
    {
        add_option( 'bookly_stripe_timeout', '0' );
        foreach ( get_users( 'role=administrator' ) as $user ) {
            add_user_meta( $user->ID, 'bookly_show_stripe_sca_update_notice', '1' );
        }
    }

    public function update_1_1()
    {
        add_option( 'bookly_stripe_increase', '0' );
        add_option( 'bookly_stripe_addition', '0' );
    }
}