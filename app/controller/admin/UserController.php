<?php

namespace app\controller\admin;

use app\base\BaseController;
use app\business\admin\UserBusiness;
use think\response\Json;

class UserController extends BaseController
{
    protected UserBusiness $userBusiness;

    protected function initialize()
    {
        parent::initialize();
        $this->userBusiness = invoke(UserBusiness::class);
    }

    /**
     * 获取所有用户
     * @return Json
     */
    public function listAction(int $page, int $size = 20)
    {
        $username = $this->request->get('username', '');
        $deleted = $this->request->get('deleted', -1);
        $userRes = $this->userBusiness->getAllUsers($page, $size, $username, $deleted);

        return $this->listJson(0, '', $page, $size, $userRes['count'], $userRes['list']);
    }

    /**
     * 新增用户
     * @return void
     */
    public function addAction()
    {
        //TODO
    }

    /**
     * 编辑用户
     * @return void
     */
    public function editAction()
    {
        //TODO
    }

    /**
     * 删除用户
     * @return void
     */
    public function delAction()
    {
        //TODO
    }
}