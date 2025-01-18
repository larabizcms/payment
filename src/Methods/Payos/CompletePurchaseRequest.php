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

class CompletePurchaseRequest extends AbstractRequest
{
    public function getData(): array
    {
        $this->validate('code');
        $data = $this->getParameter('data');

        $id = $this->getId() ?? $data['paymentLinkId'];

        return [
            'id' => $id,
        ];
    }

    public function sendData($data): CompletePurchaseResponse
    {
        $payOSClientId = config('payment.methods.Payos.clientId');
        $payOSApiKey = config('payment.methods.Payos.key');
        $payOSChecksumKey = config('payment.methods.Payos.checksumKey');

        $payOS = new PayOS($payOSClientId, $payOSApiKey, $payOSChecksumKey);
        $paymentInfo = $payOS->getPaymentLinkInformation($data['id']);

        return $this->response = new CompletePurchaseResponse($this, $paymentInfo);
    }

    public function setCode($value): static
    {
        return $this->setParameter('code', $value);
    }

    public function getCode()
    {
        return $this->getParameter('code');
    }

    public function setId($value): static
    {
        return $this->setParameter('id', $value);
    }

    public function getId()
    {
        return $this->getParameter('id');
    }

    public function setData($value): static
    {
        return $this->setParameter('data', $value);
    }
}
