<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class ResponseService
{
    public static function toArray($status, $message, $data = null, $errors = null)
    {
        return [
            'status' => $status,
            'message' => $message,
            'data' => $data,
            'errors' => $errors
        ];
    }

    public static function toJson($response, $statusCode = 200)
    {
        return response()->json($response, $statusCode);
    }

    public static function pagination($itemData, $data)
    {
        $content = [
            'data' => $data,
            'current_page' => $itemData->currentPage(),
            'first_item' => $itemData->firstItem(),
            'last_item' => $itemData->lastItem(),
            "per_page" => $itemData->perPage(),
            'last_page' => $itemData->lastPage(),
            "total" => $itemData->total(),
        ];
        return [
            'status' => true,
            'message' => 'Ok',
            'data' => $content,
            'errors' => null
        ];
    }

    public static function validatorError($errors)
    {
        return [
            'status' => false,
            'message' => 'Validator Error',
            'data' => null,
            'errors' => $errors
        ];
    }

    /**
     * Formst response on validator error
     *
     * @param Request $request
     * @param array $rules
     * @return response
     */
    public static function validatorErrorAlt($request, $rules)
    {
        $validatorService = new ValidatorService();
        $errors = $validatorService->getErrors($request, $rules);
        return [
            'status' => false,
            'message' => 'Validator Error',
            'data' => null,
            'errors' => $errors
        ];
    }

    /**
     * Format response on catch error
     *
     * @param String $message
     * @param int $errorCode
     * @return response
     */
    public static function catchError($message, $errorCode)
    {
        $data = [
            'status' => false,
            'message' => $message,
            'data' => null,
            'errors' => null,
        ];
        return response()->json($data, $errorCode);
    }


}
