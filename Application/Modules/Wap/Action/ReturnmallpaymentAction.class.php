<?php
/**
 * 商城支付回调
 *
 *
 */

require_once "./api/payment/wpay/lib/WxPay.Api.php";
require_once './api/payment/wpay/lib/WxPay.Notify.php';
//require_once './api/payment/wpay/log.php';


class ReturnmallpaymentAction extends Action{

	public function __construct() {
		parent::__construct();
	}
	/**
	* 通用通知接口demo
	* ====================================================
	* 支付完成后，微信会把相关支付和用户信息发送到商户设定的通知URL，
	* 商户接收回调信息后，根据需要设定相应的处理流程。
	* 
	* 这里使用log文件形式记录回调信息。 以及更新订单状态等
	*/
	public function index() {
		//初始化日志
		$logHandler= new CLogFileHandler("./api/payment/wpay/logs/".date('Y-m-d').'.log');
		$log = Logs::Init($logHandler, 15);

		Logs::DEBUG("\n begin notify :");
		$notify = new PayNotifyCallBack();
		$notify->Handle(false);
		// $notify->NotifyProcess();
		Logs::DEBUG("\n end notify");
	}
	
	/**
	* 通用通知接口demo
	* ====================================================
	* 支付完成后，微信会把相关支付和用户信息发送到商户设定的通知URL，
	* 商户接收回调信息后，根据需要设定相应的处理流程。
	* 
	* 这里使用log文件形式记录回调信息。 以及更新订单状态等
	*/
	public function test() {
		//初始化日志
		$logHandler= new CLogFileHandler("./api/payment/wpay/logs/".date('Y-m-d').'.log');
		$log = Logs::Init($logHandler, 15);
		 
		Logs::DEBUG("\n begin test");
		$notify = new PayNotifyCallBack();
		$notify->send_test();
	}

	//定时发送
	/*
	$time = '2010-05-27 12:11';
	$res = sendSMS($uid,$pwd,$mobile,$content,$time);
	echo $res;
	*/
	function sendSMS($order_sn=null,$money=null){
		if(empty($order_sn) || empty($money)){
			return false;
		}
		$mobile=C('SENDMOBILE');
		if(empty($mobile)){
			return false;
		}
		Logs::DEBUG("\n短信发送中:$mobile  .. $order_sn   ... $money");
		$http = 'http://http.yunsms.cn/tx/';//接口地址 'http://接口地址/tx/';
		$content="FG峰购产生一条新订单，订单号：".$order_sn."，金额,￥".$money."，时间".date("Y-m-d H:i")."微信支付";
		$data = array
		(
			'uid'=>C('SENDUID'),          //用户账号
			'pwd'=>strtolower(md5(C('SENDPWD'))), //MD5位32密码
			'mobile'=>$mobile,        //号码
			'content'=>$content,      //内容 如果对方是utf-8编码，则需转码iconv('gbk','utf-8',$content); 如果是gbk则无需转码
			'time'=>$time,    //定时发送
			'mid'=>$mid,           //子扩展号
			'encode'=>'utf8'
		);
		$re= $this->postSMS($http,$data);      //POST方式提交
		$return_data['status']=0;
		if( trim($re) == '100' )
		{
			$datas=json_encode($data);
			//$return_data['status']=1;
			//return $return_data;
			Logs::DEBUG("\n短信发送成功:".$datas);
		}else{
			$datas=json_encode($data);
			//return "发送失败! 状态：".$re;
			$return_data['error']="";
			Logs::DEBUG("\n短信发送失败:".$datas);
		}
	}
	
	public function postSMS($url,$data=''){
		$post='';
		$row = parse_url($url);
		$host = $row['host'];
		$port = $row['port'] ? $row['port']:80;
		$file = $row['path'];
		while (list($k,$v) = each($data)){
			$post .= rawurlencode($k)."=".rawurlencode($v)."&"; //转URL标准码
		}
		$post = substr( $post , 0 , -1 );
		$len = strlen($post);
		$fp = @fsockopen( $host ,$port, $errno, $errstr, 10);
		if(!$fp){
			return "$errstr ($errno)\n";
		}else{
			$receive = '';
			$out = "POST $file HTTP/1.0\r\n";
			$out .= "Host: $host\r\n";
			$out .= "Content-type: application/x-www-form-urlencoded\r\n";
			$out .= "Connection: Close\r\n";
			$out .= "Content-Length: $len\r\n\r\n";
			$out .= $post;    
			fwrite($fp, $out);
			while (!feof($fp)){
				$receive .= fgets($fp, 128);
			}
			fclose($fp);
			$receive = explode("\r\n\r\n",$receive);
			unset($receive[0]);
			return implode("",$receive);
		}
	}


}

