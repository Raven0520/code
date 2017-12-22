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
     * 获取菜单
     * @param $type
     */
    public function getMenu($type = 0)
    {
        //获取菜单
        $where = ['status' => 1];
        if ($type == 1) {
            $group       = M('auth_group')->where(['id' => $this->user['group_id']])->getField('rules');
            $group       = explode(',', $group);
            $where['id'] = ['in', $group];
        }
        $rule = $this->select('AuthRule', $where, true, 'list_order asc');
        $menu = [];
        foreach ($rule as $k => $v) {
            $second = [];
            if ($v['sort_id'] == '0') {
                $menu[$k] = $v;
                foreach ($rule as $value) {
                    if ($value['sort_id'] == $v['id']) {
                        $value['title'] == CONTROLLER_NAME && $menu[$k]['class'] = 'active';
                        $second[$value['id']] = $value;
                        $third  = [];
                        foreach ($rule as $item) {
                            if ($item['sort_id'] == $value['id']) {
                                $third[] = $item;
                            }
                        }
                        $second[$value['id']]['third'] = $third;
                        sort($menu[$k]['second'][$value['id']]['third']);
                    }
                }
                $menu[$k]['second'] = $second;
                sort($menu[$k]['second']);
            }
        }
        sort($menu);
        $this->successResponse(['menu' => $menu]);
    }

    /**
     * 获取某个menu下的所有rule
     */
    public function getRules($id)
    {
        $this->where['status'] = 1;

        $rules = $this->select();

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