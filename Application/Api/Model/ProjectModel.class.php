<?php
/**
 * Created by PhpStorm.
 * User: raven
 * Date: 2017/12/22
 * Time: 下午5:54
 */

namespace Api\Model;


class ProjectModel extends CommonModel
{
    public function _after_select(&$result, $options)
    {
        foreach ($result as $k => $v) {
            $result[$k]['update_time'] = $v['update_time'] ? date('Y-m-d H:i', $v['update_time']) : date('Y-m-d H:i', $v['create_time']);
        }
    }
}