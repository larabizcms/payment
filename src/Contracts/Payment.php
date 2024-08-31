<?php
/**
 * LARABIZ CMS - Full SPA Laravel CMS
 *
 * @package    larabizcms/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

namespace LarabizCMS\Modules\Payment\Contracts;

interface Payment
{
    /**
     * Register module in payment
     *
     * @param  string  $module
     * @param  string<Module>  $handler
     * @return void
     */
    public function registerModule(string $module, string $handler): void;

    public function getModule(string $module): Module;
}
