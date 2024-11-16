<?php

namespace app\controller\admin;

use app\base\BaseController;
use app\business\admin\AuthBusiness;
use app\business\admin\UserBusiness;
use think\response\Json;

class AuthController extends BaseController
{
    protected AuthBusiness $authBusiness;

    protected UserBusiness $userBusiness;

    protected function initialize()
    {
        parent::initialize();
        $this->authBusiness = invoke(AuthBusiness::class);
        $this->userBusiness = invoke(UserBusiness::class);
    }

    /**
     * 验证用户名密码，返回uid和ticket
     * @param string $username
     * @param string $password
     * @return Json
     */
    public function loginAction(string $username, string $password)
    {
        $loginInfo = $this->authBusiness->login($username, $password);
        if (empty($loginInfo)) {
            return $this->infoJson('1', '账号或密码错误', array());
        }
        return $this->infoJson('0', '', $loginInfo);
    }

    /**
     * @param int $uid
     * @param string $ticket
     * @return Json
     */
    public function logoutAction(int $uid, string $ticket)
    {
        $flag = $this->authBusiness->logout($uid, $ticket);
        if (!$flag) {
            return $this->infoJson('1', '登出失败，凭据异常', array());
        }
        return $this->infoJson('0', '', array());
    }

    /**
     * 变更密码, 重置ticket
     * @param int $uid
     * @param string $ticket
     * @param string $oldPassword
     * @param string $newPassword
     * @return Json
     */
    public function changePasswordAction(int $uid, string $ticket, string $oldPassword, string $newPassword)
    {
        $flag = $this->authBusiness->verify($uid, $ticket);
        if (!$flag) {
            return $this->infoJson('1', '修改失败，凭据异常', array());
        }
        $flag = $this->userBusiness->changePassword($uid, $oldPassword, $newPassword);
        if (!$flag) {
            return $this->infoJson('2', '修改失败，旧密码错误', array());
        }
        return $this->infoJson('0', '', array());
    }
}