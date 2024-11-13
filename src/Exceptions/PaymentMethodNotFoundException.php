<?php
/**
 * LARABIZ CMS - Full SPA Laravel CMS
 *
 * @package    larabizcms/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

namespace LarabizCMS\Modules\Payment\Exceptions;

class PaymentMethodNotFoundException extends PaymentException
{
    public static function make(string $method): static
    {
        return new static(__('Payment method :method not found', ['method' => $method]));
    }
}
