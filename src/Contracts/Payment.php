<?php
/**
 * LARABIZ CMS - Full SPA Laravel CMS
 *
 * @package    larabizcms/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

namespace LarabizCMS\Modules\Payment\Contracts;

use Illuminate\Http\Request;
use LarabizCMS\Modules\Payment\Method;
use LarabizCMS\Modules\Payment\PaymentResult;

interface Payment
{
    /**
     * Register module in payment
     *
     * @param  string  $module
     * @param  string<ModuleHandler>  $handler
     * @return void
     * @see \LarabizCMS\Modules\Payment\Payment::registerModule()
     */
    public function registerModule(string $module, string $handler): void;

    /**
     * Get module in payment
     *
     * @param  string  $module
     * @return ModuleHandler
     * @see \LarabizCMS\Modules\Payment\Payment::getModule()
     */
    public function getModule(string $module): ModuleHandler;

    /**
     * Create payment with request
     *
     * @param  Request  $request
     * @param  string  $module
     * @param  string  $driver
     * @return PaymentResult
     * @see \LarabizCMS\Modules\Payment\Payment::create()
     */
    public function create(Request $request, string $module, Method $method): PaymentResult;
}
