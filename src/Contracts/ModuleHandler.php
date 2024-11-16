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
use LarabizCMS\Modules\Payment\Models\PaymentHistory;
use LarabizCMS\Modules\Payment\PaymentResult;
use LarabizCMS\Modules\Payment\PurchaseResult;

interface ModuleHandler
{
    public function purchase(Request $request, string $transactionId, Method $method): PurchaseResult;

    public function success(PaymentResult $result): void;

    public function fail(PaymentResult $result): void;

    public function cancel(PaymentResult $result): void;
}
