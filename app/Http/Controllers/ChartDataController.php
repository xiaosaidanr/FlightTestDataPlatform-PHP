<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ChartDataController extends Controller
{
    //
    public function index() {
        $root_path = storage_path().'/app/';
        $json_string = file_get_contents($root_path.'chart_config.json');
        $option = json_decode($json_string, true);
        return compact("option");
    }
}
