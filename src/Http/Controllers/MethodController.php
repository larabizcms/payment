<?php
/**
 * LARABIZ CMS - Full SPA Laravel CMS
 *
 * @package    larabizcms/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

namespace LarabizCMS\Modules\Payment\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use LarabizCMS\Core\Http\Controllers\APIController;
use LarabizCMS\Modules\Payment\Facades\Payment;

class MethodController extends APIController
{
    public function index(): JsonResponse
    {
        $payments = collect(Payment::methods())
            ->map(function ($payment) {
                return $payment->toArray();
            })
            ->values();

        return $this->restSuccess($payments, 'Payment methods retrieved successfully');
    }
}
