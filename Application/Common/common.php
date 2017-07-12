<?php
/*通用获取配置文件里的状态*/
/**
 * 生成支付单编号(两位随机 + 从2000-01-01 00:00:00 到现在的秒数+微秒+会员ID%1000)，该值会传给第三方支付接口
 * 长度 =2位 + 10位 + 3位 + 3位  = 18位
 * 1000个会员同一微秒提订单，重复机率为1/100
 * @return string
 */
 function makePaySn($pay_id) {
	return 'P' . date('ymdHis',time()) . sprintf('%01d', (int) $pay_id % 10) . mt_rand(10,99);
}

function makeRefundSn($member_id) {
	return 'R' . date('ymdHis',time()) . sprintf('%01d', (int) $member_id % 10) . mt_rand(1,9);
}


/**
 * 生成提现单编号(C + 两位随机 + 从2000-01-01 00:00:00 到现在的秒数+会员ID%1000)
 * 长度 =1位 +12位 + 1位 + 2位  = 16位
 * 1000个会员同一微秒提订单，重复机率为1/100
 * @return string
 */
function makeCashSn($member_id) {
	return 'C' . date('ymdHis',time()) . sprintf('%01d', (int) $member_id % 10) . mt_rand(10,99);
}
/**
 * 订单编号生成规则，n(n>=1)个订单表对应一个支付表，
 * 生成订单编号(年取1位 + $pay_id取13位 + 第N个子订单取2位)
 * 1000个会员同一微秒提订单，重复机率为1/100
 * @param $pay_id 支付表自增ID
 * @return string
 */
 function makeOrderSn($member_id) {
    //记录生成子订单的个数，如果生成多个子订单，该值会累加
	 return 'E' . date('ymdHis',time()) . sprintf('%01d', (int) $member_id % 10) . mt_rand(10,99);
}

/**
 * 生成SessionID
 * @return string
 */
 function makeSessionID() {
 	$ip=getIP();
 	if($ip!='Unknow'){
 		$ipp=ip2long($ip);
         $str=mt_rand(1000,9999)
	      . sprintf('%010d',time() - 946656000)
	      . sprintf('%03d', (float) microtime() * 1000)
	      . sprintf('%03d', (int) $ipp % 1000);
	      //20位
 	}else{
 		//(两位随机 + 从2000-01-01 00:00:00 到现在的秒数+微秒+会员ID%1000)，
 		//20位
 		$str=mt_rand(100,999)
 		    . sprintf('%010d',time() - 946656000)
	        . sprintf('%03d', (float) microtime() * 1000)
	        . mt_rand(1000,9999);
 	}
	return $str;
}

/**
* 价格格式化
*
* @param int	$price
* @return string	$price_format
*/
function PriceFormat($price) {
	$price_format	= number_format($price,2,'.','');
	return $price_format;
}

/**
 * 获取会员等级
 *
 * @param int	当前vip积分
 */
function getVipRank($vip_i_rank,$vip_i_now) {
	$vip_i=$vip_i_now-$vip_i_rank*100;
	$vip=$vip_i_rank;
	if($vip<1){
		$vip_name='VIP';
	}else if($vip>=1 && $vip<=5){
		$vip_name='VIP'.$vip;
	}else if($vip>5 && $vip<=10){
		$vip_n=$vip-5;
		$vip_name='银牌'.$vip_n;
	}else if($vip>10 && $vip<=20){
		$vip_n=$vip-10;
		$vip_name='金牌'.$vip_n;
	}else{
		$vip_n=$vip-20;
		$vip_name='钻石'.$vip_n;
	}
	$vip_i=$vip_i>0?$vip_i:0;
	$vip_i=$vip_i<=100?$vip_i:0;
	$data['vip']=$vip;
	$data['vip_name']=$vip_name;
	$data['now_up_i']=$vip_i;
	return $data;
}

function get_status($name, $id)
{
    $temp = C($name);
    return $temp[$id];
}


