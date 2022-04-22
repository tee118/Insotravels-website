<?php
namespace BooklyInvoices\Backend\Components\Invoice;

use Bookly\Lib as BooklyLib;
use Bookly\Backend\Modules\Payments\Proxy;
use BooklyInvoices\Backend\Modules\Settings\Lib\Helper;
use Bookly\Backend\Modules\Notifications\Lib\Codes;

/**
 * Class Invoice
 * @package BooklyInvoices\Backend\Components\Invoice
 */
class Invoice extends BooklyLib\Base\Component
{
    /**
     * Render invoice content.
     *
     * @param array $payment_data
     * @return string
     */
    public static function render( array $payment_data )
    {
        $company_logo = wp_get_attachment_image_src( get_option( 'bookly_co_logo_attachment_id' ), 'full' );

        $payment = $payment_data['payment'];
        $created_at = BooklyLib\Slots\DatePoint::fromStr( $payment['created_at'] );
        $helper = new Helper();
        $codes = array(
            '{company_address}' => nl2br( get_option( 'bookly_co_address' ) ),
            '{company_logo}' => $company_logo ? sprintf( '<img src="%s"/>', esc_attr( $company_logo[0] ) ) : '',
            '{company_name}' => get_option( 'bookly_co_name' ),
            '{company_phone}' => get_option( 'bookly_co_phone' ),
            '{company_website}' => get_option( 'bookly_co_website' ),
            '{client_email}' => '',
            '{client_first_name}' => strtok( $payment['customer'], ' ' ),
            '{client_last_name}' => strtok( '' ),
            '{client_name}' => $payment['customer'],
            '{client_phone}' => '',
            '{client_note}' => '',
            '{client_address}' => '',
            '{invoice_number}' => $payment['id'],
            '{invoice_link}' => $payment['id'] ? admin_url( 'admin-ajax.php?action=bookly_invoices_download&token=' . $payment['token'] ) : '',
            '{invoice_date}' => BooklyLib\Utils\DateTime::formatDate( $created_at->format( 'Y-m-d' ) ),
            '{invoice_due_date}' => BooklyLib\Utils\DateTime::formatDate( $created_at->modify( get_option( 'bookly_invoices_due_days' ) * DAY_IN_SECONDS )->format( 'Y-m-d' ) ),
            '{invoice_due_days}' => get_option( 'bookly_invoices_due_days' ),
        );

        $time_zone_offset = null;
        $time_zone        = null;
        /** @var BooklyLib\Entities\CustomerAppointment $ca */
        $ca = BooklyLib\Entities\CustomerAppointment::query( 'ca' )
            ->where( 'ca.payment_id', $payment['id'] )
            ->findOne();
        if ( $ca ) {
            $time_zone_offset = $ca->getTimeZoneOffset();
            $time_zone        = $ca->getTimeZone();
            /** @var BooklyLib\Entities\Customer $customer */
            $customer = BooklyLib\Entities\Customer::find( $ca->getCustomerId() );
            if ( $customer ) {
                $codes['{client_email}'] = $customer->getEmail();
                $codes['{client_first_name}'] = $customer->getFirstName();
                $codes['{client_last_name}'] = $customer->getLastName();
                $codes['{client_name}'] = $customer->getFullName();
                $codes['{client_phone}'] = $customer->getPhone();
                $codes['{client_address}'] = $customer->getAddress();
                $codes['{client_birthday}'] = $customer->getBirthday() ? BooklyLib\Utils\DateTime::formatDate( $customer->getBirthday() ) : '';
                $codes['{client_note}'] = $customer->getNotes();
            }
        }

        $show_deposit = BooklyLib\Config::depositPaymentsActive();
        if ( ! $show_deposit ) {
            foreach ( $payment['items'] as $item ) {
                if ( array_key_exists( 'deposit_format', $item ) && $item['deposit_format'] ) {
                    $show_deposit = true;
                    break;
                }
            }
        }

        switch ( $payment_data['payment']['type'] ) {
            case BooklyLib\Entities\Payment::TYPE_PAYPAL:
                $price_correction =  get_option( 'bookly_paypal_increase' ) != 0
                    || get_option( 'bookly_paypal_addition' ) != 0;
                break;
            case BooklyLib\Entities\Payment::TYPE_CLOUD_STRIPE:
                $price_correction =  get_option( 'bookly_cloud_stripe_increase' ) != 0
                    || get_option( 'bookly_cloud_stripe_addition' ) != 0;
                break;
            default:
                $price_correction = Proxy\Shared::paymentSpecificPriceExists( $payment_data['payment']['type'] ) === true;
                break;
        }

        $show = array(
            'coupons' => BooklyLib\Config::couponsActive(),
            'customer_groups' => BooklyLib\Config::customerGroupsActive(),
            'deposit' => (int) $show_deposit,
            'price_correction' => $price_correction,
            'taxes' => (int) ( BooklyLib\Config::taxesActive() || $payment['tax_total'] > 0 ),
        );
        $adjustments = isset( $payment_data['adjustments'] ) ? $payment_data['adjustments'] : array();

        $content = self::renderTemplate( 'invoice', array(
            'helper'           => $helper,
            'codes'            => '',
            'payment'          => $payment_data['payment'],
            'adjustments'      => $adjustments,
            'show'             => $show,
            'time_zone_offset' => $time_zone_offset,
            'time_zone'        => $time_zone,
        ), false );

        return strtr( $content, $codes );
    }

    /**
     * Render editable template for invoice.
     *
     * @return string|void
     */
    public static function appearance()
    {
        wp_enqueue_media();

        self::enqueueScripts( array(
            'module' => array(
                'js/invoice-appearance.js' => array( 'jquery' ),
            ),
        ) );

        wp_localize_script( 'bookly-invoice-appearance.js', 'BooklyInvoicesL10n', array(
            'invalid_due_days' => __( 'Invoice due days: Please enter value in the following range (in days) - 1 to 365.', 'bookly' ),
        ) );

        $helper = new Helper( 'editable' );
        $codes_list = new Codes();
        $codes = json_encode( $codes_list->getGroups( array( 'customer', 'invoice', 'company' ) ) );

        return self::renderTemplate( 'invoice', compact( 'helper', 'codes' ), false );
    }
}