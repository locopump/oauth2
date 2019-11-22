<?php

namespace App\Models\Services\Publico\Competitions;

use Exception;
use Illuminate\Database\QueryException;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use App\Exceptions\CustomException;

use App\Models\Repositories\Publico\Competitions\CompetitionsInterface;
use App\Models\Services\Publico\Area\AreaService;
use App\Models\Services\Publico\CurrentSeason\CurrentSeasonService;
use App\Models\Services\Publico\WinnerSeason\WinnerSeasonService;

Class CompetitionsService
{
    protected $client;
    protected $comp_repo;
    protected $service_area;
    protected $service_season;
    protected $service_winseason;

    public function __construct(
        Client $client,
        AreaService $service_area,
        CurrentSeasonService $service_season,
        WinnerSeasonService $service_winseason,
        CompetitionsInterface $comp_repo
    )
    {
        $this->client = $client;
        $this->service_area = $service_area;
        $this->service_season = $service_season;
        $this->service_winseason = $service_winseason;
        $this->comp_repo = $comp_repo;
    }


    public function run($uri, $type = 'GET')
    {
        $res = $this->client->request($type, $uri);
        return json_decode( $res->getBody() );
    }
    public function updateCompetitions()
    {
        $response['status'] = 0;
        $response['message'] = '';
        $response['count'] = 0;
        $response['records'] = [];
        $code = 400;

        try {
            $pathApi = env('FOOTBALL.API') . '/' . env('FOOTBALL.VERSION') . '/';
            $epCompetitions = 'competitions';
            $epAreas = 'areas';

            # Areas
            $pathAreas = $pathApi . $epAreas;
            $areas = $this->run($pathAreas);

            foreach ($areas->areas as $area)
            {
                $data = array(
                    'id' => $area->id,
                    'name' => $area->name,
                    'countryCode' => $area->countryCode,
                    'ensignUrl' => $area->ensignUrl,
                    'parentAreaId' => $area->parentAreaId,
                    'parentArea' => $area->parentArea,
                );
                $existArea = $this->service_area->getRow( (int) $area->id );

                if ($existArea['status'] == 0)
                {
                    $insertArea = $this->service_area->insert($data);
                    if ($insertArea['status'] == 0) {
                        throw new CustomException('No se registro el area ' . $area->id);
                    }
                } else {
                    $updateArea = $this->service_area->update($data, $area->id);
                    if ($updateArea['status'] == 0) {
                        throw new  CustomException('Ocurrió un error al actualizar el area ' . $area->id);
                    }
                }
            }

            # Competitions, Current Season and Winner Season
            $pathCompetitions = $pathApi . $epCompetitions;
            $competitions = $this->run($pathCompetitions);
            $filter_comp = ['id' => [2000,2001,2002,2003,2013,2014,2015,2016,2017,2018,2019,2021]];

            foreach ($competitions->competitions as $comp)
            {
                $comp_data = array(
                    'id' => $comp->id,
                    'area_id' => $comp->area->id,
                    'name' => $comp->name,
                    'code' => $comp->code,
                    'emblemUrl' => $comp->emblemUrl,
                    'plan' => $comp->plan,
                    'curseason_id' => $comp->currentSeason->id,
                    'numberOfAvailableSeasons' => $comp->numberOfAvailableSeasons,
                    'lastUpdated' => $comp->lastUpdated,
                );

                # Current Season
                if ( !empty($comp->currentSeason) )
                {
                    $cur_data = array(
                        'id' => $comp->currentSeason->id ,
                        'startDate' => $comp->currentSeason->startDate ,
                        'endDate' => $comp->currentSeason->endDate ,
                        'currentMatchday' => $comp->currentSeason->currentMatchday ,
                    );

                    $existSeason = $this->service_season->getRow( (int) $comp->currentSeason->id );

                    if ($existSeason['status'] == 0)
                    {
                        $insertSeason = $this->service_season->insert($cur_data);
                        if ($insertSeason['status'] == 0) {
                            throw new CustomException('No se registro la temporada ' .
                                $comp->currentSeason->currentSeason->id);
                        }
                    } else {
                        $updateSeason = $this->service_season->update($cur_data, $comp->currentSeason->id);
                        if ($updateSeason['status'] == 0) {
                            throw new  CustomException('Ocurrió un error al actualizar la temporada ' .
                                $comp->currentSeason->id);
                        }
                    }
                }

                # Winner Season
                if ( !empty($comp->currentSeason->winner) )
                {
                    $winner = $comp->currentSeason->winner;
                    $win_data = array(
                        'id' => $winner->id ,
                        'curseason_id' => $comp->currentSeason->id ,
                        'name' => $winner->name ,
                        'shortName' => $winner->shortName ,
                        'tla' => $winner->tla ,
                        'crestUrl' => $winner->crestUrl ,
                    );

                    $existWinner = $this->service_winseason->getRow( (int) $winner->id );

                    if ($existWinner['status'] == 0)
                    {
                        $insertWinSeason = $this->service_winseason->insert($win_data);
                        if ($insertWinSeason['status'] == 0) {
                            throw new CustomException('No se registro la temporada ' .
                                $winner->id);
                        }
                    } else {
                        $updateWinSeason = $this->service_winseason->update($win_data, $winner->id);
                        if ($updateWinSeason['status'] == 0) {
                            throw new  CustomException('Ocurrió un error al actualizar la temporada ' .
                                $winner->id);
                        }
                    }
                }

                # competitions
                $existCompetition = $this->comp_repo->getRow( (int) $comp->id );

                if ( !empty($existCompetition) )
                {
                    $insertCompetition = $this->comp_repo->insert($comp_data);
                    if (!empty($insertCompetition) && $insertCompetition != 0) {
                        throw new CustomException('No se registro la competencia ' .
                            $comp->id);
                    }
                } else {
                    $updateCompetition = $this->comp_repo->update($comp_data, $comp->id);
                    if ($updateCompetition <= 0 || empty($updateCompetition) ) {
                        throw new  CustomException('Ocurrió un error al actualizar la competencia ' .
                            $comp->id);
                    }
                }
                dd($comp_data);
            }

            $code = 202;
            $response['message'] = 'Datos actualizados';
            $response['status'] = 1;
            $response['count'] = $competitions->count;
//            $response['records'] = collect($leagues->competitions)->toJson(JSON_PRETTY_PRINT);
        } catch (QueryException $e) {
            $response['message'] = '¡ERROR! contact with support.';
            Log::critical('Update Competitions',
                ['request' => [], 'exception' => $e->getMessage()]);
        } catch (Exception $e) {
            $response['message'] = '¡ERROR! contact with support';
            Log::alert('Update Competitions',
                ['request' => [], 'exception' => $e->getMessage()]);
        }

        return response()->json($response['records'], $code);
    }
}
