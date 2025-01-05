<?php
/**
 * LARABIZ CMS - Full SPA Laravel CMS
 *
 * @package    larabizcms/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

namespace LarabizCMS\Modules\Payment\Methods\Nganluong;

use Omnipay\Common\Message\AbstractResponse;

class CompletePurchaseResponse extends AbstractResponse
{
    /**
     * Check if the transaction is successful.
     *
     * @return bool
     */
    public function isSuccessful(): bool
    {
        return isset($this->data['error_code']) && $this->data['error_code'] === '00';
    }

    /**
     * Get the transaction reference (provided by the gateway).
     *
     * @return string|null
     */
    public function getTransactionReference(): ?string
    {
        return $this->data['transaction_id'] ?? null;
    }

    /**
     * Get the message returned by the gateway.
     *
     * @return string|null
     */
    public function getMessage(): ?string
    {
        return $this->data['error_message'] ?? null;
    }

    /**
     * Get the payment amount.
     *
     * @return string|null
     */
    public function getAmount(): ?string
    {
        return $this->data['total_amount'] ?? null;
    }

    /**
     * Check if the transaction is pending.
     *
     * @return bool
     */
    public function isPending(): bool
    {
        return isset($this->data['error_code']) && $this->data['error_code'] === '99';
    }

    /**
     * Check if the transaction is canceled.
     *
     * @return bool
     */
    public function isCancelled(): bool
    {
        return isset($this->data['error_code']) && $this->data['error_code'] === '02';
    }
}
