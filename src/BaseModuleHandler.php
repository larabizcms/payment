<?php
/**
 * LARABIZ CMS - Full SPA Laravel CMS
 *
 * @package    larabizcms/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

namespace LarabizCMS\Modules\Payment;

use LarabizCMS\Modules\Payment\Contracts\ModuleHandler;

abstract class BaseModuleHandler implements ModuleHandler
{
    public function fail(PaymentResult $result): void
    {
        // TODO: Implement fail() method.
    }

    public function cancel(PaymentResult $result): void
    {
        // TODO: Implement cancel() method.
    }
}
