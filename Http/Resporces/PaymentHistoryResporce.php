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
use LarabizCMS\Modules\Payment\Models\PaymentHistory;

/**
 * @property-read PaymentHistory $resource
 */
class PaymentHistoryResporce extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->resource->id,
            'amount' => $this->resource->amount,
            'status' => $this->resource->status,
            'created_at' => $this->resource->created_at->toDateTimeString(),
            'updated_at' => $this->resource->updated_at->toDateTimeString(),
        ];
    }
}
