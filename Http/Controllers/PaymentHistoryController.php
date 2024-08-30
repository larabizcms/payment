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
use LarabizCMS\Core\Http\Controllers\APIController;
use LarabizCMS\Modules\Payment\Facades\Payment;
use LarabizCMS\Modules\Payment\Models\PaymentHistory;
use OpenApi\Annotations as OA;

class PaymentHistoryController extends APIController
{
    public function __construct()
    {
        $this->middleware(['permission:payment-history.index', 'scopes:payment-history.all,payment-history.read'])
            ->only(['index']);
    }

    /**
     * @OA\Get(
     *      path="/payment/{module}/histories",
     *      tags={"Payment"},
     *      security={{"bearer": {}}},
     *      summary="Get all payment histories by module",
     *      operationId="payment.histories.index",
     *      @OA\Parameter(
     *            name="module",
     *            in="path",
     *            required=true,
     *            description="Payment module",
     *            @OA\Schema(type="string")
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="Get Data Success",
     *          @OA\JsonContent(
     *              @OA\Property(property="success", type="boolean", example="true"),
     *              @OA\Property(property="message", type="string", example="Permissions retrieved successfully"),
     *              @OA\Property(
     *                  property="data",
     *                  type="object",
     *              )
     *          )
     *      ),
     *     @OA\Response(
     *         response=401,
     *         ref="#/components/responses/error_401"
     *     ),
     *      @OA\Response(
     *          response=403,
     *          ref="#/components/responses/error_403"
     *      )
     * )
     */
    public function index(Request $request, string $module): JsonResponse
    {
        Payment::getModule($module);

        $results = PaymentHistory::api($request->all())
            ->where('module', $module)
            ->paginate(
                $this->getQueryLimit($request)
            );

        return $this->restSuccess($results, __('Get payment history successfully.'));
    }
}
