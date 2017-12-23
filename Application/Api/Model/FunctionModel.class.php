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
        $type = C('FUNCTION_TYPE');
        foreach ($result as $k => $v) {
            $result[$k]['type_name'] = $type[$v['type']];
        }
    }
}