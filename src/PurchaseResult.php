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

    protected array $returnQuery = [];

    protected array $cancelQuery = [];

    protected array $defaultQuery = [];

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

    public function withReturnQuery(array $returnQuery): static
    {
        $this->returnQuery = $returnQuery;

        return $this;
    }

    public function withCancelQuery(array $cancelQuery): static
    {
        $this->cancelQuery = $cancelQuery;

        return $this;
    }

    public function withQueryString(array $defaultQuery): static
    {
        $this->defaultQuery = $defaultQuery;

        return $this;
    }

    public function getPaymentable(): ?Paymentable
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
        $returnQuery = array_merge($this->defaultQuery, $this->returnQuery);
        $cancelQuery = array_merge($this->defaultQuery, $this->cancelQuery);

        return [
            'returnUrl' => url("/payment/{$this->module}/complete/{$this->transactionId}", $returnQuery),
            'cancelUrl' => url("/payment/{$this->module}/cancel/{$this->transactionId}", $cancelQuery),
        ];
    }
}
