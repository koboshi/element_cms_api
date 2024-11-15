<?php

namespace app\business\admin;

use app\model\admin\UserModel;

/**
 * 后台用户业务逻辑
 * @author koboshi
 */
class UserBusiness
{
    private string $salt = 'koboshi';

    protected UserModel $usrModel;

    public function __construct(UserModel $usrModel)
    {
        $this->usrModel = $usrModel;
    }

    public function getAllUsers()
    {

    }

    public function getUserInfo(int $uid)
    {
        if ($uid < 1) {
            return array();
        }
        $userInfo = $this->usrModel->where('uid', $uid)->limit(1)
            ->column(array('uid', 'role_id', 'name', 'editor', 'deleted', 'add_time', 'edit_time', 'delete_time'))
            ->findOrEmpty();
        if (empty($userInfo)) {
            return array();
        }
        return $userInfo;
    }

    private function genPassHash(string $password)
    {
        return md5(strtoupper($password) . strtolower($password) . $this->salt);
    }

    /**
     * @param int $roleId
     * @param string $name
     * @param string $password
     * @param string $editor
     * @return int
     */
    public function createUser(int $roleId, string $name, string $password, string $editor)
    {
        $dateTime = date("Y-m-d H:i:s");
        $data = array();
        $data['role_id'] = $roleId;
        $data['name'] = trim($name);
        $data['password'] = $this->genPassHash($password);
        $data['ticket'] = '';
        $data['expire_time'] = '0000-00-00 00:00:00';
        $data['status'] = 1;
        $data['editor'] = trim($editor);
        $data['deleted'] = 0;
        $data['add_time'] = $dateTime;
        $data['edit_time'] = $dateTime;
        $data['deleted_time'] = '0000-00-00 00:00:00';
        $flag = $this->usrModel->save($data);
        if ($flag) {
            return $this->menuModel->getKey();
        }
        return 0;
    }

    public function banUser(int $uid)
    {

    }

    public function unbanUser(int $uid)
    {

    }

    public function changePassword(int $uid, string $oldPassword, $newPassword)
    {

    }
}