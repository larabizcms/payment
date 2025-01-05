<?php
/**
 * LARABIZ CMS - Full SPA Laravel CMS
 *
 * @package    larabizcms/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

namespace LarabizCMS\Modules\Payment\Methods\Nganluong;

use Omnipay\Common\AbstractGateway;

class Gateway extends AbstractGateway
{
    public function getName(): string
    {
        return 'NganLuong';
    }

    public function getDefaultParameters(): array
    {
        return [
            'merchantId' => '',
            'merchantPassword' => '',
            'receiverEmail' => '',
            'sandbox' => true,
        ];
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

    public function purchase(array $options = [])
    {
        return $this->createRequest(PurchaseRequest::class, $options);
    }

    public function completePurchase(array $options = [])
    {
        return $this->createRequest(CompletePurchaseRequest::class, $options);
    }
}
