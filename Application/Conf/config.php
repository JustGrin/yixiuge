<?php
//网站常规配置
$siteconfig = array(
	'APP_GROUP_LIST'        => 'Index,Admin,Wap,Fghome,Flagship,Filiale',      // 项目分组设定,多个组之间用逗号分隔,例如'Home,Admin'
    'APP_GROUP_MODE'        =>  1,  // 分组模式 0 普通分组 1 独立分组
    'APP_GROUP_PATH'        =>  'Modules', // 分组目录 独立分组模式下面有效
    'ACTION_SUFFIX'         =>  '', // 操作方法后缀
     'DEFAULT_GROUP'  => 'Wap', //默认分组
    /* Cookie设置 */
    'COOKIE_EXPIRE'         => 0,    // Coodie有效期
    'COOKIE_DOMAIN'         => '',      // Cookie有效域名
    'COOKIE_PATH'           => '/',     // Cookie路径
    'COOKIE_PREFIX'         => '',      // Cookie前缀 避免冲突
    'URL_HTML_SUFFIX'		=>'shtml',	// URL伪静态后缀设置
	//'SHOW_PAGE_TRACE' =>true,
	

    'URL_CASE_INSENSITIVE'=>true,///url不区分大小写 ///

    //URL路由
    'URL_ROUTER_ON'   		=> true, //开启路由
 	'URL_ROUTE_RULES' 		=> array( //定义路由规则
		//'news/:id\d/:num'       => 'Home/News/read', //参数id为数字的时候才匹配
		//'news/:name'            => 'Home/News/read',//参数name可以匹配任意字符
		'TOKEN_ON'=>true,  // 是否开启令牌验证 默认关闭
		'TOKEN_NAME'=>'__hash__',    // 令牌验证的表单隐藏字段名称
		'TOKEN_TYPE'=>'md5',  //令牌哈希验证规则 默认为MD5
		'TOKEN_RESET'=>true,  //令牌验证出错后是否重置令牌 默认为true
    ),
	



);
return array_merge(include'./Application/Conf/data.inc.php',$siteconfig);
?>