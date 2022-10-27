<?php
namespace App\Traits;
use Illuminate\Http\Response;
use Illuminate\Pagination\LengthAwarePaginator;

trait ApiResponser{

    public function jsonResponse($data=[], $code = Response::HTTP_OK){
        return response()->json($data, $code);
    }

    public function successResponse($data=null, $message="success",$code = Response::HTTP_OK){
        $data=[
            'code'=>$code,
            'status'=>true,
            'message'=>$message,
            'data'=>$data
        ];
        return response()->json($data, $code);
    }

    public function errorResponse($message="", $errors=[], $code= 400){
        $data=[
            'code'=>$code,
            'message'=>$message,
            'errors'=>$errors
        ];
        return response()->json($data, $code);
    }

    public function forbiddenResponse($message="Anda tidak memiliki hak untuk mengakses konten ini", $code= 403){
        $data=[
            'code'=>$code,
            // 'type'=>'F',
            'message'=>$message
        ];
        return response()->json($data, $code);
    }


}

?>