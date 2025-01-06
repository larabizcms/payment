<?php
/**
 * LARABIZ CMS - Full SPA Laravel CMS
 *
 * @package    larabizcms/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

namespace LarabizCMS\Modules\Payment\Http\Controllers\Admin;

use Illuminate\Http\Request;
use LarabizCMS\Core\Http\Controllers\AdminController;
use LarabizCMS\Core\PageBuilder\Page;

class TopupController extends AdminController
{
    public function topup(Request $request, Page $page): Page
    {
        $page->with(['title' => __('Balance'), 'description' => __('Balance')]);

        $page->template('balance')->noPermission();

        return $page;
    }
}
