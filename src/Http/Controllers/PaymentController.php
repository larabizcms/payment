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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use LarabizCMS\Core\Http\Controllers\APIController;
use LarabizCMS\Modules\Payment\Exceptions\PaymentException;
use LarabizCMS\Modules\Payment\Facades\Payment;
use LarabizCMS\Modules\Payment\Http\Requests\PaymentRequest;
use LarabizCMS\Modules\Payment\PaymentResult;
use OpenApi\Annotations as OA;

class PaymentController extends APIController
{
    /**
     * @OA\Post(
     *      path="/payment/{module}/purchase",
     *      tags={"Payment"},
     *      security={{"bearer": {}}},
     *      summary="Purchase Payment",
     *      operationId="payment.purchase",
     *      @OA\Parameter(
     *           name="module",
     *           in="path",
     *           required=true,
     *           description="Payment module",
     *           @OA\Schema(type="string")
     *      ),
     *      @OA\RequestBody(
     *           required=true,
     *           ref="#/components/requestBodies/PaymentRequest"
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(property="success", type="boolean", example="true"),
     *              @OA\Property(property="message", type="string", example="Roles updated successfully"),
     *              @OA\Property(
     *                  property="data",
     *                  type="object",
     *                  @OA\Property(
     *                      property="type",
     *                      type="string",
     *                      example="complete",
     *                  ),
     *                  @OA\Property(
     *                       property="transaction_id",
     *                       type="string",
     *                       example="91c8f73b-6146-44d9-8a38-839acb945341",
     *                   ),
     *                   @OA\Property(
     *                        property="status",
     *                        type="string",
     *                        example="success",
     *                   ),
     *                   @OA\Property(
     *                         property="module",
     *                         type="string",
     *                    ),
     *              )
     *          )
     *      ),
     * )
     */
    public function purchase(PaymentRequest $request, string $module): JsonResponse
    {
        $method = $request->input('method');

        try {
            $payment = DB::transaction(
                fn () => Payment::create($request, $module, $method)
            );
        } catch (PaymentException $e) {
            return $this->restFail($e->getMessage());
        }

        if ($payment->isSuccessful()) {
            return $this->restSuccess(
                [
                    'type' => 'complete',
                    'transaction_id' => $payment->transactionId,
                    'status' => $payment->status,
                    'module' => $module,
                ],
                __('Payment successful!')
            );
        }

        if ($payment->isRedirect) {
            return $this->restSuccess(
                [
                    'type' => 'redirect',
                    'redirectUrl' => $payment->redirectUrl,
                    'status' => $payment->status,
                    'module' => $module,
                ],
                __('Redirecting...')
            );
        }

        return $this->failResponse($payment);
    }

    /**
     * @OA\Post(
     *      path="/payment/{module}/complete",
     *      tags={"Payment"},
     *      security={{"bearer": {}}},
     *      summary="Complete Payment",
     *      operationId="payment.complete",
     *      @OA\Parameter(
     *           name="module",
     *           in="path",
     *           required=true,
     *           description="Payment module",
     *           @OA\Schema(type="string")
     *      ),
     *      @OA\Parameter(
     *            name="transactionId",
     *            in="path",
     *            required=true,
     *            description="Transaction Id",
     *            @OA\Schema(type="string")
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(property="success", type="boolean", example="true"),
     *              @OA\Property(property="message", type="string", example="Roles updated successfully"),
     *              @OA\Property(
     *                  property="data",
     *                  type="object",
     *                  @OA\Property(
     *                      property="type",
     *                      type="string",
     *                      example="complete",
     *                  ),
     *                  @OA\Property(
     *                       property="transaction_id",
     *                       type="string",
     *                       example="91c8f73b-6146-44d9-8a38-839acb945341",
     *                   ),
     *                   @OA\Property(
     *                        property="status",
     *                        type="string",
     *                        example="success",
     *                   ),
     *                   @OA\Property(
     *                         property="module",
     *                         type="string",
     *                    ),
     *              )
     *          )
     *      ),
     * )
     */
    public function complete(Request $request, string $module, string $transactionId): JsonResponse
    {
        try {
            $payment = DB::transaction(fn () => Payment::complete($request, $transactionId));
        } catch (PaymentException $e) {
            return $this->restFail($e->getMessage());
        }

        if ($payment->isSuccessful()) {
            return $this->restSuccess(
                [
                    'type' => 'complete',
                    'transaction_id' => $transactionId,
                    'status' => $payment->status,
                    'module' => $module,
                ],
                __('Payment successful!')
            );
        }

        return $this->failResponse($payment);
    }

    /**
     * @OA\Post(
     *      path="/payment/{module}/cancel",
     *      tags={"Payment"},
     *      security={{"bearer": {}}},
     *      summary="Cancel Payment",
     *      operationId="payment.cancel",
     *      @OA\Parameter(
     *           name="module",
     *           in="path",
     *           required=true,
     *           description="Payment module",
     *           @OA\Schema(type="string")
     *      ),
     *       @OA\Parameter(
     *             name="transactionId",
     *             in="path",
     *             required=true,
     *             description="Transaction Id",
     *             @OA\Schema(type="string")
     *        ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(property="success", type="boolean", example="true"),
     *              @OA\Property(property="message", type="string", example="Roles updated successfully"),
     *              @OA\Property(
     *                  property="data",
     *                  type="object",
     *                  @OA\Property(
     *                      property="type",
     *                      type="string",
     *                      example="cancel",
     *                  ),
     *                  @OA\Property(
     *                       property="transaction_id",
     *                       type="string",
     *                       example="91c8f73b-6146-44d9-8a38-839acb945341",
     *                   ),
     *                   @OA\Property(
     *                        property="status",
     *                        type="string",
     *                        example="success",
     *                   ),
     *                   @OA\Property(
     *                         property="module",
     *                         type="string",
     *                   ),
     *              )
     *          )
     *      ),
     * )
     */
    public function cancel(Request $request, string $module, string $transactionId): JsonResponse
    {
        try {
            $payment = DB::transaction(fn () => Payment::cancel($request, $transactionId));
        } catch (PaymentException $e) {
            return $this->restFail($e->getMessage());
        }

        return $this->restSuccess(
            [
                'type' => 'cancel',
                'transaction_id' => $transactionId,
                'status' => $payment->status,
                'module' => $module,
            ],
            __('Payment canceled!')
        );
    }

    public function methods(): JsonResponse
    {
        $payments = collect(Payment::methods())
            ->map(function ($payment, $driver) {
                return [
                    ...$payment,
                    'code' => $driver,
                    'name' => $payment['name'] ?? title_from_key($driver),
                    'icon' => Str::snake($payment['icon'] ?? 'card'),
                    'description' => $payment['description'] ?? null,
                ];
            })
            ->values();

        return $this->restSuccess($payments, 'Payment methods retrieved successfully');
    }

    protected function failResponse(PaymentResult $result): JsonResponse
    {
        return $this->restFail(
            __('Sorry, there was an error processing your payment. Please try again later.')
        );
    }
}
