<?php
// 计划任务
class PlangoodsorderAction extends CommonAction {
	//魔术方法
	public function __construct(){
		parent::__construct();
	}
	public function index(){
		
	}

	private function write_log($msg = ''){
		$log_path = './data/order_plan/'.date('Y').'/'.date('Y_m_d').'.log';
		Log::write($msg, 'INFO', 3, $log_path);
	}

	//自动 确认收货 计划任务
	public function order_delivered_plan(){
		$where['pay_status'] = 2;// 支付状态；0，未付款；1，付款中 ；2，已付款
		$where['shipping_status'] = 1;//商品配送情况，0，未发货； 1，已发货；2，已收货；3，备货中
		$where['order_status'] = 1;//订单状态。0，未确认；1，已确 认；2，已取消；3，无效；4，退货；5,售后处理中
		$where['is_upgrade'] = 0;
		$time=time()-24*3600*7;//发货后7天系统自动确认收货
		//小于 7天之前的 时间
		$where['shipping_time'] = array('elt',$time);//订单状态。0，未确认；1，已确 认；2，已取消；3，无效；4，退货；
		$pagesize = 10;
		$p = isset($_REQUEST['p']) && !empty($_REQUEST['p']) ? $_REQUEST['p'] : 1;

		// Log::write($p."确认收货计划任务开始：".date("Y-m-d H:i:s"), 'INFO', 3, $log_path);
		$this->write_log($p."确认收货计划任务开始：".date("Y-m-d H:i:s"));
		echo "确认收货计划任务开始：".date("Y-m-d H:i:s");

		$start = ($p - 1) * $pagesize;
		//$field = "order_id,order_sn,order_status,shipping_status,pay_status,goods_amount,order_amount,add_time";
		$field = "*";
		$order_model = M('g_order_info');
		$list = $order_model->where($where)->field($field)->limit($start, $pagesize)->order('shipping_time asc,order_id asc')->select();
		if($list){
			foreach ($list as $key => $value) {
				$this->order_delivered($value);
			}
			//sleep(秒)  usleep(毫秒)  让它睡上一会。
			sleep(2);
			$_REQUEST['p']=$p+1;
			$this->order_delivered_plan();
		}
		$this->write_log("确认收货计划任务END：".date("Y-m-d H:i:s"));
		echo "确认收货计划任务END：".date("Y-m-d H:i:s");
	}
	
	//确认收货
	public function order_delivered($data_info=array()){
		if(empty($data_info)){
			// Log::write("计划任务订单不存在：".date("Y-m-d  H:i:s"));
			$this->write_log("计划任务订单不存在：".date("Y-m-d H:i:s"));
			return false;
		}
		$order_show=$this->get_order_status($data_info);
		if($order_show['code']!='delivered'){//已发货
			// Log::write("计划任务订单状态错误：订单号：".$data_info['order_sn']."，".date("Y-m-d  H:i:s"));
			$this->write_log("计划任务订单状态错误：订单号：".$data_info['order_sn']."，".date("Y-m-d  H:i:s"));
			return false;
		}
		$refund=M('g_order_goods_refund')->where(array('member_id'=>$data_info['user_id'],'order_id'=>$data_info['order_id']))->find();
		if(!empty($refund) && $refund['refund_status']!='10'){//
			// Log::write("计划任务订单退货中 不可确认收货：订单号：".$data_info['order_sn']."，".date("Y-m-d  H:i:s"));
			$this->write_log("计划任务订单退货中 不可确认收货：订单号：".$data_info['order_sn']."，".date("Y-m-d  H:i:s"));
			return false;
		}
		$model_order = D('Mallorder');
		//查询商家信息
		$model_order->startTrans();
		$o_where["order_id"] =  array("eq",$data_info["order_id"]);
		//更改订单状态
		$data = array();
		$data['shipping_status']  = 2;
		$data['finnshed_time']  = time();
		$order_res = $model_order->editOrder($data,$o_where);
		//记录商家日志
		if($order_res!==false){
			//记录订单日志
			$data = array();
			$data['order_id'] = $data_info['order_id'];
			$data['log_role'] = 'system';//系统
			$data['log_msg'] = '系统自动确认收货';
			$data['log_orderstate'] = '40';
			$insert = $model_order->addOrderLog($data);

			///用户返利 返积分 认证等
			if($data_info['activity_id'] != 1){
				$ress = D('Profit')->set_member_exchange($data_info['order_id']);
				if(isset($ress['status']) && $ress['status']){
					// Log::write("计划任务订单自动确认收货分配利润成功，订单号：".$data_info['order_sn']);
					$this->write_log("计划任务订单自动确认收货分配利润成功，订单号：".$data_info['order_sn']);
				}else{
					// Log::write("计划任务订单自动确认收货分配利润错误，订单号：".$data_info['order_sn'].'('.$ress['msg'].')');
					$this->write_log("计划任务订单自动确认收货分配利润错误，订单号：".$data_info['order_sn'].'('.$ress['msg'].')');
				}
			}

			$model_order->commit();
			echo "计划任务订单自动确认收货成功：订单号：".$data_info['order_sn']."，".date("Y-m-d  H:i:s").'<br/>';
			// Log::write("计划任务订单自动确认收货成功：订单号：".$data_info['order_sn']."，".date("Y-m-d  H:i:s"));

			$this->write_log("计划任务订单自动确认收货成功：订单号：".$data_info['order_sn']."，".date("Y-m-d  H:i:s"));
			return true;
		}else{
			// Log::write("计划任务订单自动确认收货失败：订单号：".$data_info['order_sn']."，".date("Y-m-d  H:i:s"));
			$this->write_log("计划任务订单自动确认收货失败：订单号：".$data_info['order_sn']."，".date("Y-m-d  H:i:s"));
			return false;
		}
	}

	//获取订单状态
	public function get_order_status($order_info=array()){
		if(empty($order_info)){
			return array();
		}
		// pay_status支付状态；0，未付款；1，付款中 ；2，已付款
		// shipping_status 商品配送情况，0，未发货； 1，已发货；2，已收货；3，备货中
		//order_status订单状态。0，未确认；1，已确 认；2，已取消；3，无效；4，退货；
		$order_status=array('0'=>'未确认','1'=>'已确认','2'=>'已取消','3'=>'无效','4'=>'已退货');
		$shipping_status=array('0'=>'未发货','1'=>'已发货','2'=>'已完成','3'=>'备货中');
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