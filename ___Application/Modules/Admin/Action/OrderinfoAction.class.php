<?php
/****
** 收益列表    zm
** 
**/
class OrderinfoAction extends AuthAction
{
	public static $db = array();

	//订单收益 zm
	public function index()
	{
//        var_dump($_GET);die;
		$where = array();
		if (isset($_GET['start_time'] )) {   //开始时间
			$star_time = strtotime($_GET['start_time']);
		}else{
			$_GET['start_time']  = '';
			$star_time = 0;
		}

		if (isset($_GET['end_time'])) {   //结束时间
			$end_time = strtotime($_GET['end_time']);
		}else{
			$_GET['end_time'] = '' ;
			$end_time = 0 ;
		}
		if ($star_time > 0 && $end_time > 0) {
			$where['info.finnshed_time'] = array('between', array($star_time, $end_time));
		}

		$where['info.pay_status'] = 2;// 支付状态；0，未付款；1，付款中 ；2，已付款
		$where['info.shipping_status'] = 2;//商品配送情况，0，未发货； 1，已发货；2，已收货；3，备货中
		$where['info.order_status'] = 1;//订单状态。0，未确认；1，已确 认；2，已取消；3，无效；4，退货；
		$pre = C('DB_PREFIX');//表前缀
		$count = M()->table($pre . 'g_order_info info')//
		->join($pre . 'member mem on info.user_id=mem.id')//
		->where($where)//
		->count();
		$page = D("Common")->getPage($count);//分页
		$field = "info.*,mem.member_card";

		$data = M()->table($pre . 'g_order_info info')
			->join($pre . 'member mem on info.user_id=mem.id')//
			->where($where)//
			->field($field)->limit($page["start"] . "," . $page["pagesize"])->select();
		if ($data){
			foreach ($data as $k => $v) {
				$data[$k]['order_amount'] = sprintf("%.2f", $v['order_amount']) + sprintf("%.2f", $v['shipping_fee']);//邮费
				$data[$k]['order_rebate'] = sprintf("%.2f", $v['order_rebate']);
				$data[$k]['order_profit'] = sprintf("%.2f", $v['order_profit']);
				$data[$k]['order_profit_all'] = sprintf("%.2f", $v['order_profit_all']);

			}
		}

		$list["list"] = $data;
		$list["page"] = $page["page"];
		$this->assign("list", $list);
		$where = array();
		if ($_GET['start_time']) {   //开始时间
			$star_time = strtotime($_GET['start_time']);
		}
		if ($_GET['end_time']) {   //结束时间
			$end_time = strtotime($_GET['end_time']);
		}
		if ($star_time > 0 && $end_time > 0) {
			$where['finnshed_time'] = array('between', array($star_time, $end_time));
		}
		$where['pay_status'] = 2;// 支付状态；0，未付款；1，付款中 ；2，已付款
		$where['shipping_status'] = 2;//商品配送情况，0，未发货； 1，已发货；2，已收货；3，备货中
		$where['order_status'] = 1;//订单状态。0，未确认；1，已确 认；2，已取消；3，无效；4，退货；
		$goodsorder_model = M('g_order_info');
		$this->finnshed_time = $goodsorder_model->where($where)->sum('finnshed_time');//完成时间
		$this->shipping_status = $goodsorder_model->where($where)->sum('shipping_status');//商品配送情况，0，未发货； 1，已发货；2，已收货；3，备货中
		$this->order_amount = sprintf("%.2f", $goodsorder_model->where($where)->sum('order_amount'));//应付款金额
		$this->order_rebate = sprintf("%.2f", $goodsorder_model->where($where)->sum('order_rebate'));//订单返利金额 返出去的
		$this->order_profit = sprintf("%.2f", $goodsorder_model->where($where)->sum('order_profit'));//订单利润= 应付款金额-订单返利
		$this->order_profit_all = sprintf("%.2f", $goodsorder_model->where($where)->sum('order_profit_all'));//订单返利总金额 返利池

		$this->display();
	}

