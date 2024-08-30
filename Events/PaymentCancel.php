<?php
/**
 * LARABIZ CMS - Full SPA Laravel CMS
 *
 * @package    larabizcms/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

namespace LarabizCMS\Modules\Payment\Events;

use LarabizCMS\Modules\Payment\PaymentResult;

class PaymentCancel
{
    public function __construct(
        public PaymentResult $result
    ) {
    }
}
