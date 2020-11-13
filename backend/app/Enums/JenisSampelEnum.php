<?php

namespace App\Enums;

use Spatie\Enum\Enum;

class JenisSampelEnum extends Enum
{
    public static function LAINNYA(): RoleEnum
    {
        return new class() extends RoleEnum {
            public function getValue(): string
            {
                return 'lainnya';
            }
            public function getIndex(): int
            {
                return 999999;
            }
        };
    }
}
