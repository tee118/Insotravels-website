<?php
namespace Bookly\Frontend\Modules\Booking\Proxy;

use Bookly\Lib as BooklyLib;

/**
 * Class Pro
 *
 * @package Bookly\Frontend\Modules\Booking\Proxy
 *
 * @method static void renderDetailsAddress( BooklyLib\UserBookingData $userData ) Render address fields at Details step.
 * @method static void renderDetailsBirthday( BooklyLib\UserBookingData $userData ) Render birthday fields at Details step.
 * @method static void renderFacebookButton() Render facebook button.
 * @method static void renderPaymentStep( BooklyLib\UserBookingData $userData ) Render tips block for Payment step
 * @method static void renderTimeZoneSwitcher() Render time zone switcher at Time step.
 * @method static string getHtmlPaymentImpossible( string $progress_tracker, BooklyLib\UserBookingData $userData ) Render payment impossible message
 * @method static array filterGateways( $gateways, BooklyLib\UserBookingData $userData ) Remain gateways that are suitable for all staff members
 */
abstract class Pro extends BooklyLib\Base\Proxy
{

}