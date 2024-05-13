<?php

namespace App\Helpers;

use App\Models\SubDepartemen;

class DepartemenHelper
{
    public static function createSubDeptPost($sub_dept, $dept_id)
    {
        if ($sub_dept) {
            foreach ($sub_dept as $item) {
                $sub = SubDepartemen::create([
                    'nama_sub_departemen' => $item['nama_sub_departemen'],
                    'departemen_id' => $dept_id,
                ]);
            }
        }
    }

    public static function updateOrNewSubDept($sub_dept)
    {
        if ($sub_dept) {
            foreach ($sub_dept as $item) {
                if ($item['status'] == 'then') {
                    $sub = SubDepartemen::findOrFail($item['id']);
                    $sub->update($item);
                } else {
                    $sub = SubDepartemen::create([
                        'nama_sub_departemen' => $item['nama_sub_departemen'],
                        'departemen_id' => $item['departemen_id'],
                    ]);
                }
            }
        }
    }
    public static function removeSubDept($sub_dept)
    {
        if ($sub_dept) {
            foreach ($sub_dept as $item) {
                $sub = SubDepartemen::findOrFail($item);
                $sub->delete();
            }
        }
    }
}
