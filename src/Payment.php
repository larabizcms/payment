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
use LarabizCMS\Modules\Payment\Exceptions\PaymentMethodNotFoundException;
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

    /**
     * Get registered modules
     *
     * @return array
     */
    public function modules(): array
    {
        return $this->modules;
    }

    public function methods(): array
    {
        return collect(config('payment.methods', []))
            ->filter(fn ($method) => ($method['enabled'] ?? true))
            ->map(function ($method, $driver) {
                return [
                    'driver' => $method['driver'] ?? $driver,
                    ...$method,
                ];
            })
            ->toArray();
    }

    /**
     * Get the payment method details for a given driver.
     *
     * @param  string  $driver  The payment driver identifier.
     * @return array|null  The details of the specified payment method.
     */
    public function method(string $method): array|null
    {
        return $this->methods()[$method] ?? null;
    }

    public function create(Request $request, string $module, string $method): PaymentResult
    {
        $user = $request->user();
        $handler = $this->getModule($module);
        $gateway = $this->createGateway($method);

        $params = $handler->purchase($method, $request);

        $paymentHistory = PaymentHistory::create(
            [
                'payment_method' => $method,
                'status' => 'processing',
                'module' => $module,
                'payer_type' => get_class($user),
                'payer_id' => $user->id,
                'amount' => $params['amount'],
                'data' => $params,
            ]
        );

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

        $module = $paymentHistory->module;

        $gateway = $this->createGateway($paymentHistory->payment_method);

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

    protected function createGateway(string $method): \Omnipay\Common\GatewayInterface
    {
        if ($config = $this->method($method)) {
            $gateway = Omnipay::create($config['driver']);
            $gateway->initialize($config);

            return $gateway;
        }

        throw PaymentMethodNotFoundException::make($method);
    }
}
