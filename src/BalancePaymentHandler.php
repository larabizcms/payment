<?php
/**
 * LARABIZ CMS - Full SPA Laravel CMS
 *
 * @package    larabizcms/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

namespace LarabizCMS\Modules\Payment;

use Illuminate\Http\Request;
use LarabizCMS\Core\Helpers\Traits\RestResponses;

class BalancePaymentHandler extends BaseModuleHandler
{
    use RestResponses;

    public function purchase(Request $request, string $transactionId, Method $method): PurchaseResult
    {
        $request->validate(
            [
                'amount' => [
                    'required',
                    'numeric',
                    'min:5',
                    'bail'
                ]
            ]
        );

        $amount = $request->float('amount');

        if ($amount % 5 !== 0) {
            $this->restFail(__('Amount must be a multiple of 5'))->send();
            die;
        }

        return new PurchaseResult(
            $transactionId,
            'balance',
            options: [
                'amount' => $amount,
                'description' => __('Topup balance account'),
                'currency' => 'USD',
            ],
            data: [
                'amount' => $amount,
            ]
        );
    }

    public function success(PaymentResult $result): void
    {
        $amount = $result->paymentHistory->getData('amount');

        $payer = $result->paymentHistory->payer()->lockForUpdate()->first();

        $payer->increment('balance', $amount);
    }
}
