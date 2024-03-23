<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;

class BaseApiController extends BaseController
{
    /**
     * Sends a pre-formatted response.
     * @param  string  $message
     * @param  integer $status  HTTP Status Code
     * @param  array   $data
     * @return Illuminate\Http\Response
     */
    public function sendResponse($message = 'ok', $status = 200, $data = [])
    {
        $reply = [
            'message' => $message,
            'status_code' => $status,
        ];

        if (!empty($data)) {
            $reply['data'] = $data;
        }

        return response($reply, $status)
            ->header('Content-Type', 'application/json');
    }

    /**
     * Alias method for sending back an error status.
     * @param  string  $message
     * @param  integer $status  HTTP Status Code
     * @return Illuminate\Http\Response
      */
    public function sendError($data, $message = 'System Error', $status = 500)
    {
        return $this->sendResponse($message, $status, $data);
    }

    /**
     * Alias method for sending back an ok status.
     * @param  string  $message
     * @param  integer $status  HTTP Status Code
     * @return Illuminate\Http\Response
     */
    public function sendOK($data, $message = 'OK', $status = 200)
    {
        return $this->sendResponse($message, $status, $data);
    }


    public function canSendVerbose(Request $request = null)
    {
        if (is_null($request)) {
            $request = request();
        }

        if (!$request->get('verbose', false)) {
            return false;
        }

        // if (!auth('api')->user()->can('core.debug')) {
        //     return false;
        // }

        return true;
    }
}
