<?php
namespace BooklyCustomerInformation\Lib\ProxyProviders;

use Bookly\Lib as BooklyLib;

/**
 * Class Shared
 * @package BooklyCustomerInformation\Lib\ProxyProviders
 */
class Shared extends BooklyLib\Proxy\Shared
{
    /**
     * @inheritdoc
     */
    public static function prepareTableColumns( $columns, $table )
    {
        if ( $table == BooklyLib\Utils\Tables::CUSTOMERS ) {
            foreach ( Local::getFieldsWhichMayHaveData() as $field ) {
                $columns[ 'info_fields_' . $field->id ] = BooklyLib\Utils\Common::stripScripts( $field->label );
            }
        }

        return $columns;
    }
}