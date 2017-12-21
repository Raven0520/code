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
            $this->errorResponse('用户不存在');
        } elseif ($user['status'] != 1) {
            $this->errorResponse('用户被禁用');
        } elseif (getMD5($_POST['password']) != $user['password']) {
            $this->errorResponse('密码错误');
        } else {
            $this->updateUser($user);
            $this->successResponse(['url' => '/'], '欢迎回来');
        }
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
     * @param $user
     */

    public function updateUser($user)
    {
        //获取用户IP地址
        $data['login_ip']   = getIp();
        $data['login_time'] = time();
        S('User_' . $data['login_ip'], $user, 7200);
        D('User')->where(['id' => $user['id']])->save($data);
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