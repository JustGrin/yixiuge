<?php

class WeixinAction extends BaseAction {

	public function __construct(){
		parent::__construct();
		$this->get_oauth2_token();
	}
     public function index(){

     }
    public function get_oauth2_token(){
    	$user_agent = $_SERVER['HTTP_USER_AGENT'];
		if (strpos($user_agent, 'MicroMessenger') === false) {
		    // 非微信浏览器 不调取用户的 openid
		    session('user_open_error',1);
		    return false;
		}
		$user_open_id=session('user_open_id');
		$user_open_error=session('user_open_error');
		//未登录 未获取 openid
		if(empty($_SESSION["member"]['uid']) && empty($user_open_id) && !empty($user_open_error)){
		    
		}else{
			return false;
		}
       $user_open_id=session('user_open_id');
       $user_code=$_GET['code'];
      session('user_code',$user_code);
       $get_arr=$_GET;
       $user_code=session('user_code');
       if(empty($user_code))
       $appid=C('APPID');
         $appsecret=C('APPSECRET');
         unset($get_arr['_URL_']);
         $url=GROUP_NAME.'/'.MODULE_NAME.'/'.ACTION_NAME;
         $redirect_uri="http://".$_SERVER['HTTP_HOST'].U($url,$get_arr);
         //https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx520c15f417810387&redirect_uri=https%3A%2F%2Fchong.qq.com%2Fphp%2Findex.php%3Fd%3D%26c%3DwxAdapter%26m%3DmobileDeal%26showwxpaytitle%3D1%26vb2ctag%3D4_2030_5_1194_60&response_type=code&scope=snsapi_base&state=123#wechat_redirect
         $get_code_url="https://open.weixin.qq.com/connect/oauth2/authorize?appid={$appid}&redirect_uri={$redirect_uri}&response_type=code&scope=snsapi_base&state=123#wechat_redirect";
      if(empty($user_code)){
         redirect($get_code_url);die;
      }else{//获取 openid
      	$code=$user_code;
      	$get_appid_url="https://api.weixin.qq.com/sns/oauth2/access_token?appid={$appid}&secret={$appsecret}&code={$code}&grant_type=authorization_code";
        $str=$this->curl_https($get_appid_url);
	     $str=json_decode($str);
	     if(empty($str->errcode)){
	        $user_open_id=$str->openid;//获取到的凭证
	        $expires_in=$str->expires_in;//凭证有效时间，单位：秒
	        session('user_open_id',$user_open_id);
	        session('user_open_error',null);
	     }else{
	     	 session('user_open_error',1);
	     }
      }
      //return $access_token;
    }  

}