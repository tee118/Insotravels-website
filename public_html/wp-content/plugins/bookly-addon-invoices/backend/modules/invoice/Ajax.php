<?php
namespace BooklyInvoices\Backend\Modules\Invoice;

use Bookly\Lib as BooklyLib;
use BooklyInvoices\Lib;

/**
 * Class Ajax
 * @package BooklyInvoices\Backend\Modules\Invoice
 */
class Ajax extends BooklyLib\Base\Ajax
{
    /**
     * @inheritDoc
     */
    protected static function permissions()
    {
        return array( '_default' => 'supervisor' );
    }

    public static function downloadInvoices()
    {
        $fs = BooklyLib\Utils\Common::getFilesystem();
        $payment_ids = explode( ',', self::parameter( 'invoices' ) );
        /** @var BooklyLib\Entities\Payment[] $payments */
        $payments = BooklyLib\Entities\Payment::query()
            ->whereIn( 'id', $payment_ids )
            ->find();
        if ( count( $payments ) == 1 || class_exists( 'ZipArchive', false ) === false ) {
            BooklyLib\Proxy\Invoices::downloadInvoice( $payments[0] );
        } elseif ( $payments ) {
            $files = array();
            $zip_archive = wp_tempnam();
            $zip   = new \ZipArchive();
            if ( $zip->open( $zip_archive ) === true ) {
                foreach ( $payments as $payment ) {
                    if ( $filename = BooklyLib\Proxy\Invoices::getInvoice( $payment ) ) {
                        $files[] = $filename;
                        $zip->addFile( $filename, 'Invoice_' . $payment->getId() . '.pdf' );
                    }
                }
                $zip->close();
                $files[] = $zip_archive;
                header( 'Pragma: public' );
                header( 'Expires: 0' );
                header( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
                header( 'Cache-Control: public' );
                header( 'Content-Description: File Transfer' );
                header( 'Content-type: application/octet-stream' );
                header( 'Content-Disposition: attachment; filename="Invoices.zip"' );
                header( 'Content-Transfer-Encoding: binary' );
                header( 'Content-Length: ' . $fs->size( $zip_archive ) );
                print $fs->get_contents( $zip_archive );

                foreach ( $files as $path ) {
                    $fs->delete( $path, false, 'f' );
                }
            }
        }
        exit();
    }

}