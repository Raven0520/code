<?php
/**
 * Created by PhpStorm.
 * User: raven
 * Date: 2017/12/22
 * Time: 下午11:10
 */

namespace Api\Model;


class ControllerModel extends CommonModel
{
    public function _after_select(&$result, $options)
    {
        $module = M('module')->getField('id,name');
        foreach ($result as $k => $v) {
            $result[$k]['module_name'] = $module[$v['module_id']];
        }
    }
}