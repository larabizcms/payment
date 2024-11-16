<?php
/**
 * LARABIZ CMS - Full SPA Laravel CMS
 *
 * @package    larabizcms/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

namespace LarabizCMS\Modules\Payment\Facades;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Facade;
use LarabizCMS\Modules\Payment\Contracts\ModuleHandler;
use LarabizCMS\Modules\Payment\Method;
use LarabizCMS\Modules\Payment\PaymentResult;

/**
 * @method static void registerModule(string $module, string $handler)
 * @method static ModuleHandler getModule(string $module)
 * @method static PaymentResult create(Request $request, string $module, Method $method)
 * @method static PaymentResult complete(Request $request, string $transactionId)
 * @method static PaymentResult cancel(Request $request, string $transactionId)
 * @method static array modules()
 * @method static array<Method> methods()
 * @method static Method method(string $method)
 * @see \LarabizCMS\Modules\Payment\Payment
 */
class Payment extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return \LarabizCMS\Modules\Payment\Contracts\Payment::class;
    }
}
