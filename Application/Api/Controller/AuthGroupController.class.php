<?php
/**
 * Created by PhpStorm.
 * User: raven
 * Date: 2017/5/25
 * Time: 下午1:28
 */

namespace Api\Controller;


class AuthGroupController extends EmptyController
{
    /*public function index()
    {
        $menu = $this->select('AuthRule',['sort_id'=>0,'status'=>1],'id,title,icon,name','list_order');
        foreach ($menu as $k => $v){
            $second = $this->select('AuthRule',['sort_id'=>$v['id'],'status'=>1],'id,title,icon,name','list_order');
            foreach ($second as $key =>  $val){
                $other = $this->select('AuthRule',['sort_id' => $val['id'],'status' => 1],'id,title,icon,name','list_order');
                $other_name = 'oth'.$val['id'];
                $second[$key]['other'] = $other_name;
                $this->assign($other_name,$other);
            }
            $second_name = 'sec'.$v['id'];

            $menu[$k]['second'] = $second_name;
            $this->assign($second_name,$second);
        }
        $this->assign('menu',$menu);
        $this->assign('AuthGroup',$this->getList());
        $this->display();
    }*/
}