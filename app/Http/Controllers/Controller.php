<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Response;
use InfyOm\Generator\Utils\ResponseUtil;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public function __construct(Request $request)
    {
        $lang = $request->header('lang', 'ar');
        App::setLocale($lang);
    }



    public function status($statusCode){
        if( $statusCode >= 200 && $statusCode < 300)
            return true;
        else
            return false;
    }
    public function responseFormat($statusCode, $message, $data): \Illuminate\Http\Response
    {

        $content = [
            "status" => self::status($statusCode),
            "message" => $message,
            "data" => $data
        ];
//        return Response::json($content, $statusCode);
//        return response()->json($content, $statusCode);
        return response($content, $statusCode);
    }
}
