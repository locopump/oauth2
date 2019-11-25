<?php

namespace App\Http\Controllers\API\Intranet;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Services\Publico\Team\TeamService;

class LeagueController extends Controller
{

    protected $teamService;

    public function __construct(TeamService $teamService)
    {
        $this->teamService = $teamService;
    }

    public function pruebaLista()
    {
        $data = $this->teamService->updateteams();

        dd($data);

    }
}
