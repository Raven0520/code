<?php
/**
 * Created by PhpStorm.
 * User: raven
 * Date: 2017/12/21
 * Time: 下午1:59
 */

namespace Api\Controller;

use Think\Cache\Driver\Redis;
use Think\Controller;

class CommonController extends Controller
{
    // 主键
    protected $pk = '';
    protected $pages = [];
    protected $model = CONTROLLER_NAME;
    protected $where = [];
    protected $field = true;
    protected $order = '';
    protected $redis = '';
    protected $startTrans = false;

    /**
     * 初始化方法
     */
    protected function _initialize()
    {
        header("Access-Control-Allow-Origin: *");
    }

    /**
     * 查询单条数据
     * @param $id
     * @param bool $field
     * @param mixed|string $model
     * @param null $name
     * @return mixed
     */
    protected function info($id, $field = true, $model = '', $where = array(), $name = null)
    {
        !$model && $model = $this->model;
        $pk  = M($model)->getPk();
        $map = array();
        if ($id && !$name) {
            $map[$pk] = $id;
        } else {
            $map[$name] = $id;
        }
        $info = D($model)->field($field)->where($map)->where($where)->find();
        if (is_string($field) && 1 == count(explode(',', $field))) {
            return $info[$field];
        }
        return $info;
    }

    /**
     * 分页列表数据
     * @param $model
     * @param array $where
     * @param string $order
     * @param bool $field
     * @return mixed
     */
    protected function lists($model = CONTROLLER_NAME, $where = array('status' => array('egt', 0)), $field = true, $order = '', $pages = 20)
    {
        $options = array();
        $REQUEST = (array)I('request.');
        if (is_string($model)) {
            $model = D($model);
        }
        $OPT = new \ReflectionProperty($model, 'options');
        $OPT->setAccessible(true);  //设置访问权限

        $pk = $model->getPk();
        if (null == $order) {
        } elseif (isset($REQUEST['_order']) && isset($REQUEST['_field']) && in_array(strtolower($REQUEST['_order']), array('desc', 'asc'))) {
            $options['order'] = '`' . $REQUEST['_field'] . '` ' . $REQUEST['_order'];
        } elseif ($order === '' && empty($options['order']) && !empty($pk)) {
            $options['order'] = $pk . ' desc';
        } elseif ($order) {
            $options['order'] = $order;
        }
        unset($REQUEST['_order'], $REQUEST['_field']);

        if (!empty($where)) {
            $options['where'] = $where;
        }
        $options = array_merge((array)$OPT->getValue($model), $options);
        $model->setProperty('options', $options);  //设置模型的属性值
        if (IS_POST && $pages) {
            $pages = $this->pages;
            if ($pages['rows']) {
                $listRows = $pages['rows'];
            } else {
                $listRows = C('LIST_ROWS') > 0 ? C('LIST_ROWS') : 20;
            }
            $total            = $model->where($options['where'])->count();
            $options['limit'] = ($pages['page'] > 0 ? $listRows * ($pages['page'] - 1) : 0) . ',' . $listRows;
            $model->setProperty('options', $options);
            $data['total'] = $total;
            $data['rows']  = $model->field($field)->relation(true)->select();
            return $data;
        } else if ($pages) {
            $total = $model->where($options['where'])->count();
            if (isset($REQUEST['r'])) {
                $listRows = (int)$REQUEST['r'];
            } else {
                $listRows = C('LIST_ROWS') > 0 ? C('LIST_ROWS') : $pages;
            }
            $page = new \Think\Page($total, $listRows);
            if ($total > $listRows) {
                $page->setConfig('theme', '%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
            }
            $p = $page->show();
            $this->assign('_page', $p ? $p : '');
            $this->assign('_total', $total);
            $options['limit'] = $page->firstRow . ',' . $page->listRows;
            $model->setProperty('options', $options);
        }
        return $model->field($field)->relation(true)->select();
    }

    /**
     * 成功的返回函数
     */
    protected function successResponse($data = '', $message = '请求成功')
    {
        $this->startTrans === true && M()->commit();
        $info = ['code' => 200, 'message' => $message];
        if ($data) {
            foreach ($data as $k => $v) {
                $info[$k] = $v;
            }
        }
        $this->ajaxReturn($info);
    }

    protected function startTrans()
    {
        $this->startTrans = true;
        M()->startTrans();
    }

    /**
     * 失败的返回函数
     */
    protected function errorResponse($message = '')
    {
        $this->startTrans === true && M()->rollback();
        $info = ['code' => 400, 'message' => $message];
        $this->ajaxReturn($info);
    }

    /**
     * 列表数据，不带分页
     * @param mixed|string $model
     * @param array $where
     * @param string $order
     * @param bool $field
     * @return mixed
     */
    protected function select($model = CONTROLLER_NAME, $where = array(), $field = true, $order = '')
    {
        return $this->lists($model, $where, $field, $order, false);
    }

}