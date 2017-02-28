<?php
/****
** 商城订单
** 
**/
class GoodsorderAction extends AuthAction{

    //商品订单列表
	public function index(){
/*		if($_GET['is_upgrade']!=='' && $_GET['is_upgrade'] !== null ){
			$where['is_upgrade']=$_GET['is_upgrade'];
		}*/
		$where =array();
		//下单时间
		$start_time = 0;$end_time = -1;
		isset($_GET['start_time']) ? $start_time = strtotime($_GET['start_time']) : $_GET['start_time'] = '' ;
		isset($_GET['end_time']) ? $start_time = strtotime($_GET['end_time']) : $_GET['end_time'] = '' ;

		if ($end_time > 0 && $start_time > 0) {
			$where['add_time'] = array('between', array($start_time, $end_time));
		} else {
			if ($start_time > 0) {
				$where['add_time'] = array('egt', $start_time);
			}
			if ($end_time > 0) {
				$where['add_time'] = array('elt', $end_time);
			}
		}

		//付费时间
		$pay_start_time = 0 ; $pay_end_time = -1;

		isset($_GET['pay_start_time']) ? $pay_start_time = strtotime($_GET['pay_start_time']) : $_GET['pay_start_time'] = '' ;
		isset($_GET['pay_end_time']) ? $pay_end_time = strtotime($_GET['pay_end_time']) : $_GET['pay_end_time'] = '' ;

		if ($pay_end_time > 0 && $pay_start_time > 0) {
			$where['pay_time'] = array('between', array($pay_start_time, $pay_end_time));
		} else {
			if ($pay_start_time > 0) {
				$where['pay_time'] = array('egt', $pay_start_time);
			}
			if ($pay_end_time > 0) {
				$where['pay_time'] = array('elt', $pay_end_time);
			}
		}
		//手机查询
		isset($_GET['mobile']) ? $where['mobile']=array('like','%'.$_GET['mobile'].'%') : $_GET['mobile'] = '';
		//订单号查询
		isset($_GET['order_sn']) ? $where['order_sn']=array('like', '%'.$_GET['order_sn'].'%') : $_GET['order_sn'] = '';
		//收货人查询
		isset($_GET['consignee']) ? $where['consignee']=array('like', '%'.$_GET['consignee'].'%') : $_GET['consignee'] = '';


		//如果设置可统计就使用该赛选忽略下拉框
		if(isset($_GET['statistics']) && $_GET['statistics'] == 1){
			$where['pay_status'] = 2;//筛选代付款
			$where['user_del'] = 0;//筛选已删除
			$where['order_status'] = array('neq','3');//筛选无效订单
		}else{
			$_GET['statistics'] = 0;
			if(!isset($_GET['order_state'])){   //未设置默认取已付款
				$_GET['order_state']=2;
				$_REQUEST['order_state']=2;
			}

			if (isset($_GET['order_state'])) {
				if ($_REQUEST['order_state'] != 'all') {
					if ($_REQUEST['order_state'] == 3) {   //已完成
						$where['pay_status'] = 2;// 支付状态；0，未付款；1，付款中 ；2，已付款
						$where['shipping_status'] = 2;//商品配送情况，0，未发货； 1，已发货；2，已收货；3，备货中
					} elseif ($_REQUEST['order_state'] == 1) {  //待确认
						$where['pay_status'] = 2;// 支付状态；0，未付款；1，付款中 ；2，已付款
						$where['order_status'] = 0;//订单状态。0，未确认；1，已确 认；2，已取消；3，无效；4，退货；
						$where['shipping_status'] = array('neq', '2');//商品配送情况，0，未发货； 1，已发货；2，已收货；3，备货中
					} else if ($_REQUEST['order_state'] == 2) {   //已付款
						$where['order_status'] = array('not in', array(2, 3, 4));//订单状态
						$where['pay_status'] = 2;// 支付状态；0，未付款；1，付款中 ；2，已付款
						$where['shipping_status'] = array('neq', '2');//商品配送情况，0，未发货； 1，已发货；2，已收货；3，备货中
					} else if ($_REQUEST['order_state'] == 4) {   //退货
						$where['order_status'] = 4;
						$where['pay_status'] = 2;  // 支付状态；0，未付款；1，付款中 ；2，已付款
						$where['shipping_status'] = array('neq', '2');//商品配送情况，0，未发货； 1，已发货；2，已收货；3，备货中
					} else if ($_REQUEST['order_state'] == 5) {//已取消
						$where['pay_status'] = 0;// 支付状态；0，未付款；1，付款中 ；2，已付款
						$where['user_del'] = 1;//用户删除 状态1 已删除 0未删除
					} elseif ($_REQUEST['order_state'] == 6) {  //已配送
						$where['pay_status'] = 2;// 支付状态；0，未付款；1，付款中 ；2，已付款
						$where['order_status'] = 1;//订单状态。0，未确认；1，已确 认；2，已取消；3，无效；4，退货；
						$where['shipping_status'] = 1;//商品配送情况，0，未发货； 1，已发货；2，已收货；3，备货中
					} elseif ($_REQUEST['order_state'] == 7) {  //备货中
						$where['pay_status'] = 2;// 支付状态；0，未付款；1，付款中 ；2，已付款
						$where['order_status'] = 1;//订单状态。0，未确认；1，已确 认；2，已取消；3，无效；4，退货；
						$where['shipping_status'] = 3;//商品配送情况，0，未发货； 1，已发货；2，已收货；3，备货中
					} elseif ($_REQUEST['order_state'] == 8) {  //无效
						$where['order_status'] = 3;//订单状态。0，未确认；1，已确 认；2，已取消；3，无效；4，退货；
					} elseif ($_REQUEST['order_state'] == 9) {  //无效
						$where['order_status'] = 5;//订单状态。0，未确认；1，已确 认；2，已取消；3，无效；4，退货 5,售后中；
					} else {//0 待付款
						$where['order_status'] = 0;//订单状态。0，未确认；1，已确 认；2，已取消；3，无效；4，退货；
						$where['shipping_status'] = 0;//商品配送情况，0，未发货； 1，已发货；2，已收货；3，备货中
						$where['pay_status'] = $_REQUEST['order_state'];
						$where['user_del'] = 0;//用户删除 状态1 已删除 0未删除
					}
				}
			} else {
				$_GET['order_state'] = 'all';
			}
		}
		if($_GET['start_time']){
			$start_time=strtotime($_GET['start_time']);
		}
		if($_GET['end_time']){
			$end_time=strtotime($_GET['end_time'].'23:59:59');
		}
		if($end_time>0 && $start_time>0){
			$where['add_time']=array('between',array($start_time,$end_time));
		}else{
			if($start_time>0){
				$where['add_time']=array('egt',$start_time);
			}
			if($end_time>0){
				$where['add_time']=array('elt',$end_time);
			}
		}
		if(isset($_GET['order'])){
			$order=$_GET['order']." desc";
		}
		$filed = '*';
		$menulist=D("Common")->getPageList('g_order_info',$where,$filed,'pay_time desc , order_id desc');
		//print_r(M('g_order_info')->getlastsql());die;
		$order=$menulist['list'];
		$remind_time = time() - 5 * 86400;
		if($order){
			foreach ($order as $key => $value) {
				$order[$key]['show_order']=$this->get_order_status($value);
				if($value['invoice_no'] && $value['express_code'] && $value['is_sign_for'] != 1 && $value['shipping_time'] < $remind_time){
					$order[$key]['is_show_color'] = true;
				}else{
					$order[$key]['is_show_color'] = false;
				}
			}
		}

		$menulist['list']=$order;
		unset($order);
		$this->assign("list",$menulist);
//订单状态：0(已取消)10(默认):未付款;20:已付款;30:已发货;40:已收货;
		$order_state=array('0'=>'已取消','10'=>'待付款','20'=>'已付款','30'=>'已完成');
		$this->order_state=$order_state;
		$this->display();
		//$this->display();
	}

