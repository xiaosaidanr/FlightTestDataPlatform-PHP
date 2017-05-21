<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Illuminate\Support\Facades\Redis;

use App\Events\TmdataUpdated;

class RedisSubscribe extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'redis:subscribe';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Subscribe to a Redis channel';

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
     * @return mixed
     */
    public function handle()
    {
        // Redis::subscribe(['tmdata-channel'], function ($message) {
        //     echo $message;
        //     echo 'send events';
        //     event(new TmdataUpdated());
        // });
        $tmp = Redis::hmget('FP_FLIGHT_CONTROL_ADC_0', ['ResultStr', 'Unit']);
        echo $tmp[0];
        echo $tmp[1];
    }
}
