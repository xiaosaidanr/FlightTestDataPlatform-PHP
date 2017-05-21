<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;

class TestController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index() {
        $user = Auth::user();
        $file_name = (string)($user->id).'_rawDataConfig.json';
        $file_directory = '/var/www/FlightTestDataPlatform-PHP/storage/app/'.$file_name;
        if (file_exists($file_directory)) {
            $json_string = file_get_contents($file_directory);
            $option = json_decode($json_string, true);
            return compact("option");
        }
        else{
            $json_string = file_get_contents('/var/www/FlightTestDataPlatform-PHP/storage/app/default.json');
            $option = json_decode($json_string, true);
            return compact("option");
        }

    }

    public function store(Request $request){
        $user = Auth::user();
        $file_name = (string)($user->id).'_rawDataConfig.json';
        echo $file_name;
        $file_directory = '/var/www/FlightTestDataPlatform-PHP/storage/app/'.$file_name;
        echo $file_directory;
        $body = $request->all();
        $option = json_encode($body);
        file_put_contents($file_directory, $option);
    }
}
