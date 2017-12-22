<?php
/**
 * Created by PhpStorm.
 * User: raven
 * Date: 2017/12/22
 * Time: 下午11:45
 */

namespace Api\Controller;


class ProjectController extends EmptyController
{
    public function detail($id)
    {
        $this->user['project_id'] = $id;

        $res = S('User_' . getIp(), $this->user, 7200);
        if ($res) {
            $this->successResponse();
        } else {
            $this->errorResponse('网络错误');
        }
    }
}