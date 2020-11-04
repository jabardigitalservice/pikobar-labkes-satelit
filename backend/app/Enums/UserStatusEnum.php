<?php

namespace App\Enums;

use Spatie\Enum\Enum;

/**
 * 
 * @method static self INACTIVE();
 * @method static self ACTIVE();
 */
class UserStatusEnum extends Enum
{
    public static function INACTIVE(): UserStatusEnum
    {
        return new class() extends UserStatusEnum {
            public function getValue(): string
            {
                return __('label.inactive');
            }
            public function getIndex(): int
            {
                return 1;
            }
        };
    }
    

    public static function ACTIVE(): UserStatusEnum
    {
        return new class() extends UserStatusEnum {
            public function getValue(): string
            {
                return __('label.active');
            }
            public function getIndex(): int
            {
                return 2;
            }
        };
    }
}
