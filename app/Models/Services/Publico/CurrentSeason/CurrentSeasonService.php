<?php

namespace App\Models\Services\Publico\CurrentSeason;

use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use App\Models\Repositories\Publico\CurrentSeason\CurrentSeasonInterface;

Class CurrentSeasonService
{
    protected $curseason_repo;

    public function __construct(CurrentSeasonInterface $curseason)
    {
        $this->curseason_repo = $curseason;
    }

    public function insert(array $data)
    {
        $response['status'] = 0;
        $response['message'] = '';

        try {
            $id = $this->curseason_repo->register($data);

            if (!empty($id) && $id != 0)
            {
                $response['message'] = 'Datos ingresados satisfactoriamente';
                $response['status'] = 1;
            }

        } catch (QueryException $e) {
            $response['message'] = '¡ERROR! contact with support.';
            Log::critical('Register new area',
                ['request' => [], 'exception' => $e->getMessage()]);
        } catch (Exception $e) {
            $response['message'] = '¡ERROR! contact with support';
            Log::alert('Register new area',
                ['request' => [], 'exception' => $e->getMessage()]);
        }

        return $response;
    }

    public function update(array $data, int $id)
    {
        $response['status'] = 0;
        $response['message'] = '';

        try {
            $rows = $this->curseason_repo->update($data, $id);

            if ($rows > 0)
            {
                $response['message'] = 'Datos actualizados correctamente';
                $response['status'] = 1;
            }

        } catch (QueryException $e) {
            $response['message'] = '¡ERROR! contact with support.';
            Log::critical('Update Area',
                ['request' => [], 'exception' => $e->getMessage()]);
        } catch (Exception $e) {
            $response['message'] = '¡ERROR! contact with support';
            Log::alert('Update Area',
                ['request' => [], 'exception' => $e->getMessage()]);
        }

        return $response;
    }

    public function getRow(int $id)
    {
        $response['status'] = 0;
        $response['message'] = '';
        $response['records'] = '';

        try {
            $row = $this->curseason_repo->getRow($id);

            if (!empty($row))
            {
                $response['message'] = 'Datos obtenidos satisfactoriamente';
                $response['status'] = 1;
                $response['records'] = $row;
            }

        } catch (QueryException $e) {
            $response['message'] = '¡ERROR! contact with support.';
            Log::critical('Obtain Area',
                ['request' => ['id'=> $id], 'exception' => $e->getMessage()]);
        } catch (Exception $e) {
            $response['message'] = '¡ERROR! contact with support';
            Log::alert('Obtain Area',
                ['request' => ['id'=> $id], 'exception' => $e->getMessage()]);
        }

        return $response;
    }
}