//验证 手机号码  1 开头的 11 位数字 
function  check_phone($mobilephone){
	if(preg_match("/^1[0-9]{2}[0-9]{8}$/",$mobilephone)){
		//验证通过
		 return true;
	}else{
		//手机号码格式不对
	     return false;
	} 
}
//验证 电话号码  
function  check_telphone($mobilephone){
	if(preg_match("/^([0-9]{3,4}-)?[0-9]{7,8}$/",$mobilephone)){
		//验证通过
		return true;
	}else{
		//手机号码格式不对
		return false;
	}
}
//验证邮箱
function  check_email($email){
	if(preg_match("/([a-z0-9]*[-_.]?[a-z0-9]+)*@([a-z0-9]*[-_]?[a-z0-9]+)+[.][a-z]{2,3}([.][a-z]{2})?/i",$email)){
		//验证通过
		return true;
	}else{
		//邮箱格式不对
		return false;
	}
}
//验证 是否是数字
function  check_number($num){
	if( is_numeric($num)){
		//验证通过
		return true;
	}else{
		return false;
	}
}
// 获得，默认头像 imgurl 头像地址
function get_member_logo_default($imgurl=""){
	if(empty($imgurl)){
		return __ROOT__.'/Uploads/avatar_default.png';
	}
	//http://wx.qlogo.cn  微信头像
	if (strpos($imgurl, 'wx.qlogo.cn') !== false) {
		return $imgurl;
	}
	$imgurl1='.'.$imgurl;
	return file_exists($imgurl1)?$imgurl:__ROOT__.'/Uploads/avatar_default.png';
}
//获得，默认背景图imgurl地址
function get_member_background_default($imgurl=""){
	if(empty($imgurl)){
		return __ROOT__.'/public/wap/img/member/membertopbg.jpg';
	}
	$imgurl1='.'.$imgurl;
	return file_exists($imgurl1)?$imgurl:__ROOT__.'/Uploads/wap/img/logo.png';
}
//时间判断
	function check_time($param){
		$today = time();
		$if = $today - $param;
		if($if < 30){
			$exp = '刚刚';
		}else if($if > 30 && $if < 60){
			$exp = '1分钟之前';
		}else if($if > 60 && $if < 120){
			$exp = '2分钟之前';
		}
		else if($if > 120 && $if < 180){
			$exp = '3分钟之前';
		}
		else if($if > 180 && $if < 240){
			$exp = '4分钟之前';
		}
		else if($if > 240 && $if < 300){
			$exp = '5分钟之前';
		}
		else if($if > 300 && $if < 600){
			$exp = '10分钟之前';
		}
		else if($if > 600 && $if < 1800){
			$exp = '30分钟之前';
		}
		else if($if > 1800 && $if < 3600){
			$exp = '1小时之前';
		}else{
			$exp = date('Y-m-d H:i',$param);
		}
		return $exp;
	}

function search($tel){
	header("Content-Type:text/html;charset=utf-8");
	$url = 'http://webservice.webxml.com.cn/WebServices/MobileCodeWS.asmx/getMobileCodeInfo';
	$number = $tel;
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, "mobileCode={$number}&userId=");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$data = curl_exec($ch);
	curl_close($ch);
	$data = simplexml_load_string($data);
	$str = explode(' ',$data);
	return $str;
	}


//图片缩略图
function thumbs_auto($imgurl='', $width='120', $height='120', $type = 'path'){
	//$host="http://".$_SERVER["HTTP_HOST"];
	$filename='_'.$width.'_'.$height.'.'.end(explode('.', $imgurl));
	if(!empty($imgurl)){
		$imgurl='.'.$imgurl;
	}
	$sys_img = array('avatar_default.png', 'default.jpg', 'default.png', 'logg.png', 'logg_m.png', 'logo.png', 'codelogo.png');
	if(!file_exists($imgurl)){//文件不存在
		$file_path ='./Uploads/default'.$filename;
		if(!file_exists($file_path)){//文件不存在
			$file_path = './Uploads/default.jpg';
		}
	}elseif(in_array(basename($imgurl), $sys_img)){
		$file_path = $imgurl;

	}else{
		$md5file = md5_file($imgurl);//获取文件的 md5散列
		$file_path='./Uploads/Cache/'.$md5file.$filename;
		if (!file_exists($file_path)){//文件不存在
			import('ORG.Util.Image');// 导入图片处理类
			$image       = new Image();// 实例化分页类 传入总记录数
			$file_path=$image->thumb_auto($imgurl, $file_path, '', $width, $height, true);
		}
	}

	if($type == 'path'){
		return substr($file_path, 1);
	}else{
		// $file_path = 'Uploads/Cache/1e514c14e744e7fd97be08f35b6f2dd2_200_200.';
		// header('Content-type: image/jpg');
		$file = file_get_contents($file_path);  //读取文件
		// echo  '<img src="'.substr($imgurl, 1).'" />';
		echo '<img src="data:image/jpeg;base64,'.base64_encode($file).'" />';
	}
}


