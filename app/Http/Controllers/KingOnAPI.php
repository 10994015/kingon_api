<?php

namespace App\Http\Controllers;

use App\Models\ChargerDetail;
use App\Models\RecordChargerDetail;
use App\Models\StorageChargerDetail;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Validator;
class KingOnAPI extends Controller
{
    public $status;
    public function store(Request $req){
        $validator = Validator::make($req->all(), [
            'ID' => 'required|size:14',
            'TabletNumber'=>'required|integer',
            'Trolley.Ports.*.No'=>'required|integer',
            'Trolley.Ports.*.Capacity'=>'integer'
        ]);
        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }
        try{
            $charger_detail_total = ChargerDetail::where([['charger_car_id', $req['ID']], ['school_date', "0".(date("Y")-1911).date("-m-d")], ['time_seq', date('H')]])->count();
            log::info($charger_detail_total);
            $charge_amount = 0;
            $status = "";
            foreach($req['Trolley']['Ports'] as $key => $post){
                $charge_amount = $charge_amount + $post['Capacity'];
                if($key === array_key_last($req['Trolley']['Ports'])){
                    $status = $status . $post['State'];
                }else{
                    $status = $status . $post['State'] .",";
                }
            }
            if($charger_detail_total > 0){
                $charger = ChargerDetail::where([['charger_car_id', $req['ID']], ['school_date', "0".(date("Y")-1911).date("-m-d")], ['time_seq', date('H')]])->first();
                $charger->charge_amount = $charge_amount;
                $charger->deposit_device = $req->TabletNumber;
                $charger->status = $status;
                $charger->save();
            }else{
                $charger = new ChargerDetail();
                $charger->charger_car_id = $req->ID;
                $charger->school_date = "0".(date("Y")-1911).date("-m-d");
                $charger->time_seq = date("H");
                $charger->charge_amount = $charge_amount;
                $charger->deposit_device = $req->TabletNumber;
                $charger->status = $status;
                $charger->save();
            }
            return ['message' => 'Data sent successfully!'];
        }catch (Exception $e){
            return ['message' => 'Data transfer failed!', 'error'=>$e];
        }
    }
    public function update(Request $req, $id){
        $validator = Validator::make($req->all(), [
            '*.No'=>'required|integer',
            '*.Capacity'=>'integer'
        ]);
        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }
        $isset = ChargerDetail::where('charger_car_id', $id)->count();
        if($isset == 0){
            return ['message' => 'Data transfer failed!', 'error' => 'ID not found'];
        }
        try{
            $i = 0;
            while(true){
                if(is_array($req[$i])){
                    if(count($req[$i]) > 0 ){
                        $record = new RecordChargerDetail();
                        $record->charger_car_id = $id;
                        $record->school_date = "0".(date("Y")-1911).date("-m-d");
                        $record->time_seq = date('H');
                        $record->port_no  = $req[$i]['No'];
                        $record->capacity  = $req[$i]['Capacity'];
                        $record->status  = $req[$i]['State'];
                        $record->save();
    
                        $storage = new StorageChargerDetail();
                        $storage->charger_car_id = $id;
                        $storage->school_date = "0".(date("Y")-1911).date("-m-d");
                        $storage->time_seq = date('H');
                        $storage->port_no  = $req[$i]['No'];
                        $storage->capacity  = $req[$i]['Capacity'];
                        $storage->status  = $req[$i]['State'];
                        $storage->save();
                        
                        $i++;
                    }else{
                        break;
                    }
                }else{
                    break;
                }
                
            }
            $i = 0;
            $charger_num = ChargerDetail::where([['charger_car_id', $id], ['school_date', "0".(date("Y")-1911).date("-m-d")], ['time_seq', date('H')]])->count();
            
            if($charger_num == 0){
                $charger_deposit_device = ChargerDetail::where('charger_car_id', $id)->orderBy('created_at', 'DESC')->first()->deposit_device;
                log::info('charger_deposit_device' . $charger_deposit_device); // 充電瓶數量
                $charge_amount = 0;
                $statu = "";
                $storages = StorageChargerDetail::where('charger_car_id', $id)->get();
                $count = $storages->count();
                foreach($storages as $key=>$storage){
                    $charge_amount = $charge_amount + intval($storage->capacity);
                    $statu .= $storage->status ;
                    if($key !== ($count-1)){
                        $statu .= ',';
                    }
                }

                $charger = new ChargerDetail();
                $charger->charger_car_id = $id;
                $charger->school_date = "0".(date("Y")-1911).date("-m-d");
                $charger->time_seq = date("H");
                $charger->charge_amount = $charge_amount;
                $charger->status = $statu;
                $charger->deposit_device = $charger_deposit_device;
                $charger->save();
                // StorageChargerDetail::where('id', '>', '0')->delete();
                DB::table('storage_charger_datail')->truncate();

            }
    
            return ['message' => 'Data sent successfully!'];
        }catch (Exception $e){
            return ['message' => 'Data transfer failed!', 'error'=> $e];
        }
        
    }

    public function test(){
        return ['message' => 'test !!'];
    }
    
}
