<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

use Bookly\Backend\Modules\Settings\Page as SettingsPage;
use Bookly\Lib\Utils\Common;
?>
<div id="bookly-tbs" class="wrap">
    <div id="bookly-stripe-sca-update-notice" class="alert alert-info bookly-tbs-body bookly-flexbox" data-action="bookly_stripe_dismiss_sca_update_notice">
        <div class="bookly-flex-row">
            <div class="bookly-flex-cell" style="width:39px"><i class="alert-icon"></i></div>
            <div class="bookly-flex-cell">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <p><?php printf( __( 'Bookly Stripe add-on has been upgraded to support <a href="%s" target="_blank">SCA</a>. You must update your Stripe settings to keep the integration with the upgraded add-on.', 'bookly' ), 'https://stripe.com/en-se/guides/strong-customer-authentication' ) ?></p>
                <p><?php printf( __( '1. Make sure that <b>Publishable Key</b> is provided in <a href="%s">payment settings</a>.', 'bookly' ), Common::escAdminUrl( SettingsPage::pageSlug(), array( 'tab' => 'payments' ) ) ) ?></p>
                <p><?php printf( __( '2. In the Dashboard\'s <a href="%s" target="_blank">Webhooks settings</a> section, click <b>Add endpoint</b> to reveal a form to add a new endpoint for receiving events.', 'bookly' ), 'https://dashboard.stripe.com/account/webhooks' ) ?></p>
                <p><?php printf( __( 'Enter the following URL as the destination for events <b>%s</b> and click <b>Add endpoint</b>.', 'bookly' ), admin_url( 'admin-ajax.php?action=bookly_stripe_ipn' ) ) ?></p>
            </div>
        </div>
    </div>
</div>