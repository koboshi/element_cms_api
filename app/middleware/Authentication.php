<?php

namespace app\middleware;

use app\base\Request;
use app\business\admin\AuthBusiness;

class Authentication
{
    protected $ignoreAuth = array(
        'admin.auth' => array(
            'login' => true
        ),
    );

    public function handle(Request $request, \Closure $next)
    {
        //非验证的直接跳过，例如登录入口
        $controllerName = $request->controller(true);
        $actionName = $request->action(true);
        if (isset($this->ignoreAuth[$controllerName][$actionName])) {
            if (isset($this->ignoreAuth[$controllerName][$actionName])) {
                return $next($request);
            }
        }

        $authBusiness = invoke(AuthBusiness::class);
        $uid = $request->get('uid', 0);
        $ticket = $request->get('ticket', '');
        $flag = $authBusiness->verify($uid, $ticket);
        if (!$flag) {
            $data = array();
            $data['error_code'] = 1;
            $data['error_message'] = '无效凭据';
            $data['data'] = array();
            return json($data);
        }
        return $next($request);
    }
}