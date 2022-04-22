<?php

namespace BooklyStripe\Lib\Payment\Lib\Stripe\Util;

class ObjectTypes
{
    /**
     * @var array Mapping from object types to resource classes
     */
    const mapping = [
        \BooklyStripe\Lib\Payment\Lib\Stripe\Account::OBJECT_NAME => \BooklyStripe\Lib\Payment\Lib\Stripe\Account::class,
        \BooklyStripe\Lib\Payment\Lib\Stripe\AccountLink::OBJECT_NAME => \BooklyStripe\Lib\Payment\Lib\Stripe\AccountLink::class,
        \BooklyStripe\Lib\Payment\Lib\Stripe\AlipayAccount::OBJECT_NAME => \BooklyStripe\Lib\Payment\Lib\Stripe\AlipayAccount::class,
        \BooklyStripe\Lib\Payment\Lib\Stripe\ApplePayDomain::OBJECT_NAME => \BooklyStripe\Lib\Payment\Lib\Stripe\ApplePayDomain::class,
        \BooklyStripe\Lib\Payment\Lib\Stripe\ApplicationFee::OBJECT_NAME => \BooklyStripe\Lib\Payment\Lib\Stripe\ApplicationFee::class,
        \BooklyStripe\Lib\Payment\Lib\Stripe\ApplicationFeeRefund::OBJECT_NAME => \BooklyStripe\Lib\Payment\Lib\Stripe\ApplicationFeeRefund::class,
        \BooklyStripe\Lib\Payment\Lib\Stripe\Balance::OBJECT_NAME => \BooklyStripe\Lib\Payment\Lib\Stripe\Balance::class,
        \BooklyStripe\Lib\Payment\Lib\Stripe\BalanceTransaction::OBJECT_NAME => \BooklyStripe\Lib\Payment\Lib\Stripe\BalanceTransaction::class,
        \BooklyStripe\Lib\Payment\Lib\Stripe\BankAccount::OBJECT_NAME => \BooklyStripe\Lib\Payment\Lib\Stripe\BankAccount::class,
        \BooklyStripe\Lib\Payment\Lib\Stripe\BillingPortal\Session::OBJECT_NAME => \BooklyStripe\Lib\Payment\Lib\Stripe\BillingPortal\Session::class,
        \BooklyStripe\Lib\Payment\Lib\Stripe\BitcoinReceiver::OBJECT_NAME => \BooklyStripe\Lib\Payment\Lib\Stripe\BitcoinReceiver::class,
        \BooklyStripe\Lib\Payment\Lib\Stripe\BitcoinTransaction::OBJECT_NAME => \BooklyStripe\Lib\Payment\Lib\Stripe\BitcoinTransaction::class,
        \BooklyStripe\Lib\Payment\Lib\Stripe\Capability::OBJECT_NAME => \BooklyStripe\Lib\Payment\Lib\Stripe\Capability::class,
        \BooklyStripe\Lib\Payment\Lib\Stripe\Card::OBJECT_NAME => \BooklyStripe\Lib\Payment\Lib\Stripe\Card::class,
        \BooklyStripe\Lib\Payment\Lib\Stripe\Charge::OBJECT_NAME => \BooklyStripe\Lib\Payment\Lib\Stripe\Charge::class,
        \BooklyStripe\Lib\Payment\Lib\Stripe\Checkout\Session::OBJECT_NAME => \BooklyStripe\Lib\Payment\Lib\Stripe\Checkout\Session::class,
        \BooklyStripe\Lib\Payment\Lib\Stripe\Collection::OBJECT_NAME => \BooklyStripe\Lib\Payment\Lib\Stripe\Collection::class,
        \BooklyStripe\Lib\Payment\Lib\Stripe\CountrySpec::OBJECT_NAME => \BooklyStripe\Lib\Payment\Lib\Stripe\CountrySpec::class,
        \BooklyStripe\Lib\Payment\Lib\Stripe\Coupon::OBJECT_NAME => \BooklyStripe\Lib\Payment\Lib\Stripe\Coupon::class,
        \BooklyStripe\Lib\Payment\Lib\Stripe\CreditNote::OBJECT_NAME => \BooklyStripe\Lib\Payment\Lib\Stripe\CreditNote::class,
        \BooklyStripe\Lib\Payment\Lib\Stripe\CreditNoteLineItem::OBJECT_NAME => \BooklyStripe\Lib\Payment\Lib\Stripe\CreditNoteLineItem::class,
        \BooklyStripe\Lib\Payment\Lib\Stripe\Customer::OBJECT_NAME => \BooklyStripe\Lib\Payment\Lib\Stripe\Customer::class,
        \BooklyStripe\Lib\Payment\Lib\Stripe\CustomerBalanceTransaction::OBJECT_NAME => \BooklyStripe\Lib\Payment\Lib\Stripe\CustomerBalanceTransaction::class,
        \BooklyStripe\Lib\Payment\Lib\Stripe\Discount::OBJECT_NAME => \BooklyStripe\Lib\Payment\Lib\Stripe\Discount::class,
        \BooklyStripe\Lib\Payment\Lib\Stripe\Dispute::OBJECT_NAME => \BooklyStripe\Lib\Payment\Lib\Stripe\Dispute::class,
        \BooklyStripe\Lib\Payment\Lib\Stripe\EphemeralKey::OBJECT_NAME => \BooklyStripe\Lib\Payment\Lib\Stripe\EphemeralKey::class,
        \BooklyStripe\Lib\Payment\Lib\Stripe\Event::OBJECT_NAME => \BooklyStripe\Lib\Payment\Lib\Stripe\Event::class,
        \BooklyStripe\Lib\Payment\Lib\Stripe\ExchangeRate::OBJECT_NAME => \BooklyStripe\Lib\Payment\Lib\Stripe\ExchangeRate::class,
        \BooklyStripe\Lib\Payment\Lib\Stripe\File::OBJECT_NAME => \BooklyStripe\Lib\Payment\Lib\Stripe\File::class,
        \BooklyStripe\Lib\Payment\Lib\Stripe\File::OBJECT_NAME_ALT => \BooklyStripe\Lib\Payment\Lib\Stripe\File::class,
        \BooklyStripe\Lib\Payment\Lib\Stripe\FileLink::OBJECT_NAME => \BooklyStripe\Lib\Payment\Lib\Stripe\FileLink::class,
        \BooklyStripe\Lib\Payment\Lib\Stripe\Invoice::OBJECT_NAME => \BooklyStripe\Lib\Payment\Lib\Stripe\Invoice::class,
        \BooklyStripe\Lib\Payment\Lib\Stripe\InvoiceItem::OBJECT_NAME => \BooklyStripe\Lib\Payment\Lib\Stripe\InvoiceItem::class,
        \BooklyStripe\Lib\Payment\Lib\Stripe\InvoiceLineItem::OBJECT_NAME => \BooklyStripe\Lib\Payment\Lib\Stripe\InvoiceLineItem::class,
        \BooklyStripe\Lib\Payment\Lib\Stripe\Issuing\Authorization::OBJECT_NAME => \BooklyStripe\Lib\Payment\Lib\Stripe\Issuing\Authorization::class,
        \BooklyStripe\Lib\Payment\Lib\Stripe\Issuing\Card::OBJECT_NAME => \BooklyStripe\Lib\Payment\Lib\Stripe\Issuing\Card::class,
        \BooklyStripe\Lib\Payment\Lib\Stripe\Issuing\CardDetails::OBJECT_NAME => \BooklyStripe\Lib\Payment\Lib\Stripe\Issuing\CardDetails::class,
        \BooklyStripe\Lib\Payment\Lib\Stripe\Issuing\Cardholder::OBJECT_NAME => \BooklyStripe\Lib\Payment\Lib\Stripe\Issuing\Cardholder::class,
        \BooklyStripe\Lib\Payment\Lib\Stripe\Issuing\Dispute::OBJECT_NAME => \BooklyStripe\Lib\Payment\Lib\Stripe\Issuing\Dispute::class,
        \BooklyStripe\Lib\Payment\Lib\Stripe\Issuing\Transaction::OBJECT_NAME => \BooklyStripe\Lib\Payment\Lib\Stripe\Issuing\Transaction::class,
        \BooklyStripe\Lib\Payment\Lib\Stripe\LineItem::OBJECT_NAME => \BooklyStripe\Lib\Payment\Lib\Stripe\LineItem::class,
        \BooklyStripe\Lib\Payment\Lib\Stripe\LoginLink::OBJECT_NAME => \BooklyStripe\Lib\Payment\Lib\Stripe\LoginLink::class,
        \BooklyStripe\Lib\Payment\Lib\Stripe\Mandate::OBJECT_NAME => \BooklyStripe\Lib\Payment\Lib\Stripe\Mandate::class,
        \BooklyStripe\Lib\Payment\Lib\Stripe\Order::OBJECT_NAME => \BooklyStripe\Lib\Payment\Lib\Stripe\Order::class,
        \BooklyStripe\Lib\Payment\Lib\Stripe\OrderItem::OBJECT_NAME => \BooklyStripe\Lib\Payment\Lib\Stripe\OrderItem::class,
        \BooklyStripe\Lib\Payment\Lib\Stripe\OrderReturn::OBJECT_NAME => \BooklyStripe\Lib\Payment\Lib\Stripe\OrderReturn::class,
        \BooklyStripe\Lib\Payment\Lib\Stripe\PaymentIntent::OBJECT_NAME => \BooklyStripe\Lib\Payment\Lib\Stripe\PaymentIntent::class,
        \BooklyStripe\Lib\Payment\Lib\Stripe\PaymentMethod::OBJECT_NAME => \BooklyStripe\Lib\Payment\Lib\Stripe\PaymentMethod::class,
        \BooklyStripe\Lib\Payment\Lib\Stripe\Payout::OBJECT_NAME => \BooklyStripe\Lib\Payment\Lib\Stripe\Payout::class,
        \BooklyStripe\Lib\Payment\Lib\Stripe\Person::OBJECT_NAME => \BooklyStripe\Lib\Payment\Lib\Stripe\Person::class,
        \BooklyStripe\Lib\Payment\Lib\Stripe\Plan::OBJECT_NAME => \BooklyStripe\Lib\Payment\Lib\Stripe\Plan::class,
        \BooklyStripe\Lib\Payment\Lib\Stripe\Price::OBJECT_NAME => \BooklyStripe\Lib\Payment\Lib\Stripe\Price::class,
        \BooklyStripe\Lib\Payment\Lib\Stripe\Product::OBJECT_NAME => \BooklyStripe\Lib\Payment\Lib\Stripe\Product::class,
        \BooklyStripe\Lib\Payment\Lib\Stripe\Radar\EarlyFraudWarning::OBJECT_NAME => \BooklyStripe\Lib\Payment\Lib\Stripe\Radar\EarlyFraudWarning::class,
        \BooklyStripe\Lib\Payment\Lib\Stripe\Radar\ValueList::OBJECT_NAME => \BooklyStripe\Lib\Payment\Lib\Stripe\Radar\ValueList::class,
        \BooklyStripe\Lib\Payment\Lib\Stripe\Radar\ValueListItem::OBJECT_NAME => \BooklyStripe\Lib\Payment\Lib\Stripe\Radar\ValueListItem::class,
        \BooklyStripe\Lib\Payment\Lib\Stripe\Recipient::OBJECT_NAME => \BooklyStripe\Lib\Payment\Lib\Stripe\Recipient::class,
        \BooklyStripe\Lib\Payment\Lib\Stripe\RecipientTransfer::OBJECT_NAME => \BooklyStripe\Lib\Payment\Lib\Stripe\RecipientTransfer::class,
        \BooklyStripe\Lib\Payment\Lib\Stripe\Refund::OBJECT_NAME => \BooklyStripe\Lib\Payment\Lib\Stripe\Refund::class,
        \BooklyStripe\Lib\Payment\Lib\Stripe\Reporting\ReportRun::OBJECT_NAME => \BooklyStripe\Lib\Payment\Lib\Stripe\Reporting\ReportRun::class,
        \BooklyStripe\Lib\Payment\Lib\Stripe\Reporting\ReportType::OBJECT_NAME => \BooklyStripe\Lib\Payment\Lib\Stripe\Reporting\ReportType::class,
        \BooklyStripe\Lib\Payment\Lib\Stripe\Review::OBJECT_NAME => \BooklyStripe\Lib\Payment\Lib\Stripe\Review::class,
        \BooklyStripe\Lib\Payment\Lib\Stripe\SetupIntent::OBJECT_NAME => \BooklyStripe\Lib\Payment\Lib\Stripe\SetupIntent::class,
        \BooklyStripe\Lib\Payment\Lib\Stripe\Sigma\ScheduledQueryRun::OBJECT_NAME => \BooklyStripe\Lib\Payment\Lib\Stripe\Sigma\ScheduledQueryRun::class,
        \BooklyStripe\Lib\Payment\Lib\Stripe\SKU::OBJECT_NAME => \BooklyStripe\Lib\Payment\Lib\Stripe\SKU::class,
        \BooklyStripe\Lib\Payment\Lib\Stripe\Source::OBJECT_NAME => \BooklyStripe\Lib\Payment\Lib\Stripe\Source::class,
        \BooklyStripe\Lib\Payment\Lib\Stripe\SourceTransaction::OBJECT_NAME => \BooklyStripe\Lib\Payment\Lib\Stripe\SourceTransaction::class,
        \BooklyStripe\Lib\Payment\Lib\Stripe\Subscription::OBJECT_NAME => \BooklyStripe\Lib\Payment\Lib\Stripe\Subscription::class,
        \BooklyStripe\Lib\Payment\Lib\Stripe\SubscriptionItem::OBJECT_NAME => \BooklyStripe\Lib\Payment\Lib\Stripe\SubscriptionItem::class,
        \BooklyStripe\Lib\Payment\Lib\Stripe\SubscriptionSchedule::OBJECT_NAME => \BooklyStripe\Lib\Payment\Lib\Stripe\SubscriptionSchedule::class,
        \BooklyStripe\Lib\Payment\Lib\Stripe\TaxId::OBJECT_NAME => \BooklyStripe\Lib\Payment\Lib\Stripe\TaxId::class,
        \BooklyStripe\Lib\Payment\Lib\Stripe\TaxRate::OBJECT_NAME => \BooklyStripe\Lib\Payment\Lib\Stripe\TaxRate::class,
        \BooklyStripe\Lib\Payment\Lib\Stripe\Terminal\ConnectionToken::OBJECT_NAME => \BooklyStripe\Lib\Payment\Lib\Stripe\Terminal\ConnectionToken::class,
        \BooklyStripe\Lib\Payment\Lib\Stripe\Terminal\Location::OBJECT_NAME => \BooklyStripe\Lib\Payment\Lib\Stripe\Terminal\Location::class,
        \BooklyStripe\Lib\Payment\Lib\Stripe\Terminal\Reader::OBJECT_NAME => \BooklyStripe\Lib\Payment\Lib\Stripe\Terminal\Reader::class,
        \BooklyStripe\Lib\Payment\Lib\Stripe\ThreeDSecure::OBJECT_NAME => \BooklyStripe\Lib\Payment\Lib\Stripe\ThreeDSecure::class,
        \BooklyStripe\Lib\Payment\Lib\Stripe\Token::OBJECT_NAME => \BooklyStripe\Lib\Payment\Lib\Stripe\Token::class,
        \BooklyStripe\Lib\Payment\Lib\Stripe\Topup::OBJECT_NAME => \BooklyStripe\Lib\Payment\Lib\Stripe\Topup::class,
        \BooklyStripe\Lib\Payment\Lib\Stripe\Transfer::OBJECT_NAME => \BooklyStripe\Lib\Payment\Lib\Stripe\Transfer::class,
        \BooklyStripe\Lib\Payment\Lib\Stripe\TransferReversal::OBJECT_NAME => \BooklyStripe\Lib\Payment\Lib\Stripe\TransferReversal::class,
        \BooklyStripe\Lib\Payment\Lib\Stripe\UsageRecord::OBJECT_NAME => \BooklyStripe\Lib\Payment\Lib\Stripe\UsageRecord::class,
        \BooklyStripe\Lib\Payment\Lib\Stripe\UsageRecordSummary::OBJECT_NAME => \BooklyStripe\Lib\Payment\Lib\Stripe\UsageRecordSummary::class,
        \BooklyStripe\Lib\Payment\Lib\Stripe\WebhookEndpoint::OBJECT_NAME => \BooklyStripe\Lib\Payment\Lib\Stripe\WebhookEndpoint::class,
    ];
}