<?php
/**
 * Created by PhpStorm.
 * User: raven
 * Date: 2017/12/21
 * Time: 下午3:58
 */

namespace Api\Controller;


class LoginController extends CommonController
{
    public function login()
    {
        $user = M('user')->where(['username|nickname' => $_POST['username']])->find();
        if (!$user) {
            $info = ['info' => '用户不存在', 'status' => 2];
        } elseif ($user['status'] != 1) {
            $info = ['info' => '用户被禁用', 'status' => 2];
        } elseif (getMD5($_POST['password']) != $user['password']) {
            $info = ['info' => '密码错误', 'status' => 2];
        } else {
            $this->updateUser($user['id']);
            $info = ['info' => '欢迎回来', 'status' => 1, 'url' => '/'];
        }
        session('User', $user);
        $this->ajaxReturn($info);
    }

    public function register()
    {
        if ($_POST) {
            $nickname = $this->info($_POST['nickname'], true, 'User', [], 'nickname');
            if ($nickname) {
                $this->errorResponse('昵称已存在');
            }
            $token = $this->info($_POST['token'], true, 'Config', ['name' => 'register'], 'value');
            if (!$token) {
                $this->errorResponse('Token 错误');
            }
            $_POST['password'] = getMD5($_POST['password']);
            $_POST['group_id'] = 2;
            $data              = $_POST;
            //列出 Public/head_img 目录中的文件和目录
            $head_img = scandir('Public/head_img');
            unset($head_img[0]);
            unset($head_img[1]);
            unset($head_img[2]);
            sort($head_img);

            $data['head_img'] = '/Public/head_img/' . $head_img[rand(0, count($head_img) - 1)];
            $res              = D('User')->update($data);
            if ($res) {
                $this->successResponse(['url' => '/'], 'Register Success !');
            } else {
                $this->errorResponse(D('User')->getError());
            }
        }
    }

    /**
     * 获取用户登录IP
     */
    public function getIp()
    {
        if (getenv("HTTP_CLIENT_IP")) {
            $ip = getenv("HTTP_CLIENT_IP");
        } elseif (getenv("HTTP_X_FORWARDED_FOR")) {
            $ip = getenv("HTTP_X_FORWARDED_FOR");
        } elseif (getenv("REMOTE_ADDR")) {
            $ip = getenv("REMOTE_ADDR");
        } else {
            $ip = 'UnKnow';
        }
        return $ip;
    }

    /**
     * @param $id
     */

    public function updateUser($id)
    {
        //获取用户IP地址
        $data['login_ip']   = $this->getIp();
        $data['login_time'] = time();
        D('User')->where(['id' => $id])->save($data);
    }

    /**
     * 退出操作
     */
    public function loginOut()
    {
        $user = session('User');
        $this->updateUser($user['id']);
        session('User', null);
        return redirect('/login');
    }
}