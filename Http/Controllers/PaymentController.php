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
use LarabizCMS\Modules\Payment\Events\PaymentSuccess;
use LarabizCMS\Modules\Payment\Exceptions\PaymentException;
use LarabizCMS\Modules\Payment\Facades\Payment;
use Omnipay\Omnipay;

class PaymentController extends APIController
{
    public function purchase(Request $request, string $module, string $driver): JsonResponse
    {
        $gateway = Omnipay::create($driver);

        $gateway->initialize(config("payment.methods.{$driver}"));

        try {
            $handler = Payment::getModule($module);

            $response = $gateway->purchase($handler->options($driver, $request->all()))->send();
        } catch (PaymentException $e) {
            return $this->restFail($e->getMessage());
        } catch (Exception $e) {
            report($e);
            return $this->restFail(__('Sorry, there was an error processing your payment. Please try again later.'));
        }

        if ($response->isSuccessful()) {
            event(new PaymentSuccess($module, $driver));

            $handler->success($response->getData());

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

        $handler->fail($response->getData());

        return $this->restFail($response->getMessage());
    }
}
