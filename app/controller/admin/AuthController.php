<?php

namespace app\controller\admin;

class AuthController
{
    /**
     * 验证用户名密码，返回uid和ticket
     * @param string $username
     * @param string $password
     * @return void
     */
    public function loginAction(string $username, string $password)
    {
        //todo
    }

    /**
     * 变更密码, 重置ticket
     * @param int $uid
     * @param string $ticket
     * @param string $oldPassword
     * @param string $newPassword
     * @return void
     */
    public function changePasswordAction(int $uid, string $ticket, string $oldPassword, string $newPassword)
    {
        //todo
    }

    /**
     * 验证ticket
     * @param int $uid
     * @param string $ticket
     * @return void
     */
    public function authAction(int $uid, string $ticket)
    {
        //todo 应该作为公共前置方法实现
    }
}