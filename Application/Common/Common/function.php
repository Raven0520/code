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