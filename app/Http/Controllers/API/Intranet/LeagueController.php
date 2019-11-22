<?php

namespace App\Http\Controllers\API\Intranet;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Services\Publico\Competitions\CompetitionsService;

class LeagueController extends Controller
{

    protected $cptService;

    public function __construct(CompetitionsService $cptService)
    {
        $this->cptService = $cptService;
    }

    public function pruebaLista()
    {
        $data = $this->cptService->updateCompetitions();

        dd($data);

    }
}