class PayNotifyCallBack extends WxPayNotify{
	//查询订单
	public function Queryorder($transaction_id)
	{    
		$input = new WxPayOrderQuery();
		$input->SetTransaction_id($transaction_id);
		$result = WxPayApi::orderQuery($input);
		Logs::DEBUG("query:" . json_encode($result));
		if(array_key_exists("return_code", $result) && array_key_exists("result_code", $result) && $result["return_code"] == "SUCCESS" && $result["result_code"] == "SUCCESS")
		{
			return true;
		}
		return false;
	}
    
    //重写回调处理函数
    public function NotifyProcess($data, &$msg)
    {
        Logs::DEBUG("\n call back:" . json_encode($data));
        $notfiyOutput = array();
        
        if(!array_key_exists("transaction_id", $data)){
			
            $msg = "输入参数不正确";
			 Logs::DEBUG("\n ERROR:" . $msg);
            return false;
        }
		// Logs::DEBUG("------noerror1---------");
        //查询订单，判断订单真实性
        if(!$this->Queryorder($data["transaction_id"])){
            $msg = "订单查询失败";
			 Logs::DEBUG("\n ERROR:" . $msg);
            return false;
        }
		// Logs::DEBUG("------noerror---------");
         //商户订单号
            $out_trade_no = $data['out_trade_no'];
            //交易号
            $trade_no = $data['transaction_id'];
            //支付接口代码
			//支付金额
			$pay_arr['total_fee'] = $data['total_fee'];
			//
			$pay_arr['end_time'] =$data['end_time'];
            $payment_code = 'wxpay';
            //验证成功      
            $model_order = D('Mallorder');
            $model_payment = D('Mallpayment');

            $pay_record_id = M("g_order_pay")->where(array('pay_sn'=>$out_trade_no))->getField('pay_id');
        //pay_status 支付状态；0，未付款；1，付款中 ；2，已付款
            $order_list = $model_order->getOrderList(array('pay_record_id'=>$pay_record_id,'pay_status'=>'0'));

            $result = $model_payment->updateProductBuy($out_trade_no, $payment_code, $order_list, $trade_no,$pay_arr);
            if(empty($result['error'])) {
                $money=$data["cash_fee"]/100;


                //购买成功  查询是否是会员 不是会员则升级为会员
                $log=array();
/*                $member=M('member')->where(array('id'=>$order_list['user_id']))->field('id, member_card, member_vip_type')->find();
                if($order_list['is_upgrade']){//升级订单
                    if($member && $member['member_vip_type']<2) {
                       // M('member')->where(array('id' => $order_list['user_id']))->save(array('member_vip_type' => 1,'vip_time'=>time()));
                        //$upgrade_ob = A('Common');
						$ress = $this->upgrade_level($out_trade_no);
						Logs::DEBUG("\n 收益返回 : \n" . json_encode($ress));
						Logs::DEBUG("\n 会员" . $member['member_card'] . "升级VIP成功");
                        $log['des'] = '用户使用微信支付￥' . $money . '升级会员成功';
                    }else{
                        $log['des'] = '用户使用微信支付￥'.$money;
                    }
                }else{*/
                    $log['des'] = '用户使用微信支付￥'.$money;
                //}

                //计算本次需要在线支付的订单总金额
                $time = time();

				$pay_amount = 0;
				$pay_amount += PriceFormat(floatval($order_list['shipping_fee']));//运费
				///减去余额支付部分 减去折扣部分
				///折扣是否过期
				if ($order_list['discount_start_time'] <= $time && $order_list['discount_end_time'] >= $time) {
					$pay_amount += PriceFormat(floatval($order_list['order_amount']) - floatval($order_list['surplus']) - floatval($order_list['integral_money']) - floatval($order_list['discount']));
				} else {
					$pay_amount += PriceFormat(floatval($order_list['order_amount']) - floatval($order_list['surplus']) - floatval($order_list['integral_money']));
				}
				if ($order_list['offline']) {
					$pay_amount -= $order_list['offline_money'];//
				}
				$log['des'] = '用户使用微信支付￥'.$pay_amount;
			
                    //记录订单日志
                    $data = array();
                    $data['order_id'] = $order_list['order_id'];
                    $data['log_role'] = 'buyer';
                    $data['log_msg'] = $log['des'];
                    $data['log_orderstate'] = '20';
                    $insert = $model_order->addOrderLog($data);

                //支付完成 给卖家发送微信通知
			/*	$common_action = A("Common");
                for ($i=0; $i<count($order_list); $i++) { 
                    $goods_msg = '';
                    $order_goods = M("g_order_goods")->where(array('order_id' => $order_list['order_id']))->select();
                    for ($j=0; $j<count($order_goods); $j++) { 
                        $goods_msg = $goods_msg."，".$order_goods[$j]['goods_name'];
                    }
                    $send_data['member_id'] = $order_list['user_id'];
                    $send_data['goods_msg'] = substr($goods_msg, 3);
                    $common_action->weixin_send($order_list['share_uid'], $send_data, 1);
                }*/

                Logs::DEBUG("\n update success:订单更新成功");
                // $rep=new ReturnmallpaymentAction();
                // $rep->sendSMS($out_trade_no,$money);
                return true;
            }else{
				 Logs::DEBUG("\n update error:" . json_encode($result));
               return false;
            }
        
        return true;
    }
	//升级订单支付后回调升级信息
	public function upgrade_level($pay_sn){
		//g_order_info
		//member_level_order
		//member
		$order_sn = M('g_order_pay')->where('pay_sn="'.$pay_sn.'"')->getField('order_sn');
		$where['order_sn']=$order_sn;
		$order_info=M('g_order_info')->where($where)->find();
		if($order_info['is_upgrade']){
			$where1['id'] = $order_sn;
			$where1['pay_status'] = 0;
			$level_order_info=M('member_level_order')->where($where1)->find();
			if (!empty($level_order_info)){

				if($level_order_info['last_level'] == 1){   //标准店升级到高级店 的订单直接改为完成
					$order_data['order_status'] = 1;
					$order_data['shipping_status'] = 2;
					M("g_order_info")->where('order_sn="'.$level_order_info['id'].'"')->save($order_data);
				}

				//更新升级订单
				M('member_level_order')->where($where1)->setField('pay_status','1');
				//更新用户信息：升级等级，升级订单状态，升级时间
				$where2['id']=$order_info['user_id'];
				$member_data['member_vip_type']=$level_order_info['levelId'];
				$member_data['member_vip_order']=$level_order_info['levelId'];
				$member_data['vip_time']=time();
				//升级开启奖池
				$jackpot_member=M('jackpot_member')->where('member_id='.$order_info['user_id'])->find();
				$jackpot_data['member_id']=$order_info['user_id'];
				$jackpot_data['c_time']=$member_data['vip_time'];
				if($jackpot_member){
					M('jackpot_member')->where('member_id='.$order_info['user_id'])->save($jackpot_data);
				}else{
					M('jackpot_member')->add($jackpot_data);
				}
				//将开店积分变为可用
				// $member_detail = M('member_detail')->where('member_id='.$order_info['user_id'])->find();
				// if ($member_detail['present_points'] >= C('PRESENT_POINTS')) {
				// if ($level_order_info['last_level']==0) {
				// $detail_data['points'] = $member_detail['points'] + C('PRESENT_POINTS');
				// $detail_data['present_points'] = $member_detail['present_points'] - C('PRESENT_POINTS');
				// $save_member_detail = M("member_detail")->where('member_id='.$order_info['user_id'])->save($detail_data);
				// $member_integral['member_id'] = $order_info['user_id'];
				// $member_integral['integral'] = $level_order_info['pay_price'];
				// $member_integral['status'] = 1;
				// $member_integral['type'] = 1;
				// $member_integral['des'] = "首次开店获得积分：".C('PRESENT_POINTS');
				// $member_integral['order_id'] = $order_info['order_sn'];
				// $member_integral['add_time'] = time();
				// $member_integral_add = M("member_integral")->add($member_integral);
				// }
				// }

				//在下级会员升级后查询上级会员升级旗舰店的条件
				$member = M('member')->where(array('id'=>$order_info['user_id']))->find();
				if ($member) {
					if (!empty($member['recommend_code'])) {
						$indirect_num = M('member')->where(array('recommend_code'=>$member['recommend_code'], 'member_vip_type'=>array('gt', 0)))->count();
						$indirect1_num = M('member')->where(array('indirect_recommend_code'=>$member['recommend_code'], 'member_vip_type'=>array('gt', 0)))->count();
						if ($indirect_num >= 10 && $indirect1_num >= 20) {
							$member_data1['member_vip_type'] = 3;
						}
					}
					if (!empty($member['indirect_recommend_code'])) {
						$indirect_num2 = M('member')->where(array('recommend_code'=>$member['indirect_recommend_code'], 'member_vip_type'=>array('gt', 0)))->count();
						$indirect1_num2 = M('member')->where(array('indirect_recommend_code'=>$member['indirect_recommend_code'], 'member_vip_type'=>array('gt', 0)))->count();
						if ($indirect_num2 >= 10 && $indirect1_num2 >= 20) {
							$member_data2['member_vip_type'] = 3;
						}
					}
				}

				/*if($level_order_info['last_level']==0){
					$log = array();
					$log['order_id'] = $order_info['order_id'];
					$log['des'] = '开店赠送积分：500';
					$this->set_member_points($order_info['user_id'], 1, 500, $log);
				}*/

				//如果有是有人分享的链接来开店，更新推荐人
				$share_info = '';
				$share_detail = '';
				if(isset($order_info['share_uid']) && $order_info['share_uid']){
					$share_info = M('member')->where('id='.$order_info['share_uid'])->find();
					$share_detail = M('member_detail')->where('member_id='.$order_info['share_uid'])->find();
				}
				if($share_info && $share_detail && $level_order_info['last_level'] == 0){
					$member_data['recommend_code'] = $share_info['member_card'];
					$member_data['indirect_recommend_code'] = $share_info['recommend_code'];
					$member_data['indirect2_recommend_code'] = $share_info['indirect_recommend_code'];
					$member_data_detail['relevel'] = $share_detail['relevel'] + 1;
					$member_data_detail['repath'] = $share_detail['repath'].','.$order_info['user_id'];
					M('member_detail')->where('member_id='.$share_info['id'])->setInc('t_m_fans_add');
				}
				if(isset($member_data_detail) && $member_data_detail){
					M('member_detail')->where(array('member_id'=>$order_info['user_id']))->save($member_data_detail);
				}

				$member_db = M('member');
				$member_db->where($where2)->save($member_data);
				if(!empty($member['recommend_code']) && isset($member_data1['member_vip_type']) && $member_data1['member_vip_type'] == 3){
					$member_db->where(array('member_card'=>$member['recommend_code'], 'member_vip_type'=>2))->save($member_data1);
					// $member_db->where('member_card="'.$member['recommend_code'].'" and member_vip_type = 2')->save($member_data1);
				}
				if(!empty($member['indirect_recommend_code']) && isset($member_data2['member_vip_type']) && $member_data2['member_vip_type'] == 3){
					$member_db->where(array('member_card'=>$member['indirect_recommend_code'], 'member_vip_type'=>2))->save($member_data2);
					// $member_db->where('member_card="'.$member['indirect_recommend_code'].'" and member_vip_type = 2')->save($member_data2);
				}

				//利润分配
				$ret_exchange = D('Profit')->set_member_exchange($order_info['order_id']);
				if(empty($ret_exchange)){
					$ret_exchange = '空';
				}
				return $ret_exchange;
			}else{
				return  "该订单不存在";
			}
		}else{
			return  "该订单不是升级订单";
		}
	}
}

