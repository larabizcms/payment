<?php
/**
 * LARABIZ CMS - Full SPA Laravel CMS
 *
 * @package    larabizcms/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

namespace LarabizCMS\Modules\Payment\Exceptions;

class TransactionNotFoundException extends PaymentException
{
    public static function make(string $transactionId): static
    {
        return new static(__('Transaction :transactionId not found', ['transactionId' => $transactionId]));
    }
}
