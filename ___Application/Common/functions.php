<?php
/*通用获取配置文件里的状态*/
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
/************邮件******************/

/*
$address 收件人
$title   邮件标题
$message 发送信息

*/
function sendEmail($address,$title,$message){
	include_once("./ThinkPHP/Extend/Vendor/PHPMailer/class.phpmailer.php");
	$mail = new PHPMailer();

	 // 设置PHPMailer使用SMTP服务器发送Email
    $mail->IsSMTP();

	// 设置邮件的字符编码，若不指定，则为'UTF-8'
    $mail->CharSet='UTF-8';

	 // 添加收件人地址，可以多次使用来添加多个收件人
    $mail->AddAddress($address);

	 // 设置邮件正文
    $mail->Body=$message;

	 // 设置邮件头的From字段。
    $mail->From=C('MAIL_ADDRESS');

	// 设置发件人名字
    $mail->FromName='e佳生活';

	// 设置邮件标题
    $mail->Subject=$title;

	// 设置SMTP服务器。
    $mail->Host=C('MAIL_SMTP');

	// 设置为"需要验证"
    $mail->SMTPAuth=true;

	// 设置用户名和密码。
    $mail->Username=C('MAIL_LOGINNAME');
    $mail->Password=C('MAIL_PASSWORD');
	
	if($mail->Send()){
		return true;
	}else{
		return false;
	}
	
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


// 获取排序名
function get_order($param){
	switch($param){
		case 'sort asc':
			$name = "默认";
		break;
		case 'add_time desc':
			$name = "时间";
		break;
		case 'price asc':
			$name="价格";
		break;
		case 'click desc':
			$name = "点击";
		break;
		default:
			$name = "默认";
		break;
	}
	return $name;
}


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
 * 根据省份Id 查询省份名称
 * 2014-10-13
 * @param unknown $proid
 */
function getproname($proid){
	$proname = M("province")->where("provinceid=".$proid)->getField("province");
	return $proname;
}

/**
 * 根据城市Id查询城市名称 
 * 2014-10-13
 * @param unknown $cityid
 */
function getcityname($cityid){
	$cityname = M("city")->where("cityid=".$cityid)->getField("city");
	return $cityname;
}

/**
 * 根据区县id查询区县名称
 * 2014-10-13
 * @param unknown $areaid
 * @return Ambigous <mixed, NULL, multitype:Ambigous <unknown, string> unknown , multitype:>
 */
function getareaname($areaid){
	$areaname = M("area")->where("areaid=".$areaid)->getField("area");
	return $areaname;
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
	    		$cityarr["province"]=M ( "province" )->field ( "provinceid as cityid,province as cityname" )->select ();
	    		S("set_province_list",$cityarr["province"]);//缓存省份信息
	    	}
	    }
	    if(!empty($citylist["provinceid"])){//查询省份下的城市信息
	    	$cityarr["city"]=S("set_city_list".$citylist["provinceid"]);//获取缓存城市信息
	    	if(empty($cityarr["city"])){
	    		$cityarr["city"]=M ( "city" )->field ( "cityid,city as cityname" )->where ( array("fatherid"=>"{$citylist['provinceid']}"))->select ();
	    		S("set_city_list",$cityarr["city"]);//缓存城市信息
	    	}
	    }
	    if(!empty($citylist["cityid"])){//查询城市下的区县信息
	    	$cityarr["area"]=S("set_area_list".$citylist["cityid"]);//获取缓存区县信息
	    	if(empty($cityarr["area"])){
	    		$cityarr["area"]=M ( "area" )->field ( "areaid as cityid,area as cityname" )->where (array("fatherid"=>"{$citylist['cityid']}") )->select ();
	    		S("set_area_list".$citylist["cityid"],$cityarr["area"]);//缓存区县信息
	    	}
	    }
		return  $cityarr;
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
function getcity_shop($citylist=array("province"=>1,"provinceid"=>0,"cityid"=>0)){
	if(!empty($citylist["province"])){//查询省份信息
		$cityarr["province"]=S("shop_province_list");//获取缓存省份信息
		if(empty($cityarr["province"])){
			$cityarr["province"]=M()->table("shop_area")//
			->where(array("area_deep"=>1))//
			->field ( "area_id as cityid,area_name as cityname" )->select ();
			S("shop_province_list",$cityarr["province"]);//缓存省份信息
		}
	}
	if(!empty($citylist["provinceid"])){//查询省份下的城市信息
		$cityarr["city"]=S("shop_city_list".$citylist["provinceid"]);//获取缓存城市信息
		if(empty($cityarr["city"])){
			$cityarr["city"]=M()->table("shop_area")//
			->where(array("area_deep"=>2,"area_parent_id"=>"{$citylist['provinceid']}"))//
			->field ( "area_id as cityid,area_name as cityname" )->select ();
			S("shop_city_list".$citylist["provinceid"],$cityarr["city"]);//缓存城市信息
		}
	}
	if(!empty($citylist["cityid"])){//查询城市下的区县信息
		$cityarr["area"]=S("shop_area_list".$citylist["cityid"]);//获取缓存区县信息
		if(empty($cityarr["area"])){
			$cityarr["area"]=M()->table("shop_area")//
			->where(array("area_deep"=>3,"area_parent_id"=>"{$citylist['cityid']}"))//
			->field ( "area_id as cityid,area_name as cityname" )->select ();
			S("shop_area_list".$citylist["cityid"],$cityarr["area"]);//缓存区县信息
		}
	}
	return  $cityarr;
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

	//导入分页
	function importPage($where,$data){
		import('ORG.Util.Page');
    	$count = $data->where($where)->count();
    	$Page = new Page($count,5);
    	$Page->setConfig('theme',"%upPage% %linkPage% %downPage%");
        $Page->setConfig('prev','上一页');
        $Page->setConfig('header','篇记录'); 
		$Page->setConfig('next','下一页');
    	$show = $Page->show();
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

function thumbs_auto($imgurl='',$width='120',$height='120'){
	header('Content-type: image/jpg');
    //$host="http://".$_SERVER["HTTP_HOST"];
    $filename='_'.$width.'_'.$height;
    if (!file_exists($imgurl)){//文件不存在
        $return_url ='./Uploads/'.$filename;
    }else{
         $md5file = md5_file($filename);//获取文件的 md5散列
         $return_url='./Uploads/Cache/'.$md5file.$filename;
         if (!file_exists($return_url)){//文件不存在
            import('ORG.Util.Image');// 导入图片处理类
			$image       = new Image();// 实例化分页类 传入总记录数
		    $return_url=$image->thumb_auto($img_url,$return_url,'',$width,$height,true);
         } 
    }
    $return_url = file_get_contents($return_url);  //读取文件 
    echo  $return_url;
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
 * 对象转数组 hkj
  * @param string $member_id
 * @return string
 */
function obj_to_aar($obj) {
    if(is_object($obj)) {
        $obj = (array)$obj;
        $obj = obj_to_aar($obj);
    } elseif(is_array($obj)) {
        foreach($obj as $key => $value) {
            $obj[$key] = obj_to_aar($value);
        }
    }
    return $obj;
}

/**
	* +----------------------------------------------------------
	 * 剪切字符串函数
	* +-----------------------------------------------------------
	 * @return string
	* +-----------------------------------------------------------
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
function obj_to_aar($array){
	if(is_object($array)) {
        $array = (array)$array; 
        $array = obj_to_aar($array);   
     } if(is_array($array)) {  
         foreach($array as $key=>$value) {  
             $array[$key] = obj_to_aar($value);  
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


    //家政后台预约状态判断
	function check_status($param){
	switch($param){
		case '1':
			$data = "预约成功";
		break;
		case '2':
			$data = "已拒绝";
		break;
		case '3':
			$data = "已取消";
		break;
		case '4':
			$data = "已完成";
		break;
		default:
			$data = "待确定";
			break;
	}
	return $data;
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


?>