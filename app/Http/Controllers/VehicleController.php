<?php

namespace App\Http\Controllers;

use App\Models\User;

use App\Services\AuthService;
use App\Services\VehicleService;
use App\Services\ResponseService;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class VehicleController extends Controller {

    private $vehicleService;

    public function __construct(){
        $this->vehicleService = new VehicleService();
    }

    public function listVehicle(Request $request){
        return $this->vehicleService->listVehicle($request);
    }

    public function addVehicle(Request $request){
        return $this->vehicleService->addVehicle($request);
    }

    public function editVehicle(Request $request,$id){
        return $this->vehicleService->editVehicle($request,$id);
    }

    public function sellVehicle(Request $request,$id){
        return $this->vehicleService->sellVehicle($request,$id);
    }

    public function deleteVehicle(Request $request,$id){
        return $this->vehicleService->deleteVehicle($request,$id);
    }

}
