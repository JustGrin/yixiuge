<?php
// 前台控制器，继承公共目录下的HomeAction，方便公共数据调用
class FarmAction extends CommonAction{
	//魔术方法
	public function __construct(){
		parent::__construct();
	}

    public function index(){
		// $this->display();
	}
	
	public function curl_https($url=''){
		if (empty($url)) {
			return false;
		}
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_HEADER, 1);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);//这个是重点。
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); // https请求 不验证证书和hosts
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		$data = curl_exec($curl);
		curl_close($curl);
		$receive = explode("\r\n\r\n",$data);
		unset($receive[0]);
		return implode("",$receive);
	}
	
	//获取用户 openid
	public function get_oauth2_openid(){
		$user_agent = $_SERVER['HTTP_USER_AGENT'];
		if (strpos($user_agent, 'MicroMessenger') === false) {
			// 非微信浏览器 不调取用户的 openid
			//session('user_open_error',1);
			return false;
		}
		
		$user_code=$_GET['code'];
		session('user_code',$user_code);
		$get_arr=$_GET;
		$user_code=session('user_code');
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
				return $str->openid;
			}else{
				return false;
			}
		}
	}

	public function farm_agreement(){//基地协议
		$open_id = $this->get_oauth2_openid();
		if(!$open_id){
			$this->error('打开失败，请稍后用微信重试！');
		}
		$this->open_id = $open_id;
		$time = time();
		$farm_id = $_REQUEST['farm_id'];
		if(!$farm_id){
			$this->error('参数错误');
		}
		$farm_info = M('base')->where(array('base_id'=>$farm_id))->find();
		if(!$farm_info){
			$this->error('基地不存在');
		}
		
        $order_model = M('g_order_info');
        $order_pay_model = M('g_order_pay');
		$farm_order = $order_model->where(array('farm_id'=>$farm_info['base_id'], 'order_type'=>3))->find();
		if(empty($farm_order)){
			$order_sn = makeOrderSn($farm_info['base_id']); //生成订单号
			$farm_order = array();
			$farm_order['order_sn'] = $order_sn;
			$farm_order['order_type'] = 3;
			$farm_order['user_id'] = 0;
			$farm_order['farm_id'] = $farm_info['base_id'];
			$farm_order['consignee'] = $farm_info['base_name'];
			$farm_order['is_gift'] = 0;//活动类型0 无 1 限时抢购
			$farm_order['is_upgrade'] = 0;//升级产品
			$farm_order['goods_amount'] = isset($farm_info['pledge_money']) && $farm_info['pledge_money'] > 0 ? $farm_info['pledge_money'] : 1000;//商品总金额
			$farm_order['order_amount'] = isset($farm_info['pledge_money']) && $farm_info['pledge_money'] > 0 ? $farm_info['pledge_money'] : 1000;//应付款金额
			$farm_order['order_cost'] = 0;//成本
			$farm_order['pay_note'] = I('pay_note');
			$farm_order['add_time'] = $time;
			$add_order_res = $order_model->add($farm_order);
			if($add_order_res){
				$pay_sn = makePaySn($farm_info['base_id']); //生成支付单号
				//订单付款信息
				$order_pay_data['buyer_id'] = 0;
				$order_pay_data['pay_sn'] = $pay_sn;
				$order_pay_data['order_sn'] = $order_sn;
				$order_pay_data['add_time'] = $time;
				$order_pay_data['total_money'] = isset($farm_info['pledge_money']) && $farm_info['pledge_money'] > 0 ? $farm_info['pledge_money'] : 1000;;
				$add_order_pay = $order_pay_model->add($order_pay_data);
				$order_model->where(array('order_id'=>$add_order_res))->save(array('pay_record_id'=>$add_order_pay));
			}
		}
		
		$farm_order = $order_model->where(array('farm_id'=>$farm_info['base_id']))->find();
		$farm_pay_order = $order_pay_model->where(array('pay_id'=>$farm_order['pay_record_id']))->find();
		
		$this->farm_pay_order = $farm_pay_order;
		$this->farm_order = $farm_order;
		$this->farm_info = $farm_info;
		$this->display();
	}

}