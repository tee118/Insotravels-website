<?php

namespace BooklyStripe\Lib\Payment\Lib\Stripe\ApiOperations;

/**
 * Trait for listable resources. Adds a `all()` static method to the class.
 *
 * This trait should only be applied to classes that derive from StripeObject.
 */
trait All
{
    /**
     * @param null|array $params
     * @param null|array|string $opts
     *
     * @throws \BooklyStripe\Lib\Payment\Lib\Stripe\Exception\ApiErrorException if the request fails
     *
     * @return \BooklyStripe\Lib\Payment\Lib\Stripe\Collection of ApiResources
     */
    public static function all($params = null, $opts = null)
    {
        self::_validateParams($params);
        $url = static::classUrl();

        list($response, $opts) = static::_staticRequest('get', $url, $params, $opts);
        $obj = \BooklyStripe\Lib\Payment\Lib\Stripe\Util\Util::convertToStripeObject($response->json, $opts);
        if (!($obj instanceof \BooklyStripe\Lib\Payment\Lib\Stripe\Collection)) {
            throw new \BooklyStripe\Lib\Payment\Lib\Stripe\Exception\UnexpectedValueException(
                'Expected type ' . \BooklyStripe\Lib\Payment\Lib\Stripe\Collection::class . ', got "' . \get_class($obj) . '" instead.'
            );
        }
        $obj->setLastResponse($response);
        $obj->setFilters($params);

        return $obj;
    }
}
