<?php
/**
 * LARABIZ CMS - Full SPA Laravel CMS
 *
 * @package    larabizcms/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

namespace LarabizCMS\Modules\Payment\Methods\Nganluong;

use Omnipay\Common\Message\AbstractRequest;

class PurchaseRequest extends AbstractRequest
{
    protected string $endpoint = 'https://www.nganluong.vn/checkout.php'; // Sandbox URL

    public function getData(): array
    {
        $this->validate('merchantId', 'merchantPassword', 'receiverEmail', 'amount', 'returnUrl');

        $orderCode = '123';

        $secureCode = md5($this->getParameter('merchantId')
            . ' '
            . $this->getReturnUrl() . ' '
            . $this->getParameter('receiverEmail')
            . ' '
            . $this->getDescription() . ' '
            . $orderCode . ' '
            . $this->getAmount()
            . ' ' . $this->getCurrency()
            . ' ' . 1
            . ' ' . 0
            . ' ' . 0
            . ' ' . 0
            . ' ' . 0
            . ' ' . $this->getDescription()
            . ' ' . ''
            . ' ' . ''
            . ' ' . $this->getMerchantPassword()
        );

        return [
            'merchant_site_code' => $this->getParameter('merchantId'),
            'transaction_info' => $this->getDescription(),
            'receiver' => $this->getParameter('receiverEmail'),
            'currency' => $this->getCurrency(),
            'order_code' => $orderCode,
            'price' => $this->getAmount(),
            'quantity' => 1,
            'return_url' => $this->getReturnUrl(),
            'cancel_url' => $this->getCancelUrl(),
            'tax' => 0,
            'discount' => 0,
            'fee_cal' => 0,
            'fee_shipping' => 0,
            'secure_code' => $secureCode,
            'order_description' => $this->getDescription(),
        ];
    }

    public function sendData($data): PurchaseResponse
    {
        $queryString = http_build_query($data);

        return $this->response = new PurchaseResponse($this, $this->endpoint . '?' . $queryString);
    }

    public function setMerchantId($value): static
    {
        return $this->setParameter('merchantId', $value);
    }

    public function getMerchantId()
    {
        return $this->getParameter('merchantId');
    }

    public function setMerchantPassword($value)
    {
        return $this->setParameter('merchantPassword', $value);
    }

    public function getMerchantPassword()
    {
        return $this->getParameter('merchantPassword');
    }

    public function setReceiverEmail($value)
    {
        return $this->setParameter('receiverEmail', $value);
    }

    public function getReceiverEmail()
    {
        return $this->getParameter('receiverEmail');
    }

    public function setSandbox($value)
    {
        return $this->setParameter('sandbox', $value);
    }

    public function getSandbox()
    {
        return $this->getParameter('sandbox');
    }
}
