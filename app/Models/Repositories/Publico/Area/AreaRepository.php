<?php

namespace App\Models\Repositories\Publico\Area;

use Illuminate\Support\Facades\DB;

class AreaRepository implements AreaInterface
{
    public function register(array $data) {
        $id = DB::table('area')
            ->insertGetId($data);

        return $id;
    }

    public function update(array $data, int $id) {
        $affected_rows = DB::table('area')
            ->where('id', $id)
            ->update($data);

        return $affected_rows;
    }

    public function getRow(int $id)
    {
        $data = DB::table('area')
            ->where('id', $id)
            ->first();

        return $data;
    }

}
