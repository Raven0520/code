<?php

namespace Api\Model;

use Think\Model\RelationModel;

/**
 * Created by PhpStorm.
 * User: raven
 * Date: 2017/12/21
 * Time: 下午4:46
 */
class CommonModel extends RelationModel
{
    protected $_data = array();

    protected $_auto = array(
        array('create_time', NOW_TIME, 1),
        array('update_time', NOW_TIME, 2)
    );

    public function update($data = null)
    {
        $this->_data = $data;

        $data = empty($this->_data) ? $_POST : $this->_data;
        if ($data['skipping_link']) {
            unset($data['skipping_link']);
        }
        '自动编号' == $data['id'] && $data['id'] = '';
        $data = $this->create($data);
        if (!$data) {
            return false;
        }
        if (empty ($data [$this->getPk()])) {
            $data [$this->getPk()] = $this->add();
        } else {
            $this->save();
        }
        return $data [$this->getPk()];
    }
}