//以下为日志

interface ILogHandler
{
    public function write($msg);
    
}

class CLogFileHandler implements ILogHandler
{
    private $handle = null;
    
    public function __construct($file = '')
    {
        $this->handle = fopen($file,'a');
    }
    
    public function write($msg)
    {
        fwrite($this->handle, $msg, 4096);
    }
    
    public function __destruct()
    {
        fclose($this->handle);
    }
}

class Logs
{
    private $handler = null;
    private $level = 15;
    
    private static $instance = null;
    
    private function __construct(){}

    private function __clone(){}
    
    public static function Init($handler = null,$level = 15)
    {
        if(!self::$instance instanceof self)
        {
            self::$instance = new self();
            self::$instance->__setHandle($handler);
            self::$instance->__setLevel($level);
        }
        return self::$instance;
    }
    
    
    private function __setHandle($handler){
        $this->handler = $handler;
    }
    
    private function __setLevel($level)
    {
        $this->level = $level;
    }
    
    public static function DEBUG($msg)
    {
        self::$instance->write(1, $msg);
    }
    
    public static function WARN($msg)
    {
        self::$instance->write(4, $msg);
    }
    
    public static function ERROR($msg)
    {
        $debugInfo = debug_backtrace();
        $stack = "[";
        foreach($debugInfo as $key => $val){
            if(array_key_exists("file", $val)){
                $stack .= ",file:" . $val["file"];
            }
            if(array_key_exists("line", $val)){
                $stack .= ",line:" . $val["line"];
            }
            if(array_key_exists("function", $val)){
                $stack .= ",function:" . $val["function"];
            }
        }
        $stack .= "]";
        self::$instance->write(8, $stack . $msg);
    }
    
    public static function INFO($msg)
    {
        self::$instance->write(2, $msg);
    }
    
    private function getLevelStr($level)
    {
        switch ($level)
        {
        case 1:
            return 'debug';
        break;
        case 2:
            return 'info';  
        break;
        case 4:
            return 'warn';
        break;
        case 8:
            return 'error';
        break;
        default:
                
        }
    }
    
    protected function write($level,$msg)
    {
        if(($level & $this->level) == $level )
        {
            $msg = "\r\n ".'['.date('Y-m-d H:i:s').']['.$this->getLevelStr($level).'] '.$msg."\n";
            $this->handler->write($msg);
        }
    }



   } 