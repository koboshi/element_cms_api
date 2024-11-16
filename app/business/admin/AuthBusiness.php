<?php

namespace app\business\admin;

use app\base\BaseBusiness;
use app\model\admin\UserModel;

/**
 * 登录认证相关业务逻辑
 */
class AuthBusiness extends BaseBusiness
{
    private $key;

    private $userBusiness;

    private $userModel;

    public function __construct(UserBusiness $userBusiness, UserModel $userModel)
    {
        $this->userBusiness = $userBusiness;
        $this->userModel = $userModel;
        $this->key = env('APP_KEY', md5('default'));
    }

    protected function genTicket(int $uid)
    {
        $timeStamp = time();
        $random = uniqid();
        //生成ticket
        $data = "{$uid}|{$random}|{$timeStamp}";
        $ticket = aes_ecb_encrypt($data, $this->key);
        return base64_encode($ticket);
    }

    protected function decodeTicket(string $ticket)
    {
        $ticket = base64_decode($ticket);
        $data = aes_ecb_decrypt($ticket, $this->key);
        return explode("|", $data);
    }

    /**
     * 用户登录，成功则返回ticket
     * @param string $username
     * @param string $password
     * @return array|null
     */
    public function login(string $username, string $password)
    {
        //校验用户名和密码
        $userInfo = $this->userBusiness->chkUserPass($username, $password);
        if (empty($userInfo)) {
            //登录失败
            return null;
        }
        if ($userInfo->isEmpty()) {
            return null;
        }
        $ticket = $this->genTicket($userInfo['admin_id']);
        if (empty($ticket)) {
            return null;
        }
        //存储ticket
        $dateTime = date("Y-m-d H:i:s", time() + 86400);//有效期一天
        $data = array();
        $data['ticket'] = $ticket;
        $data['expire_time'] = $dateTime;
        $this->userModel->where('admin_id', $userInfo['admin_id'])->save($data);

        return array('uid' => $userInfo['admin_id'], 'ticket' => $ticket, 'username' => $userInfo['name']);
    }

    /**
     * 验证用户登录皮凭据
     * @param int $uid
     * @param string $ticket
     * @return bool
     */
    public function verify(int $uid, string $ticket)
    {
        if ($uid < 1) {
            return false;
        }
        //校验ticket
        $data = $this->decodeTicket($ticket);
        if (empty($data)) {
            return false;
        }
        if (!isset($data[0]) || intval($data[0]) !== $uid) {
            return false;
        }
        $ticketTimeStamp = intval($data[2]);
        $nowTimeStamp = time();
        if ($ticketTimeStamp > $nowTimeStamp) {
            return false;
        }
        //获取用户信息，进一步校验
        $userInfo = $this->userModel->where('admin_id', $uid)->where('deleted', 0)->limit(1)->findOrEmpty();
        if (empty($userInfo)) {
            return false;
        }
        if ($userInfo['ticket'] !== $ticket) {
            return false;
        }
        if (strtotime($userInfo['expire_time']) < $nowTimeStamp) {
            return false;
        }
        return true;
    }

    /**
     * 登出，清空用户ticket
     * @return bool
     */
    public function logout(int $uid, string $ticket)
    {
        //验证凭据
        $flag = $this->verify($uid, $ticket);
        if (!$flag) {
            return false;
        }

        //清空凭据
        $data = array();
        $data['ticket'] = '';
        $data['expire_time'] = EMPTY_DATETIME;
        return $this->userModel->where('admin_id', $uid)->save($data);
    }
}