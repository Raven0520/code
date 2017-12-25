<?php
/**
 * Created by PhpStorm.
 * User: raven
 * Date: 2017/12/22
 * Time: 下午11:11
 */

namespace Api\Model;


class FunctionModel extends CommonModel
{
    public function _after_insert($data, $options)
    {
        M('project')->where(['id' => $data['project_id']])->setInc('function', 1);
    }

    public function _after_select(&$result, $options)
    {
        $type       = C('FUNCTION_TYPE');
        $module     = M('module')->getField('id,name');
        $folder     = M('folder')->getField('id,name');
        $controller = M('controller')->getField('id,name,folder_id');

        foreach ($result as $k => $v) {
            $result[$k]['type_name'] = $type[$v['type']];
            $v['module_id'] && $result[$k]['module_name'] = $module[$v['module_id']];
            $result[$k]['controller_name'] = $controller[$v['controller_id']]['name'];
            $result[$k]['path']            = '/' . $module[$v['module_id']] . '/' . $folder[$controller[$v['controller_id']]['folder_id']] . '/' . $controller[$v['controller_id']]['name'] . '/' . $type[$v['type']] . ' function ' . $v['name'];
        }
    }

    public function _after_find(&$result, $options)
    {
        $type       = C('FUNCTION_TYPE');
        $module     = M('module')->where(['id' => $result['module_id']])->find();
        $controller = M('controller')->where(['id' => $result['controller_id']])->find();
        $folder     = M('folder')->where(['id' => $controller['folder_id']])->find();
        if ($result['used']) {

        } else {
            $result['used_path'] = '<span><strong>None</strong></span>';
        }
        if ($result['function']) {

        } else {
            $result['function_path'] = '<span><strong>None</strong></span>';
        }
        if ($result['javascript']) {
        } else {
            $result['javascript_path'] = '<span><strong>None</strong></span>';
        }
        $result['path'] = '/' . $module['name'] . '/' . $folder['name'] . '/' . $controller['name'] . '/' . $type[$result['type']] . ' function ' . $result['name'];
    }
}