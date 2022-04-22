<?php
namespace BooklyInvoices\Backend\Modules\Payments\ProxyProviders;

use Bookly\Backend\Components\Controls\Buttons;
use Bookly\Backend\Modules\Payments\Proxy;
use Bookly\Lib as BooklyLib;

/**
 * Class Local
 * @package BooklyInvoices\Backend\Modules\Payments\ProxyProviders
 */
abstract class Local extends Proxy\Invoices
{
    /**
     * @inheritDoc
     */
    public static function renderDownloadButton()
    {
        $action = admin_url( 'admin-ajax.php?action=bookly_invoices_download_invoices&csrf_token=' . BooklyLib\Utils\Common::getCsrfToken() );

        Buttons::render( 'bookly-download-invoices', 'btn-default', __( 'Download invoices', 'bookly' ), array( 'data-spinner-color' => '#333', 'data-action' => $action ) );
    }
}