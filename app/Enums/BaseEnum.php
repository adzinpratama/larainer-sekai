<?php

namespace App\Enums;

class BaseEnum
{

    protected static $data;
    protected $alias;

    /**
     * Returns class constant values
     * @return array
     */
    public static function toArray(): array
    {
        $class = new \ReflectionClass(static::class);
        return array_values($class->getConstants());
    }

    /**
     * Returns class constant key
     * @return array
     */
    public static function keyToArray(): array
    {
        $class = new \ReflectionClass(static::class);
        return array_keys($class->getConstants());
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return implode(',', static::toArray());
    }

    public function getConstant()
    {
        $class = new \ReflectionClass(static::class);
        return $class->getConstants();
    }

    public static function ini(mixed $data = NULL)
    {
        return new static(static::$data = $data);
    }

    public function toString(mixed $data = null)
    {
        return array_search($data ?? static::$data, $this->getConstant());
    }

    public function toCode(mixed $data = null)
    {
        return array_search($data ?? static::$data, array_flip($this->getConstant()));
    }

    public function toAlias(mixed $data = null)
    {
        return array_search($this->toString($data), array_flip($this->alias));
    }

    public function toColumn(mixed $var, mixed $data = null)
    {
        return array_search($this->toString($data), array_flip($var));
    }

    /**
     * getOption
     *
     * @param  bool $alias
     * @param  string $varAlias
     * @return array
     */
    public function getOption(bool $alias = false, string $varAlias = 'toAlias'): array
    {
        $option = [];
        $count = 0;
        foreach ($this->getConstant() as $key => $constant) {
            $option[$count]['label'] = $alias ? $this->{$varAlias}($constant) : $key;
            $option[$count]['value'] = $constant;
            $count++;
        };
        return $option;
    }

    /**
     * getAllConstant
     *
     * @return object
     */
    public function getAllConstant(): object
    {
        $data['string'] = $this->toString();
        if ($this->alias) $data['alias'] = $this->toAlias();
        return (object)$data;
    }
}
