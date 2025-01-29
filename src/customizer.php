<?php
/**
 * LARABIZ CMS - Full SPA Laravel CMS
 *
 * @package    larabizcms/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

use LarabizCMS\Modules\Payment\Http\Controllers\Admin\TopupController;

larabiz()->adminPage('balance', [TopupController::class, 'topup'])
    ->menuIcon('AccountBalanceWallet')
    ->menuGroup('account')
    ->title('Add funds')
    ->forClient();

larabiz()->adminMenu('payment/balance/histories', 'Topup Histories')
    ->icon('History')
    ->group('account')
    ->forClient();
