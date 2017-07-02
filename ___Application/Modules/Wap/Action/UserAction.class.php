<?php

class UserAction extends BaseAction {

	public function __construct(){
		parent::__construct();
		$this->check_login();
		$this->weixin_share();
		if(!IS_AJAX){
			$this->now_menu='member';
		}
	}

    public function check_login(){
    	if(IS_AJAX){
    		$data['status']=0;
    		$data['error']="登录超时，请重新登录，";
    		$data['msg']="登录超时，请重新登录，";
    		if(empty($_SESSION["member"])){
				echo json_encode($data);
			    exit();
			}
			if(empty($_SESSION["member"]['uid'])){
				echo json_encode($data);
		        exit();
			}
    		
    	}
    $url=GROUP_NAME.'/'.MODULE_NAME.'/'.ACTION_NAME;
    $url=strtolower($url);//大写转成小写
    $not_check=array(
        'wap/usernotice/tweet_detail',
        'wap/activity/index'
      );
    if(in_array($url, $not_check)){
       $user_agent = $_SERVER['HTTP_USER_AGENT'];
      if(strpos($user_agent, 'MicroMessenger') === false) {
           die('请在微信中打开');
      }
    }
		if(empty($_SESSION["member"])){
			redirect(U("wap/Login/index"));
		    exit();
		}
		if(empty($_SESSION["member"]['uid'])){
			redirect(U("wap/Login/index"));
		    exit();
		}
    }
    ///微信分享  接口数据
    public function weixin_share(){
      if(!IS_AJAX){
		    $shop_info=$this->shop_info;
			  $shar_title = $shop_info['member_name'].'的店铺';//C('SHAR_TITLE');//分享标题
			  $this->shar_url = $url = "http://" . $_SERVER['HTTP_HOST'] . U('wap/index/index', array('share' => $this->shop_code));//分享地址
			  $this->shar_title = $shar_title;///分享标题
			  $this->shar_desc = C('SHAR_DESC');///分享内容
			  $this->shar_imgurl = "http://" . $_SERVER['HTTP_HOST'] .$shop_info['member_logo'];///分享图片地址
            $this->get_weixin();///获取微信 信息   
      }
    } 

     //获取今天中奖信息
  public function get_today_draw(){
        $uid=$this->uid;
        $todaytime=strtotime(date('Y-m-d',time()));
        $tmdaytime=strtotime(date('Y-m-d',strtotime("+1 day")));
        $where['member_id']=$uid;
        $where['add_time']=array('between',array($todaytime,$tmdaytime));
        $data=M('draw_log')->where($where)->find();
        $data=$this->get_draw_msg($data);
        return $data;
  }  
   //获取奖品信息
  public function get_draw_msg($draw_data=array()){
      if(empty($draw_data)){
        return array();
      }
     if($draw_data){
      //1积分 2金币 3话费4折扣5商品
      $draw_data['goods_name']=""; 
       switch ($draw_data['draw_type']) {
         case '1':
           $draw_data['draw_type_name']='积分';
           $draw_data['draw_num']=$draw_data['draw_value'];
           $draw_data['draw_value_unit']="积分"; //单位 
           break;
         case '2':
           $draw_data['draw_type_name']='金币';
           $draw_data['draw_num']=$draw_data['draw_value'];
           $draw_data['draw_value_unit']="金币"; //单位 
           break;
         case '3':
           $draw_data['draw_type_name']='话费';
           $draw_data['draw_num']=$draw_data['draw_value'];
           $draw_data['draw_value_unit']="元"; //单位 
           break;
         case '4':
           $draw_data['draw_type_name']='折扣';
           $draw_data['draw_num']=sprintf("%.1f", $draw_data['draw_value']);
           $draw_data['draw_value_unit']="折"; //单位 
            break; 
         case '5':
           $goods_name=M('g_goods')->where(array('goods_id'=>$draw_data['draw_value']))->getField('goods_name');
           $draw_data['draw_type_name']='商品';  
             $draw_data['goods_name']=$goods_name; 
            $draw_data['draw_num']=1;
            $draw_data['draw_value_unit']="件"; //单位 
           break;
         default:
          $draw_data=array();
           break;
       }
     }
     return $draw_data;
  }

   public function get_refund_status($refund=array()){
      if(empty($refund)){
        return array();
      } 
      //0 待确认 1已确认 2 用户已发货 3商家已收货 4商家已发货 5用户已收货  10商户拒绝
       $refund_arr=array('0'=>'等待售后客服确认','1'=>'已确认，等待买家发回商品',//
        '2'=>'买家已退货','3'=>'FG峰购已收到货品',//
        '4'=>'FG峰购已补发货品','5'=>'完成补发','10'=>'不同意补发');
       $refund['refund_type_name']='换货';
      if($refund['refund_type']){//0换货 1退款
         $refund_arr=array('0'=>'等待售后客服确认','1'=>'r已确认,买家发货',//
        '2'=>'买家已退货','3'=>'FG峰购已收到货品',//
        '6'=>'财务退款中','5'=>'完成退款','10'=>'商家拒绝');
         $refund['refund_type_name']='退款';
      }
      $refund['code']=$refund['refund_status'];
      $refund['code_name']=$refund_arr[$refund['refund_status']];
     return $refund;
    }

   //获取订单状态
	public function get_order_status($order_info=array()){
		if(empty($order_info)){
			return array();
		}
// pay_status支付状态；0，未付款；1，付款中 ；2，已付款
// shipping_status 商品配送情况，0，未发货； 1，已发货；2，已收货；3，备货中
//order_status订单状态。0，未确认；1，已确 认；2，已取消；3，无效；4，退货；
		$order_status=array('0'=>'未确认','1'=>'已确认','2'=>'已取消','3'=>'无效','4'=>'已退货','5'=>'售后申请中');
		$shipping_status=array('0'=>'待发货','1'=>'待收货','2'=>'已完成','3'=>'备货中');
		$code=$name="";
		if($order_info['order_status']==1){//已确认
			switch ($order_info['shipping_status']) {
				case '1':
					$code="delivered";//已发货
					break;
				case '2':
					$code="received";//已收货
					break;
				default:
					$code='nondelivery';//未发货
					break;
			}
			return array('code'=>$code,'name'=>$shipping_status[$order_info['shipping_status']]);
		}else{//未确认
			//2已取消；3无效；4，退货；
			if(in_array($order_info['order_status'], array('2','3','4'))){
				return array('code'=>'finish','name'=>$order_status[$order_info['order_status']]);
			}elseif ($order_info['order_status'] == 5){
				return array('code'=>'after_sale','name'=>$order_status[$order_info['order_status']]);
			}else{////未确认
				if($order_info['pay_status']==2){//已付款 待确认
					return array('code'=>'unconfirmed','name'=>'待确认');
				}else{
					return array('code'=>'nopay','name'=>'未付款');
				}
			}
		}
	}







}