/************邮件******************/

/*
$address 收件人
$title   邮件标题
$message 发送信息

*/
function sendEmail($address,$title,$message){
	//include_once("./extend/ThinkPHP/Extend/Vendor/PHPMailer/class.phpmailer.php");
	vendor('PHPMailer.class#phpmailer');

	$mail = new phpmailer();
	 // 设置PHPMailer使用SMTP服务器发送Email
    $mail->IsSMTP();
    //$mail->Mailer = 'SMTP';
	// 设置邮件的字符编码，若不指定，则为'UTF-8'
    $mail->CharSet='UTF-8';
	 // 添加收件人地址，可以多次使用来添加多个收件人
    $mail->AddAddress($address);
	 // 设置邮件正文
    $mail->Body=$message;
	 // 设置邮件头的From字段。
    $mail->From=C('MAIL_ADDRESS');
	// 设置发件人名字
    $mail->FromName='XXXX';
	// 设置邮件标题
    $mail->Subject=$title;
	// 设置SMTP服务器。
    $mail->Host=C('MAIL_SMTP');
	// 设置为"需要验证"
    $mail->SMTPAuth=true;
    $mail->SMTPDebug=2;
	// 设置用户名和密码。
    $mail->Username=C('MAIL_LOGINNAME');
    $mail->Password=C('MAIL_PASSWORD');
	if($mail->Send()){
		return true;
	}else{
		return false;
	}
	$mail->ErrorInfo();
	
}

/**
 * 系统邮件发送函数
 * @param string $to    接收邮件者邮箱
 * @param string $name  接收邮件者名称
 * @param string $subject 邮件主题 
 * @param string $body    邮件内容
 * @param string $attachment 附件列表
 * @return boolean 
 */
function think_send_mail($to, $name, $subject = '', $body = '', $attachment = null){
    $config = C('THINK_EMAIL');
    var_dump( $config);
    vendor('PHPMailer.class#phpmailer'); //从PHPMailer目录导class.phpmailer.php类文件
    $mail             = new PHPMailer(); //PHPMailer对象
    $mail->CharSet    = 'UTF-8'; //设定邮件编码，默认ISO-8859-1，如果发中文此项必须设置，否则乱码
    $mail->IsSMTP();  // 设定使用SMTP服务
    $mail->SMTPDebug  = 2;                     // 关闭SMTP调试功能
                                               // 1 = errors and messages
                                               // 2 = messages only
    $mail->SMTPAuth   = true;                  // 启用 SMTP 验证功能
    $mail->SMTPSecure = 'ssl';                 // 使用安全协议
    $mail->Host       = $config['SMTP_HOST'];  // SMTP 服务器
    $mail->Port       = $config['SMTP_PORT'];  // SMTP服务器的端口号
    $mail->Username   = $config['SMTP_USER'];  // SMTP服务器用户名
    $mail->Password   = $config['SMTP_PASS'];  // SMTP服务器密码
    $mail->SetFrom($config['FROM_EMAIL'], $config['FROM_NAME']);
    $replyEmail       = $config['REPLY_EMAIL']?$config['REPLY_EMAIL']:$config['FROM_EMAIL'];
    $replyName        = $config['REPLY_NAME']?$config['REPLY_NAME']:$config['FROM_NAME'];
    $mail->AddReplyTo($replyEmail, $replyName);
    $mail->Subject    = $subject;
    $mail->MsgHTML($body);
    $mail->AddAddress($to, $name);
    if(is_array($attachment)){ // 添加附件
        foreach ($attachment as $file){
            is_file($file) && $mail->AddAttachment($file);
        }
    }
    return $mail->Send() ? true : $mail->ErrorInfo;
}



/**
 * 系统邮件发送函数
 * @param string $to    接收邮件者邮箱
 * @param string $name  接收邮件者名称
 * @param string $subject 邮件主题 
 * @param string $body    邮件内容
 * @param string $attachment 附件列表
 * @return boolean 
 */
