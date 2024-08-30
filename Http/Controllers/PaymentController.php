<?php
/**
 * LARABIZ CMS - Full SPA Laravel CMS
 *
 * @package    larabizcms/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

namespace LarabizCMS\Modules\Payment\Http\Controllers;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use LarabizCMS\Core\Http\Controllers\APIController;
use LarabizCMS\Modules\Payment\Events\PaymentFail;
use LarabizCMS\Modules\Payment\Events\PaymentSuccess;
use LarabizCMS\Modules\Payment\Exceptions\PaymentException;
use LarabizCMS\Modules\Payment\Facades\Payment;
use LarabizCMS\Modules\Payment\Models\PaymentHistory;
use LarabizCMS\Modules\Payment\PaymentResult;
use Omnipay\Omnipay;

class PaymentController extends APIController
{
    public function purchase(Request $request, string $module, string $driver): JsonResponse
    {
        $gateway = Omnipay::create($driver);

        $gateway->initialize(config("payment.methods.{$driver}"));

        $user = $request->user();

        try {
            $handler = Payment::getModule($module);

            $params = $handler->options($driver, $request);

            if (isset($params['returnUrl'])) {
                $params['returnUrl'] = route('api.payment.complete', ['module' => $module, 'driver' => $driver]);
            }

            if (isset($params['cancelUrl'])) {
                $params['cancelUrl'] = route('api.payment.cancel', ['module' => $module, 'driver' => $driver]);
            }

            $paymentHistory = PaymentHistory::create(
                [
                    'payment_method' => $driver,
                    'status' => 'processing',
                    'module_id' => $module,
                    'module_type' => 'payment',
                    'payer_type' => get_class($user),
                    'payer_id' => $user->id,
                    'amount' => $params['amount'],
                ]
            );

            $response = $gateway->purchase($params)->send();
        } catch (PaymentException $e) {
            return $this->restFail($e->getMessage());
        } catch (Exception $e) {
            report($e);
            return $this->restFail(__('Sorry, there was an error processing your payment. Please try again later.'));
        }

        if ($response->isSuccessful()) {
            $paymentHistory->update(
                [
                    'status' => PaymentHistory::STATUS_SUCCESS,
                    'payment_id' => $response->getTransactionReference(),
                ]
            );

            $result = PaymentResult::make($request, $module, $driver)
                ->setStatus(PaymentHistory::STATUS_SUCCESS)
                ->fill(
                    compact('paymentHistory', 'response')
                );

            $handler->success($result);

            event(new PaymentSuccess($result));

            return $this->restSuccess([], __('Payment successful!'));
        }

        if ($response->isRedirect()) {
            return $this->restSuccess(
                [
                    'type' => 'redirect',
                    'redirectUrl' => $response->getRedirectUrl(),
                ]
            );
        }

        $result = PaymentResult::make($request, $module, $driver)
            ->setStatus(PaymentHistory::STATUS_FAIL)
            ->fill(
                compact('paymentHistory', 'response')
            );

        event(new PaymentFail($result));

        $handler->fail($result);

        return $this->restFail($response->getMessage());
    }

    public function complete(Request $request, string $module, string $driver): JsonResponse
    {
        $gateway = Omnipay::create($driver);

        $gateway->initialize(config("payment.methods.{$driver}"));

        try {
            $handler = Payment::getModule($module);

            $response = $gateway->completePurchase($request->all())->send();
        } catch (PaymentException $e) {
            return $this->restFail($e->getMessage());
        } catch (Exception $e) {
            report($e);
            return $this->restFail(__('Sorry, there was an error processing your payment. Please try again later.'));
        }

        if ($response->isSuccessful()) {
            $result = PaymentResult::make($request, $module, $driver)
                ->setStatus(PaymentHistory::STATUS_SUCCESS)
                ->fill(compact('response'));

            $handler->success($result);

            return $this->restSuccess([], __('Payment successful!'));
        }

        $result = PaymentResult::make($request, $module, $driver)
            ->setStatus(PaymentHistory::STATUS_FAIL)
            ->fill(compact('response'));

        $handler->fail($result);

        event(new PaymentFail($result));

        return $this->restFail($response->getMessage());
    }

    public function cancel(Request $request, string $module, string $driver): JsonResponse
    {
        try {
            $handler = Payment::getModule($module);
        } catch (PaymentException $e) {
            return $this->restFail($e->getMessage());
        }

        $result = PaymentResult::make($request, $module, $driver)
            ->setStatus(PaymentHistory::STATUS_CANCEL);

        $handler->cancel($result);

        return $this->restSuccess([], __('Payment canceled!'));
    }
}
