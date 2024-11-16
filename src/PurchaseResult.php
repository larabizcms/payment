<?php
/**
 * LARABIZ CMS - Full SPA Laravel CMS
 *
 * @package    larabizcms/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

namespace LarabizCMS\Modules\Payment;

use LarabizCMS\Modules\Payment\Contracts\Paymentable;

class PurchaseResult
{
    protected ?Paymentable $paymentable = null;

    protected array $options = [];

    protected array $data = [];

    public function __construct(
        protected string $transactionId,
        protected string $module,
        Paymentable $paymentable = null,
        array $options = [],
        array $data = []
    ) {
        $this->paymentable = $paymentable;

        $this->options = $options;

        $this->data = $data;
    }

    public function getPaymentable(): Paymentable
    {
        return $this->paymentable;
    }

    public function getOptions(): array
    {
        return [
            ...$this->getDefaultOptions(),
            ...$this->options,
        ];
    }

    public function getData(): array
    {
        return $this->data;
    }

    protected function getDefaultOptions(): array
    {
        return [
            'returnUrl' => url("/payment/{$this->module}/complete/{$this->transactionId}"),
            'cancelUrl' => url("/payment/{$this->module}/cancel/{$this->transactionId}"),
        ];
    }
}
