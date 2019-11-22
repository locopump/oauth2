<?php

namespace App\Models\Services\Publico\WinnerSeason;

use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use App\Models\Repositories\Publico\WinnerSeason\WinnerSeasonInterface;

Class WinnerSeasonService
{
    protected $winseason_repo;

    public function __construct(WinnerSeasonInterface $winseason)
    {
        $this->winseason_repo = $winseason;
    }

    public function insert(array $data)
    {
        $response['status'] = 0;
        $response['message'] = '';

        try {
            $id = $this->winseason_repo->register($data);

            if (!empty($id) && $id != 0)
            {
                $response['message'] = 'Datos ingresados satisfactoriamente';
                $response['status'] = 1;
            }

        } catch (QueryException $e) {
            $response['message'] = '¡ERROR! contact with support.';
            Log::critical('Register new Winner Season',
                ['request' => [], 'exception' => $e->getMessage()]);
        } catch (Exception $e) {
            $response['message'] = '¡ERROR! contact with support';
            Log::alert('Register new Winner Season',
                ['request' => [], 'exception' => $e->getMessage()]);
        }

        return $response;
    }

    public function update(array $data, int $id)
    {
        $response['status'] = 0;
        $response['message'] = '';

        try {
            $rows = $this->winseason_repo->update($data, $id);

            if ($rows > 0)
            {
                $response['message'] = 'Datos actualizados correctamente';
                $response['status'] = 1;
            }

        } catch (QueryException $e) {
            $response['message'] = '¡ERROR! contact with support.';
            Log::critical('Update Winner Season',
                ['request' => [], 'exception' => $e->getMessage()]);
        } catch (Exception $e) {
            $response['message'] = '¡ERROR! contact with support';
            Log::alert('Update Winner Season',
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
            $row = $this->winseason_repo->getRow($id);

            if (!empty($row))
            {
                $response['message'] = 'Datos obtenidos satisfactoriamente';
                $response['status'] = 1;
                $response['records'] = $row;
            }

        } catch (QueryException $e) {
            $response['message'] = '¡ERROR! contact with support.';
            Log::critical('Obtain Winner Season',
                ['request' => ['id'=> $id], 'exception' => $e->getMessage()]);
        } catch (Exception $e) {
            $response['message'] = '¡ERROR! contact with support';
            Log::alert('Obtain Winner Season',
                ['request' => ['id'=> $id], 'exception' => $e->getMessage()]);
        }

        return $response;
    }
}
