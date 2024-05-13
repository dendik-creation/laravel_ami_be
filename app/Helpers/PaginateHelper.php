<?php

namespace App\Helpers;

class PaginateHelper
{
    public static function metaPaginateInfo($array_data)
    {
        $meta = [
            'current_page' => $array_data->currentPage(),
            'per_page' => $array_data->perPage(),
            'last_page' => $array_data->lastPage(),
            'total' => $array_data->total(),
        ];
        return $meta;
    }
}
