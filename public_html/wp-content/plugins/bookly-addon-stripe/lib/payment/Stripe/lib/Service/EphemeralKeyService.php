<?php

namespace BooklyStripe\Lib\Payment\Lib\Stripe\Service;

class EphemeralKeyService extends \BooklyStripe\Lib\Payment\Lib\Stripe\Service\AbstractService
{
    /**
     * Invalidates a short-lived API key for a given resource.
     *
     * @param string $id
     * @param null|array $params
     * @param null|array|\BooklyStripe\Lib\Payment\Lib\Stripe\Util\RequestOptions $opts
     *
     * @throws \BooklyStripe\Lib\Payment\Lib\Stripe\Exception\ApiErrorException if the request fails
     *
     * @return \BooklyStripe\Lib\Payment\Lib\Stripe\EphemeralKey
     */
    public function delete($id, $params = null, $opts = null)
    {
        return $this->request('delete', $this->buildPath('/v1/ephemeral_keys/%s', $id), $params, $opts);
    }

    /**
     * Creates a short-lived API key for a given resource.
     *
     * @param null|array $params
     * @param null|array|\BooklyStripe\Lib\Payment\Lib\Stripe\Util\RequestOptions $opts
     *
     * @throws \BooklyStripe\Lib\Payment\Lib\Stripe\Exception\ApiErrorException if the request fails
     *
     * @return \BooklyStripe\Lib\Payment\Lib\Stripe\EphemeralKey
     */
    public function create($params = null, $opts = null)
    {
        if (!$opts || !isset($opts['stripe_version'])) {
            throw new \BooklyStripe\Lib\Payment\Lib\Stripe\Exception\InvalidArgumentException('stripe_version must be specified to create an ephemeral key');
        }

        return $this->request('post', '/v1/ephemeral_keys', $params, $opts);
    }
}
