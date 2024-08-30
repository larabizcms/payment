<?php

namespace LarabizCMS\Modules\Payment\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use LarabizCMS\Core\Models\Model;

class PaymentHistory extends Model
{
    protected $table = 'payment_histories';

    protected $fillable = [
        'payment_method',
        'status',
        'data',
        'module_id',
        'module_type',
        'payer_type',
        'payer_id',
        'payment_id',
        'amount',
    ];

    protected $casts = ['data' => 'array', 'amount' => 'float'];

    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method', 'type');
    }

    public function payer()
    {
        return $this->morphTo(__FUNCTION__, 'payer_type', 'payer_id');
    }
}