	public function order_list()
	{
		$order_info = M('g_order_info');
		if (IS_POST) {
			$_POST['start_time'] = strtotime($_POST['start_time']);//选择时间段条件
			if ($_POST['end_time']) {//无结束时间为当前时间
				$_POST['end_time'] = strtotime($_POST['end_time']);
			} else {
				$_POST['end_time'] = time();
			}

			$data = $order_info->where("finnshed_time>" . $_POST['start_time'] . " and finnshed_time<" . $_POST['end_time'])->select();
		} else {
			$data = $order_info->where(array('order_status' => 1))->select();
		}
		$date = array();
		/* 遍历每个月的销量数 */
		foreach ($data as $v) {
			$m = date('m', $v['finnshed_time']);
			if ($m == 11 || $m == 12) {/* 不查找11月12月的数据 */
				continue;
			}
			if ($date[$m]['m'] == $m) {
				$date[$m]['s'] += 1;
			} else {
				$date[$m]['m'] = $m;
				$date[$m]['s'] = 1;
			}
		}

		for ($i = 1; $i <= 12; $i++) {
			foreach ($date as $k => $v) {
				if ($k == $i) {
					$db[$k] = array();
					array_push($db[$k], $v['m'], $v['s']);
				}
			}
		}
		foreach ($db as $v) {
			$da[] = $v[1];
			$s[] = $v[0] . "月";
		}
		$this->assign('da', $db);
		$this->assign('db', json_encode($da));//传出json对象（销量）给模版的js语句
		$this->assign('s', json_encode($s));//传出json对象（月份）给模版的js语句
		$this->display();
	}


	public function refund_apply()
	{
		$where = array();

		isset($_GET['mobile']) ? $where['mem.mobile'] = array('eq', $_GET['mobile']) : $_GET['mobile'] = '';
		isset($_GET['member_card']) ? $where['mem.member_card'] = array('like', '%'.$_GET['member_card'] . '%') : $_GET['member_card'] = '';
		isset($_GET['order_sn']) ? $where['ord.order_sn'] = array('like', '%'.$_GET['order_sn'] . '%') : $_GET['order_sn'] = '';
		isset($_GET['refund_sn']) ? $where['order_sn'] = array('like', '%'.$_GET['refund_sn'] . '%') : $_GET['refund_sn'] = '';
		if (isset($_GET['status'])) {
			if ($_GET['status'] != 'all') {
				$where['ap.status'] = $_GET['status'];
			}
		} else {
			$_GET['status'] = 'all';
		}
		//type 1商家提现 2会员提现

		//$menulist=D("Common")->getPageList('cash_apply',$where);

		$count = M()->table('db_g_order_refund_apply ap')//
		->join('db_g_order_refund ref on ap.refund_id=ref.ref_id')//
		->join('db_g_order_info ord on ord.order_id = ref.order_id')
		->join('db_member mem on mem.id = ref.member_id')
		->where($where)->count();
		$page = D("Common")->getPage($count);//分页

		$field = "ap.*,ref.refund_sn,ref.order_id,ref.member_id,ref.order_goods_id,mem.member_name,mem.member_card,ord.order_sn,ord.order_amount";

		$list = M()->table('db_g_order_refund_apply ap')
			->join('db_g_order_refund ref on ap.refund_id=ref.ref_id')//
			->join('db_g_order_info ord on ord.order_id = ref.order_id')
			->join('db_member mem on mem.id = ref.member_id')
			->where($where)->field($field)//
			->order('ap.add_time desc')->limit($page['start'] . ',' . $page['pagesize'])->select();
		if($list){
			foreach ($list as $k => $v) {
				$list[$k]['goods_price'] = M('g_order_goods')->where('rec_id=' . $v['order_goods_id'])->getField('goods_price');
			}
		}

		$menulist['list'] = $list;
		$menulist['page'] = $page['page'];
//		print_r($list);
		//status 状态 0 拒绝提现  1提现申请中 2财务打款中 3已提现
		$this->status_arr = array('0' => '未审核', '1' => '同意返款', '2' => '拒绝返款');
		$this->assign("list", $menulist);
		$this->display();
	}


