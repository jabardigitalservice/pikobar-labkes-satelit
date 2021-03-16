<?php

namespace App\Traits;

trait PaginateTrait
{
    public function getValidPerpage($perpage)
    {
        if (!in_array($perpage, [10, 20, 50, 100, 500, 1000])) {
            $perpage = 20;
        }
        return $perpage;
    }

    public function getValidOrderDirection($orderDirection)
    {
        if (!in_array($orderDirection, ['asc', 'desc'])) {
            $orderDirection = 'desc';
        }
        return $orderDirection;
    }
}
