<?php

namespace App\Models\Repositories\Publico\CurrentSeason;

use Illuminate\Support\Facades\DB;

class CurrentSeasonRepository implements CurrentSeasonInterface
{
    public function register(array $data) {
        $id = DB::table('current_season')
            ->insertGetId($data);

        return $id;
    }

    public function update(array $data, int $id) {
        $affected_rows = DB::table('current_season')
            ->where('id', $id)
            ->update($data);

        return $affected_rows;
    }

    public function getRow(int $id)
    {
        $data = DB::table('current_season')
            ->where('id', $id)
            ->first();

        return $data;
    }

}
