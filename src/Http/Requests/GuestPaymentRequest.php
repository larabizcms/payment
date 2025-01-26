<?php
/**
 * LARABIZ CMS - Full SPA Laravel CMS
 *
 * @package    larabizcms/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

namespace LarabizCMS\Modules\Payment\Http\Requests;

class GuestPaymentRequest extends PaymentRequest
{
    public function rules(): array
    {
        $rules = parent::rules();

        if (! $this->user()) {
            $rules['email'] = [
                'required',
                'string',
                'email:rfc',
                'max:255',
            ];
            $rules['name'] = [
                'required',
                'string',
                'max:255',
            ];
        }

        return $rules;
    }
}
