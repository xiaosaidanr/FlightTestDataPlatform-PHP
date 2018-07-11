<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ProcessProtocol extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'protocol:process';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '通过Dsc协议生成各种网站后台需要的其他协议';

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
        $dsc_protocol_path = storage_path().'/app/DSC_Protocol.xml';
        $dsc_doc = new \DOMDocument();
        $dsc_doc->load($dsc_protocol_path);
        $root = $dsc_doc->documentElement;
        $tmframe_list = $root->getElementsByTagName('TMFrame');
        $id_index_collection = [
          'info_message'=>[]
        ];
        $option = [
          'name'=>'root',
          'children'=>[]
        ];
        foreach ($tmframe_list as $tmframe) {
            $tmframe_dict = [
              'name'=>$tmframe->getElementsByTagName('name')[0]->nodeValue,
              'id_index'=>$tmframe->getElementsByTagName('ID')[0]->nodeValue.'_'.((string)base_convert($tmframe->getElementsByTagName('index')[0]->nodeValue, 16, 10)),
              'children'=>[]
            ];
            $param_list = array();
            foreach ($tmframe->childNodes as $tmframe_child) {
                if ($tmframe_child->nodeName == 'Param') {
                    array_push($param_list, $tmframe_child);
                }
            }
            foreach ($param_list as $param) {
                $name = $param->getElementsByTagName('name')[0]->nodeValue;
                if ($name != '备用' && $name != '校验和') {
                    $param_dict = [
                      'name'=>$param->getElementsByTagName('name')[0]->nodeValue
                    ];
                    $subparam_list = $param->getElementsByTagName('Param');
                    if ($subparam_list->length == 0) {
                        $id_index_collection[$param->getElementsByTagName('ID')[0]->nodeValue.'_'.((string)base_convert($param->getElementsByTagName('index')[0]->nodeValue, 16, 10))] = '0';
                        $param_dict['id_index'] = $param->getElementsByTagName('ID')[0]->nodeValue.'_'.((string)base_convert($param->getElementsByTagName('index')[0]->nodeValue, 16, 10));
                        if ($param->getElementsByTagName('minValue')[0]->nodeValue != '' && $param->getElementsByTagName('maxValue')[0]->nodeValue != '') {
                            $param_dict['min'] = $param->getElementsByTagName('minValue')[0]->nodeValue;
                            $param_dict['max'] = $param->getElementsByTagName('maxValue')[0]->nodeValue;
                        }
                    } else {
                        $param_dict['children'] = [];
                        foreach ($subparam_list as $subparam) {
                            $name = $subparam->getElementsByTagName('name')[0]->nodeValue;
                            if ($name != '备用' && $name != '校验和') {
                                $id_index_collection[$subparam->getElementsByTagName('ID')[0]->nodeValue.'_'.((string)base_convert($subparam->getElementsByTagName('index')[0]->nodeValue, 16, 10))] = '0';
                                $subparam_dict = [
                                  'name'=>$subparam->getElementsByTagName('name')[0]->nodeValue,
                                  'id_index'=>$subparam->getElementsByTagName('ID')[0]->nodeValue.'_'.((string)base_convert($subparam->getElementsByTagName('index')[0]->nodeValue, 16, 10)),
                                ];
                                if ($subparam->getElementsByTagName('minValue')[0]->nodeValue != '' && $subparam->getElementsByTagName('maxValue')[0]->nodeValue != '') {
                                    $subparam_dict['min'] = $subparam->getElementsByTagName('minValue')[0]->nodeValue;
                                    $subparam_dict['max'] = $subparam->getElementsByTagName('maxValue')[0]->nodeValue;
                                }
                                array_push($param_dict['children'], $subparam_dict);
                            }
                        }
                    }
                    array_push($tmframe_dict['children'], $param_dict);
                }
            }
            array_push($option['children'], $tmframe_dict);
        }
        file_put_contents(storage_path().'/app/id_index_collection.json', json_encode($id_index_collection));
        $this->info('生成id_index_collection.json!');
        file_put_contents(storage_path().'/app/option.json', json_encode($option, JSON_UNESCAPED_UNICODE));
        $this->info('生成option.json!');
        // $this->add_id();
        $this->generate_4_chart();
    }

    private function add_id()
    {
        $json_string = file_get_contents(storage_path().'/app/default.json');
        $data = json_decode($json_string, true);
        $data['id'] = 'root';
        for ($i=0; $i < count($data['children']); $i++) {
            # code...
            $data['children'][$i]['id'] = (string)$i;
            for ($j=0; $j < count($data['children'][$i]['children']); $j++) {
                # code...
                $data['children'][$i]['children'][$j]['id'] = $i.'_'.$j;
                for ($k=0; $k < count($data['children'][$i]['children'][$j]['children']); $k++) {
                    # code...
                    $data['children'][$i]['children'][$j]['children'][$k]['id'] = $i.'_'.$j.'_'.$k;
                }
            }
        }
        $json_string = json_encode($data);
        file_put_contents(storage_path().'/app/default.json', $json_string);
        $this->info('生成default.json!');
    }

    private function generate_4_chart(){
        $dsc_protocol_path = storage_path().'/app/DSC_Protocol.xml';
        $dsc_doc = new \DOMDocument();
        $dsc_doc->load($dsc_protocol_path);
        $root = $dsc_doc->documentElement;
        $tmframe_list = $root->getElementsByTagName('TMFrame');
        $chart_config = [];
        foreach ($tmframe_list as $tmframe) {
            $tmframe_dict = [
              'id'=>$tmframe->getElementsByTagName('ID')[0]->nodeValue.'_'.((string)base_convert($tmframe->getElementsByTagName('index')[0]->nodeValue, 16, 10)),
              'label'=>$tmframe->getElementsByTagName('name')[0]->nodeValue,
              'id_index'=>$tmframe->getElementsByTagName('ID')[0]->nodeValue.'_'.((string)base_convert($tmframe->getElementsByTagName('index')[0]->nodeValue, 16, 10)),
              'children'=>[],
            ];
            $param_list = array();
            foreach ($tmframe->childNodes as $tmframe_child) {
                if ($tmframe_child->nodeName == 'Param') {
                    array_push($param_list, $tmframe_child);
                }
            }
            foreach ($param_list as $param) {
                $name = $param->getElementsByTagName('name')[0]->nodeValue;
                $EnumTypeID = $param->getElementsByTagName('EnumTypeID')[0]->nodeValue;
                if ($name != '备用' && $name != '校验和' && $EnumTypeID == '') {
                    $param_dict = [
                      'id'=>$param->getElementsByTagName('ID')[0]->nodeValue.'_'.((string)base_convert($param->getElementsByTagName('index')[0]->nodeValue, 16, 10)),
                      'label'=>$param->getElementsByTagName('name')[0]->nodeValue,
                      'id_index'=>$param->getElementsByTagName('ID')[0]->nodeValue.'_'.((string)base_convert($param->getElementsByTagName('index')[0]->nodeValue, 16, 10)),
                    ];
                    $subparam_list = $param->getElementsByTagName('Param');
                    if ($subparam_list->length != 0) {
                        $param_dict['children'] = [];
                        foreach ($subparam_list as $subparam) {
                            $name = $subparam->getElementsByTagName('name')[0]->nodeValue;
                            $EnumTypeID = $subparam->getElementsByTagName('EnumTypeID')[0]->nodeValue;
                            if ($name != '备用' && $name != '校验和' && $EnumTypeID == '') {
                                $subparam_dict = [
                                  'id'=>$subparam->getElementsByTagName('ID')[0]->nodeValue.'_'.((string)base_convert($subparam->getElementsByTagName('index')[0]->nodeValue, 16, 10)),
                                  'label'=>$subparam->getElementsByTagName('name')[0]->nodeValue,
                                  'id_index'=>$subparam->getElementsByTagName('ID')[0]->nodeValue.'_'.((string)base_convert($subparam->getElementsByTagName('index')[0]->nodeValue, 16, 10)),
                                ];
                                array_push($param_dict['children'], $subparam_dict);
                            }
                        }
                    }
                    if (isset($param_dict['children'])) {
                        # code...
                        if (count($param_dict['children']) > 0) {
                            # code...
                            array_push($tmframe_dict['children'], $param_dict);
                        }
                    }
                    else {
                        # code...
                        array_push($tmframe_dict['children'], $param_dict);
                    }
                }
            }
            if (count($tmframe_dict['children']) > 0) {
                # code...
                array_push($chart_config, $tmframe_dict);
            }
        }
        file_put_contents(storage_path().'/app/chart_config.json', json_encode($chart_config, JSON_UNESCAPED_UNICODE));
        $this->info('生成chart_config.json!');
    }
}
