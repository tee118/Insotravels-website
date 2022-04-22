<?php
namespace BooklyStripe\Frontend\Modules\Stripe;

use BooklyStripe\Lib;
use Bookly\Lib as BooklyLib;
use Bookly\Frontend\Modules\Booking\Lib\Errors;
use BooklyStripe\Lib\Payment\Lib\Stripe as StripeApi;

/**
 * Class Controller
 * @package Bookly\Frontend\Modules\Stripe
 */
class Ajax extends BooklyLib\Base\Ajax
{
    /**
     * @inheritDoc
     */
    protected static function permissions()
    {
        return array( '_default' => 'anonymous' );
    }

    /**
     * Override parent method to exclude actions from CSRF token verification.
     *
     * @param string $action
     * @return bool
     */
    protected static function csrfTokenValid( $action = null )
    {
        $excluded_actions = array(
            'ipn',
        );

        return in_array( $action, $excluded_actions ) || parent::csrfTokenValid( $action );
    }

    public static function createIntent()
    {
        $response = null;
        $form_id  = self::parameter( 'form_id' );
        $userData = new BooklyLib\UserBookingData( $form_id );

        if ( $userData->load() ) {
            $failed_cart_key = $userData->cart->getFailedKey();
            if ( $failed_cart_key === null ) {
                include_once Lib\Plugin::getDirectory() . '/lib/payment/Stripe/init.php';
                StripeApi\Stripe::setApiKey( get_option( 'bookly_stripe_secret_key' ) );
                StripeApi\Stripe::setApiVersion( '2019-02-19' );

                $cart_info = $userData->cart->getInfo( BooklyLib\Entities\Payment::TYPE_STRIPE );
                $payment   = null;
                try {
                    if ( in_array( get_option( 'bookly_pmt_currency' ), array( 'BIF', 'CLP', 'DJF', 'GNF', 'JPY', 'KMF', 'KRW', 'MGA', 'PYG', 'RWF', 'VND', 'VUV', 'XAF', 'XOF', 'XPF', ) ) ) {
                        // Zero-decimal currency
                        $stripe_amount = $cart_info->getGatewayAmount();
                    } else {
                        $stripe_amount = $cart_info->getGatewayAmount() * 100; // amount in cents
                    }

                    $cart_info = $userData->cart->getInfo( BooklyLib\Entities\Payment::TYPE_STRIPE );

                    $customer = $userData->getCustomer();
                    try {
                        if ( $customer->getStripeAccount() ) {
                            StripeApi\Customer::update( $customer->getStripeAccount(),
                                array(
                                    'email' => $userData->getEmail(),
                                    'name'  => $userData->getFullName(),
                                ) );
                        } else {
                            $sc = StripeApi\Customer::create( array(
                                'email' => $userData->getEmail(),
                                'name'  => $userData->getFullName(),
                            ) );
                            $customer->setStripeAccount( $sc->id );
                        }
                    } catch ( \Exception $e ) { }

                    $payment = new BooklyLib\Entities\Payment();
                    $payment
                        ->setType( BooklyLib\Entities\Payment::TYPE_STRIPE )
                        ->setCartInfo( $cart_info )
                        ->setStatus( BooklyLib\Entities\Payment::STATUS_PENDING )
                        ->save();

                    $intent = StripeApi\PaymentIntent::create( array(
                        'amount'               => round( $stripe_amount ),
                        'currency'             => get_option( 'bookly_pmt_currency' ),
                        'payment_method_types' => array( 'card' ),
                        'description'          => $userData->cart->getItemsTitle(),
                        'receipt_email'        => $userData->getEmail(),
                        'customer'             => $customer->getStripeAccount(),
                        'metadata'             => array(
                            'payment_id'  => $payment->getId(),
                            'description' => $userData->cart->getItemsTitle(),
                            'customer'    => $userData->getFullName(),
                            'email'       => $userData->getEmail(),
                        ),
                    ) );
                    if ( $intent->client_secret ) {
                        $userData->setPaymentStatus( BooklyLib\Entities\Payment::TYPE_STRIPE, 'processing' );
                        $order = $userData->save( $payment );
                        if ( $coupon = $userData->getCoupon() ) {
                            $coupon->claim();
                            $coupon->save();
                        }
                        $payment->setDetailsFromOrder( $order, $cart_info )->save();
                        $response = array( 'success' => true, 'intent_secret' => $intent->client_secret, 'intent_id' => $intent->id );
                    } else {
                        $payment->delete();
                        $response = array( 'success' => false, 'error' => Errors::PAYMENT_ERROR, 'error_message' => __( 'Error', 'bookly' ) );
                    }
                } catch ( \Exception $e ) {
                    if ( $payment !== null ) {
                        $payment->delete();
                    }
                    $response = array( 'success' => false, 'error' => Errors::PAYMENT_ERROR, 'error_message' => $e->getMessage() );
                }
            } else {
                $response = array(
                    'success'         => false,
                    'error'           => Errors::CART_ITEM_NOT_AVAILABLE,
                    'failed_cart_key' => $failed_cart_key,
                );
            }
        } else {
            $response = array( 'success' => false, 'error' => Errors::SESSION_ERROR );
        }
        $userData->sessionSave();

        // Output JSON response.
        wp_send_json( $response );
    }

