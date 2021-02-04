<?php

namespace App\Enums;

use Spatie\Enum\Enum;

class JenisSampelEnum extends Enum
{
    public static function LAINNYA(): JenisSampelEnum
    {
        return new class () extends JenisSampelEnum {
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