function think_send_mail2($to='', $name='', $subject = '', $body = '', $attachment = null){
    $config = C('THINK_EMAIL');
    var_dump( $config);
    include_once("./extend/ThinkPHP/Extend/Vendor/sendemail/sendmail.class.php");
   // vendor('PHPMailer.class#phpmailer'); //从PHPMailer目录导class.phpmailer.php类文件
   /*
	* 邮件发送类
	* 支持发送纯文本邮件和HTML格式的邮件，可以多收件人，多抄送，多秘密抄送，带附件(单个或多个附件),支持到服务器的ssl连接
	* 需要的php扩展：sockets、Fileinfo和openssl。
	* 编码格式是UTF-8，传输编码格式是base64
	* @example
	*/
	$mail = new sendmail();
	//$mail->setServer("smtp@163.com", "jun584745119@163.com", "jUN544164681Jun"); //设置smtp服务器，普通连接方式
	$mail->setServer("smtp@163.com", "jun584745119@163.com", "jUN544164681Jun", 25, true); //设置smtp服务器，到服务器的SSL连接
	$mail->setFrom("jun584745119@163.com"); //设置发件人
	$mail->setReceiver("937143129@qq.com"); //设置收件人，多个收件人，调用多次
	//$mail->setCc("XXXX"); //设置抄送，多个抄送，调用多次
	//$mail->setBcc("XXXXX"); //设置秘密抄送，多个秘密抄送，调用多次
	//$mail->addAttachment( array("XXXX","xxxxx") ); //添加附件，多个附件，可调用多次，第一个文件名是 程序要去抓的文件名，第二个文件名是显示在邮件中的文件名。
	$mail->setMail("test", "<b>test</b>"); //设置邮件主题、内容
	$re=$mail->sendMail(); //发送
var_dump($re);
var_dump($mail->error());

}


/***********邮件end****************/

// 求两个日期相差天数
function get_day($time){
	$day = floor(($time-time())/(24*60*60));
	
	if($day >= 3){
		return "剩余3天以上";
	}else{
		return "剩余".$day."天";
	}
}
/**  
 * mb_substr截取utf-8字符串  
 * @since 2008.12.23  
 * @param string $str 被截取的字符串  
 * @param integer $start 起始位置  
 * @param integer $length 截取长度(每个汉字为3字节)  
 */  
function msubstr($str, $start=0, $length, $charset="utf-8", $suffix=true)
{
	if(function_exists("mb_substr")){
		if ($suffix && strlen($str)>$length)
			return mb_substr($str, $start, $length, $charset)."...";
		else
			return mb_substr($str, $start, $length, $charset);
	}
	elseif(function_exists('iconv_substr')) {
		if ($suffix && strlen($str)>$length)
			return iconv_substr($str,$start,$length,$charset)."...";
		else
			return iconv_substr($str,$start,$length,$charset);
	}
	$re['utf-8']   = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
	$re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
	$re['gbk']    = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
	$re['big5']   = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
	preg_match_all($re[$charset], $str, $match);
	$slice = join("",array_slice($match[0], $start, $length));
	if($suffix) return $slice."…";
	return $slice;
}
/**  
 * 截取utf-8字符串  去掉html 标签 
 * @since 2008.12.23  
 * @param string $str 被截取的字符串  
 * @param integer $start 起始位置  
 * @param integer $length 截取长度(每个汉字为3字节)  
 */  
function mbubstr_html($str, $start=0, $length=15,$charset="utf-8")
{
   $str=strip_tags($str);
   $str=trim($str);
    $str=str_replace('&nbsp;','',$str);
   $strleng=mb_strlen($str,$charset);
   $str=mb_substr($str, $start,$length,$charset);
   if($strleng>=$length){
      $str.="......";
   }
   return  $str;   
}
/**
 * 根据城市id 查询城市列表名称
 * 2014-10-13
 * @param array $citylist 
 * @param array $citylist  $citylist["province"] 1 查询省份列表  0或null不查询   
 * @param array $citylist  $citylist["provinceid"]  省份id 查询省份下的城市列表  0或null不查询   
 * @param array $citylist  $citylist["cityid"]  城市id 查询城市下的区县列表  0或null不查询   
 * @return array $cityarr $cityarr["province"] 省份列表 $cityarr["city"] 城市列表 $cityarr["area"] 区县列表  不查则不返回
 */
