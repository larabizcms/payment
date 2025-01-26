<?php
/**
 * LARABIZ CMS - Full SPA Laravel CMS
 *
 * @package    larabizcms/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

namespace LarabizCMS\Modules\Payment\Http\Resporces;

use Illuminate\Http\Resources\Json\JsonResource;
use LarabizCMS\Modules\Payment\Facades\Payment;
use LarabizCMS\Modules\Payment\Models\PaymentHistory;
use Illuminate\Support\Str;

/**
 * @property-read PaymentHistory $resource
 */
class PaymentHistoryResporce extends JsonResource
{
    public function toArray($request): array
    {
        $methods = Payment::methods();

        return [
            'id' => $this->resource->id,
            'code' => $this->resource->code,
            'payment_method' => [
                'name' => $this->resource->payment_method,
                'label' => $methods[$this->resource->payment_method]->label ?? Str::title($this->resource->payment_method),
            ],
            'amount' => $this->resource->amount,
            'status' => $this->resource->status,
            'created_at' => $this->resource->created_at,
            'updated_at' => $this->resource->updated_at,
        ];
    }
}
