<?php

namespace App\Enums;

class RoleEnum extends BaseEnum
{
    public const DEVELOPER = 1;
    public const OWNER = 2;
    public const ADMIN = 3;
    public const MEMBER = 4;

    /**
     * stringToLower
     *
     * @return string
     */
    public function stringToLower(): string
    {
        return strtolower($this->toString());
    }

    /**
     * getAllConstant
     *
     * @return object
     */
    public function getAllConstant(): object
    {
        $data = parent::getAllConstant();
        $data->lower = $this->stringToLower();
        return $data;
    }
}
