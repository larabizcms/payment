<?php
/**
 * LARABIZ CMS - Full SPA Laravel CMS
 *
 * @package    larabizcms/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

namespace LarabizCMS\Modules\Payment\Events;

class PaymentSuccess
{
    public function __construct(
        public string $module,
        public string $driver
    ) {
    }
}
