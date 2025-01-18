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

class CompletePurchaseRequest extends AbstractRequest
{
    protected string $endpoint = 'https://api-merchant.payos.vn/v2/payment-requests/{id}';

    public function getData(): array
    {
        $this->validate('code', 'id');

        return [
            'id' => $this->httpRequest->get('id'),
        ];
    }

    public function sendData($data): CompletePurchaseResponse
    {
        $response = $this->httpClient->request(
            'POST',
            str_replace('{id}', $data['id'], $this->endpoint),
            [
                'x-client-id' => $this->httpRequest->get('code'),
                'x-api-key' => $this->httpRequest->get('key'),
            ]
        );

        $content = json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);

        return $this->response = new CompletePurchaseResponse($this, $content);
    }
}
