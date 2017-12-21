<?php
/**
 * Created by PhpStorm.
 * User: raven
 * Date: 2017/12/21
 * Time: 下午4:17
 */

namespace Api\Controller;


class ConfigController extends EmptyController
{
    /**
     * 更新注册token
     */
    public function getToken()
    {
        1 == I('expired') && $this->where['expired_time'] = ['lt', time()];
        $name   = I('name');
        $action = I('action');
        $token  = [];
        1 == $action && $token = $this->info($name, 'id,name,value,expired_time,status', CONTROLLER_NAME, [], 'name');
        if (!$token || $action != 1) {
            $data = [
                'name'  => $name,
                'value' => md5($name . time()),
            ];
            1 == I('expired') && $data['expired_time'] = time() + 3600;
            1 != $action && $data['id'] = $_POST['id'];
            $this->startTrans();
            $res = D($this->model)->update($data);
            if ($res) {
                $token = $this->info($name, 'id,name,value,expired_time,status', CONTROLLER_NAME, [], 'name');
            } else {
                $this->errorResponse('写入数据失败');
            }
        }
        $this->successResponse(['info' => $token], '获取成功');
    }
}