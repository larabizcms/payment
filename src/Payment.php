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
use Illuminate\Support\Arr;
use LarabizCMS\Modules\Payment\Contracts\ModuleHandler;
use LarabizCMS\Modules\Payment\Events\PaymentCancel;
use LarabizCMS\Modules\Payment\Events\PaymentFail;
use LarabizCMS\Modules\Payment\Events\PaymentSuccess;
use LarabizCMS\Modules\Payment\Exceptions\PaymentException;
use LarabizCMS\Modules\Payment\Models\PaymentHistory;
use Omnipay\Common\GatewayInterface;
use Omnipay\Omnipay;

class Payment implements Contracts\Payment
{
    protected array $modules = [];

    /**
     * Register module in payment
     *
     * @param  string  $module
     * @param  string<ModuleHandler>  $handler
     * @return void
     */
    public function registerModule(string $module, string $handler): void
    {
        $this->modules[$module] = $handler;
    }

    public function getModule(string $module): ModuleHandler
    {
        if (!isset($this->modules[$module])) {
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

    /**
     * Get all payment methods.
     *
     * @return array<string, Method>
     */
    public function methods(): array
    {
        $allMethods = array_filter(config('payment.methods', []), fn($method) => ($method['enabled'] ?? true));
        $methods = [];

        foreach ($allMethods as $driver => $method) {
            $methods[$driver] = new Method(
                $driver,
                [
                    'driver' => $method['driver'] ?? $driver,
                    'label' => $method['label'] ?? title_from_key($method['name'] ?? $driver),
                    ...$method,
                ]
            );
        }

        return $methods;
    }

    /**
     * Get the payment method details for a given driver.
     *
     * @param  string  $driver  The payment driver identifier.
     * @return Method|null  The details of the specified payment method.
     */
    public function method(string $method): Method|null
    {
        return $this->methods()[$method] ?? null;
    }

    /**
     * Create payment
     *
     * @param  Request  $request
     * @param  string  $module
     * @param  Method  $method
     * @return PaymentResult
     */
    public function create(Request $request, string $module, Method $method): PaymentResult
    {
        $user = $request->user();
        $handler = $this->getModule($module);
        $gateway = $this->createGateway($method);

        $paymentHistory = new PaymentHistory(
            [
                'payment_method' => $method->name,
                'status' => 'processing',
                'module' => $module,
            ]
        );

        $paymentHistory->payer()->associate($user);

        $paymentHistory->save();

        $purchase = $handler->purchase($request, $paymentHistory->id, $method);

        $response = $gateway->purchase($purchase->options)->send();

        if ($response->isSuccessful() && !$response->isRedirect()) {
            $paymentHistory->paymentable()->associate($purchase->paymentable);
            $paymentHistory->fill(
                [
                    'status' => PaymentHistory::STATUS_SUCCESS,
                    'payment_id' => $response->getTransactionReference(),
                ]
            );
            $paymentHistory->save();

            $result = PaymentResult::make($request, $paymentHistory)
                ->setStatus(PaymentHistory::STATUS_SUCCESS)
                ->fill(compact('response'));

            $handler->success($result);

            event(new PaymentSuccess($result));

            return $result;
        }

        $result = PaymentResult::make($request, $paymentHistory)->fill(compact('response'));

        if ($response->isRedirect()) {
            $paymentHistory->paymentable()->associate($purchase->paymentable);
            $paymentHistory->fill(['payment_id' => $response->getTransactionReference()]);
            $paymentHistory->save();

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

    public function complete(Request $request, PaymentHistory $paymentHistory): PaymentResult
    {
        $gateway = $this->createGateway($this->method($paymentHistory->payment_method));

        $handler = $this->getModule($paymentHistory->module);

        $params = $request->all();
        $params['transactionReference'] = $paymentHistory->payment_id;
        unset($params['token']);

        $response = $gateway->completePurchase($params)->send();

        if ($response->isSuccessful()) {
            $result = PaymentResult::make($request, $paymentHistory)
                ->setStatus(PaymentHistory::STATUS_SUCCESS)
                ->fill(compact('response'));

            $paymentHistory->update(
                [
                    'status' => PaymentHistory::STATUS_SUCCESS,
                ]
            );

            $handler->success($result);

            return $result;
        }

        $result = PaymentResult::make($request, $paymentHistory)
            ->setStatus(PaymentHistory::STATUS_FAIL)
            ->fill(compact('response'));

        $paymentHistory->update(
            [
                'status' => PaymentHistory::STATUS_FAIL,
                'data' => array_merge($paymentHistory->data ?? [], ['error' => $response->getMessage()]),
            ]
        );

        $handler->fail($result);

        event(new PaymentFail($result));

        return $result;
    }

    public function cancel(Request $request, PaymentHistory $paymentHistory): PaymentResult
    {
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

    protected function createGateway(Method $method): GatewayInterface
    {
        $gateway = Omnipay::create($method->driver);
        $gateway->initialize($method->getConfigs());
        return $gateway;
    }
}
