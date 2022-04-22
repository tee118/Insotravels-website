<?php

namespace BooklyStripe\Lib\Payment\Lib\Stripe;

/**
 * Client used to send requests to Stripe's API.
 *
 * @property \BooklyStripe\Lib\Payment\Lib\Stripe\Service\AccountLinkService $accountLinks
 * @property \BooklyStripe\Lib\Payment\Lib\Stripe\Service\AccountService $accounts
 * @property \BooklyStripe\Lib\Payment\Lib\Stripe\Service\ApplePayDomainService $applePayDomains
 * @property \BooklyStripe\Lib\Payment\Lib\Stripe\Service\ApplicationFeeService $applicationFees
 * @property \BooklyStripe\Lib\Payment\Lib\Stripe\Service\BalanceService $balance
 * @property \BooklyStripe\Lib\Payment\Lib\Stripe\Service\BalanceTransactionService $balanceTransactions
 * @property \BooklyStripe\Lib\Payment\Lib\Stripe\Service\BillingPortal\BillingPortalServiceFactory $billingPortal
 * @property \BooklyStripe\Lib\Payment\Lib\Stripe\Service\ChargeService $charges
 * @property \BooklyStripe\Lib\Payment\Lib\Stripe\Service\Checkout\CheckoutServiceFactory $checkout
 * @property \BooklyStripe\Lib\Payment\Lib\Stripe\Service\CountrySpecService $countrySpecs
 * @property \BooklyStripe\Lib\Payment\Lib\Stripe\Service\CouponService $coupons
 * @property \BooklyStripe\Lib\Payment\Lib\Stripe\Service\CreditNoteService $creditNotes
 * @property \BooklyStripe\Lib\Payment\Lib\Stripe\Service\CustomerService $customers
 * @property \BooklyStripe\Lib\Payment\Lib\Stripe\Service\DisputeService $disputes
 * @property \BooklyStripe\Lib\Payment\Lib\Stripe\Service\EphemeralKeyService $ephemeralKeys
 * @property \BooklyStripe\Lib\Payment\Lib\Stripe\Service\EventService $events
 * @property \BooklyStripe\Lib\Payment\Lib\Stripe\Service\ExchangeRateService $exchangeRates
 * @property \BooklyStripe\Lib\Payment\Lib\Stripe\Service\FileLinkService $fileLinks
 * @property \BooklyStripe\Lib\Payment\Lib\Stripe\Service\FileService $files
 * @property \BooklyStripe\Lib\Payment\Lib\Stripe\Service\InvoiceItemService $invoiceItems
 * @property \BooklyStripe\Lib\Payment\Lib\Stripe\Service\InvoiceService $invoices
 * @property \BooklyStripe\Lib\Payment\Lib\Stripe\Service\Issuing\IssuingServiceFactory $issuing
 * @property \BooklyStripe\Lib\Payment\Lib\Stripe\Service\MandateService $mandates
 * @property \BooklyStripe\Lib\Payment\Lib\Stripe\Service\OrderReturnService $orderReturns
 * @property \BooklyStripe\Lib\Payment\Lib\Stripe\Service\OrderService $orders
 * @property \BooklyStripe\Lib\Payment\Lib\Stripe\Service\PaymentIntentService $paymentIntents
 * @property \BooklyStripe\Lib\Payment\Lib\Stripe\Service\PaymentMethodService $paymentMethods
 * @property \BooklyStripe\Lib\Payment\Lib\Stripe\Service\PayoutService $payouts
 * @property \BooklyStripe\Lib\Payment\Lib\Stripe\Service\PlanService $plans
 * @property \BooklyStripe\Lib\Payment\Lib\Stripe\Service\PriceService $prices
 * @property \BooklyStripe\Lib\Payment\Lib\Stripe\Service\ProductService $products
 * @property \BooklyStripe\Lib\Payment\Lib\Stripe\Service\Radar\RadarServiceFactory $radar
 * @property \BooklyStripe\Lib\Payment\Lib\Stripe\Service\RefundService $refunds
 * @property \BooklyStripe\Lib\Payment\Lib\Stripe\Service\Reporting\ReportingServiceFactory $reporting
 * @property \BooklyStripe\Lib\Payment\Lib\Stripe\Service\ReviewService $reviews
 * @property \BooklyStripe\Lib\Payment\Lib\Stripe\Service\SetupIntentService $setupIntents
 * @property \BooklyStripe\Lib\Payment\Lib\Stripe\Service\Sigma\SigmaServiceFactory $sigma
 * @property \BooklyStripe\Lib\Payment\Lib\Stripe\Service\SkuService $skus
 * @property \BooklyStripe\Lib\Payment\Lib\Stripe\Service\SourceService $sources
 * @property \BooklyStripe\Lib\Payment\Lib\Stripe\Service\SubscriptionItemService $subscriptionItems
 * @property \BooklyStripe\Lib\Payment\Lib\Stripe\Service\SubscriptionScheduleService $subscriptionSchedules
 * @property \BooklyStripe\Lib\Payment\Lib\Stripe\Service\SubscriptionService $subscriptions
 * @property \BooklyStripe\Lib\Payment\Lib\Stripe\Service\TaxRateService $taxRates
 * @property \BooklyStripe\Lib\Payment\Lib\Stripe\Service\Terminal\TerminalServiceFactory $terminal
 * @property \BooklyStripe\Lib\Payment\Lib\Stripe\Service\TokenService $tokens
 * @property \BooklyStripe\Lib\Payment\Lib\Stripe\Service\TopupService $topups
 * @property \BooklyStripe\Lib\Payment\Lib\Stripe\Service\TransferService $transfers
 * @property \BooklyStripe\Lib\Payment\Lib\Stripe\Service\WebhookEndpointService $webhookEndpoints
 */
class StripeClient extends BaseStripeClient
{
    /**
     * @var \BooklyStripe\Lib\Payment\Lib\Stripe\Service\CoreServiceFactory
     */
    private $coreServiceFactory;

    public function __get($name)
    {
        if (null === $this->coreServiceFactory) {
            $this->coreServiceFactory = new \BooklyStripe\Lib\Payment\Lib\Stripe\Service\CoreServiceFactory($this);
        }

        return $this->coreServiceFactory->__get($name);
    }
}
