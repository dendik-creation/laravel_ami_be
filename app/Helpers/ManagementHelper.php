<?php

namespace App\Helpers;

class ManagementHelper {
    public static function onlyHeaderNotNull($data){
        $result = array_filter($data, function($item){
            return !is_null($item);
        });
        return array_values($result);
    }
}
