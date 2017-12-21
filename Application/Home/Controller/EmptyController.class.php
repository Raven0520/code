<?php
/**
 * Created by PhpStorm.
 * User: raven
 * Date: 2017/12/21
 * Time: 下午2:10
 */

namespace Home\Controller;

use Think\Auth;
use Think\Controller;

class EmptyController extends Controller
{
    protected $user = [];
    protected $auth = '';

    protected function _initialize()
    {
        $this->user = S('User_' . getIp());
//        $this->checkAuth();
    }

    public function _empty()
    {
        $this->display();
    }

    /**
     * 权限验证的方法
     */
    public function checkAuth()
    {
        //判断用户是否有权限
        $auth       = new Auth();
        $action     = ACTION_NAME;
        $controller = CONTROLLER_NAME;

        $this->auth = $auth->check('/' . $controller . '/' . $action, $this->user['id']);
        'Index' == $controller && $action == 'index' && $this->auth = true;
        'Blog' == $controller && $action == 'index' && $this->auth = true;

        if ($this->auth == false) {
            if (empty($this->user)) {
                redirect('/login');
            }
            $this->assign('auth_status', 401);
            IS_AJAX && $this->ajaxReturn(['code' => 401]);
        }
    }
}