	public function refund_apply_detail()
	{
		$field = "ap.*,ref.refund_sn,ref.order_id,ref.member_id,ref.order_goods_id";
		$where['id'] = $_GET['id'];
		$data = M()->table('db_g_order_refund_apply ap')
			->join('db_g_order_refund ref on ap.refund_id=ref.ref_id')//
			->where($where)->field($field)->find();
		if(!$data){
			$this->error('退款申请未找到！');
		}
		$member_info = M('member')->where('id=' . $data['member_id'])->find();
		$order_goods = M('g_order_goods')->where('rec_id=' . $data['order_goods_id'])->find();
		$order_info = M('g_order_info')->where('order_id=' . $data['order_id'])->find();
		if(!$order_info){
			$this->error('退款订单不存在！');
		}
		$order_trade_no = M('g_order_pay')->where('pay_id = ' . $order_info['pay_record_id'])->getField('trade_no');
		$is_weix = empty($order_trade_no) ? 0 : 1 ;
		$wx_refund_data['transaction_id'] = $order_trade_no;
		$wx_refund_data['out_trade_no'] = $order_info['order_sn'];
		$wx_refund_data['out_refund_no'] = $data['wx_refund_id'];
		$wx_refund_data['refund_id'] = $data['refund_sn'];
		$list['wx_refund_data'] = $wx_refund_data;
		$list['member'] = $member_info;
		$list['order'] = $order_info;
		$list['order_goods'] = $order_goods;
		$this->is_wx = $is_weix;
		$this->list = $list;
		//print_r($list);
		$this->data = $data;
		$this->display();
	}

	//执行退款审核
	public function do_refund()
	{
		$refund_apply_id = $_POST['id'];
		$status = $_POST['auid_status'];
		$remarks = $_POST['remarks'];
		$refund_apply = M('g_order_refund_apply')->where('id=' . $refund_apply_id)->find();
		$where['ref_id'] = $refund_apply['refund_id'];
		$refund = M('g_order_refund')->where($where)->find();
		$apply_save = array();
		$re = '';
		//退款金额
		$integral = $refund_apply['integral'];
		$balance = $refund_apply['balance'];
		$wx_refund = $refund_apply['wx_refund'];
		if ($status == 2) {
			$log_msg = '财务拒绝退款';
			$log_orderstate = 6;
			$apply_save['remarks'] = $remarks;
		} elseif ($status == 1) {
			$ref_save['refund_status'] = 5;
			$ref_save['refund_type'] = 1;
			$ref_save['refund_money'] = $balance;
			$ref_save['refund_points'] = $integral;
			$ref_save['refund_wx'] = $wx_refund;
			$refund_modle = M('g_order_refund');
			$refund_modle->startTrans();//开启事务
			$re = $refund_modle->where($where)->save($ref_save);
			if ($re) {
				$g_order_info = M('g_order_info')->where(array('order_id' => $refund['order_id']))->find();
				$total = $refund_apply['total'];
				$log = array();
				$log['type'] = 4;//消费类型 1订单消费 2充值 3提现   4退款 5 收益 6认证消费
				$log['order_id'] = $refund['order_id'];
				$log['des'] = '商品售后返还：';
				if ($wx_refund > 0) {
					$wx_msg = $this->wx_refund($g_order_info['order_sn'], $refund['refund_sn'], $wx_refund);
					if ($wx_msg['return_code'] == 'SUCCESS' && $wx_msg['return_msg'] == 'OK' && $wx_msg['result_code'] == 'SUCCESS') {
						$log['des'] .= '微信￥' . $wx_refund;
						$apply_save['wx_refund_id'] = $wx_msg['refund_id'];
						//更新支付信息 加入退款金额
						M('g_order_pay')->where('pay_sn='.$g_order_info['order_sn'])->setInc('refund_money',$wx_refund);
					} else {
						$refund_modle->rollback();//回滚
						$this->error($wx_msg['err_code_des'] . '&&' . $wx_msg['return_msg']);
					}
				}
				if ($integral > 0) {
					///退款先退 积分
					$log['des'] .= '积分' . $integral;
					$res_i = $this->set_member_points($refund['member_id'], 1, $integral, $log);//退款更新用户积分
				}
				if ($balance > 0) {
					$log['des'] .= '余额￥' . $balance;
					$res_b = $this->set_member_balance($refund['member_id'], 1, $balance, $log, 0);//退款更新用户余额
				}
				$log_orderstate = 5;
				$log_msg = '商品售后返还:积分' . $integral . '余额￥' . $balance . '微信钱包￥' . $wx_refund;
			} else {
				$refund_modle->rollback();//回滚
				$this->error('操作错误');
			}
			$refund_modle->commit();//提交事务
		}
		//更新订单状态  记录订单日志
		//if ($refund['refund_status'] == '3' && $_POST['status'] == '4' && $refund['refund_type'] == '1') {
		if ($status == 1) {
			$g_where = array('rec_id' => $refund['order_goods_id']);
			$save_g_order = array();
			$save_g_order = array('yes_refund' => 1, 'refund_time' => time());
			M('g_order_goods')->where($g_where)->save($save_g_order);///更新订单产品 退货状态
			//更新订单状态  记录订单日志
			$this->goods_refund_status($refund['order_id']);
		}
		//记录订单日志
		//更改申请状态
		$apply_save['status'] = $status;
		M('g_order_refund_apply')->where('id=' . $refund_apply_id)->save($apply_save);
		//添加修改记录
		$log = array();
		$log['order_id'] = $refund['order_id'];
		$log['refund_id'] = $refund_apply['refund_id'];
		$log['log_msg'] = $log_msg;
		$log['log_time'] = time();
		$log['log_role'] = "系统";
		$log['log_orderstate'] = $log_orderstate;
		M('g_order_refund_log')->add($log);
		$last_chat_id=M('g_order_refund_chat')->where(array('refund_id'=>$refund['ref_id'],'log_orderstate'=>'6','type'=>2))->getField('id');
		if ($re) {
			$save_chat['content'] = $log_msg;
			$save_chat['log_orderstate'] = $log_orderstate;
			$save_chat['add_time'] = time();
			$save_chat['type'] = 2;
			$save_chat['member_id'] =$refund['member_id'];
			$save_chat['refund_id'] = $refund['ref_id'];
			$save_chat['pid'] = $last_chat_id;
			$chat = M('g_order_refund_chat')->add($save_chat);
		}
		$this->success('操作成功');
	}

