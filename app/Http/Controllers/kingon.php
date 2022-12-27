<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Port;
use App\Models\Record;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
class kingon extends Controller
{
    public function index(){
        return ['id' => 1];
    }
    public function store(Request $req){
        // log::info($req->Trolley['Port'][0]['No']);
        $old_car = Car::where('id', $req->ID)->count();
        if($old_car == 0){
            $car = new Car();
            $car->id = $req->ID;
            $car->type = $req->Type;
            $car->name = $req->Name;
            $car->mac = $req->MAC;
            $car->fwver = $req->FWVer;
            $car->trolley_model = $req->Trolley['Model'];
            $car->trolley_fwver = $req->Trolley['FWVer'];
            $car->trolley_ts = $req->Trolley['TimeStamp'];
            $car->trolley_port_number = $req->Trolley['PortNumber'];
            $car->save();
            for($n=0;$n<$req->Trolley['PortNumber'];$n++){
                $port = new Port();
                $port->no = $req->Trolley['Port'][$n]['No'];
                $port->voltage = $req->Trolley['Port'][$n]['Voltage'];
                $port->current = $req->Trolley['Port'][$n]['Current'];
                $port->capacity = $req->Trolley['Port'][$n]['Capacity'];
                $port->state = $req->Trolley['Port'][$n]['State'];
                $port->uptime = $req->Trolley['Port'][$n]['ChargeTime'];
                $port->car_id = $req->ID;
                $port->save();
            }

            $record = new Record();
            $record->method = "POST";
            $record->api_url = "/devices";
            $record->save();
            
            return ['message' => '傳送成功！'];
        }else{
            return ['message' => '傳送失敗，資料已存在！'];
        }
    }
}

