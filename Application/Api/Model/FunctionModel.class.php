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
            $result[$k]['path']            = '/' . $module[$v['module_id']] . '/' . $folder[$controller[$v['controller_id']]['folder_id']] . '/' . $controller[$v['controller_id']]['name'] . '/' . $type[$v['type']] . ' function <span style="color: #1ab394">' . $v['name'] . '</span>';
        }
    }

    public function _after_find(&$result, $options)
    {
        $type       = C('FUNCTION_TYPE');
        $module     = M('module')->where(['id' => $result['module_id']])->find();
        $controller = M('controller')->where(['id' => $result['controller_id']])->find();
        $folder     = M('folder')->where(['id' => $controller['folder_id']])->find();
        $function   = D('function')->select();
        if ($result['used']) {
            $path                = $this->getRelatedFunction($result['used'], $function);
            $result['used_path'] = $path;
        } else {
            $result['used_path'] = '<span><strong>None</strong></span>';
        }
        if ($result['function']) {
            $path                    = $this->getRelatedFunction($result['function'], $function);
            $result['function_path'] = $path;
        } else {
            $result['function_path'] = '<span><strong>None</strong></span>';
        }
        if ($result['javascript']) {
            $path                      = $this->getRelatedFunction($result['javascript'], $function);
            $result['javascript_path'] = $path;
        } else {
            $result['javascript_path'] = '<span><strong>None</strong></span>';
        }
        $result['path'] = '/' . $module['name'] . '/' . $folder['name'] . '/' . $controller['name'] . '/' . $type[$result['type']] . ' function <span style="color: #1ab394">' . $result['name'] . '</span>';
    }

    public function _after_update($data, $options)
    {
        $function = D('function')->select();
        if ($data['used']) {
            $this->setRelatedFunction($data['id'], $function);
        }
    }

    /**
     * 根据字符串 查找所需要的方法
     * @param $list
     * @param $function_
     * @return string
     */
    public function getRelatedFunction($list, $function_)
    {
        $function_list = explode(',', $list);
        $function      = [];
        foreach ($function_ as $k => $v) {
            $function[$v['id']] = $v;
        }
        $data = '';
        $i    = 0;
        foreach ($function_list as $k => $v) {
            if ($i == 0 || $v == '') {
                if ($v != '') {
                    $data .= '<p>' . $function[$v]['path'] . '</p>';
                    $i++;
                }
            } else {
                $data .= '<label class="col-sm-3 control-label"></label><p>' . $function[$v]['path'] . '</p>';
                $i++;
            }
        }
        return $data;
    }

    public function setRelatedFunction($id, $function_)
    {
        $function = [];
        foreach ($function_ as $k => $v) {
            $function[$v['id']] = $v;
        }
        $used_list     = explode(',', $_POST['used']);
        $used_list_old = explode(',', $_POST['used_old']);
        foreach ($used_list as $k => $v) {
            if (!in_array($v, $used_list_old)) {
                $function_list = explode(',', $function[$v]['function']);
                array_push($function_list, $id);
                $function[$v]['function'] = implode(',', $function_list);
                M('function')->where(['id' => $function[$v]['id']])->save(['function' => $function[$v]['function']]);
            }
        }
        foreach ($used_list_old as $k => $v) {
            if (!in_array($v, $used_list)) {
                $function_list = explode(',', $function[$v]['function']);
                foreach ($function_list as $key => $val) {
                    if ($id == $val) {
                        array_splice($function_list, $key, 1);
                    }
                }
                $function[$v]['function'] = implode(',', $function_list);
                M('function')->where(['id' => $function[$v]['id']])->save(['function' => $function[$v]['function']]);
            }
        }
    }
}