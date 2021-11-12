<?php

namespace App\Console\Commands;

use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Webkul\Sales\Models\Order;

class ReportOrderCommand extends Command
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
        $orders = Order::query()
            ->where('is_report', 0)
            ->select([
                'id',
                'status',
                'is_guest',
                'customer_email',
                'customer_first_name',
                'customer_last_name',
                'grand_total',
                'sub_total',
                'created_at',
                'is_report',
            ])->get();
        // dd($orders);
        $report_url = '';
        $client = new Client();
        $app_url = config('app.url');dd($app_url); 
        try{
            $req = $client->request('POST', $report_url, [
                'json' => [
                    'orders' => $orders,
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
