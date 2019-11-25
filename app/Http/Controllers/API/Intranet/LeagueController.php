<?php

namespace App\Http\Controllers\API\Intranet;

use App\Http\Controllers\Controller;
use App\Models\Services\Publico\Competitions\CompetitionsService;
use Illuminate\Http\Request;

use App\Models\Services\Publico\Team\TeamService;

class LeagueController extends Controller
{

    protected $teamService;
    protected $compService;

    public function __construct(
        TeamService $teamService,
        CompetitionsService $compService
    )
    {
        $this->teamService = $teamService;
        $this->compService = $compService;
    }

    public function pruebaLista()
    {
//        $data = $this->teamService->updateteams();
        $data = $this->compService->updateCompetitions();

        dd($data);

    }

    public function getCompetitions(Request $request)
    {
        $getCompetitions = (
            $request->route('id')?
                $this->compService->getCompetition($request->route('id')) :
                $this->compService->getAllCompetitions()
        );

        return $getCompetitions;
    }
}
