<?php

namespace LarabizCMS\Modules\Payment\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\RequestBody(
 *      request="PaymentRequest",
 *      required=true,
 *      @OA\MediaType(
 *          mediaType="multipart/form-data",
 *          @OA\Schema(
 *              required={"method"},
 *              @OA\Property(property="method", type="string"),
 *          )
 *      )
 * )
 */
class PaymentRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'method' => ['required', 'string'],
        ];
    }
}
