<?php

namespace App\Models\Repositories\Publico\Competitions;

interface CompetitionsInterface
{
    /**
     * @param array $data
     * @return mixed
     */
    public function register(array $data);

    /**
     * @param array $data
     * @param int $id
     * @return mixed
     */
    public function update(array $data, int $id);

    /**
     * @param int $id
     * @return mixed
     */
    public function getRow(int $id);

    /**
     * @return mixed
     */
    public function getAll();
}
