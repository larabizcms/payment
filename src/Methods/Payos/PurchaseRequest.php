<?php
/**
 * LARABIZ CMS - Full SPA Laravel CMS
 *
 * @package    larabizcms/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

namespace LarabizCMS\Modules\Payment\Methods\Payos;

use Omnipay\Common\Message\AbstractRequest;
use PayOS\PayOS;

class PurchaseRequest extends AbstractRequest
{
    public function getData(): array
    {
        $this->validate('amount', 'orderCode');

        $orderCode = (int) $this->getOrderCode();
        $amount = $this->getAmount() * config('payment.currency_conversion.VND');

        return [
            'orderCode' => $orderCode,
            'amount' => $amount,
            'quantity' => 1,
            'items' => [
                [
                    'name' => $this->getDescription(),
                    'price' => $amount,
                    'quantity' => 1,
                ]
            ],
            'returnUrl' => $this->getReturnUrl(),
            'cancelUrl' => $this->getCancelUrl(),
            'description' => $this->getDescription(),
        ];
    }

    public function sendData($data): PurchaseResponse
    {
        $payOSClientId = config('payment.methods.Payos.clientId');
        $payOSApiKey = config('payment.methods.Payos.key');
        $payOSChecksumKey = config('payment.methods.Payos.checksumKey');

        $payOS = new PayOS($payOSClientId, $payOSApiKey, $payOSChecksumKey);
        $response = $payOS->createPaymentLink($data);

        return $this->response = new PurchaseResponse($this, $response);
    }

    public function setOrderCode($value): static
    {
        return $this->setParameter('orderCode', $value);
    }

    public function getOrderCode()
    {
        return $this->getParameter('orderCode');
    }
}
