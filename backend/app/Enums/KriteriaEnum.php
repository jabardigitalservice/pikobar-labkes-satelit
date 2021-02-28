<?php

namespace App\Enums;

use Spatie\Enum\Enum;

/**
 *
 * @method static self kontak_erat()
 * @method static self suspek()
 * @method static self probable()
 * @method static self konfirmasi()
 * @method static self tanpa_kriteria()
 */

class KriteriaEnum extends Enum
{
    public static function kontak_erat(): KriteriaEnum
    {
        return new class () extends KriteriaEnum
        {
            public function getValue(): string
            {
                return 'kontak erat';
            }
            public function getIndex(): int
            {
                return 1;
            }
        };
    }

    public static function suspek(): KriteriaEnum
    {
        return new class () extends KriteriaEnum
        {
            public function getValue(): string
            {
                return 'suspek';
            }
            public function getIndex(): int
            {
                return 2;
            }
        };
    }

    public static function probable(): KriteriaEnum
    {
        return new class () extends KriteriaEnum
        {
            public function getValue(): string
            {
                return 'probable';
            }
            public function getIndex(): int
            {
                return 3;
            }
        };
    }

    public static function konfirmasi(): KriteriaEnum
    {
        return new class () extends KriteriaEnum
        {
            public function getValue(): string
            {
                return 'konfirmasi';
            }
            public function getIndex(): int
            {
                return 4;
            }
        };
    }

    public static function tanpa_kriteria(): KriteriaEnum
    {
        return new class () extends KriteriaEnum
        {
            public function getValue(): string
            {
                return 'tanpa kriteria';
            }
            public function getIndex(): int
            {
                return 5;
            }
        };
    }
}
