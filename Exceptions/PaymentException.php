<?php
/**
 * LARABIZ CMS - Full SPA Laravel CMS
 *
 * @package    larabizcms/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

namespace LarabizCMS\Modules\Payment\Exceptions;

class PaymentException extends \Exception
{
    public static function moduleNotFound(string $module): static
    {
        return new static(__('Module :module not found', ['module' => $module]));
    }
}
