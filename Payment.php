<?php
/**
 * LARABIZ CMS - Full SPA Laravel CMS
 *
 * @package    larabizcms/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

namespace LarabizCMS\Modules\Payment;

use LarabizCMS\Modules\Payment\Contracts\Module;
use LarabizCMS\Modules\Payment\Exceptions\PaymentException;

class Payment implements Contracts\Payment
{
    protected array $modules = [];

    /**
     * Register module in payment
     *
     * @param  string  $module
     * @param  string<Module>  $handler
     * @return void
     */
    public function registerModule(string $module, string $handler): void
    {
        $this->modules[$module] = $handler;
    }

    public function getModule(string $module): Module
    {
        if (! isset($this->modules[$module])) {
            throw PaymentException::moduleNotFound('Module not found');
        }

        return app($this->modules[$module]);
    }
}
