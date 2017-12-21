<?php
/**
 * Created by PhpStorm.
 * User: raven
 * Date: 2017/5/30
 * Time: 上午2:46
 */

/**
 * 密码加密
 */
function getMD5($password){
    $pwd = md5(C('MD5_PRE').md5($password.C('MD5_POS')));
    return $pwd;
}

/**
 * 获取用户登录IP
 */
function getIp()
{
    if (getenv("HTTP_CLIENT_IP")) {
        $ip = getenv("HTTP_CLIENT_IP");
    } elseif (getenv("HTTP_X_FORWARDED_FOR")) {
        $ip = getenv("HTTP_X_FORWARDED_FOR");
    } elseif (getenv("REMOTE_ADDR")) {
        $ip = getenv("REMOTE_ADDR");
    } else {
        $ip = 'UnKnow';
    }
    return $ip;
}