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

    protected UserModel $userModel;

    public function __construct(UserModel $usrModel)
    {
        $this->userModel = $usrModel;
    }


    /**
     * @param string $username
     * @param int $deleted
     * @param int $page
     * @param $countOnly
     * @param int $size
     * @return UserModel[]|array|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getAllUsers(string $username, int $deleted, int $page, $countOnly = false, int $size = 20)
    {
        $username = trim($username);
        $queryObj = $this->userModel->column(array('admin_id', 'role_id', 'name', 'editor', 'deleted', 'add_time',
            'edit_time', 'delete_time'));
        if (!empty($username)) {
            $queryObj = $this->userModel->where('username', 'like', $username);
        }
        if ($deleted !== -1) {
            $queryObj->where('deleted', $deleted);
        }
        if ($countOnly) {
            $queryObj->count();
        }
        return $queryObj->page($page, $size)->select();
    }

    /**
     * @param int $uid
     * @return array
     */
    public function getUserInfo(int $uid)
    {
        if ($uid < 1) {
            return array();
        }
        $userInfo = $this->userModel->where('uid', $uid)->limit(1)
            ->column(array('uid', 'role_id', 'name', 'editor', 'deleted', 'add_time', 'edit_time', 'delete_time'))
            ->findOrEmpty();
        if (empty($userInfo)) {
            return array();
        }
        return $userInfo;
    }

    protected function genPassHash(string $password)
    {
        return md5(strtoupper($password) . strtolower($password) . $this->salt);
    }

    /**
     * @param string $username
     * @param string $password
     * @return UserModel|array|mixed|\think\Model|null
     */
    public function chkUserPass(string $username, string $password)
    {
        $username = trim($username);
        $password = trim($password);
        if  (empty($username) || empty($password)) {
            return null;
        }
        $passwordHash = $this->genPassHash($password);
        $userInfo = $this->userModel->where('deleted', 0)->where('username', $username)
            ->where('password', $passwordHash)->limit(1)->findOrEmpty();
        if (empty($userInfo)) {
            return null;
        }
        return $userInfo;
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
        $data['password'] = $this->genPassHash($password);//只存密码的hash
        $data['ticket'] = '';
        $data['expire_time'] = '0000-00-00 00:00:00';
        $data['status'] = 1;
        $data['editor'] = trim($editor);
        $data['deleted'] = 0;
        $data['add_time'] = $dateTime;
        $data['edit_time'] = $dateTime;
        $data['deleted_time'] = '0000-00-00 00:00:00';
        $flag = $this->userModel->save($data);
        if ($flag) {
            return $this->menuModel->getKey();
        }
        return 0;
    }

    /**
     * 封闭用户
     * @param int $uid
     * @return UserModel
     */
    public function banUser(int $uid)
    {
        if ($uid < 1) {
            return null;
        }
        //软删除
        $dateTime = date("Y-m-d H:i:s");
        $data = array();
        $data['ticket'] = '';
        $data['expire_time'] = '0000-00-00 00:00:00';
        $data['deleted'] = 1;
        $data['edit_time'] = $dateTime;
        $data['delete_time'] = $dateTime;

        return $this->userModel-where('uid', $uid)->limit(1)->save($data);
    }

    /**
     * 激活用户
     * @param int $uid
     * @return UserModel
     */
    public function permitUser(int $uid)
    {
        if ($uid < 1) {
            return null;
        }
        //软删除
        $dateTime = date("Y-m-d H:i:s");
        $data = array();
        $data['ticket'] = '';
        $data['expire_time'] = '0000-00-00 00:00:00';
        $data['deleted'] = 0;
        $data['edit_time'] = $dateTime;

        return $this->userModel-where('uid', $uid)->limit(1)->save($data);
    }

    /**
     * 变更用户密码
     * @param int $uid
     * @param string $oldPassword
     * @param $newPassword
     * @return bool|null
     */
    public function changePassword(int $uid, string $oldPassword, $newPassword)
    {
        if ($uid < 1) {
            return null;
        }
        $oldPasswordHash = $this->genPassHash($oldPassword);
        $newPasswordHash = $this->genPassHash($newPassword);

        $dateTime = date("Y-m-d H:i:s");
        $data = array();
        $data['ticket'] = '';
        $data['expire_time'] = '0000-00-00 00:00:00';
        $data['edit_time'] = $dateTime;
        $data['password'] = $newPasswordHash;
        return $this->userModel->where('uid', $uid)->where('deleted', 0)
            ->where('password', $oldPasswordHash)->limit(1)->save($data);
    }
}