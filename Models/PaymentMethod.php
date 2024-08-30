<?php
/**
 * JUZAWEB CMS - The Best CMS for Laravel Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang <dangtheanh16@gmail.com>
 * @link       https://juzaweb.com/cms
 * @license    MIT
 */

namespace LarabizCMS\Modules\Payment\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use LarabizCMS\Core\Models\Model;

/**
 * Juzaweb\Ecommerce\Models\PaymentMethod
 *
 * @property int $id
 * @property string $type
 * @property string $name
 * @property array|null $data
 * @property int $active
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read int|null $order_count
 * @method static Builder|PaymentMethod newModelQuery()
 * @method static Builder|PaymentMethod newQuery()
 * @method static Builder|PaymentMethod query()
 * @method static Builder|PaymentMethod whereActive($value)
 * @method static Builder|PaymentMethod whereCreatedAt($value)
 * @method static Builder|PaymentMethod whereData($value)
 * @method static Builder|PaymentMethod whereFilter($params = [])
 * @method static Builder|PaymentMethod whereId($value)
 * @method static Builder|PaymentMethod whereName($value)
 * @method static Builder|PaymentMethod whereType($value)
 * @method static Builder|PaymentMethod whereUpdatedAt($value)
 * @method static Builder active()
 * @mixin Eloquent
 */
class PaymentMethod extends Model
{
    use ResourceModel;

    public const STATUS_ACTIVE = 1;
    public const STATUS_INACTIVE = 1;

    protected $table = 'payment_methods';
    protected string $fieldName = 'name';

    protected $fillable = [
        'name',
        'type',
        'description',
        'data',
        'active',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    public static function findByType(string $type): PaymentMethod|null
    {
        return self::where('type', '=', $type)->first();
    }

    public function scopeActive(Builder $builder): Builder
    {
        return $builder->where('active', '=', self::STATUS_ACTIVE);
    }
}
