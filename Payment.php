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
use LarabizCMS\Modules\Payment\Contracts\Module;
use LarabizCMS\Modules\Payment\Events\PaymentCancel;
use LarabizCMS\Modules\Payment\Events\PaymentFail;
use LarabizCMS\Modules\Payment\Events\PaymentSuccess;
use LarabizCMS\Modules\Payment\Exceptions\PaymentException;
use LarabizCMS\Modules\Payment\Models\PaymentHistory;
use Omnipay\Omnipay;

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

    public function create(Request $request, string $module, string $driver): PaymentResult
    {
        $user = $request->user();
        $handler = $this->getModule($module);
        $params = $handler->options($driver, $request);

        $paymentHistory = PaymentHistory::create(
            [
                'payment_method' => $driver,
                'status' => 'processing',
                'module' => $module,
                'payer_type' => get_class($user),
                'payer_id' => $user->id,
                'amount' => $params['amount'],
                'data' => $params,
            ]
        );

        $gateway = Omnipay::create($driver);

        $gateway->initialize(config("payment.methods.{$driver}"));

        $response = $gateway->purchase($params)->send();

        if ($response->isSuccessful() && ! $response->isRedirect()) {
            $paymentHistory->update(
                [
                    'status' => PaymentHistory::STATUS_SUCCESS,
                    'payment_id' => $response->getTransactionReference(),
                ]
            );

            $result = PaymentResult::make($request, $paymentHistory)
                ->setStatus(PaymentHistory::STATUS_SUCCESS)
                ->fill(compact('response'));

            $handler->success($result);

            event(new PaymentSuccess($result));

            return $result;
        }

        $result = PaymentResult::make($request, $paymentHistory)
            ->fill(compact('response'));

        if ($response->isRedirect()) {
            $paymentHistory->update(
                [
                    'payment_id' => $response->getTransactionReference(),
                ]
            );

            return $result->setIsRedirect(true)
                ->setRedirectUrl($response->getRedirectUrl());
        }

        $result = PaymentResult::make($request, $paymentHistory)
            ->setStatus(PaymentHistory::STATUS_FAIL)
            ->fill(compact('response'));

        event(new PaymentFail($result));

        $handler->fail($result);

        report($response->getMessage());

        return $result;
    }

    public function complete(Request $request, string $transactionId): PaymentResult
    {
        $paymentHistory = PaymentHistory::find($transactionId);

        if (! $paymentHistory) {
            throw PaymentException::transactionNotFound($transactionId);
        }

        $driver = $paymentHistory->payment_method;
        $module = $paymentHistory->module;

        $gateway = Omnipay::create($driver);

        $gateway->initialize(config("payment.methods.{$driver}"));

        $handler = $this->getModule($module);

        $response = $gateway->completePurchase($request->all())->send();

        if ($response->isSuccessful()) {
            $result = PaymentResult::make($request, $paymentHistory)
                ->setStatus(PaymentHistory::STATUS_SUCCESS)
                ->fill(compact('response'));

            $handler->success($result);

            return $result;
        }

        $result = PaymentResult::make($request, $paymentHistory)
            ->setStatus(PaymentHistory::STATUS_FAIL)
            ->fill(compact('response'));

        $handler->fail($result);

        event(new PaymentFail($result));

        report($response->getMessage());

        return $result;
    }

    public function cancel(Request $request, string $transactionId): PaymentResult
    {
        $paymentHistory = PaymentHistory::find($transactionId);

        $paymentHistory->update(
            [
                'status' => PaymentHistory::STATUS_CANCEL,
            ]
        );

        $handler = $this->getModule($paymentHistory->module);

        $result = PaymentResult::make($request, $paymentHistory)->setStatus(PaymentHistory::STATUS_CANCEL);

        event(new PaymentCancel($result));

        $handler->cancel($result);

        return $result;
    }
}
