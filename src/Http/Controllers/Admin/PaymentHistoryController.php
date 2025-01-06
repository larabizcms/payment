<?php
/**
 * LARABIZ CMS - Full SPA Laravel CMS
 *
 * @package    larabizcom/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

namespace LarabizCMS\Modules\Payment\Http\Controllers\Admin;

use LarabizCMS\Core\Facades\Breadcrumb;
use LarabizCMS\Core\Http\Controllers\AdminController;
use LarabizCMS\Core\PageBuilder\Page;
use LarabizCMS\Modules\Payment\Repositories\PaymentHistoryRepository;
use LarabizCMS\Modules\Payment\Http\DataTables\PaymentHistoryDatatable;

class PaymentHistoryController extends AdminController
{
    public function __construct(
        protected PaymentHistoryRepository $paymentHistoryRepository
    ) {
        //
    }

    public function index(string $module): Page
    {
        Breadcrumb::add(__('Payment Histories'));

        $page = Page::make()
            ->template('crud-index')
            ->params(['canCreate' => false])
            ->noPermission();

        $page->fill(['title' => __('Payment Histories'), 'description' => __('Payment Histories')]);

        $page->add(PaymentHistoryDatatable::make($module));

        return $page;
    }
}
