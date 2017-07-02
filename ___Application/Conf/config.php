<?php
//网站常规配置
$siteconfig = array(
	'APP_GROUP_LIST'        => 'Admin,Wap',      // 项目分组设定,多个组之间用逗号分隔,例如'Home,Admin'
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
	//微信配置 start
	'APPID'=>'wx53feaf0edf3b068c',
	'APPSECRET'=>'8fad3a28eb342a654eb0a5dff1de02c5',
	//微信配置 end

	'SET_MAP_AK'=>'D283dbc3d443f0e20b1c3ccebfcc2021',//百度地图api
	'SENDUID'=>'56696',
	'SENDPWD'=>'a937so',
	'SENDMOBILE'=>' ',//支付完成 发送短信通知号码 设置后才生效

	'SHAR_TITLE'=>'汽车易修哥',///分享标题
	'SHAR_DESC'=>'一个关于汽车配件销售及维护的平台 。',///分享介绍

	'MAIL_ADDRESS'=>'', // 邮箱地址
	'MAIL_SMTP'=>'smtp.126.com', // 邮箱SMTP服务器
	'MAIL_LOGINNAME'=>'', // 邮箱登录帐号
	'MAIL_PASSWORD'=>'', // 邮箱密码

	'THINK_EMAIL' => array(
		'SMTP_HOST'   => 'smtp.163.com', //SMTP服务器
		'SMTP_PORT'   => '25', //SMTP服务器端口
		'SMTP_USER'   => '', //SMTP服务器用户名
		'SMTP_PASS'   => '', //SMTP服务器密码
		'FROM_EMAIL'  => '', //发件人EMAIL
		'FROM_NAME'   => '', //发件人名称
		'REPLY_EMAIL' => '', //回复EMAIL（留空则为发件人EMAIL）
		'REPLY_NAME'  => '', //回复名称（留空则为发件人名称）
		/*'SMTP_HOST'   => 'smtp.163.com', //SMTP服务器
		'SMTP_PORT'   => '25', //SMTP服务器端口
		'SMTP_USER'   => '', //SMTP服务器用户名
		'SMTP_PASS'   => '', //SMTP服务器密码
		'FROM_EMAIL'  => '', //发件人EMAIL
		'FROM_NAME'   => 'msg', //发件人名称
		'REPLY_EMAIL' => '', //回复EMAIL（留空则为发件人EMAIL）
		'REPLY_NAME'  => '', //回复名称（留空则为发件人名称）*/
	),
	//物流信息查询配置
	'EXPRESS' => array(
		'EBusinessID' => 1268349,
		'AppKey' => '0997f972-bbe2-4861-bc58-55885f2881ef',
		'ReqURL' => 'http://api.kdniao.cc/Ebusiness/EbusinessOrderHandle.aspx',
	),
);
return array_merge(include'./Application/Conf/data.inc.php',$siteconfig);
?>