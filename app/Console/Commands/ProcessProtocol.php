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
              'id_index'=>$tmframe->getElementsByTagName('ID')[0]->nodeValue.'_'.$tmframe->getElementsByTagName('index')[0]->nodeValue,
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
                        $id_index_collection[$param->getElementsByTagName('ID')[0]->nodeValue.'_'.$param->getElementsByTagName('index')[0]->nodeValue] = '0';
                        $param_dict['id_index'] = $param->getElementsByTagName('ID')[0]->nodeValue.'_'.$param->getElementsByTagName('index')[0]->nodeValue;
                        if ($param->getElementsByTagName('minValue')[0]->nodeValue != '' && $param->getElementsByTagName('maxValue')[0]->nodeValue != '') {
                            $param_dict['min'] = $param->getElementsByTagName('minValue')[0]->nodeValue;
                            $param_dict['max'] = $param->getElementsByTagName('maxValue')[0]->nodeValue;
                        }
                    } else {
                        $param_dict['children'] = [];
                        foreach ($subparam_list as $subparam) {
                            $name = $subparam->getElementsByTagName('name')[0]->nodeValue;
                            if ($name != '备用' && $name != '校验和') {
                                $id_index_collection[$subparam->getElementsByTagName('ID')[0]->nodeValue.'_'.$subparam->getElementsByTagName('index')[0]->nodeValue] = '0';
                                $subparam_dict = [
                                  'name'=>$subparam->getElementsByTagName('name')[0]->nodeValue,
                                  'id'=>$subparam->getElementsByTagName('ID')[0]->nodeValue.'_'.$subparam->getElementsByTagName('index')[0]->nodeValue,
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
    }
}
