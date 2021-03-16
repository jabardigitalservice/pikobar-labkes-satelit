<?php

namespace App\Enums;

use Spatie\Enum\Enum;


class UserStatusEnum extends Enum
{
    public static function INACTIVE(): UserStatusEnum
    {
        return new class () extends UserStatusEnum
        {
            public function getValue(): string
            {
                return 'inactive';
            }
            public function getIndex(): int
            {
                return 1;
            }
        };
    }


    public static function ACTIVE(): UserStatusEnum
    {
        return new class () extends UserStatusEnum
        {
            public function getValue(): string
            {
                return 'active';
            }
            public function getIndex(): int
            {
                return 2;
            }
        };
    }
}
