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
    protected $signature = 'report-order';

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
        $report_url = '';
        $client = new Client();
        $app_url = config('app.url');
        try {
            $req = $client->request('POST', $report_url, [
                'json' => [
                    'app_url' => $app_url   
                ]
            ]);
            $reps = $req->getBody()->getContents();
            dump($reps);
            // foreach ($orders as $key => $order) {
            //     # code...
            //     $order->is_report = 1;
            //     $order->save();
            // }

        } catch (\Exception $e) {
            Log::error($e);
            dump($reps);
        }
    }
}
