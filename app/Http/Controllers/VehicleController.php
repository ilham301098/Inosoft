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

    public function editVehicle(Request $request){
        return $this->vehicleService->editVehicle($request);
    }

    public function buyVehicle(Request $request){
        return $this->vehicleService->buyVehicle($request);
    }

    public function sellVehicle(Request $request){
        return $this->vehicleService->sellVehicle($request);
    }

    public function deleteVehicle(Request $request){
        return $this->vehicleService->deleteVehicle($request);
    }

}
