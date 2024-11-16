<?php

namespace app\middleware;

//Access-Control-Allow-Origin
use app\base\Request;

class CrossDomain
{
    public function handle(Request $request, \Closure $next)
    {
        if ($request->isOptions()) {
            return response()->code(200)->header(array(
                'Access-Control-Allow-Origin' => '*',
                'Access-Control-Allow-Headers' => '*',
                'Access-Control-Allow-Methods' => 'GET, POST, PUT,DELETE,OPTIONS,PATCH',
                'Access-Control-Max-Age' => 86400 * 7,
                'Content-Length' => 0,
                'Content-Type' => 'text/plain; charset=utf-8'
            ));
        }
        $response = $next($request);
        $response->header(array(
            'Access-Control-Allow-Origin' => '*',
            //'Access-Control-Allow-Headers' => '*',
            //'Access-Control-Allow-Methods' => 'GET, POST, PUT,DELETE,OPTIONS,PATCH',
        ));
        return $response;
    }
}