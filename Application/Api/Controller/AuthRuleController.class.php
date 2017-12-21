<?php
/**
 * Created by PhpStorm.
 * User: raven
 * Date: 2017/4/20
 * Time: 下午3:29
 */

namespace Api\Controller;


class AuthRuleController extends EmptyController
{
    //注册顶级菜单
    /*public function index()
    {

        $this->where['status'] = 1;

        $menu = $this->select(CONTROLLER_NAME, $this->where, true, 'list_order');
        $menu = json_encode($menu);

        $this->where['menu_type'] = ['in', [0, 1, 2]];

        $icon = $this->select(CONTROLLER_NAME, $this->where, 'icon,title', 'list_order');
        $icon = json_encode($icon);
        $sort = $this->select(CONTROLLER_NAME, $this->where);

        $this->assign('icon', $icon);
        $this->assign('menu', $menu);
        $this->assign('sort', $sort);
        $this->display();
    }*/

    /**
     * 获取某个menu下的所有rule
     */
    public function getRules($id)
    {
        $this->where['status'] = 1;

        $rules = $this->getList();

        $info = [];
        foreach ($rules as $k => $v) {
            $v['sort_id'] == $id && $info['sec'][$k] = $v['id'];
        }
        foreach ($rules as $k => $v) {
            true == in_array($v['sort_id'], $info['sec']) && $info['oth'][$k] = $v['id'];
        }
        $this->ajaxReturn($info);
    }
}