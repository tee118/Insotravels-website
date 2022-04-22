<?php

namespace BooklyStripe\Lib\Payment\Lib\Stripe\Service\BillingPortal;

class SessionService extends \BooklyStripe\Lib\Payment\Lib\Stripe\Service\AbstractService
{
    /**
     * Creates a session of the customer portal.
     *
     * @param null|array $params
     * @param null|array|\BooklyStripe\Lib\Payment\Lib\Stripe\Util\RequestOptions $opts
     *
     * @throws \BooklyStripe\Lib\Payment\Lib\Stripe\Exception\ApiErrorException if the request fails
     *
     * @return \BooklyStripe\Lib\Payment\Lib\Stripe\BillingPortal\Session
     */
    public function create($params = null, $opts = null)
    {
        return $this->request('post', '/v1/billing_portal/sessions', $params, $opts);
    }
}
