<?php
/**
 * Created by PhpStorm.
 * User: raven
 * Date: 2017/12/22
 * Time: 上午6:21
 */

namespace Home\Controller;

use Think\Controller;

class IndexController extends Controller
{
    public function index()
    {
        $ip   = getIp();
        $user = S('User_' . $ip);
        if (!$user) {
            redirect('/Login');
        } else {
            $this->display();
        }
    }
}