    /**
     * Failed payment.
     */
    public static function failedPayment()
    {
        $userData  = new BooklyLib\UserBookingData( self::parameter( 'form_id' ) );
        if ( $userData->load() ) {
            $userData->setFailedPaymentStatus( BooklyLib\Entities\Payment::TYPE_STRIPE, 'error' )
                ->sessionSave();

            $response = array( 'success' => true );
        } else {
            $response = array( 'success' => false, 'error' => Errors::SESSION_ERROR );
        }

        // Output JSON response.
        wp_send_json( $response );
    }

    /**
     * WebHook endpoint to handle payment.
     */
    public static function ipn()
    {
        $input      = @file_get_contents( 'php://input' );
        $event_json = json_decode( $input, true );
        if ( $event_json && isset( $event_json['type'] ) && in_array( $event_json['type'], array( 'payment_intent.succeeded', 'payment_intent.payment_failed' ) ) ) {
            $intent_id = $event_json['data']['object']['id'];
            include_once Lib\Plugin::getDirectory() . '/lib/payment/Stripe/init.php';
            StripeApi\Stripe::setApiKey( get_option( 'bookly_stripe_secret_key' ) );
            StripeApi\Stripe::setApiVersion( '2019-02-19' );
            $intent = StripeApi\PaymentIntent::retrieve( $intent_id );
            /** @var BooklyLib\Entities\Payment $payment */
            if ( $intent && isset( $intent->metadata->payment_id ) && $payment = BooklyLib\Entities\Payment::query()->where( 'type', BooklyLib\Entities\Payment::TYPE_STRIPE )->where( 'id', $intent->metadata->payment_id )->findOne() ) {
                if ( $intent->status == 'succeeded' ) {
                    if( $payment->getStatus() !== BooklyLib\Entities\Payment::STATUS_COMPLETED ) {
                        $total    = (float) $payment->getPaid();
                        $received = (float) $intent->amount_received;
                        if ( ! in_array( get_option( 'bookly_pmt_currency' ), array( 'BIF', 'CLP', 'DJF', 'GNF', 'JPY', 'KMF', 'KRW', 'MGA', 'PYG', 'RWF', 'VND', 'VUV', 'XAF', 'XOF', 'XPF', ) ) ) {
                            $total = $total * 100; // amount in cents
                        }
                        if ( abs( $received - $total ) <= 0.01 && strtolower( get_option( 'bookly_pmt_currency' ) ) == $intent->currency ) {
                            $payment->setStatus( BooklyLib\Entities\Payment::STATUS_COMPLETED )->save();
                            if ( $order = BooklyLib\DataHolders\Booking\Order::createFromPayment( $payment ) ) {
                                BooklyLib\Notifications\Cart\Sender::send( $order );
                            }
                            /** @var BooklyLib\Entities\Appointment $appointment */
                            foreach (
                                BooklyLib\Entities\Appointment::query( 'a' )
                                    ->leftJoin( 'CustomerAppointment', 'ca', 'a.id = ca.appointment_id' )
                                    ->where( 'ca.payment_id', $payment->getId() )->find() as $appointment
                            ) {
                                if ( $appointment->getGoogleEventId() !== null ) {
                                    BooklyLib\Proxy\Pro::syncGoogleCalendarEvent( $appointment );
                                }
                                if ( $appointment->getOutlookEventId() !== null ) {
                                    BooklyLib\Proxy\OutlookCalendar::syncEvent( $appointment );
                                }
                            }
                        }
                    }
                } else {
                    if ( $event_json['type'] == 'payment_intent.payment_failed' ) {
                        /** @var BooklyLib\Entities\CustomerAppointment $ca */
                        foreach ( BooklyLib\Entities\CustomerAppointment::query()->where( 'payment_id', $payment->getId() )->find() as $ca ) {
                            BooklyLib\Utils\Log::deleteEntity( $ca, __METHOD__ );
                            $ca->deleteCascade();
                        }
                        $payment->delete();
                    }
                }
            }
        }

        wp_send_json_success();
    }
}