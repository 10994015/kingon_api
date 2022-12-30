<?php

namespace App\Http\Controllers;

use App\Models\ChargerDetail;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
class KingOnAPI extends Controller
{
    public function store(Request $req){
        //一個row是一個平板還是一台車?
        //如果是一個平板，我們一次新增就是新增他所有平版的資料嗎?
        //如果是每台車，那statu上游是傳送每個平版的status, 那每個row的status要寫哪一台的?
        try{
            $charge_amount = 0;
            foreach($req['Trolley']['Ports'] as $post){
                $charge_amount = $charge_amount + $post['Capacity'];
            }
            log::info($charge_amount);
            $charger = new ChargerDetail();
            $charger->charger_car_id = $req->ID;
            $charger->school_date = date("Y-m-d");
            $charger->time_seq = date("H");
            $charger->charge_amount = $charge_amount;
            $charger->deposit_device = $req->TabletNumber;
            $charger->statu = "0";
            $result = $charger->save();
            return ['message' => '成功!'];
        }catch (Exception $e){
            return ['message' => $e];
        }
    }
    public function update(Request $req, $id){
        log::info($id);
        $charger_num = ChargerDetail::where([['charger_car_id', $id], ['school_date', date("Y-m-d")], ['time_seq', date('H')]])->count();
        log::info($charger_num);
        if($charger_num > 0){
            
        }
    }
}
