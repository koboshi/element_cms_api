<?php

namespace app\controller;

use app\base\BaseController;

class IndexController extends BaseController
{
    public function indexAction()
    {
        $str = 'welcome';
        return $this->infoJson(99, '错误调用', array());
    }
}
