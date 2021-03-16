<?php

return [
    'enum' => 'The :attribute field is not a valid :enum.',
    'enum_index' => 'Isian :attribute harus salah satu dari jenis berikut :other.',
    'enum_name' => 'The :attribute field is not a valid name of :enum.',
    'enum_value' => 'Isian :attribute harus salah satu dari jenis berikut: :other.',

    'enums' => [
        // example content - replace/remove it if needed
        \Spatie\Enum\Enumerable::class => [
            'slugged_name' => 'translated value',
            'slugged_other_name' => 'translated other value',
        ],
    ],
];
