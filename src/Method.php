<?php
/**
 * LARABIZ CMS - Full SPA Laravel CMS
 *
 * @package    larabizcms/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

namespace LarabizCMS\Modules\Payment;

use Illuminate\Contracts\Support\Arrayable;
use LarabizCMS\Core\Traits\Fillable;

class Method implements Arrayable
{
    use Fillable;

    public ?string $description = null;

    public ?string $icon = null;

    public string $driver;

    public function __construct(public string $name, array $options)
    {
        $this->fill($options);
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
