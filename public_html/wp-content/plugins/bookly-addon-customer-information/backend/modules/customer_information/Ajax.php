<?php
namespace BooklyCustomerInformation\Backend\Modules\CustomerInformation;

use Bookly\Lib as BooklyLib;

/**
 * Class Ajax
 * @package BooklyCustomerInformation\Backend\Modules\CustomerInformation
 */
class Ajax extends BooklyLib\Base\Ajax
{
    /**
     * Save customer fields.
     */
    public static function saveFields()
    {
        $fields = self::parameter( 'fields', '[]' );

        // Register WPML strings.
        foreach ( json_decode( $fields ) as $field ) {
            switch ( $field->type ) {
                case 'checkboxes':
                case 'radio-buttons':
                case 'drop-down':
                    foreach ( $field->items as $i => $label ) {
                        do_action(
                            'wpml_register_single_string',
                            'bookly',
                            sprintf( 'customer_field_%d_%d', $field->id, $i ),
                            $label
                        );
                    }
                case 'textarea':
                case 'text-content':
                case 'text-field':
                    do_action(
                        'wpml_register_single_string',
                        'bookly',
                        sprintf( 'customer_field_%d', $field->id ),
                        $field->label
                    );
            }
        }

        update_option( 'bookly_customer_information_data', $fields );

        wp_send_json_success();
    }
}