	//订单详情
	public function order_detail(){
		if($_POST){
			if($_POST['id']){
                $res = M("member")->where("id=".$_POST["id"])->save($_POST);
			}else{
                $res = M("member")->add($_POST);
			}
			if($res!==false){
				$this->success("操作成功",U("Admin/Member/index"));
			}else{
				$this->error("操作失败");
			}
		}else{
			$where["order_id"] =  array("eq",$_GET["id"]);
			$data = M("g_order_info")->where($where)->find();
			$member=M('member')->field('member_card,mobile')->where(array('id'=>$data['user_id']))->find();
			$data['member_card']=$member['member_card'];
			$show_order=$this->get_order_status($data);
			$data['code']=$show_order['code'];
			$data['code_name']=$show_order['name'];
			$data['mobile'] =$data['mobile']?$data['mobile']:$member['mobile'];
			$data['member_mobile'] = $member['mobile'];
			     ///商城返利比例
           $shop_rebate=M('sys_param')->where(array('param_code'=>'shop_rebate'))->getField('param_value');//返利比例；
           //总返利金额
           $this->discount_rebate=$data['order_amount']*$shop_rebate;//返利金额
			//$data
			//订单商品
			//$this->order_goods=M('g_order_goods')->where(array('order_id'=>$data["order_id"]))->select();
			$order_goods = M('g_order_goods')->where(array('order_id' => $data["order_id"]))->select();
            foreach ($order_goods as $key => $value) {
                $order_goods[$key]['seller_note'] = M('g_goods')->where(array('goods_id' => $value['goods_id']))->getField('seller_note');
            }
            $this->order_goods = $order_goods;
            
             $pre=C('DB_PREFIX');//表前缀
            //返利记录
            $order_rebate_record=M()->table($pre.'rebate_record record')//
            ->join($pre.'member men on men.id=record.member_id')//
            ->where(array('record.order_id'=>$data["order_id"], 'rebate_status'=>1))->field("record.*,men.member_card")->select();
			if($order_rebate_record){
				$m_member=M('member');
				foreach($order_rebate_record as $key=>$val){
					//
					$des=$val['des'];
					preg_match('/\(([a-z0-9]+)\)/', $des, $matches);
					if($matches){
						$rep=$matches[0];
						$member_card=$matches[1];
						$member_id=$m_member->where(array('member_card'=>$member_card))->getField('id');
						if($member_id){
							$rep_text='(<a href="'.U("Admin/Member/member_edit",array('id'=>$data['id'])).'" >'.$member_card.'</a>)';
							$order_rebate_record[$key]['des']=str_replace($rep,$rep_text,$des);
							unset($rep_text);
						}
					}
					unset($des);
					unset($matches);
				}
			}
			$this->order_rebate_record=$order_rebate_record;
            //订单日志
			$this->order_log=M('g_order_log')->where(array('order_id'=>$data["order_id"]))->select();


	        // 支付状态；0，未付款；1，付款中 ；2，已付款
			$pay_status=array('0'=>'未付款','1'=>'付款中','2'=>'已付款');
			$this->pay_status=$pay_status;
			//商品配送情况，0，未发货； 1，已发货；2，已收货；3，备货中
			$shipping_status=array('0'=>'未发货','1'=>'已发货','2'=>'已收货','3'=>'备货中');
			$this->shipping_status=$shipping_status;
			//订单状态。0，未确认；1，已确 认；2，已取消；3，无效；4，退货；
			$this->order_status=array('0'=>'未确认','1'=>'已确认','2'=>'已取消','3'=>'无效','4'=>'退货');
			$order_express = $this->express_query($data['order_id']);//物流信息
			$title="订单信息";
			//订单配送状态
			$status_before_deliver = $this->status_before_deliver_list($data);
			$this->assign("status_before_deliver",$status_before_deliver);
			$this->assign("order_express",$order_express);
			$this->assign("data",$data);
//			var_dump($data);die;
			$this->assign("msgtitle",$title);
			$this->display();
		}
	}
	