function getcitylist($citylist=array("province"=>1,"provinceid"=>0,"cityid"=>0)){
	    if(!empty($citylist["province"])){//查询省份信息
	    	$cityarr["province"]=S("set_province_list");//获取缓存省份信息
	    	if(empty($cityarr["province"])){
	    		$cityarr["province"]=M ( "city_province" )->field ( "provinceid as cityid,province as cityname" )->select ();
	    		S("set_province_list",$cityarr["province"]);//缓存省份信息
	    	}
	    }
	    if(!empty($citylist["provinceid"])){//查询省份下的城市信息
	    	$cityarr["city"]=S("set_city_list".$citylist["provinceid"]);//获取缓存城市信息
	    	if(empty($cityarr["city"])){
	    		$cityarr["city"]=M ( "city_city" )->field ( "cityid,city as cityname" )->where ( array("fatherid"=>"{$citylist['provinceid']}"))->select ();
	    		S("set_city_list",$cityarr["city"]);//缓存城市信息
	    	}
	    }
	    if(!empty($citylist["cityid"])){//查询城市下的区县信息
	    	$cityarr["area"]=S("set_area_list".$citylist["cityid"]);//获取缓存区县信息
	    	if(empty($cityarr["area"])){
	    		$cityarr["area"]=M ( "city_area" )->field ( "areaid as cityid,area as cityname" )->where (array("fatherid"=>"{$citylist['cityid']}") )->select ();
	    		S("set_area_list".$citylist["cityid"],$cityarr["area"]);//缓存区县信息
	    	}
	    }
		return  $cityarr;
}

/*
* 2016-09-23
* kkxx
* 获取所有的城市 并按父级id分组
*/
function get_city_group(){
	$arr = 0;//S("get_city_group");
	if(empty($arr)){
		$arr = array();
		//省
		$province = M("city_province")->field("provinceid as value, province as text" )->select();
		$arr["province"] = json_encode($province);
		
		//市
		$city_list = M("city_city")->field("cityid as value, city as text, fatherid as pid")->select();
		$city = array();
		foreach($city_list as $v){
			$pid = $v['pid'];
			unset($v['pid']);
			$city[$pid][] = $v;
		}
		$arr["city"] = json_encode($city);
		
		//区县
		$area_list = M("city_area")->field("areaid as value, area as text, fatherid as pid")->select();
		$area = array();
		foreach($area_list as $v){
			$pid = $v['pid'];
			unset($v['pid']);
			$area[$pid][] = $v;
		}
		$arr["area"] = json_encode($area);
		// S("get_city_group" ,$arr);
	}
	return $arr;
}

/**
 * 2014-10-29
 * 返回订单状态
 * @order_state 订单状态 return_state
 * @return str
 */
 function get_order_state($order_state){
	
		switch($order_state){
			
			case '1':
				$str = '待付款';
				break;
			case '2':
				$str = '待发货';
				break;
			case '3':
				$str = '待收货';
				break;
			case '4':
				$str = '已完成';
				break;
		}
	return $str;
 } 
 //判断是否是手机浏览
 function is_mobile()   
{   
  $_SERVER['ALL_HTTP'] = isset($_SERVER['ALL_HTTP']) ? $_SERVER['ALL_HTTP'] : '';   
  $mobile_browser = '0';   
  if(preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|iphone|ipad|ipod|android|xoom)/i', strtolower($_SERVER['HTTP_USER_AGENT'])))   
    $mobile_browser++;   
  if((isset($_SERVER['HTTP_ACCEPT'])) and (strpos(strtolower($_SERVER['HTTP_ACCEPT']),'application/vnd.wap.xhtml+xml') !== false))   
    $mobile_browser++;   
  if(isset($_SERVER['HTTP_X_WAP_PROFILE']))   
    $mobile_browser++;   
  if(isset($_SERVER['HTTP_PROFILE']))   
    $mobile_browser++;   
  $mobile_ua = strtolower(substr($_SERVER['HTTP_USER_AGENT'],0,4));   
  $mobile_agents = array(   
        'w3c ','acs-','alav','alca','amoi','audi','avan','benq','bird','blac',   
        'blaz','brew','cell','cldc','cmd-','dang','doco','eric','hipt','inno',   
        'ipaq','java','jigs','kddi','keji','leno','lg-c','lg-d','lg-g','lge-',   
        'maui','maxo','midp','mits','mmef','mobi','mot-','moto','mwbp','nec-',   
        'newt','noki','oper','palm','pana','pant','phil','play','port','prox',   
        'qwap','sage','sams','sany','sch-','sec-','send','seri','sgh-','shar',   
        'sie-','siem','smal','smar','sony','sph-','symb','t-mo','teli','tim-',   
        'tosh','tsm-','upg1','upsi','vk-v','voda','wap-','wapa','wapi','wapp',   
        'wapr','webc','winw','winw','xda','xda-'  
        );   
  if(in_array($mobile_ua, $mobile_agents))   
    $mobile_browser++;   
  if(strpos(strtolower($_SERVER['ALL_HTTP']), 'operamini') !== false)   
    $mobile_browser++;   
  // Pre-final check to reset everything if the user is on Windows   
  if(strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'windows') !== false)   
    $mobile_browser=0;   
  // But WP7 is also Windows, with a slightly different characteristic   
  if(strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'windows phone') !== false)   
    $mobile_browser++;   
  if($mobile_browser>0)   
    return true;   
  else 
    return false;   
}


