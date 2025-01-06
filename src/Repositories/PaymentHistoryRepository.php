<?php
/**
 * LARABIZ CMS - Full SPA Laravel CMS
 *
 * @package    larabizcms/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

namespace LarabizCMS\Modules\Payment\Repositories;

use LarabizCMS\Core\Repositories\Repository;
use LarabizCMS\Modules\Payment\Models\PaymentHistory;

/**
 * @mixin PaymentHistory
 */
interface PaymentHistoryRepository extends Repository
{
    /**
     * Get model class name.
     *
     * @return string
     */
    public function model(): string;
}