	//订单更改   zm 2015.10.15修改ajax返回值
	public function order_exchange(){
		if($_POST){
			$order_id=$_POST['order_id'];
			$order=M('g_order_info')->where(array('order_id'=>$order_id))->find();
			if(empty($order)){
				$this->error('订单不存在');
			}
			$show_order=$this->get_order_status($order);
            if($show_order['code']=='unconfirmed'){//未确认
               if(!isset($_POST['order_status'])){
               	 $this->error("订单状态错误,请刷新再试");
               }
               if($_POST['order_status']==1){//确认订单
                   $save_data['confirm_time']=time();
                   $save_data['order_status']=1;
                   $save_data['shipping_status']=3;//配货中
                      //记录订单日志
		              $log_data = array();
		              $log_data['order_id'] = $order['order_id'];
		              $log_data['log_role'] = 'seller';
		              $log_data['log_msg'] = '确认订单配货中';
		              $log_data['log_orderstate'] = '20';
               }else{//无效订单
               	   $save_data['confirm_time']=time();
                   $save_data['order_status']=3;
                    $log_data = array();
		              $log_data['order_id'] = $order['order_id'];
		              $log_data['log_role'] = 'seller';
		              $log_data['log_msg'] = '无效订单';
		              $log_data['log_orderstate'] = '0';
               }
            }elseif($show_order['code']=='nondelivery'){//未发货
               if(!isset($_POST['shipping_status'])){
               	 $this->error("订单状态错误,请刷新再试");
               }

				if ($_POST['express_id'] == "0") {
					$data['status'] = 0;
					$data['error'] = "请选择一家快递公司";
					echo json_encode($data);
					die;
				}
				$where_express['shipping_id'] = $_POST['express_id'];
				$express_name = M("g_shipping")->where($where_express)->find();
				if (!$express_name) {
					$data['status'] = 0;
					$data['error'] = "查找不到物流公司";
					echo json_encode($data);
					die;
				}
				$save_data['express_name'] = $express_name['shipping_name'];
				$save_data['express_code'] = $express_name['shipping_code'];
	            $save_data['shipping_time']=time();
	            $save_data['shipping_status']=1;//已发货
	            $save_data['invoice_no']=$_POST['invoice_no'];
	            $log_data = array();
              $log_data['order_id'] = $order['order_id'];
              $log_data['log_role'] = 'seller';
              $log_data['log_msg'] = '订单发货 发货单号：'.$save_data['invoice_no'];
              $log_data['log_orderstate'] = '30';
			}elseif($show_order['code']=='delivered'){//已发货
				if(!isset($_POST['shipping_status'])){
					$this->error("订单状态错误,请刷新再试");
				}

				if ($_POST['express_id'] == "0") {
					$data['status'] = 0;
					$data['error'] = "请选择一家快递公司";
					echo json_encode($data);
					die;
				}
				$where_express['shipping_id'] = $_POST['express_id'];
				$express_name = M("g_shipping")->where($where_express)->find();
				if (!$express_name) {
					$data['status'] = 0;
					$data['error'] = "查找不到物流公司";
					echo json_encode($data);
					die;
				}
				$save_data['express_name'] = $express_name['shipping_name'];
				$save_data['express_code'] = $express_name['shipping_code'];
				$save_data['invoice_no']=$_POST['invoice_no'];
				$log_data = array();
				$log_data['order_id'] = $order['order_id'];
				$log_data['log_role'] = 'seller';
				$log_data['log_msg'] = '运单修改 发货单号：'.$save_data['invoice_no'];
				$log_data['log_orderstate'] = '30';
            }else{
            	$this->error('订单状态错误，订单不可操作');
            }
			$res = M("g_order_info")->where(array('order_id'=>$order_id))->save($save_data);
			if($res!==false){
				if($save_data['order_status']==3){
					//无效订单 退还 付款金额
					if($order['pay_status']==2){//已付款
						$order_amount=$order['order_amount'];
                        if($order['offline']){//货到付款
						$order_amount=$order_amount-$order['offline_money'];//减去 货到付款 部分
						}
						if($order['surplus']){//余额支付
							$order_amount=$order_amount-$order['surplus'];//减去 余额支付 部分
							  $log=array();
				              $log['type']=4;//消费类型 1订单消费 2充值 3提现   4退款 5 收益 6认证消费
				              $log['order_id']=$order['order_id'];
				              $log['des']='无效订单退余额支付金额：￥'.$order['surplus'];
				              //加减用户 记录日志  状态 1收入 2支出  如果有赠送余额支付 扣除赠送余额
				              $surplus_return=$this->set_member_balance($order['user_id'],1,$order['surplus'],$log,$order['surplus_give']);
				              if($surplus_return){
			                    //记录订单日志
			                    $log_data_tui = array();
			                    $log_data_tui['order_id'] = $order['order_id'];
			                    $log_data_tui['log_role'] = 'seller';
			                    $log_data_tui['log_msg'] = '无效订单退余额支付金额：￥'.$order['surplus'];
			                    $log_data_tui['log_orderstate'] = '0';
			                    $insert = D('Mallorder')->addOrderLog($log_data_tui);
				              }
						}
						if($order_amount>0){
							//在线支付部分
                            $log=array();
				              $log['type']=4;//消费类型 1订单消费 2充值 3提现   4退款 5 收益 6认证消费
				              $log['order_id']=$order['order_id'];
				              $log['des']='无效订单退在线支付金额：￥'.$order_amount;
				              //加减用户 记录日志  状态 1收入 2支出  如果有赠送余额支付 扣除赠送余额
				              $surplus_return=$this->set_member_balance($order['user_id'],1,$order_amount,$log);
				              if($surplus_return){
			                    //记录订单日志
			                    $log_data_tui = array();
			                    $log_data_tui['order_id'] = $order['order_id'];
			                    $log_data_tui['log_role'] = 'seller';
			                    $log_data_tui['log_msg'] = '无效订单退在线支付金额：￥'.$order_amount;
			                    $log_data_tui['log_orderstate'] = '0';
			                    $insert = D('Mallorder')->addOrderLog($log_data_tui);
				              }
						}
					}
				}
				//如果是修改运单号
				if($show_order['code']=='delivered'){
					$express_cache = M('express_query_cache')->where(array('order_id='.$order_id))->delete();
				}
              //增加订单日志
              $insert = D('Mallorder')->addOrderLog($log_data);
				$data['status']=1;
				//确认或备货完成 给买家发送微信通知
              $send_data['order_sn'] = $order['order_sn'];
              $this->weixin_send($order['user_id'], $send_data, 2);
				echo json_encode($data);
			}else{
				$data['error']="操作失败";
				echo json_encode($data);
			}
		}else{
			$where["order_id"] =  array("eq",$_GET["id"]);
			$data = M("g_order_info")->where($where)->find();
			//订单商品
			$this->order_goods=M('g_order_goods')->where(array('order_id'=>$data["order_id"]))->select();

            //订单日志
			$this->order_log=M('g_order_log')->where(array('order_id'=>$data["order_id"]))->select();

            $this->show_order=$this->get_order_status($data);
	        // 支付状态；0，未付款；1，付款中 ；2，已付款
			$pay_status=array('0'=>'未付款','1'=>'付款中','2'=>'已付款');
			$this->pay_status=$pay_status;
			//商品配送情况，0，未发货； 1，已发货；2，已收货；3，备货中
			$shipping_status=array('0'=>'未发货','1'=>'已发货','2'=>'已收货','3'=>'备货中');
			$this->shipping_status=$shipping_status;
			//订单状态。0，未确认；1，已确 认；2，已取消；3，无效；4，退货；
			$this->order_status=array('0'=>'未确认','1'=>'已确认','2'=>'已取消','3'=>'无效','4'=>'退货');
			$title="订单信息";
			//显示快递公司
			$where_express['enabled'] = 1;
			$order_express = "shipping_order desc";
			$express = M("g_shipping")->where($where_express)->order($order_express)->select();
			//修改运单的时候 显示原来的 信息
			if(in_array($data['shipping_status'],array(1,2))){
			$shipping_info['invoice_no'] = $data['invoice_no'];
			$where_express['shipping_code'] =  $data['express_code'];
			$shipping_info['shipping_id'] = M("g_shipping")->where($where_express)->getField('shipping_id');
			$this->assign("shipping_info",$shipping_info);
			}
			$this->assign("express",$express);
			$this->assign("data",$data);
			$this->assign("msgtitle",$title);
			$this->display();
		}
	}

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

