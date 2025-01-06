<?php
/**
 * LARABIZ CMS - Full SPA Laravel CMS
 *
 * @package    larabizcom/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

namespace LarabizCMS\Modules\Payment\Http\DataTables;

use LarabizCMS\Core\DataTables\Abstracts\DataTable;
use LarabizCMS\Core\DataTables\Components\Column;

class PaymentHistoryDatatable extends DataTable
{
    public function __construct(protected string $module)
    {
    }

    public function columns(): array
    {
        return [
			Column::make('code'),
			Column::make('payment_method'),
			Column::make('payment_id'),
			Column::make('paymentable_type'),
			Column::make('paymentable_id'),
            Column::make('status')->format(Column::FORMAT_STATUS),
			Column::make('created_at')
                ->disabledFlex()
                ->width(200)
                ->format(Column::FORMAT_DATETIME)
		];
    }

    public function getDataUrl(): ?string
    {
        return "payment/{$this->module}/histories";
    }
}