/**
 * 获取客户端IP地址
 * 2014-11-12
 * @return Ambigous <unknown, string>
 */
function getIP(){
	global $ip;
	if (getenv("HTTP_CLIENT_IP")){
		$ip = getenv("HTTP_CLIENT_IP");
	}else if(getenv("HTTP_X_FORWARDED_FOR")){
		$ip = getenv("HTTP_X_FORWARDED_FOR");
	}else if(getenv("REMOTE_ADDR")){
		$ip = getenv("REMOTE_ADDR");
	}else{
		$ip = "Unknow";
	}
	return $ip;
}



function switch_status($name){
	switch($name){
		case 0:
			$module_name = '已取消';
			break;
		case 10:
			$module_name = '未付款';
			break;
		case 20:
			$module_name = '已付款';
			break;
		case 30:
			$module_name = '已使用';
			break;
	}
    return $module_name;
}
/**
*2015.4.1
*后台记录日志
*参数$info 操作简介  $type 操作类型
**/
function get_userlogo($type,$info){
$ndate = date("Y-m-d",time());

$infoarr["ip"] = getIP(); //获取IP
$infoarr["info"] = $info;
$infoarr["dates"] = date("Y-m-d H:i:s",time());
$content=$info."  操作时间：".$infoarr["dates"]."  操作IP地址：".$infoarr["ip"]."\n";

file_put_contents("./Uploads/userlogs/".$type."/".$ndate.".txt",$content."".PHP_EOL,FILE_APPEND);
}

/**
 * 用户操作日志记录
 * @param 操作内容 $operate
 * @param 文件名  $name
 */
function savelog($operate,$name){
	//创建log目录
	$destination = date("Y-m-d");//创建错误日志保存路径
	if (!is_dir("logs/".$destination)) {
		echo $destination;
		mkdir("logs/".$destination);
	}
	$time=date("Y-m-d H:i:s");
	$ip=$_SERVER['REMOTE_ADDR'];
	error_log("时间：".$time."------操作：$operate-----Ip地址：$ip \r\n",3,"./logs/".$destination."/".$name.".txt");
}
///反序列化序列化 hkj
function mb_unserialize($serial_str) {  
    $serial_str= preg_replace('!s:(\d+):"(.*?)";!se', "'s:'.strlen('$2').':\"$2\";'", $serial_str );  
    $serial_str= str_replace("\r", "", $serial_str);  
    return unserialize($serial_str);  
}  
  
// ascii   反序列化 序列化 hkj
function asc_unserialize($serial_str) {
    $serial_str = preg_replace('!s:(\d+):"(.*?)";!se', '"s:".strlen("$2").":\"$2\";"', $serial_str );  
    $serial_str= str_replace("\r", "", $serial_str);  
    return unserialize($serial_str);  
} 

/**
 * 成员头像 hkj
  * @param string $member_id
 * @return string
 */
function getMemberAvatarForID($member_id=null){
	$path='/mall/data/upload/shop/avatar/';
	$file_path='.'.$path.'avatar_'.$member_id.'.jpg';
	if(file_exists($file_path)){
		return $path.'avatar_'.$member_id.'.jpg';
	}else{
		return '/mall/data/upload/shop/common/default_user_portrait.gif';
	}
}
/**
* +----------------------------------------------------------
 * 剪切字符串函数
 *+-----------------------------------------------------------
 * @return string
 *+-----------------------------------------------------------
 */
function cutstr ($data, $no, $le = '...') {
	$data = strip_tags(htmlspecialchars_decode($data));
	$data = str_replace(array("\r\n", "\n\n", "\r\r", "\n", "\r"), '', $data);
	$datal = strlen($data);
	$str = msubstr710($data, 0, $no);
	$datae = strlen($str);
	if ($datal > $datae)
		$str .= $le;
	return $str;
}

