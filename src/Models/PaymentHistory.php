<?php

namespace LarabizCMS\Modules\Payment\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;
use LarabizCMS\Core\Models\Model;
use LarabizCMS\Core\Traits\HasAPI;
use LarabizCMS\Modules\Payment\Http\Resporces\PaymentHistoryResporce;

/**
 * @class PaymentHistory
 *
 * @property string $id
 * @property string $module
 * @property string|null $paymentable_type
 * @property string|null $paymentable_id
 * @property string $payer_type
 * @property string $payer_id
 * @property string|null $payment_id
 * @property string $payment_method
 * @property string $status
 * @property array|null $data
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Model|Eloquent $payer
 * @property-read PaymentMethod|null $paymentMethod
 * @property-read \Illuminate\Database\Eloquent\Model|Eloquent|null $paymentable
 * @method static Builder|PaymentHistory api(array $params = [])
 * @method static Builder|PaymentHistory filter(array $params)
 * @method static Builder|PaymentHistory newModelQuery()
 * @method static Builder|PaymentHistory newQuery()
 * @method static Builder|PaymentHistory query()
 * @method static Builder|PaymentHistory search(string $keyword)
 * @method static Builder|PaymentHistory sort(array $params)
 * @method static Builder|PaymentHistory whereCreatedAt($value)
 * @method static Builder|PaymentHistory whereData($value)
 * @method static Builder|PaymentHistory whereId($value)
 * @method static Builder|PaymentHistory whereModule($value)
 * @method static Builder|PaymentHistory wherePayerId($value)
 * @method static Builder|PaymentHistory wherePayerType($value)
 * @method static Builder|PaymentHistory wherePaymentId($value)
 * @method static Builder|PaymentHistory wherePaymentMethod($value)
 * @method static Builder|PaymentHistory wherePaymentableId($value)
 * @method static Builder|PaymentHistory wherePaymentableType($value)
 * @method static Builder|PaymentHistory whereStatus($value)
 * @method static Builder|PaymentHistory whereUpdatedAt($value)
 * @mixin Eloquent
 */
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
}