	//申请微信退款
	public function wx_refund($out_trade_no, $out_refund_no, $wx_refund_money)
	{
		$order_info = M('g_order_info')->where('order_sn="'.$out_trade_no.'"')->find();
		if(empty($order_info) ){
			$refund_data['err_code_des'] = 'order_info';
			$refund_data['return_msg'] = '订单信息错误';
			return  $refund_data;
		}
		
		$order_pay_info = M('g_order_pay')->where('pay_id="'.$order_info['pay_record_id'].'"')->find();
		if(empty($order_pay_info) ){
			$refund_data['err_code_des'] = 'order_pay';
			$refund_data['return_msg'] = '支付信息错误';
			return  $refund_data;
		}
		$order_wx_money = $order_info['order_amount'] - $order_info['surplus'] - $order_info['integral_money'];
		$total_fee = $order_pay_info['total_money'] ? $order_pay_info['total_money'] : $order_wx_money;
		$refund_data['out_trade_no'] = $order_pay_info['pay_sn'];
		$refund_data['transaction_id'] = $order_pay_info['trade_no'];
		$refund_data['total_fee'] = $total_fee;
		$refund_data['refund_fee'] = $wx_refund_money;
		$refund_data['Out_refund_no'] = $out_refund_no;
		require_once './api/payment/wpay/refund.php';
		return refund_wx($refund_data);
	}
	//查询微信退款信息


	//判断该订单所有 商品退单情况
	public function goods_refund_status($order_id)
	{
		$where_r_c['order_id'] = $order_id;
		$where_r_c['refund_status'] = array('neq', '5');
		$re_unfinish_count = M('g_order_refund')->where($where_r_c)->count();
		//若该订单没有未完成
		if ($re_unfinish_count == 0) {
			$where_x['order_id'] =$order_id;//该订单id
			$order_goods_count = M('g_order_goods')->where($where_x)->count();//订单商品数
			$re_count = M('g_order_refund')->where($where_x)->count();//退单数
			$where_rm['order_id'] =$order_id;
			$where_rm['refund_type'] = 1 ;// 0换货 1退款
			$where_rm['refund_status'] = 5;//已完成
			$re_rm_count = M('g_order_refund')->where($where_rm)->count();//退款商品数
			$order_save['order_status']=1;
			if($re_count == $order_goods_count){
				if ($re_count == $re_rm_count) {//如果退款数等于退单数 则将订单状态改为退货
					$order_save['finnshed_time'] =time();
					$order_save['order_status']=4;
				}
			}
			M('g_order_info')->where($where_x)->save($order_save);
		}
	}
}
//echo strtotime('2015-05-15 15:19:34');