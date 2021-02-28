<?php

namespace App\Enums;

use Spatie\Enum\Enum;

/**
 *
 * @method static self SUPERADMIN()
 * @method static self LABORATORIUM()
 * @method static self DINKES()
 * @method static self PERUJUK()
 */

class RoleEnum extends Enum
{
    public static function SUPERADMIN(): RoleEnum
    {
        return new class () extends RoleEnum
        {
            public function getValue(): string
            {
                return 'superadmin';
            }
            public function getIndex(): int
            {
                return 1;
            }
        };
    }

    public static function LABORATORIUM(): RoleEnum
    {
        return new class () extends RoleEnum
        {
            public function getValue(): string
            {
                return 'laboratorium';
            }
            public function getIndex(): int
            {
                return 8;
            }
        };
    }

    public static function DINKES(): RoleEnum
    {
        return new class () extends RoleEnum
        {
            public function getValue(): string
            {
                return 'dinkes';
            }
            public function getIndex(): int
            {
                return 2;
            }
        };
    }

    public static function PERUJUK(): RoleEnum
    {
        return new class () extends RoleEnum
        {
            public function getValue(): string
            {
                return 'perujuk';
            }
            public function getIndex(): int
            {
                return 9;
            }
        };
    }
}
