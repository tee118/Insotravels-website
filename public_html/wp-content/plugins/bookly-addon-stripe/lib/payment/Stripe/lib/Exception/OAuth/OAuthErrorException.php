<?php

namespace BooklyStripe\Lib\Payment\Lib\Stripe\Exception\OAuth;

/**
 * Implements properties and methods common to all (non-SPL) Stripe OAuth
 * exceptions.
 */
abstract class OAuthErrorException extends \BooklyStripe\Lib\Payment\Lib\Stripe\Exception\ApiErrorException
{
    protected function constructErrorObject()
    {
        if (null === $this->jsonBody) {
            return null;
        }

        return \BooklyStripe\Lib\Payment\Lib\Stripe\OAuthErrorObject::constructFrom($this->jsonBody);
    }
}
