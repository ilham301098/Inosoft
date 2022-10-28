<?php

namespace App\Services;

use App\Http\Resources\UserResource;
use App\Http\Resources\UserSimpleResource;
use App\Models\User;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class VehicleService{

    private $modelUser;
    private $authService;

    public function __construct(){
        $this->modelUser = new User();
        $this->authService = new AuthService();
    }

    public function listVehicle($request){
        try {
            
            $response = ResponseService::toArray(true, 'Data berhasil diupdate');
            return ResponseService::toJson($response);
        } catch (\Exception $e) {
            $response = ResponseService::toArray(false, $e->getMessage());
            return ResponseService::toJson($response);
        }
    }
    
    public function addVehicle($request){
        try {
            
            $response = ResponseService::toArray(true, 'Data berhasil diupdate');
            return ResponseService::toJson($response);
        } catch (\Exception $e) {
            $response = ResponseService::toArray(false, $e->getMessage());
            return ResponseService::toJson($response);
        }
    }


    public function editVehicle($request){
        try {
            
            $response = ResponseService::toArray(true, 'Data berhasil diupdate');
            return ResponseService::toJson($response);
        } catch (\Exception $e) {
            $response = ResponseService::toArray(false, $e->getMessage());
            return ResponseService::toJson($response);
        }
    }


    public function buyVehicle($request){
        try {
            
            $response = ResponseService::toArray(true, 'Data berhasil diupdate');
            return ResponseService::toJson($response);
        } catch (\Exception $e) {
            $response = ResponseService::toArray(false, $e->getMessage());
            return ResponseService::toJson($response);
        }
    }


    public function sellVehicle($request){
        try {
            
            $response = ResponseService::toArray(true, 'Data berhasil diupdate');
            return ResponseService::toJson($response);
        } catch (\Exception $e) {
            $response = ResponseService::toArray(false, $e->getMessage());
            return ResponseService::toJson($response);
        }
    }

    public function deleteVehicle($request){
        try {
            
            $response = ResponseService::toArray(true, 'Data berhasil diupdate');
            return ResponseService::toJson($response);
        } catch (\Exception $e) {
            $response = ResponseService::toArray(false, $e->getMessage());
            return ResponseService::toJson($response);
        }
    }

}
