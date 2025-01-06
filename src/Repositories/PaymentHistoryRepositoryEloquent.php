<?php
/**
 * LARABIZ CMS - Full SPA Laravel CMS
 *
 * @package    larabizcms/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

namespace LarabizCMS\Modules\Payment\Repositories;

use LarabizCMS\Core\Repositories\EloquentRepository;
use LarabizCMS\Core\Repositories\Traits\HasBulkActions;
use LarabizCMS\Modules\Payment\Models\PaymentHistory;

class PaymentHistoryRepositoryEloquent extends EloquentRepository implements PaymentHistoryRepository
{
    use HasBulkActions;

    public function model(): string
    {
        return PaymentHistory::class;
    }
}
