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
    protected $_validate = [
        ['name','','控制器名称已经存在！',0,'unique',3]    // 新增或更新的时候验证name字段是否唯一
    ];
}