<?php

namespace BooklyStripe\Lib\Payment\Lib\Stripe\Exception;

/**
 * AuthenticationException is thrown when invalid credentials are used to
 * connect to Stripe's servers.
 */
class AuthenticationException extends ApiErrorException
{
}
