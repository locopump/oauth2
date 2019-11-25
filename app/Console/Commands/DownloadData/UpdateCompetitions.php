<?php

namespace App\Console\Commands\DownloadData;

use Illuminate\Console\Command;
use App\Models\Services\Publico\Competitions\CompetitionsService;
use Exception;

class UpdateCompetitions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'football:competitions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Download information from football-data.org API';

    /**
     * Services
     *
     * @var string
     */
    protected $cptService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(CompetitionsService $cptService)
    {
        parent::__construct();
        $this->cptService = $cptService;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            $message = 'con problemas.';
            $this->line("DESCARGA DE INFORMACION DE COMPETICIONES DE FOOTBALL");
            $this->line("====================================================");


            $competitions = $this->cptService->updateCompetitions();

            if ($competitions['status'] == 1 ) {
                $message = 'Correcta';
            }

            $this->info('Carga de información a bd local: ' . $message);
        } catch (Exception $e) {
            $this->error($e->getMessage());
        }
    }
}
