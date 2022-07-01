<?php

namespace App\Traits;

trait ApiTraits
{
    public function responseJson($status = 200, $message = "Successfully Done", $data)
    {
        return response()->json([
            "success" => true,
            "status" => $status,
            "message" => [$message],
            "data" => $data
        ], $status);
    }

    public function responseJsonPaginate($status = 200, $message = "Successfully Done", $data)
    {
        return response()->json([
            "success" => true,
            "status" => $status,
            "message" => [$message],
            "data" => $data,
        ], $status);
    }


    public function responseJsonWithoutData($status = 200 , $message = "Successfully Done")
    {
        return response()->json([
            "success" => true,
            "status" => $status,
            "message" => [$message],
        ], $status);
    }


    public function responseJsonFailed($status = 422 , $message = "Fail")
    {
        return response()->json([
            "success" => false,
            "status" => $status,
            "message" => [$message],
        ], $status);
    }

    public function responseValidationJsonFailed($message = "Fail")
    {
        return response()->json([
            "success" => false,
            "status" => 200,
            "message" => $message,
        ], 200);
    }

    public function returnValidationError($validator){
        return $this->responseValidationJsonFailed(011, $validator->errors());
    }
}
