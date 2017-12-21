<?php
/**
 * Created by PhpStorm.
 * User: raven
 * Date: 2017/12/21
 * Time: 下午2:10
 */

namespace Home\Controller;

use Think\Controller;

class EmptyController extends Controller
{
    protected function _initialize()
    {

    }

    public function _empty()
    {
        $this->display();
    }
}