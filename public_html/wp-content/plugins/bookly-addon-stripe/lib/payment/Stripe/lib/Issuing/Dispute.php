<?php

namespace BooklyStripe\Lib\Payment\Lib\Stripe\Issuing;

/**
 * As a <a href="https://stripe.com/docs/issuing">card issuer</a>, you can <a
 * href="https://stripe.com/docs/issuing/purchases/disputes">dispute</a>
 * transactions that you do not recognize, suspect to be fraudulent, or have some
 * other issue.
 *
 * Related guide: <a
 * href="https://stripe.com/docs/issuing/purchases/disputes">Disputing
 * Transactions</a>
 *
 * @property string $id Unique identifier for the object.
 * @property string $object String representing the object's type. Objects of the same type share the same value.
 * @property \BooklyStripe\Lib\Payment\Lib\Stripe\BalanceTransaction[] $balance_transactions List of balance transactions associated with this dispute.
 * @property bool $livemode Has the value <code>true</code> if the object exists in live mode or the value <code>false</code> if the object exists in test mode.
 * @property string|\BooklyStripe\Lib\Payment\Lib\Stripe\Issuing\Transaction $transaction The transaction being disputed.
 */
class Dispute extends \BooklyStripe\Lib\Payment\Lib\Stripe\ApiResource
{
    const OBJECT_NAME = 'issuing.dispute';

    use \BooklyStripe\Lib\Payment\Lib\Stripe\ApiOperations\All;
    use \BooklyStripe\Lib\Payment\Lib\Stripe\ApiOperations\Create;
    use \BooklyStripe\Lib\Payment\Lib\Stripe\ApiOperations\Retrieve;
    use \BooklyStripe\Lib\Payment\Lib\Stripe\ApiOperations\Update;
}
