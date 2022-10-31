<?php

namespace App\Services;

use App\Http\Resources\UserResource;
use App\Http\Resources\UserSimpleResource;
use App\Models\User;
use App\Models\Vehicle;

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

            $data= Vehicle::where('status','!=','sold')->get();
            
            $response = [
                'success'=>true,
                'data'=>$data
            ];
            return ResponseService::toJson($response);
        } catch (\Exception $e) {
            $response = ResponseService::toArray(false, $e->getMessage());
            return ResponseService::toJson($response);
        }
    }
    
    public function addVehicle($request){
        try {
            
            $data= Vehicle::insert($request->all());
            
            $response = [
                'success'=>true,
                'data'=>$data
            ];
            return ResponseService::toJson($response);
        } catch (\Exception $e) {
            $response = ResponseService::toArray(false, $e->getMessage());
            return ResponseService::toJson($response);
        }
    }


    public function editVehicle($request,$id){
        try {
            
            $data= Vehicle::where('id',$id)->update($request->all());
            
            $response = [
                'success'=>true,
                'data'=>$data
            ];
            return ResponseService::toJson($response);
        } catch (\Exception $e) {
            $response = ResponseService::toArray(false, $e->getMessage());
            return ResponseService::toJson($response);
        }
    }


    public function sellVehicle($request,$id){
        try {
            $data= Vehicle::where('id',$id)->update($request->all());
            
            $response = [
                'success'=>true,
                'data'=>$data
            ];
            return ResponseService::toJson($response);
        } catch (\Exception $e) {
            $response = ResponseService::toArray(false, $e->getMessage());
            return ResponseService::toJson($response);
        }
    }

    public function deleteVehicle($request,$id){
        try {
            
            $data= Vehicle::where('id',$id)->delete();
            
            $response = [
                'success'=>true,
                'data'=>$data
            ];
            return ResponseService::toJson($response);
        } catch (\Exception $e) {
            $response = ResponseService::toArray(false, $e->getMessage());
            return ResponseService::toJson($response);
        }
    }

}
