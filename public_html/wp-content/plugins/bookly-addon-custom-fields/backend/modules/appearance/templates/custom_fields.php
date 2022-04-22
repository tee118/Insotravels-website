<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<div class="bookly-box bookly-js-custom-fields bookly-table"<?php if ( ! get_option( 'bookly_custom_fields_enabled' ) ) : ?> style="display: none;"<?php endif ?>>
    <div class="bookly-form-group">
        <label for="bookly-custom-fields"><?php esc_html_e( 'Custom Fields', 'bookly' ) ?></label>
        <div class="bookly-form-field">
            <input class="bookly-form-element" id="bookly-custom-fields" type="text"/>
            <div><?php esc_html_e( 'Custom field description', 'bookly' ) ?></div>
        </div>
    </div>
</div>