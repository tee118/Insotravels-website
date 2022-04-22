<?php
namespace BooklyStripe\Backend\Modules\Appearance\ProxyProviders;

use Bookly\Backend\Modules\Appearance\Proxy;
use BooklyStripe\Lib\Plugin;

/**
 * Class Shared
 * @package BooklyStripe\Backend\Modules\Appearance\ProxyProviders
 */
class Shared extends Proxy\Shared
{
    /**
     * @inheritDoc
     */
    public static function paymentGateways( $data )
    {
        $data[ Plugin::getSlug() ] = array(
            'label_option_name' => 'bookly_l10n_label_pay_stripe',
            'title' => 'Stripe',
            'with_card' => true,
            'logo_url' => 'default',
        );

        return $data;
    }

    /**
     * @inheritDoc
     */
    public static function prepareOptions( array $options_to_save, array $options )
    {
        return array_merge( $options_to_save, array_intersect_key( $options, array_flip( array (
            'bookly_l10n_label_pay_stripe',
        ) ) ) );
    }
}