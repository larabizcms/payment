<?php
/**
 * LARABIZ CMS - Full SPA Laravel CMS
 *
 * @package    larabizcms/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

namespace LarabizCMS\Modules\Payment;

use LarabizCMS\Modules\Payment\Contracts\Paymentable;

class PurchaseResult
{
    public function __construct(
        public Paymentable $paymentable,
        public array $options
    ) {
    }
}
