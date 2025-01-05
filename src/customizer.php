<?php
/**
 * LARABIZ CMS - Full SPA Laravel CMS
 *
 * @package    larabizcms/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

use LarabizCMS\Modules\Payment\Http\Controllers\Admin\TopupController;

larabiz()->profilePage('balance', [TopupController::class, 'topup']);
