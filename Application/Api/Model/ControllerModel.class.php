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
        $folder = M('folder')->getField('id,name');
        foreach ($result as $k => $v) {
            $result[$k]['module_name'] = $module[$v['module_id']];
            $result[$k]['folder_name'] = $folder[$v['folder_id']];
        }
    }

    public function _after_insert($data, $options)
    {
        M('project')->where(['id' => $data['project_id']])->setInc('controller', 1);
    }
}