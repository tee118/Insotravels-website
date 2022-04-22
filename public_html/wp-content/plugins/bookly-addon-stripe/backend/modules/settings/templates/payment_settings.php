<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
use Bookly\Backend\Components\Settings\Inputs;
use Bookly\Backend\Components\Settings\Payments;
use Bookly\Backend\Components\Settings\Selects;
use Bookly\Backend\Components\Controls\Elements;
use Bookly\Lib\Utils\DateTime;
use Bookly\Lib\Plugin;
?>

<div class="card bookly-collapse" data-slug="bookly-addon-stripe">
    <div class="card-header d-flex align-items-center">
        <?php Elements::renderReorder() ?>
        <a href="#bookly_pmt_stripe" class="ml-2" role="button" data-toggle="collapse">
            Stripe
        </a>
        <img class="ml-auto" src="<?php echo plugins_url( 'frontend/modules/stripe/resources/images/stripe.png', Plugin::getMainFile() ) ?>" />
    </div>
    <div id="bookly_pmt_stripe" class="collapse show">
        <div class="card-body">
            <?php Selects::renderSingle( 'bookly_stripe_enabled' ) ?>
            <div class="bookly-stripe">
                <div class="form-group">
                    <h4><?php esc_html_e( 'Instructions', 'bookly' ) ?></h4>
                    <ol>
                        <li><?php printf( __( 'Provide <b>Secret</b> and <b>Publishable</b> keys which are available in the <a href="%s" target="_blank">Dashboard</a>.', 'bookly' ), 'https://dashboard.stripe.com/account/apikeys' ) ?></li>
                        <li><?php printf( __( 'In the Dashboard\'s <a href="%s" target="_blank">Webhooks settings</a> section, click <b>Add endpoint</b> to reveal a form to add a new endpoint for receiving events.', 'bookly' ), 'https://dashboard.stripe.com/account/webhooks' ) ?></li>
                        <li><?php printf( __( 'Enter the following URL as the destination for events <b>%s</b> and click <b>Add endpoint</b>.', 'bookly' ), admin_url( 'admin-ajax.php?action=bookly_stripe_ipn' ) ) ?></li>
                        <li><?php _e( 'Add these events: <b>payment_intent.succeeded</b>, <b>payment_intent.payment_failed</b> and click <b>Add endpoint</b>.', 'bookly' ) ?></li>
                    </ol>
                </div>
                <?php Inputs::renderText( 'bookly_stripe_publishable_key', __( 'Publishable Key', 'bookly' ) ) ?>
                <?php Inputs::renderText( 'bookly_stripe_secret_key', __( 'Secret Key', 'bookly' ) ) ?>
                <?php Payments::renderPriceCorrection( 'stripe' ) ?>
                <?php
                $values = array( array( '0', __( 'OFF', 'bookly' ) ) );
                foreach ( array_merge( range( 1, 23, 1 ), range( 24, 168, 24 ), array( 336, 504, 672 ) ) as $hour ) {
                    $values[] = array( $hour * HOUR_IN_SECONDS, DateTime::secondsToInterval( $hour * HOUR_IN_SECONDS ) );
                }
                Selects::renderSingle( 'bookly_stripe_timeout', __( 'Time interval of payment gateway', 'bookly' ), __( 'This setting determines the time limit after which the payment made via the payment gateway is considered to be incomplete. This functionality requires a scheduled cron job.', 'bookly' ), $values );
                ?>
            </div>
        </div>
    </div>
</div>