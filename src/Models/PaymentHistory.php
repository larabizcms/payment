<?php

namespace LarabizCMS\Modules\Payment\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use LarabizCMS\Core\Models\Model;
use LarabizCMS\Core\Traits\HasAPI;
use LarabizCMS\Core\Traits\HasCodeWithMonth;
use LarabizCMS\Modules\Payment\Http\Resporces\PaymentHistoryResporce;

class PaymentHistory extends Model
{
    use HasUuids, HasAPI, HasCodeWithMonth;

    public const STATUS_PROCESSING = 'processing';
    public const STATUS_SUCCESS = 'success';
    public const STATUS_FAIL = 'fail';
    public const STATUS_CANCEL = 'cancel';

    protected $table = 'payment_histories';

    protected $fillable = [
        'code',
        'payment_method',
        'status',
        'data',
        'module',
        'payer_type',
        'payer_id',
        'payment_id',
        'paymentable_type',
        'paymentable_id',
    ];

    protected $casts = ['data' => 'array', 'amount' => 'float'];

    public $sortable = [
        'created_at',
        'status',
        'payment_method',
    ];

    public $sortDefault = [
        'created_at' => 'desc',
    ];

    public static function getResource(): string
    {
        return PaymentHistoryResporce::class;
    }

    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method', 'type');
    }

    public function payer(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'payer_type', 'payer_id');
    }

    public function paymentable(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'paymentable_type', 'paymentable_id');
    }

    public function getData(?string $key = null, $default = null): null|array|string
    {
        if (is_null($key)) {
            return $this->data;
        }

        return data_get($this->data, $key, $default);
    }
}
