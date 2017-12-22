<?php
/**
 * Created by PhpStorm.
 * User: raven
 * Date: 2017/12/21
 * Time: 下午2:10
 */

namespace Api\Controller;

use Think\Auth;


class EmptyController extends CommonController
{
    protected $user = [];
    protected $auth = '';

    protected function _initialize()
    {
        parent::_initialize();
        $this->user = S('User_' . getIp());
        '' != I('post.status') && $this->where['status'] = I('post.status');
        $this->checkAuth();
    }

    /**
     * 首页获取数据
     * @param string $field
     * @param string $like
     * @param string $in_
     * @param bool $fields
     * @param string $order
     */
    public function index($field = '', $like = '', $in_ = '', $fields = true, $order = '')
    {
        if (IS_POST) {
            if ($field && $like) {
                $this->where[$field] = ['like', '%' . $like . '%'];
            }
            if ($field && $in_) {
                $this->where[$field] = ['in', $in_];
            }
            $this->successResponse(['list' => $this->lists(CONTROLLER_NAME, $this->where, $fields, $order)]);
        }
    }

    /**
     * 信息新增或修改操作
     */
    public function add()
    {
        if (IS_POST) {
            $url = U('/' . CONTROLLER_NAME);
            if (I('skipping_link')) {
                $url = I('skipping_link');
            }
            $model = D(CONTROLLER_NAME);
            $this->startTrans();
            $id = $model->update();
            if (false != $id) {
                $this->successResponse(['id' => $id, 'url' => $url], 'Success');
            } else {
                $this->errorResponse($model->getError());
            }
        }
    }

    /**
     * 编辑操作
     * @param int $id
     */
    public function edit($id = 0)
    {
        $this->successResponse(['info' => $this->info($id)]);
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
            $this->assign('auth_status', 9);
            IS_AJAX && $this->ajaxReturn(['code' => 401]);
        }
    }
}