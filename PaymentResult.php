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
    public Request $request;

    public string $module;

    public string $driver;

    public string $status = PaymentHistory::STATUS_PROCESSING;

    public ?ResponseInterface $response = null;

    public ?PaymentHistory $paymentHistory = null;

    public static function make(Request $request, string $module, string $driver): static
    {
        return new static($request, $module, $driver);
    }

    public function __construct(Request $request, string $module, string $driver)
    {
        $this->request = $request;
        $this->module = $module;
        $this->driver = $driver;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function fill(array $params): static
    {
        foreach ($params as $key => $value) {
            $this->{$key} = $value;
        }

        return $this;
    }
}