	//退货订单列表   10-13 gqh
	public function order_refund(){

		//0待确认 1已确认 2 用户已发货 换货单 3商家已收货（待发货） 4商家已发货 5用户已收货(已完成) 退货单 3商家已收货（待退款） 5已完成 10商户拒绝  6退款申请中
		$where = array();
		$status_arr=array(
			0=>'待确认',
			1=>'已确认',
			2=>'买家已发货',
			3=>'商家已收货',
		);
		$this->assign('status_arr',$status_arr);
		if(isset($_GET['refund_type'])&&$_GET['refund_type']!=='all'){
			$where['refund.refund_type']=$_GET['refund_type'];
			if(isset($_GET['refund_status'])&&$_GET['refund_status']!=='all'){
				$where['refund.refund_status']=$_GET['refund_status'];
			}else{
				$_GET['refund_status'] = 'all';
			}
		}else{
			$_GET['refund_type'] = 'all';
			$_GET['refund_status'] = 'all';
		}
		isset($_GET['member_card']) && $_GET['member_card'] != '' ? $where['member.member_card']=array('like','%'.$_GET['member_card'].'%') : $_GET['member_card'] = '';

		isset($_GET['order_sn']) && $_GET['order_sn'] != '' ? $where['ord.order_sn'] = array('like', '%'.$_GET['order_sn'].'%') : $_GET['order_sn'] = '' ;

		isset($_GET['refund_sn']) && $_GET['refund_sn'] != '' ? $where['refund.refund_sn'] = array('like', '%'.$_GET['refund_sn'].'%') : $_GET['refund_sn'] = '' ;
		$pre=C('DB_PREFIX');//表前缀
		import('ORG.Util.Page');// 导入分页类
		$field='refund.*,ord.order_sn,member.member_card,member.member_name';
		$joinOne=$pre.'member member on member.id=refund.member_id';
		$joinTwo=$pre.'g_order_info ord on ord.order_id=refund.order_id';
//		$joinThree=$pre.'g_order_goods goods on goods.order_id=refund.order_id';
		$order='refund.add_time desc';
		$count = M('g_order_refund')->alias('refund')->join($joinOne)
			->join($joinTwo)->where($where)->count();        //获取记录总数

		$page = new Page($count,'20');  //实例化分也类,传入数据总数及每页显示记录数量
		$show =$page->show();  //分页显示输出
		//进行分页数据查询 注意limit方法的参数要使用Page类的属性
		$list=M('g_order_refund')->table($pre.'g_order_refund refund')->field($field)->join($joinOne)
			->join($joinTwo)->where($where)->order($order)
			->limit($page->firstRow.','.$page->listRows)
			->select();
		$page=$show;
		//售后商品详细信息查询
		if(!empty($list)){
			foreach($list as $key =>$value){
				$gwhere['rec_id']= $list[$key]['order_goods_id'];
				//$gwhere['order_id']=$list[$key]['order_id'];
				$goods=M('g_order_goods')->field('goods_id,goods_image,goods_name,goods_price')
					->where($gwhere)->find();
				$where_chat=array();
				$where_chat['refund_id'] = $value['ref_id'];
				$where_chat['type'] = 1;
				$where_chat['is_read'] =0;
				$un_read=M('g_order_refund_chat')->where($where_chat)->count();
				$list[$key]['un_read'] =$un_read;
				$list[$key]['goods_id']=$goods['goods_id'];
				$list[$key]['goods_name'] = $goods['goods_name'];
				$list[$key]['goods_image'] = $goods['goods_image'];
				$list[$key]['goods_price'] = $goods['goods_price'];
				$list[$key]['show_status']= $this->getRefundStatus($list[$key]);

			}
		};
//		var_dump($list);die;
//		echo M()->_sql();
		$this->list=$list;
		$this->assign('page',$show);
//		var_dump($list);die;
		$this->display();
	}
	//退货订单详细页   10-13 gqh
	public function order_refDetail(){
		$where['refund.ref_id']=$_GET['id'];
		$pre = C('DB_PREFIX');//表前缀
		$field = 'refund.*,ord.order_sn,ord.order_amount,ord.pay_time,ord.shipping_fee,member.member_name,member_card,ord.surplus,ord.integral,ord.integral_money';
		$joinOne = $pre . 'member member on member.id=refund.member_id';
		$joinTwo = $pre . 'g_order_info ord on ord.order_id=refund.order_id';
		$msg = M()->table($pre.'g_order_refund refund')->field($field)->where($where)
			->join($joinOne)->join($joinTwo)->find();
		 if($msg['refund_img']){
          $msg['refund_img']=explode(',', $msg['refund_img']);
        }
		//查询售后商品详细信息
		//$gwhere['order_id']=$msg['order_id'];
		$gwhere['rec_id']=$msg['order_goods_id'];
		$goodsDetail=M('g_order_goods')->where($gwhere)->find();
		$msg['goods_name']=$goodsDetail['goods_name'];
		$msg['goods_image']=$goodsDetail['goods_image'];
		$msg['goods_price']=$goodsDetail['goods_price'];
		$msg['goods_attr']= $goodsDetail['goods_attr'];
		$msg['is_refund'] = $goodsDetail['is_refund'];
		$msg['order_amount']= sprintf("%.2f", $msg['order_amount']+$msg['shipping_fee']);  //商品总额加运费
		$msg['show_status']=$this->getRefundStatus($msg);
		$this->assign('msg', $msg);
//		var_dump($msg);die;
		//订单日志
		$where_chat['refund_id']=$_GET['id'];
		$where_chat['type']=1;
		$last_chat=M('g_order_refund_chat')->where($where_chat)->order('id desc')->getField('id');
		$this->assign('last_chat',$last_chat);
		$this->order_log = M('g_order_refund_log')->where(array('refund_id' => $msg["ref_id"]))->order('log_time desc')->select();
		//查看对话，未读信息清零
		$is_apply_check= M('g_order_refund_apply')->where(array('refund_id='=>$msg['ref_id'],'status'=>'0'))->count();
		if($is_apply_check == 0){
			$deny_remarks=M('g_order_refund_apply')->where(array('refund_id='=>$msg['ref_id'],'status'=>'2'))->order('id desc')->getfield('remarks');
			$this->assign('deny_remarks',$deny_remarks);
		}
		$this->assign('is_apply_check',$is_apply_check);
		M('g_order_refund_chat')->where($where_chat)->setField('is_read','1');
			$chat_list=M('g_order_refund_chat')->where('refund_id='.$_GET['id'])->select();
			foreach ($chat_list as $k =>$v){
				$chat_list[$k]['chat_img'] =explode(',',$v['img']);
			}
		//显示快递公司
		$where_express['enabled'] = 1;
		$order_express = "shipping_order desc";
		$express = M("g_shipping")->where($where_express)->order($order_express)->select();
		$this->assign("express",$express);
		$this->list=$chat_list;
		//物流信息
		$express_data['user']= $this->express_query('0',$msg['invoice_no_user'] ,$msg['invoice_no_code']);
		$express_data['shop']= $this->express_query('0',$msg['invoice_no_shop'] ,$msg['invoice_no_shop_code']);
		$this->assign('express_data',$express_data);
		$this->display();
	}

