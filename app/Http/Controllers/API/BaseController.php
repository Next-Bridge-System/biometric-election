<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BaseController extends Controller
{
    public function sendResponse($result, $message)
    {
        $response = [
            'success' => true,
            'status_code' => 200,
            'message' => $message,
            'data'    => $result,
        ];

        return response()->json($response, 200);
    }

    public function sendError($message, $code = 200)
    {
        if (is_array($message)) {
            $errors = array_values($message);
            $response = [
                'success' => false,
                'status_code' => 400,
                'message' => $errors[0][0],
                'data' => null,
            ];
        } else if (is_a($message, 'Illuminate\Support\MessageBag')) {
            $errors = array_values($message->toArray());
            $response = [
                'success' => false,
                'status_code' => 400,
                'message' => $errors[0][0],
                'data' => null,
            ];
        } else {
            $response = [
                'success' => false,
                'status_code' => 400,
                'message' => $message,
                'data' => null,
            ];
        }

        return response()->json($response, $code);
    }
}
