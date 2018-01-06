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

	/**
	 * 获取某个menu下的所有rule
	 */
	public function getRules($id)
	{
		$this->where['status'] = 1;

		$rules = $this->select();

		$info = array();
		foreach ($rules as $k => $v) {
			$v['sort_id'] == $id && $info['sec'][$k] = $v['id'];
		}
		foreach ($rules as $k => $v) {
			true == in_array($v['sort_id'], $info['sec']) && $info['oth'][$k] = $v['id'];
		}
		$this->ajaxReturn($info);
	}
}