<?php
namespace BooklyCustomFields\Lib;

use Bookly\Lib;

/**
 * Class Updates
 * @package BooklyCustomFields\Lib
 */
class Updater extends Lib\Base\Updater
{
    function update_2_8()
    {
        $custom_fields = json_decode( get_option( 'bookly_custom_fields_data', '[]' ), true );
        foreach ( $custom_fields as &$custom_field ) {
            if ( ! isset( $custom_field['description'] ) ) {
                $custom_field['description'] = '';
            }
        }
        update_option( 'bookly_custom_fields_data', json_encode( $custom_fields ) );

        $conditions = get_option( 'bookly_custom_fields_conditions', array() );
        foreach ( $conditions as &$condition ) {
            if ( isset( $condition['value'] ) && ! is_array( $condition['value'] ) ) {
                $condition['value'] = array( $condition['value'] );
            }
        }
        update_option( 'bookly_custom_fields_conditions', $conditions );
    }
}