<?php

namespace App\Models\Services\Publico\Team;

use App\Models\Services\Publico\Area\AreaService;
use Exception;
use Illuminate\Database\QueryException;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use App\Exceptions\CustomException;

use App\Models\Repositories\Publico\Competitions\CompetitionsInterface;

Class TeamService
{
    protected $client;
    protected $service_area;
    protected $team_repo;

    public function __construct(
        Client $client,
        AreaService $service_area,
        CompetitionsInterface $team_repo
    )
    {
        $this->client = $client;
        $this->service_area = $service_area;
        $this->team_repo = $team_repo;
    }


    public function run($uri, $type = 'GET')
    {
        $res = $this->client->request($type, $uri);
        return json_decode( $res->getBody() );
    }

    public function updateTeams()
    {
        $response['status'] = 0;
        $response['message'] = '';
        $response['count'] = 0;
        $response['records'] = [];

        try {
            $pathApi = env('FOOTBALL.API') . '/' . env('FOOTBALL.VERSION') . '/';
            $epTeams = 'teams';

            # Teams
            $pathTeams = $pathApi . $epTeams;
            $teamsRun = $this->run($pathTeams);
            $teams = $teamsRun;
            $filter_team = ['id' => [2000,2001,2002,2003,2013,2014,2015,2016,2017,2018,2019,2021]];
            dd($teams);

            foreach ($teams->teams as $team)
            {
                $team_data = array(
                    'id' => $team->id,
                    'area_id' => $team->area->id,
                    'name' => $team->name,
                    'code' => $team->code,
                    'emblemUrl' => $team->emblemUrl,
                    'plan' => $team->plan,
                    'curseason_id' => $team->currentSeason->id,
                    'numberOfAvailableSeasons' => $team->numberOfAvailableSeasons,
                    'lastUpdated' => $team->lastUpdated,
                );

                # teams
                $existCompetition = $this->team_repo->getRow( (int) $team->id );
//                $existCompArea = $this->service_area->getRow( (int) $team->area->id );
//
//                if ($existCompArea['status'] == 0)
//                {
//                    $dataArea = array(
//                        'id' => $team->area->id,
//                        'name' => $team->area->name
//                    );
//                    $insertCompArea = $this->service_area->insert($dataArea);
//                    if ($insertCompArea['status'] == 0) {
//                        throw new CustomException('No se registro el area de competición ' . $team->area->id);
//                    }
//                }
//
//                if ( empty($existCompetition) || $existCompetition == null || !isset($existCompetition) )
//                {
//                    $insertCompetition = $this->team_repo->register($team_data);
//                    if (empty($insertCompetition)) {
//                        throw new CustomException('No se registro la competencia ' .
//                            $team->id);
//                    }
//                } else {
//                    $updateCompetition = $this->team_repo->update($team_data, $team->id);
//                    if ($updateCompetition <= 0 || empty($updateCompetition) ) {
//                        throw new  CustomException('Ocurrió un error al actualizar la competencia ' .
//                            $team->id . ' - comp_data => ' . json_encode($team_data));
//                    }
//                }
            }

            $response['message'] = 'Datos actualizados';
            $response['status'] = 1;
            $response['count'] = $teams->count;
            $response['records'] = $teams;
        } catch (QueryException $e) {
            $response['message'] = '¡ERROR! contact with support.';
            Log::critical('Update Teams',
                ['request' => [], 'exception' => $e->getMessage()]);
        } catch (Exception $e) {
            $response['message'] = '¡ERROR! contact with support';
            Log::alert('Update Teams',
                ['request' => [], 'exception' => $e->getMessage()]);
        }

        return $response;
    }
}
