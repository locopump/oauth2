<?php

namespace App\Models\Repositories\Publico\Competitions;

use Illuminate\Support\Facades\DB;

class CompetitionsRepository implements CompetitionsInterface
{
    public function register(array $data) {
        $id = DB::table('competitions')
            ->insertGetId($data);

        return $id;
    }

    public function update(array $data, int $id) {
        $affected_rows = DB::table('competitions')
            ->where('id', $id)
            ->update($data);

        return $affected_rows;
    }

    public function getRow(int $id)
    {
        $data = DB::table('competitions')
            ->where('id', $id)
            ->first();

        return $data;
    }

}
