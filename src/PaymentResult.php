<?php
/**
 * LARABIZ CMS - Full SPA Laravel CMS
 *
 * @package    larabizcms/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

namespace LarabizCMS\Modules\Payment;

use Illuminate\Http\Request;
use LarabizCMS\Modules\Payment\Models\PaymentHistory;
use Omnipay\Common\Message\ResponseInterface;

class PaymentResult
{
    public string $module;

    public string $method;

    public ?string $redirectUrl = null;

    public bool $isRedirect = false;

    public string $transactionId;

    public string $status = PaymentHistory::STATUS_PROCESSING;

    public ?ResponseInterface $response = null;

    public static function make(Request $request, PaymentHistory $paymentHistory): static
    {
        return new static($request, $paymentHistory);
    }

    public function __construct(public Request $request, public PaymentHistory $paymentHistory)
    {
        $this->module = $paymentHistory->module;
        $this->method = $paymentHistory->payment_method;
        $this->transactionId = $paymentHistory->id;
    }

    public function setRedirectUrl(string $url): static
    {
        $this->redirectUrl = $url;

        return $this;
    }

    public function setIsRedirect(bool $isRedirect): static
    {
        $this->isRedirect = $isRedirect;

        return $this;
    }

    public function isSuccessful(): bool
    {
        return $this->status === PaymentHistory::STATUS_SUCCESS;
    }

    public function isRedirect(): bool
    {
        return $this->isRedirect;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->response?->getMessage();
    }

    public function fill(array $params): static
    {
        foreach ($params as $key => $value) {
            $this->{$key} = $value;
        }

        return $this;
    }
}