function msubstr710($str, $start=0, $length, $charset="utf-8", $suffix=true){
	if(function_exists("mb_substr"))
		return mb_substr($str, $start, $length, $charset);
	elseif(function_exists('iconv_substr')) {
		return iconv_substr($str,$start,$length,$charset);
	}
	$re['utf-8']   = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
	$re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
	$re['gbk']    = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
	$re['big5']   = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
	preg_match_all($re[$charset], $str, $match);
	$slice = join("",array_slice($match[0], $start, $length));
	if($suffix) return $slice."…";
	return $slice;
}

/**
* 对象转数组 hkj
* @param string obj
* @return string array()
*/
function obj_to_arr($array){
	if(is_object($array)) {
        $array = (array)$array; 
        $array = obj_to_arr($array);   
     } if(is_array($array)) {  
         foreach($array as $key=>$value) {  
             $array[$key] = obj_to_arr($value);  
         }  
     }  
	return $array;
}


//PHP处理函数
function ubbReplace($str){
    $str1 = str_replace("<",'&lt;',$str);
    $str1 = str_replace(">",'&gt;',$str);
    $str1 = str_replace("\n",'<br/>',$str);
	$str1 = preg_replace("[\[/em_([0-9]*)\]]","<img src=\"/public/images/face/$1.gif\" />",$str);
    return $str.$str1;
}


  
function writelog($tname, $content) {
	$filename = 'Uploads/EjLogs/' . $tname . '.txt';
	$content = $content . "\r\n" . date('Y-m-d H:i:s', time());
	if (filesize($filename) < 2 * 1024 * 1024) {
		file_put_contents($filename, $content, FILE_APPEND);
	} else {
		file_put_contents($filename, $content);
	}
}

//二维数组根据字段排序
function list_sort_by($list, $field, $sortby = 'asc')
{
    if (is_array($list))
    {
        $refer = $resultSet = array();
        foreach ($list as $i => $data)
        {
            $refer[$i] = &$data[$field];
        }
        switch ($sortby)
        {
            case 'asc': // 正向排序
                asort($refer);
                break;
            case 'desc': // 逆向排序
                arsort($refer);
                break;
            case 'nat': // 自然排序
                natcasesort($refer);
                break;
        }
        foreach ($refer as $key => $val)
        {
            $resultSet[] = &$list[$key];
        }
        return $resultSet;
    }
    return false;
}

/**
*防挂马、防跨站攻击、防sql注入函数
*$date 传入的参数，要是个变量或者数组；$ignore_magic_quotes变量的魔术引用
*2015.6.9
**/
function check_data($data,$ignore_magic_quotes=false)
{
	 if(is_string($data))
	 {
		  $data=trim(htmlspecialchars($data));//防止被挂马，跨站攻击
		  if(($ignore_magic_quotes==true)||(!get_magic_quotes_gpc())) 
		  {
		     $data = addslashes($data);//防止sql注入
		  }
	  	return  $data;
	 }else if(is_array($data)){//如果是数组采用递归过滤
		  foreach($data as $key=>$value)
		  {
		    $data[$key]=check_data($value);
		  }
		  return $data;
	}else{
  		return $data;
  	}
}
//得到最近的旗舰店信息
function getflageship($recommend_code){
	$recommend_info=M('member')->where("member_card=$recommend_code")->find();
	if ($recommend_info['member_vip_type']!=3){
		if (empty($recommend_info['recommend_code'])){
			return array();
		}
		$recommend_info=getflageship($recommend_info['recommend_code']);
	}
	return  $recommend_info;
}

