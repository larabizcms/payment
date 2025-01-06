<?php
/**
 * LARABIZ CMS - Full SPA Laravel CMS
 *
 * @package    larabizcms/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

use LarabizCMS\Modules\Payment\Http\Controllers\Admin\TopupController;

larabiz()->adminMenuGroup('payment', 'Payment')->position(90);

larabiz()->profilePage('balance', [TopupController::class, 'topup'])
    ->menuIcon('AccountBalanceWallet');

larabiz()->adminMenu('payment/balance/histories', 'Payment Histories')
    ->icon('History')
    ->group('payment')
    ->noPermission();
