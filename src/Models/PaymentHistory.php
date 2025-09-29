<?php

namespace LarabizCMS\Modules\Payment\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Http\Request;
use LarabizCMS\Core\Models\Model;
use LarabizCMS\Core\Traits\HasAPI;
use LarabizCMS\Core\Traits\HasCodeWithMonth;
use LarabizCMS\Modules\Payment\Exceptions\PaymentException;
use LarabizCMS\Modules\Payment\Facades\Payment;
use LarabizCMS\Modules\Payment\Http\Resporces\PaymentHistoryResporce;
use LarabizCMS\Modules\Payment\Models\Enums\PaymentHistoryStatus;
use LarabizCMS\Modules\Payment\PaymentResult;

class PaymentHistory extends Model
{
    use HasUuids, HasAPI, HasCodeWithMonth;

    public const STATUS_PROCESSING = 'processing';
    public const STATUS_SUCCESS = 'success';
    public const STATUS_FAIL = 'fail';
    public const STATUS_CANCEL = 'cancel';

    public static bool $randomSubfix = true;

    protected $table = 'payment_histories';

    protected $fillable = [
        'code',
        'payment_method',
        'status',
        'data',
        'module',
        'amount',
        'payer_type',
        'payer_id',
        'payment_id',
        'paymentable_type',
        'paymentable_id',
        'fail_message',
    ];

    protected $casts = [
        'data' => 'array',
        'amount' => 'float',
        'status' => PaymentHistoryStatus::class,
    ];

    public $sortable = [
        'code',
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

    public function complete(Request $request): PaymentResult
    {
        throw_if(
            $this->status !== PaymentHistoryStatus::PROCESSING,
            new PaymentException(__('Transaction has been processed!'))
        );

        return Payment::complete($request, $this);
    }
}
