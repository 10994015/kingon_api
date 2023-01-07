<?php

namespace App\Http\Controllers;

use App\Models\ChargerDetail;
use App\Models\RecordChargerDetail;
use App\Models\StorageChargerDetail;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
class KingOnAPI extends Controller
{
    public $status;
    public function store(Request $req){
        //一個row是一個平板還是一台車?
        //如果是一個平板，我們一次新增就是新增他所有平版的資料嗎?
        //如果是每台車，那statu上游是傳送每個平版的status, 那每個row的status要寫哪一台的?
        try{
            $charger_detail_total = ChargerDetail::where([['charger_car_id', $req['ID']], ['school_date', date('Y-m-d')], ['time_seq', date('H')]])->count();
            $charge_amount = 0;
            $status = "";
            foreach($req['Trolley']['Ports'] as $post){
                $charge_amount = $charge_amount + $post['Capacity'];
                $status = $status . ", " . $post['State'];
            }
            if($charger_detail_total > 0){
                $charger = ChargerDetail::where([['charger_car_id', $req['ID']], ['school_date', date('Y-m-d')], ['time_seq', date('H')]])->first();
                $charger->charge_amount = $charge_amount;
                $charger->deposit_device = $req->TabletNumber;
                $charger->status = $status;
                $charger->save();
            }else{
                $charger = new ChargerDetail();
                $charger->charger_car_id = $req->ID;
                $charger->school_date = date("Y-m-d");
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
        try{
            $i = 0;
            while(true){
                if(is_array($req[$i])){
                    if(count($req[$i]) > 0 ){
                        $record = new RecordChargerDetail();
                        $record->charger_car_id = $id;
                        $record->school_date = date("Y-m-d");
                        $record->time_seq = date('H');
                        $record->port_no  = $req[$i]['No'];
                        $record->capacity  = $req[$i]['Capacity'];
                        $record->status  = $req[$i]['State'];
                        $record->save();
    
                        $storage = new StorageChargerDetail();
                        $storage->charger_car_id = $id;
                        $storage->school_date = date("Y-m-d");
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
            $charger_num = ChargerDetail::where([['charger_car_id', $id], ['school_date', date("Y-m-d")], ['time_seq', date('H')]])->count();
            if($charger_num == 0){
                $charger_deposit_device = ChargerDetail::where('charger_car_id', $id)->orderBy('created_at', 'DESC')->first()->deposit_device;
    
                $charge_amount = 0;
                $statu = "";
    
                $storages = StorageChargerDetail::where('charger_car_id', $id)->get();
                foreach($storages as $storage){
                    $charge_amount = $charge_amount + $storage->capacity;
                    $statu = $statu . $storage->status . ", ";
                }
    
    
                $charger = new ChargerDetail();
                $charger->charger_car_id = $id;
                $charger->school_date = date("Y-m-d");
                $charger->time_seq = date("H");
                $charger->charge_amount = $charge_amount;
                $charger->status = $statu;
                $charger->deposit_device = $charger_deposit_device;
                $charger->save();
                // StorageChargerDetail::where('id', '>', '0')->get()->delete();
                DB::table('storage_charger_datail')->truncate();

            }
    
            return ['message' => 'Data sent successfully!'];
        }catch (Exception $e){
            return ['message' => 'Data transfer failed!', 'error'=> $e];
        }
        
    }
    
}
