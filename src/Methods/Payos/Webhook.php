<?php
/**
 * LARABIZ CMS - Full SPA Laravel CMS
 *
 * @package    larabizcms/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

namespace LarabizCMS\Modules\Payment\Methods\Payos;

use Illuminate\Http\Request;
use LarabizCMS\Modules\Payment\Exceptions\WebhookPaymentException;
use LarabizCMS\Modules\Payment\Models\PaymentHistory;

class Webhook
{
    public function handle(Request $request): array
    {
        $checksumkey = config('payment.methods.Payos.checksumKey');

        if ($this->isValidData($request->input('data'), $request->input('signature'), $checksumkey)) {
            $paymentHistory = PaymentHistory::lockForUpdate()
                ->where(['code' => $request->input('data.orderCode')])
                ->first();

            if ($paymentHistory) {
                $result = $paymentHistory->complete($request);

                return ['success' => $result->isSuccessful(), 'message' => $result->getMessage()];
            }

            throw new WebhookPaymentException('Payment not found');
        }

        throw new WebhookPaymentException('Invalid signature');
    }

    public function isValidData($transaction, $transaction_signature, $checksum_key): bool
    {
        ksort($transaction);
        $transaction_str_arr = [];
        foreach ($transaction as $key => $value) {
            if (is_null($value) || in_array($value, ["undefined", "null"])) {
                $value = "";
            }

            if (is_array($value)) {
                $valueSortedElementObj = array_map(function ($ele) {
                    ksort($ele);
                    return $ele;
                }, $value);
                $value = json_encode($valueSortedElementObj, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE);
            }
            $transaction_str_arr[] = $key . "=" . $value;
        }

        $transaction_str = implode("&", $transaction_str_arr);
        dump($transaction_str);
        $signature = hash_hmac("sha256", $transaction_str, $checksum_key);
        dump($signature);
        return $signature == $transaction_signature;
    }
}
