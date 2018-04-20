<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GetProtocol extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'protocol:get {ip} {port}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get protocol file from remote tcp server';

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
        $ip = $this->argument('ip');
        $port = (int)$this->argument('port');
        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP) or die("Could not create  socket\n");
        $connection = socket_connect($socket, $ip, $port) or die("Could not connet server\n");
        $message = json_encode([
          'FileName' => 'DSC_Protocol.xml',
          'MD5' => '1234',
          'File' => null,
          'IsChange' => false
        ]);
        $header = $this->to_str(array(0xee,0xcc,0x00));
        $message = $header.$message;
        socket_write($socket, $message) or die("Write failed\n");
        $received_string = '';
        $received_object = '';
        try {
            set_time_limit(10);
            while (true) {
                $buff = socket_read($socket, 99999, PHP_BINARY_READ);
                $received_string = $received_string.$buff;
                $received_object = json_decode(substr($received_string, 3, strlen($received_string)-3));
                if (!is_null($received_object)) {
                    break;
                } else {
                    continue;
                }
            }
            socket_close($socket);
        } catch (\Exception $e) {
            socket_close($socket);
            $this->error('远程协议加载失败!');
            return;
        }
        $root_path = storage_path().'/app/';
        try {
            $result = file_put_contents($root_path.'DSC_Protocol.xml', $this->to_str($received_object->File));
            if ($result>0) {
                $this->info('远程协议加载成功!');
            } else {
                $this->error('远程协议加载失败!');
            }
        } catch (\Exception $e) {
            $this->error('远程协议加载失败!');
            return;
        }
    }

    protected function get_bytes($str)
    {
        $len = strlen($str);
        $bytes = array();
        for ($i=0;$i<$len;$i++) {
            if (ord($str[$i]) >= 128) {
                $byte = ord($str[$i]) - 256;
            } else {
                $byte = ord($str[$i]);
            }
            $bytes[] =  $byte ;
        }
        return $bytes;
    }

    protected function to_str($bytes)
    {
        $str = '';
        foreach ($bytes as $ch) {
            $str .= chr($ch);
        }

        return $str;
    }
}
