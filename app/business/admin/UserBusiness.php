<?php

namespace app\business\admin;

use app\base\BaseBusiness;
use app\model\admin\UserModel;

/**
 * 后台用户业务逻辑
 * @author koboshi
 */
class UserBusiness extends BaseBusiness
{
    private string $salt = 'koboshi';

    protected UserModel $userModel;

    public function __construct(UserModel $usrModel)
    {
        $this->userModel = $usrModel;
        $this->salt = env('APP_SALT', 'default');
    }

    private function buildAllUsersQuery(string $username, int $deleted)
    {
        $queryObj = $this->userModel;

        $username = trim($username);
        if (!empty($username)) {
            $queryObj = $this->userModel->where('username', 'like', $username);
        }
        if ($deleted !== -1) {
            $queryObj->where('deleted', $deleted);
        }
        return $queryObj;
    }


    /**
     * @param int $page
     * @param int $size
     * @param string $username
     * @param int $deleted
     * @return array
     */
    public function getAllUsers(int $page, int $size, string $username, int $deleted)
    {
        $userList = $this->buildAllUsersQuery($username, $deleted)->page($page, $size)->column(array('admin_id',
            'role_id', 'name', 'editor', 'deleted', 'add_time', 'edit_time', 'delete_time'));
        $userCount = $this->buildAllUsersQuery($username, $deleted)->count();

        return ['count' => $userCount, 'list' => $userList];
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
        $userInfo = $this->userModel->where('admin_id', $uid)->limit(1)
            ->column(array('admin_id', 'role_id', 'name', 'editor', 'deleted', 'add_time', 'edit_time', 'delete_time'));
        if (empty($userInfo)) {
            return array();
        }
        return $userInfo[0];
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
        $userInfo = $this->userModel->where('deleted', 0)->where('name', $username)
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
        $data['expire_time'] = EMPTY_DATETIME;
        $data['status'] = 1;
        $data['editor'] = trim($editor);
        $data['deleted'] = 0;
        $data['add_time'] = $dateTime;
        $data['edit_time'] = $dateTime;
        $data['deleted_time'] = EMPTY_DATETIME;
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

        return $this->userModel-where('admin_id', $uid)->limit(1)->save($data);
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

        return $this->userModel-where('admin_id', $uid)->limit(1)->save($data);
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
        $data['expire_time'] = EMPTY_DATETIME;
        $data['edit_time'] = $dateTime;
        $data['password'] = $newPasswordHash;
        return $this->userModel->where('admin_id', $uid)->where('deleted', 0)
            ->where('password', $oldPasswordHash)->limit(1)->save($data);
    }
}