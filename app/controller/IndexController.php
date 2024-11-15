<?php

namespace app\controller;

use app\BaseController;

class IndexController extends BaseController
{
    public function indexAction()
    {
        $str = 'hello world';
        return $str;
        //return '<style>*{ padding: 0; margin: 0; }</style><iframe src="https://www.thinkphp.cn/welcome?version=' . \think\facade\App::version() . '" width="100%" height="100%" frameborder="0" scrolling="auto"></iframe>';
    }

    public function loginAction() {
        $data = array();
        $data['username'] = 'koboshi';
        $data['token'] = 'dwadjioawjdioawd';
        return json($data);
    }

    public function hello($name = 'ThinkPHP6') {
        return $name;
    }

    public function list($name)
    {
        return 'Hello,' . $name . '！This is '. $this->request->action();
    }

    public function world()
    {
        return 'world';
    }

    public function like()
    {
        return 'wow';
    }
}
