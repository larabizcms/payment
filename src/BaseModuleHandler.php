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
    protected function getDefaultReturnUrl(string $module, string $transactionId): string
    {
        return url("/payment/{$module}/complete/{$transactionId}");
    }

    protected function getDefaultCancelUrl(string $module, string $transactionId): string
    {
        return url("/payment/{$module}/cancel/{$transactionId}");
    }
}
