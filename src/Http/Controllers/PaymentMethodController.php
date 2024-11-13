<?php

namespace LarabizCMS\Modules\Payment\Http\Controllers;

use Illuminate\Http\Request;
use LarabizCMS\Core\Http\Controllers\APIController;
use LarabizCMS\Modules\Payment\Facades\Payment;

class PaymentMethodController extends APIController
{
    public function index(): \Illuminate\Http\JsonResponse
    {
        return [];
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        return view('payment::show');
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
