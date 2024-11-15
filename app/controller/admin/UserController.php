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
    public function getAllAction(int $page, int $size = 20)
    {
        $username = $this->request->get('username', '');
        $deleted = $this->request->get('deleted', -1);
        $userCount = $this->userBusiness->getAllUsers($username, $deleted, $page, true, $size);
        $userList = $this->userBusiness->getAllUsers($username, $deleted, $page, false, $size);

        $data = array();
        $data['page'] = $page;
        $data['size'] = $size;
        $data['total'] = $userCount;
        $data['total_page'] = ceil($userCount / $size);
        $data['list'] = $userList;
        return $this->listJson(0, '', $page, $size, $userCount, $userList);
        //return json($data);
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