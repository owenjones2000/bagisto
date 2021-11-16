<?php

namespace App\Console\Commands;

use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ReportStatusCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'report-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $report_url = 'https://bagistoorders.luckfun.vip/api/shop';
        $client = new Client();
        $app_url = config('app.url');
        $app_name = config('app.name');
   
        try {
            $req = $client->request('POST', $report_url, [
                'json' => [
                    'url' => $app_url,
                    'name' => $app_name   
                ]
            ]);
            $reps = $req->getBody()->getContents();
            dump($reps);
            
        } catch (\Exception $e) {
            Log::error($e);
            dump($reps);
        }
    }
}
