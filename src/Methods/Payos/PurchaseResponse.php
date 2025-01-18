<?php
/**
 * LARABIZ CMS - Full SPA Laravel CMS
 *
 * @package    larabizcms/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

namespace LarabizCMS\Modules\Payment\Methods\Payos;

use Omnipay\Common\Message\AbstractResponse;

class PurchaseResponse extends AbstractResponse
{
    public function isSuccessful(): bool
    {
        return isset($this->data['code']) && $this->data['code'] === '00';
    }

    public function isRedirect(): bool
    {
        return false;
    }
}
