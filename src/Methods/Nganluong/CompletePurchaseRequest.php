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

class CompletePurchaseRequest extends AbstractRequest
{
    protected string $endpoint = 'https://www.nganluong.vn/service/order/checkV2';

    public function getData(): array
    {
        $this->validate('merchantId', 'merchantPassword');
        $orderCode = $this->httpRequest->get('order_code');

        // $verifySecureCode = ' ' . $this->httpRequest->get('transaction_info')
        //     . ' ' . $this->httpRequest->get('order_code')
        //     . ' ' . $this->httpRequest->get('price')
        //     . ' ' . $this->httpRequest->get['payment_id']
        //     . ' ' . $this->httpRequest->get['payment_type']
        //     . ' ' . $this->httpRequest->get['error_text']
        //     . ' ' . $this->getParameter('merchantId')
        //     . ' ' . $this->getParameter('merchantPassword');

        return [
            'order_code' => $orderCode,
            'merchant_id' => $this->getParameter('merchantId'),
            'checksum' => md5($orderCode.'|'. $this->getParameter('merchantPassword')),
        ];
    }

    public function sendData($data): CompletePurchaseResponse
    {
        $response = $this->httpClient->request(
            'POST',
            $this->endpoint,
            [],
            http_build_query($data)
        );

        $content = json_encode($response->getBody()->getContents(), JSON_THROW_ON_ERROR);

        return $this->response = new CompletePurchaseResponse($this, $content);
    }
}