function get_fans_count($member_card){
	$where1=array('recommend_code'=>$member_card,'member_vip_type'=>array('gt','0'));
	$shar_r1=M('member')
		->field("count('recommend_code') as r1  ")
		->where($where1)
		->getField("count('recommend_code')");
	$where2=array('indirect_recommend_code'=>$member_card,'member_vip_type'=>array('gt','0'));
	$shar_r2=M('member')
		->field("count('indirect_recommend_code') as r2  ")
		->where($where2)
		->getField("count('indirect_recommend_code')");
	$where3=array('indirect2_recommend_code'=>$member_card,'member_vip_type'=>array('gt','0'));
	$shar_r3=M('member')
		->field("count('indirect2_recommend_code') as r3  ")
		->where($where3)
		->getField("count('indirect2_recommend_code')");

	$beginThismonth=mktime(0,0,0,date('m'),1,date('Y'));
	$beginLastmonth=mktime(0,0,0,date('m')-1,1,date('Y'));
	$where_m_add=array(
		'recommend_code|indirect_recommend_code|indirect2_recommend_code'=>$member_card,
		'member_vip_type'=>array('gt','0')
	);
	$last_month=array('between',array("$beginLastmonth","$beginThismonth"));
	$this_month=array('gt',"$beginThismonth");
	$where_m_add['vip_time']=$last_month;
	$shar_last_m=M('member')->where($where_m_add)->count();
	$where_m_add['vip_time']=$this_month;
	$shar_this_m=M('member')->where($where_m_add)->count();
	$fans['shar_last_m']=$shar_last_m;
	$fans['shar_this_m']=$shar_this_m;
	$fans['shar_r1']=$shar_r1;
	$fans['shar_r2']=$shar_r2;
	$fans['shar_r3']=$shar_r3;
	$fans['shar_r0']=$shar_r1+$shar_r2+$shar_r3;
	return $fans;
}

function mb_number($number, $bit = 2){
	return sprintf("%.2f", substr(sprintf("%.4f", $number), 0, -2));
}

function is_stop_request(){
	//屏蔽IP和GPS所在地点
	$i = 0;
	$gaode_key = array("cc4f144425587c41f05379a9309beb92","d7166c8eb55af9a337275afcfc271ab1","f821fb0fc837225b85f16697ec6e29d4","3fd4eaeb983d93a30001fad792b40ae3");
	do{
		//ip
		$user_ip = getIP();
		$json_ip=file_get_contents('http://restapi.amap.com/v3/ip?ip='.$user_ip.'&output=JSON&key='.$gaode_key[$i]);
		$address_ip=json_decode($json_ip);
		$arr_ip = array("湖南省");
		if (isset($address_ip->province)) {
			if (in_array($address_ip->province,$arr_ip)) {
				//IP属于被屏蔽范围
				$html = '<!DOCTYPE html>
						<html>
						<head>
						<meta charset="utf-8">
						<title>此地区暂不开放</title>
						<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
						<meta name="format-detection" content="telephone=no">
						</head>
						<style>
						html,body{ width:100%; height:100%; padding:0; margin:0;}
							.tip {  
							  margin: auto; height:2rem; line-height:2rem; text-align:center; font-size:1.6rem; font-family:"microsoft yahei";
							  position: absolute;  
							  top: 0; left: 0; bottom: 0; right: 0;  
							}  
						</style>
						<body>
						<div class="tip">此地区暂不开放</div>
						</body>
						</html>';
				exit($html);
			}
		}
		$i++;
	}while($address_ip->infocode == "10004");
	unset($i);
}
//替换特殊符
function replace_special($subject){
/*	$search=array( "/","\"","'",""/["'<>%;)(&+]/);*/
	$search =  array("&","<",">","=","%","/","\\");
	$replace = array("&amp;","&lt;","&gt;","&eq;","&#37","&#47","&#92;");
	return str_replace ( $search ,  $replace , $subject );
}
/**
 * 邮费信息 json 数组转换
 * 邮费信息 json -> array 或 array->json
 * @param $param 邮费信息  str[json] 或 array
 * @param  $type   '0':json -> array  ,'1':array->json
 * @return str 或 array
 */
function postage_trans($param,$type = '0'){
	if($type == '1'){
		$postages = array();
		foreach ($param['provinceid'] as $k => $v){
			$postage_arr = array();
			$postage_arr['provinceid'] = $_POST['provinceid'][$k];
			$postage_arr['province'] =$_POST['province'][$k];
			$postage_arr['first'] = $_POST['first'][$k];
			$postage_arr['first_price'] =$_POST['first_price'][$k];
			$postage_arr['add'] = $_POST['add'][$k];
			$postage_arr['add_price'] = $_POST['add_price'][$k];
			$postage_arr['by_condition'] =$_POST['by_condition'][$k];
			$postage_arr['by_unit'] =$_POST['by_unit'][$k];
			$postages[$k] = $postage_arr;
		}
		$postage_json = json_encode($postages);
		return $postage_json;
	}else{
		$postage_info = json_decode($param,true);
		return $postage_info;
	}

}
?>