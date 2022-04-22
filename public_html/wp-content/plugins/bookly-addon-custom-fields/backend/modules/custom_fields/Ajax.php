<?php
namespace BooklyCustomFields\Backend\Modules\CustomFields;

use Bookly\Lib as BooklyLib;

/**
 * Class Ajax
 * @package BooklyCustomFields\Backend\Modules\CustomFields
 */
class Ajax extends BooklyLib\Base\Ajax
{
    /**
     * Save custom fields.
     */
    public static function saveCustomFields()
    {
        $fields = self::parameter( 'fields', '[]' );
        $per_service = (int) self::parameter( 'per_service' );
        $merge_repeating = (int) self::parameter( 'merge_repeating' );
        $custom_fields = json_decode( $fields, true );

        foreach ( $custom_fields as $custom_field ) {
            switch ( $custom_field['type'] ) {
                case 'textarea':
                case 'text-content':
                case 'text-field':
                case 'captcha':
                case 'file':
                    do_action(
                        'wpml_register_single_string',
                        'bookly',
                        sprintf(
                            'custom_field_%d_%s',
                            $custom_field['id'],
                            sanitize_title( $custom_field['label'] )
                        ),
                        $custom_field['label']
                    );
                    do_action(
                        'wpml_register_single_string',
                        'bookly',
                        sprintf(
                            'custom_field_%d_%s_description',
                            $custom_field['id'],
                            sanitize_title( $custom_field['label'] )
                        ),
                        $custom_field['description']
                    );
                    break;
                case 'checkboxes':
                case 'radio-buttons':
                case 'drop-down':
                    do_action(
                        'wpml_register_single_string',
                        'bookly',
                        sprintf(
                            'custom_field_%d_%s',
                            $custom_field['id'],
                            sanitize_title( $custom_field['label'] )
                        ),
                        $custom_field['label']
                    );
                    do_action(
                        'wpml_register_single_string',
                        'bookly',
                        sprintf(
                            'custom_field_%d_%s_description',
                            $custom_field['id'],
                            sanitize_title( $custom_field['label'] )
                        ),
                        $custom_field['description']
                    );
                    foreach ( $custom_field['items'] as $label ) {
                        do_action(
                            'wpml_register_single_string',
                            'bookly',
                            sprintf(
                                'custom_field_%d_%s=%s',
                                $custom_field['id'],
                                sanitize_title( $custom_field['label'] ),
                                sanitize_title( $label )
                            ),
                            $label
                        );
                    }
                    break;
            }
        }

        BooklyLib\Proxy\Files::saveCustomFields( $custom_fields );

        update_option( 'bookly_custom_fields_data', $fields );
        update_option( 'bookly_custom_fields_per_service', $per_service );
        update_option( 'bookly_custom_fields_merge_repeating', $merge_repeating );
        wp_send_json_success();
    }

    /**
     * Load tab data for custom fields page.
     */
    public static function loadTab()
    {
        $tab = self::parameter( 'tab', 'general' );

        $custom_fields = json_decode( get_option( 'bookly_custom_fields_data', '[]' ) );

        switch ( $tab ) {
            case 'general' :
                $service_dropdown_data = BooklyLib\Utils\Common::getServiceDataForDropDown( 's.type = "simple"' );
                $response = array(
                    'html' => self::renderTemplate( '_general', array(
                        'services_html' => self::renderTemplate( '_services', compact( 'service_dropdown_data' ), false ),
                        'description_html' => self::renderTemplate( '_description', array(), false ),
                    ), false ),
                    'custom_fields' => $custom_fields,
                );
                break;
            default:
                $response = array(
                    'html' => self::renderTemplate( '_conditions', compact( 'custom_fields' ), false ),
                    'custom_fields' => $custom_fields,
                    'custom_fields_conditions' => get_option( 'bookly_custom_fields_conditions', array() ),
                );
                break;
        }

        wp_send_json_success( $response );
    }

    /**
     * Save custom fields conditions
     */
    public static function saveConditions()
    {
        update_option( 'bookly_custom_fields_conditions', self::parameter( 'conditions', array() ) );

        wp_send_json_success();
    }
}