	//操作售后订单 10-13 gqh
	public function refundHandle(){
		if($_GET['id']){
			$where['ref_id']=$_GET['id'];
			$status['refund_status']='';
			switch ($_POST['status']){  //'0 待确认 1已确认 2 用户已发货 3商家已收货 4商家已发货 5用户已收货  \r\n10商户拒绝并继续协商',
				case 0: unset($status['refund_status']);
						$msg = '继续协商';
						$state='10';
						$status['shop_remark']= $_POST['shop_remark'];
					break;
				case 1: $status['refund_status']=1;
						$msg ='同意退货';
						$state='1';
						$status['return_goods'] = 1;//0 该商品直接售后 1该商品售后之前先退货
						$status['send_address']= $_POST['send_address'];
						$status['shop_remark']= $_POST['shop_remark'];
					break;
				case 2: $status['refund_status']=3;
						$msg= '确认收货';
						$state='3';
					break;
				case 3:$status['refund_status']=4;
						$msg = '商家发货';
						$status['refund_type'] =0;
						$state='3';
					break;
				case 4:
					$status['refund_status'] = 6;
					$msg = '申请退款';
					$status['refund_type'] =1;
					$state = '6';
					break;
				default : $state['refund_status']=5;
						$msg='完成';
						$state='5';
			}
			if (isset($_POST['express_code'])) {  //商家发货快递
				$invoice_express=M('g_shipping')->where('shipping_id='.$_POST['express_code'])->find();
				$status['invoice_no_shop_code'] = $invoice_express['shipping_code'];
				$status['invoice_no_shop_name'] = $invoice_express['shipping_name'];
			}
			if(isset($_POST['invoice_no_shop'])){  //商家发货单号
				$status['invoice_no_shop']= $_POST['invoice_no_shop'];
			}
			$refund=M('g_order_refund')->where(array('ref_id'=>$_GET['id']))->find();
			$goods=M('g_order_goods')->where(array('rec_id'=>$refund['order_goods_id']))->find();
			$re=M('g_order_refund')->where($where)->save($status);
//			echo M()->_sql();die;
//			var_dump($refund);die;
			if($re!==false){
				///退货 退款
				if(isset($_POST['total'])){
					//if ($refund['refund_status'] == '3' && $_POST['status'] == '4' && $refund['refund_type'] == '1' && $_POST['ref_price'] > 0) {//未付款 已支付的订单退款 （余额支付）
					if ( $_POST['status'] == '4' && $_POST['total'] > 0) {
						$g_order_info=M('g_order_info')->where(array('order_id'=>$refund['order_id']))->find();
						$ref_price=$_POST['ref_price'];
						$log = array();
						$log['type'] = 4;//消费类型 1订单消费 2充值 3提现   4退款 5 收益 6认证消费
						$log['order_id'] = $refund['order_id'];
						 $refund_apply= M('g_order_refund_apply')->where(array('refund_id'=>$refund['ref_id'],'status'=>'0'))->find();
						if($refund_apply){
							$this->error('请待财务审核之后再次申请');
							die();
						}else{
							$refund_apply_model=M('g_order_refund_apply');
							$apply_save['refund_id'] =$refund['ref_id'];
							$apply_save['total'] =$_POST['total'];
							$apply_save['wx_refund'] =$_POST['wx_refund'];
							$apply_save['balance'] = $_POST['balance'];
							$apply_save['type'] = $_POST['is_refund'];
							$apply_save['integral'] = $_POST['points'];
							$apply_save['add_time'] = time();
							$apply_save['remarks1'] = $_POST['remarks1'];
							$ram=$refund_apply_model->add($apply_save);
						}
						$log['des'] = '后台申请退款';
						$msg=$log['des'];
					}
				}
				//更新订单状态  记录订单日志
/*				if($_POST['status'] == 3){
					$where_r_c['order_id']=$refund['order_id'];
					$where_r_c['refund_status']=array('neq','5');
					$where_r_c['member_id']=$this->uid;
					$re_unfinish_count=M('g_order_refund')->where($where_r_c)->count();
					//若该订单是最后的订单
					if($re_unfinish_count == 0){
						$where_x='order_id='.$refund['order_id'];//该订单id
						//订单商品数
						$order_goods_count=M('g_order_goods')->where($where_x)->count();
						//退单数
						$re_count =M('g_order_refund')->where($where_x)->count();
						M('g_order_info')->where($where_x)->setField('order_status','1');//将订单状态改为 待收货
					}
				}*/

				$log = array();
				$log['refund_id'] = $_GET['id'];
				$log['order_id']=$refund['order_id'];
				$log['log_msg'] = $msg;
				$log['log_time'] = time();
				$log['log_role'] = "商家";
				$log['log_orderstate'] = $state;
				//回复客户
				$refund_apply_have=M('g_order_refund_apply')->where(array('refund_id'=>$refund['ref_id']))->count();
				if($_POST['status'] != '4' || $refund_apply_have <=1){
					if($_POST['status'] == 1){
						$save_chat['content']=$msg.','.$_POST['shop_remark'].',退货地址-'.$_POST['send_address'];
					}elseif($_POST['status'] == 3){
						$save_chat['content']=$msg.',发货单:'.$status['invoice_no_shop_name'].'-'.$status['invoice_no_shop'];
					}else{
						$save_chat['content']=$_POST['shop_remark']?$_POST['shop_remark']:$msg;
					}
					$save_chat['log_orderstate']=$state;
					$save_chat['add_time'] = time();
					$save_chat['type'] = 2;
					$save_chat['member_id'] = $refund['member_id'];
					$save_chat['refund_id']=$_GET['id'];
					$save_chat['pid'] =$_GET['chat_id'];
					$chat=M('g_order_refund_chat')->add($save_chat);
				}
				M('g_order_refund_log')->add($log);
				$this->success('操作成功');
			}else{
				$this->error('操作失败');
			}
		}else{
			$this->error('没有找到该退款订单');
		}
	}
	//
	public function refundChat(){
		$add_chat['pid']=$_GET['chat_id'];
		$add_chat['refund_id'] = $_GET['id'];
		$add_chat['content'] = $_POST['shop_remark'];
		//$refund_img=$_POST['refund_img']?implode(',', $_POST['refund_img']):'';
		//$add_chat['img'] =$refund_img;
		$add_chat['time'] =time();
		$add_chat['type'] = 1 ;//2，商家 1，买家
		$last_chat=M('g_order_refund_chat')->where('id='.$_POST['chat_id'])->find();//getField('log_orderstate');
		$add_chat['member_id'] = $last_chat['member_id'];
		$add_chat['log_orderstate'] = 6;
		$return_data['status']=1;
		$re= M('g_order_refund_chat')->add($add_chat);
		if($re){
			$return_data['status']=1;
			echo json_encode($return_data);die;
		}else{
			$return_data['error']="提交失败";
			echo json_encode($return_data);die;
		}
	}


//	获取售后单状态  10-13 gqh
	public function getRefundStatus($status=array()){
		if(empty($status)){
			return null;
		}
		//refund_type  售后类型=> 0换货 1退货'
		//refund_status 当前售后状态  =>0 待确认 1已确认 2 用户已发货 3商家已收货 4商家已发货 5售后完结 10繼續協商　
		$refund=array(0=>'待商家处理',1=>'待买家发货',2=>'待商家收货',3=>'商家已收货',5=>'退款成功',6=>'返款申请中',10=>'继续协商');    //退货状态
		$swop=array(0=>'待商家处理',1=>'待买家发货',2=>'待商家收货',3=>'商家已收货',4=>'待买家收货',5=>'售后完结',6=>'返款申请中',10=>'继续协商');      //换货状态
		if($status['refund_type']){  //退货
			return $refund[$status['refund_status']];
		}else{  //换货
			return $swop[$status['refund_status']];
		}
	}
	//新付费或者退款订单产生时提示铃声   10-19  gqh
	public function OrderRemind(){
		if($_POST['status']){   //status=>订单状态,1查询代表新付费订单,0查询新退货订单
			$where['pay_status'] =2;
			$re = M('g_order_info')->where($where)->count();
			echo json_encode($re);
		}else{
			$re = M('g_order_refund')->count();
			echo json_encode($re);
		}
	}
	
	//為訂單填寫留言
	public function edit_to_buyer(){
		$order_id = $_POST['order_id'];
		$to_buyer = $_POST['to_buyer'];
		$re= M('g_order_info')->where('order_id='.$order_id)->setField('to_buyer',$to_buyer);
		if($re){
			$this->success('操作成功');
		}else{
			$this->error('操作失敗');
		}
	}
}
?>