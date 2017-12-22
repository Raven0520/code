<?php
return array(
    //'配置项'=>'配置值'
    'URL_CASE_INSENSITIVE'  => true,  //设置为true的时候表示URL地址不区分大小写
    'SHOW_PAGE_TRACE'       => true,     //使得页面底部显示跟踪信息
    'MD5_PRE'               => 'Raven_', //加密前缀
    'MD5_POS'               => '_code', //加密后缀

    //url访问模式为PathInfo模式
    'URL_MODEL'             => '1',

    'DATA_CACHE_PREFIX' => 'Redis_',//缓存前缀
    'DATA_CACHE_TYPE'   => 'Redis',//默认动态缓存为Redis
//    'REDIS_HOST'        => '120.76.29.107', //redis服务器ip，多台用逗号隔开；读写分离开启时，第一台负责写，其它[随机]负责读；
//    'REDIS_PORT'        => '7379',//端口号7379
//    'REDIS_AUTH'        => 'kgjcb7be6c',//AUTH认证密码kgjcb7be6c
    'REDIS_HOST'        => '127.0.0.1', //redis服务器ip，多台用逗号隔开；读写分离开启时，第一台负责写，其它[随机]负责读；
    'REDIS_PORT'        => '6379',//端口号7379
    'REDIS_AUTH'        => '',
    'REDIS_TIMEOUT'     => '300',//超时时间
    'REDIS_PERSISTENT'  => false,//是否长连接 false=短连接

    //测试时关闭缓存
    'TMPL_CACHE_ON'         => false,//禁止模板编译缓存
    'HTML_CACHE_ON'         => false,//禁止静态缓存

    //域名部署
    'APP_SUB_DOMAIN_DEPLOY' => 1,
    'APP_SUB_DOMAIN_RULES'  => array(
        'code.com'     => 'Home',
        'api.code.com' => 'Api'
    ),
    // 加载扩展配置文件 多个用,隔开
    'LOAD_EXT_CONFIG'       => 'db',

    //Auth权限设置
//    'AUTH_CONFIG' => array(
    'AUTH_ON'               => true,  // 认证开关
    'AUTH_TYPE'             => 1, // 认证方式，1为实时认证；2为登录认证。
//        'AUTH_GROUP' => 'blog_auth_group', // 用户组数据表名
//        'AUTH_GROUP_ACCESS' => 'blog_auth_group_access', // 用户-用户组关系表
//        'AUTH_RULE' => 'blog_auth_rule', // 权限规则表
    'AUTH_USER'             => 'user', // 用户信息表
//    ),
);