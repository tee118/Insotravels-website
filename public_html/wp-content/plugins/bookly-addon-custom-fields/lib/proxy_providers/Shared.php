<?php
namespace BooklyCustomFields\Lib\ProxyProviders;

use Bookly\Lib as BooklyLib;
use BooklyCustomFields\Lib;

/**
 * Class Shared
 * @package BooklyCustomFields\Lib\ProxyProviders;
 */
class Shared extends BooklyLib\Proxy\Shared
{
    /**
     * @inheritDoc
     */
    public static function prepareTableColumns( $columns, $table )
    {
        if ( $table == BooklyLib\Utils\Tables::APPOINTMENTS ) {
            foreach ( Local::getAll( array( 'captcha', 'text-content', 'file' ) ) as $custom_field ) {
                $columns[ 'custom_fields_' . $custom_field->id ] = BooklyLib\Utils\Common::stripScripts( $custom_field->label );
            }
        }

        return $columns;
    }


    /**
     * @inheritDoc
     */
    public static function prepareCustomerAppointmentCodes( $codes, $customer_appointment, $format )
    {
        $codes['custom_fields'] = Local::getFormatted( $customer_appointment, $format );
        foreach ( Lib\ProxyProviders\Local::getForCustomerAppointment( $customer_appointment ) as $custom_field ) {
            $codes[ 'custom_field#' . $custom_field['id'] ] = is_array( $custom_field['value'] ) ? esc_html( implode( ',', $custom_field['value'] ) ) : esc_html( $custom_field['value'] );
        }

        return $codes;
    }
}