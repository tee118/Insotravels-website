<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
use Bookly\Lib\Utils;

/** @var Bookly\Lib\CartInfo $cart_info */
?>
<div class="bookly-box bookly-list">
    <label>
        <input type="radio" class="bookly-payment" name="payment-method-<?php echo $form_id ?>" value="card" data-form="stripe"/>
        <span><?php echo Utils\Common::getTranslatedOption( 'bookly_l10n_label_pay_stripe' ) ?>
            <?php if ( $show_price ) : ?>
                <span class="bookly-js-pay"><?php echo Utils\Price::format( $cart_info->getPayNow() ) ?></span>
            <?php endif ?>
        </span>
        <img src="<?php echo $url_cards_image ?>" alt="cards"/>
    </label>
    <form class="bookly-stripe" style="display: none; margin-top: 15px;">
        <div class="bookly-form-group">
            <div style="max-width: 400px;" class="bookly-form-group">
                <label><?php echo Utils\Common::getTranslatedOption( 'bookly_l10n_label_ccard_number' ) ?></label>
                <div id="bookly-stripe-card-field" style="padding: 10px; border: 1px solid silver; width: 100%"></div>
                <div style="width: 100%; display: flex; margin-top: 10px;">
                    <div style="width: 50%; margin-right: 5px;" class="bookly-form-group">
                        <label><?php echo Utils\Common::getTranslatedOption( 'bookly_l10n_label_ccard_expire' ) ?></label>
                        <div id="bookly-stripe-card-expiry-field" style="padding: 10px; border: 1px solid silver; margin: 5px 0 0 0;"></div>
                    </div>
                    <div style="width: 50%; margin-left: 5px;" class="bookly-form-group">
                        <label><?php echo Utils\Common::getTranslatedOption( 'bookly_l10n_label_ccard_code' ) ?></label>
                        <div id="bookly-stripe-card-cvc-field" style="padding: 10px; border: 1px solid silver; margin: 5px 0 0 0;"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="bookly-label-error bookly-js-card-error"></div>
    </form>
</div>