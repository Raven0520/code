<?php
/**
 * Created by PhpStorm.
 * User: raven
 * Date: 2017/12/25
 * Time: 下午4:09
 */

namespace Api\Controller;


class FunctionController extends EmptyController
{
    public function getFunctions($module_id)
    {
        $this->where['module_id'] = $module_id;

        $controller = $this->select('Controller', $this->where, 'id,name,module_id,folder_id', 'list_order asc');
        $function   = $this->select('Function', $this->where, 'id,name,controller_id,type', 'list_order asc');

        foreach ($controller as $k => $v) {
            foreach ($function as $key => $val) {
                $val['controller_id'] == $v['id'] && $controller[$k]['functions'][$key] = $val;
            }
            sort($controller[$k]['functions']);
        }
        $this->successResponse(['list' => $controller]);
    }
}