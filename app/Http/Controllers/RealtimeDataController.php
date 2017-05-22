<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Redis;

class RealtimeDataController extends Controller
{

	// public static $count = 0;

	// public function __construct()
 //    {
 //        $this->middleware('auth');
 //    }

	public function index(Request $request){
		// $realtimeData = 'test';

		// self::$count = self::$count + 1;
		// $realtimeData = self::$count;
		$json_string = file_get_contents('/var/www/FlightTestDataPlatform-PHP/storage/app/id_index_collection.json');
		$realtimeData = json_decode($json_string, true);
		foreach ($realtimeData as $key => $value) {
			$tmp = Redis::hmget($key, ['ResultDouble', 'ResultStr', 'Unit']);
			$realtimeData[$key] = [
				'ResultDouble'=>$tmp[0],
				'ResultStr'=>$tmp[1].$tmp[2],
			];
		}
		return compact('realtimeData');
	}
}
