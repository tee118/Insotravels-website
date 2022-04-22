<?php

namespace BooklyStripe\Lib\Payment\Lib\Stripe\Service;

class CountrySpecService extends \BooklyStripe\Lib\Payment\Lib\Stripe\Service\AbstractService
{
    /**
     * Lists all Country Spec objects available in the API.
     *
     * @param null|array $params
     * @param null|array|\BooklyStripe\Lib\Payment\Lib\Stripe\Util\RequestOptions $opts
     *
     * @throws \BooklyStripe\Lib\Payment\Lib\Stripe\Exception\ApiErrorException if the request fails
     *
     * @return \BooklyStripe\Lib\Payment\Lib\Stripe\Collection
     */
    public function all($params = null, $opts = null)
    {
        return $this->requestCollection('get', '/v1/country_specs', $params, $opts);
    }

    /**
     * Returns a Country Spec for a given Country code.
     *
     * @param string $id
     * @param null|array $params
     * @param null|array|\BooklyStripe\Lib\Payment\Lib\Stripe\Util\RequestOptions $opts
     *
     * @throws \BooklyStripe\Lib\Payment\Lib\Stripe\Exception\ApiErrorException if the request fails
     *
     * @return \BooklyStripe\Lib\Payment\Lib\Stripe\CountrySpec
     */
    public function retrieve($id, $params = null, $opts = null)
    {
        return $this->request('get', $this->buildPath('/v1/country_specs/%s', $id), $params, $opts);
    }
}
