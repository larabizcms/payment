<?php
/**
 * LARABIZ CMS - Full SPA Laravel CMS
 *
 * @package    larabizcms/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

namespace LarabizCMS\Modules\Payment\Methods\Nganluong;

use Omnipay\Common\Message\AbstractResponse;

class PurchaseResponse extends AbstractResponse
{
    public function isSuccessful(): bool
    {
        return isset($this->data['error_code']) && $this->data['error_code'] === '00';
    }

    public function isRedirect(): true
    {
        return true;
    }

    public function getRedirectUrl()
    {
        return $this->data;
    }
}
