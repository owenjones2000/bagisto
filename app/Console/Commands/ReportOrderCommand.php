<?php

namespace App\Console\Commands;

use Exception;
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

    protected $url = 'https://bagistoorders.luckfun.vip/api/order';

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
            ->whereColumn('status', '!=', 'report_status')
            ->select([
                'id as order_id',
                'status',
                'is_guest',
                'customer_email',
                'customer_first_name',
                'customer_last_name',
                'grand_total',
                'sub_total',
                'created_at as order_created_at',
                'report_status',
            ])->get();

        $report_url = $this->url;

        dump($report_url);
        $client = new Client();
        $app_url = config('app.url');
        $reps = [];
        try {
            if ($orders->isNotEmpty()) {
                $req = $client->request('POST', $report_url, [
                    'json' => [
                        'orders' => $orders,
                        'app_url' => $app_url
                    ]
                ]);
                $reps = $req->getBody()->getContents();
                $reps = json_decode($reps, true);

                if ($reps['code'] == 0) {
                    foreach ($orders as $order) {
                        # code...

                        $update = Order::query()->where('id', $order->order_id)->update([
                            'report_status' => $order->status,
                        ]);
                        dump($update);
                    }
                } else {
                    Log::error($reps);
                    throw new Exception('上传订单接口返回失败', -1);
                }
            } else {
                dump('没有订单上报');
            }
        } catch (\Exception $e) {
            Log::error($e);
            dump('上报order失败');
        }
    }
}
