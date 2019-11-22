<?php

namespace App\Models\Repositories\Publico\WinnerSeason;

use Illuminate\Support\Facades\DB;

class WinnerSeasonRepository implements WinnerSeasonInterface
{
    public function register(array $data) {
        $id = DB::table('winner_season')
            ->insertGetId($data);

        return $id;
    }

    public function update(array $data, int $id) {
        $affected_rows = DB::table('winner_season')
            ->where('id', $id)
            ->update($data);

        return $affected_rows;
    }

    public function getRow(int $id)
    {
        $data = DB::table('winner_season')
            ->where('id', $id)
            ->first();

        return $data;
    }

}
