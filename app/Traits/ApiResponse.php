<?php

namespace App\Traits;

use App\Enums\HTTPHeader;

trait ApiResponse
{
    protected function success($message = '', $data = [], $status = HTTPHeader::SUCCESS): \Illuminate\Http\JsonResponse
    {
        $res = new \stdClass();
        $res->message = $message;
        $res->data = $data;
        return response()->json($res, $status);
    }

    /** easier to notice if it is a success or failure in the controllers */

    protected function failure($message = '', $status = HTTPHeader::BAD_REQUEST): \Illuminate\Http\JsonResponse
    {
        $res = new \stdClass();
        $res->message = $message;
        return response()->json($res, $status);
    }

    protected function abort($message = '', $status = HTTPHeader::BAD_REQUEST)
    {
        abort($status, $message);
    }
}
