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
    protected bool $rowActionable = false;

    public function __construct(protected string $module)
    {
    }

    public function columns(): array
    {
        return [
			Column::make('code'),
			Column::make('payment_method')->value('payment_method.label'),
			Column::make('amount'),
            Column::make('status')->format(Column::FORMAT_STATUS),
			Column::make('created_at')
                ->format(Column::FORMAT_DATETIME)
		];
    }

    public function getDataUrl(): ?string
    {
        return "payment/{$this->module}/histories";
    }
}
