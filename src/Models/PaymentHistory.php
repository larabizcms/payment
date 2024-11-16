<?php

namespace LarabizCMS\Modules\Payment\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use LarabizCMS\Core\Models\Model;
use LarabizCMS\Core\Traits\HasAPI;
use LarabizCMS\Modules\Payment\Http\Resporces\PaymentHistoryResporce;

class PaymentHistory extends Model
{
    use HasUuids, HasAPI;

    public const STATUS_PROCESSING = 'processing';
    public const STATUS_SUCCESS = 'success';
    public const STATUS_FAIL = 'fail';
    public const STATUS_CANCEL = 'cancel';

    protected $table = 'payment_histories';

    protected $fillable = [
        'payment_method',
        'status',
        'data',
        'module',
        'payer_type',
        'payer_id',
        'payment_id',
        'amount',
    ];

    protected $casts = ['data' => 'array', 'amount' => 'float'];

    public $sortable = [
        'created_at',
        'status',
        'payment_method',
        'amount',
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
}
