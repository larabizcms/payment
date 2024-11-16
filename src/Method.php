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
use Illuminate\Support\Arr;
use LarabizCMS\Core\Traits\Fillable;

class Method implements Arrayable, \Stringable
{
    use Fillable;

    /**
     * @var string|null Label of method
     */
    protected ?string $label = null;

    /**
     * @var string|null Description of method
     */
    public ?string $description = null;

    /**
     * @var string|null Icon of method
     */
    public ?string $icon = null;

    /**
     * @var string Driver of method
     */
    public string $driver;

    /**
     * @var array Configs of method
     */
    protected array $configs = [];

    /**
     * Method constructor.
     *
     * @param string $name
     * @param array $configs
     */
    public function __construct(public string $name, array $configs)
    {
        $this->configs = Arr::except($configs, array_keys(get_object_vars($this)));

        $this->fill($configs);
    }

    /**
     * Get configs of method
     *
     * @return array
     */
    public function getConfigs(): array
    {
        return $this->configs;
    }

    /**
     * Get config of method
     *
     * @param  string $key
     * @param  $default
     * @return string|array
     */
    public function config(string $key, $default = null): string|array
    {
        return $this->configs[$key] ?? $default;
    }

    /**
     * Convert object to array
     *
     * @return array
     */
    public function toArray(): array
    {
        return Arr::except(get_object_vars($this), 'configs');
    }

    public function __toString(): string
    {
        return $this->name;
    }
}
