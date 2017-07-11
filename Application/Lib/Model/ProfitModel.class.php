<?php
class ProfitModel extends Model {

	public function __construct(){
		$this->log_path = './data/profit/'.date('Y').'/'.date('m').'/';
		if (!is_dir($this->log_path)){
			mkdir(iconv("UTF-8", "GBK", $this->log_path),0744,true);
		}
		$this->log_path .= date('Y_m_d').'.log';
	}

	private function write_log($msg = ''){
		Log::write($msg, 'INFO', 3, $this->log_path);
	}

	/*
    *
    * 利润分配
    * $data_info array：订单信息，必须包括order_id , int：order_id;
    * 返回 status 0错误（msg 为错误信息） 1成功
    * kkxx 2016-10-08
    */
	public function set_member_exchange($order_id = array()){
		$time = time();
		$ret = array();
		$ret['status'] = 0;
		if(!$order_id){
			$ret['msg'] = '参数错误！';
			$this->write_log($ret['msg']);
			return $ret;
		}
		$g_order_info = M('g_order_info');
		$g_order_goods = M('g_order_goods');
		$g_purchases = M('g_purchases');
		// $order_info = $g_order_info->where(array('order_id'=>409))->find();
		$order_info = $g_order_info->where(array('order_id'=>$order_id))->find();
		if (empty($order_info)){
			$ret['msg'] = '没有找到订单';
			$this->write_log($ret['msg'].'['.$order_id);
			return $ret;
		}

		if($order_info['pay_status'] != 2){
			$ret['msg'] = '订单没有付款';
			$this->write_log($order_info['order_sn'].'['.$order_info['order_id'].'] : '.$ret['msg']);
			return $ret;
		}

		if($order_info['is_rebate'] == 1){
			$ret['msg'] = '订单已经成功分配';
			$this->write_log($order_info['order_sn'].'['.$order_info['order_id'].'] : '.$ret['msg']);
			return $ret;
		}

		// $pre = C('DB_PREFIX');//表前缀
		$commonob = A('Common');
		$order_profit = 0;
		$goods_names = array();
		$goods_ids = array();

		//订单包涵的商品
		$order_goods = $g_order_goods->where(array('order_id'=>$order_info['order_id']))->select();
		foreach($order_goods as $order_goods_item){
				$unit_profit = isset($order_goods_item['gift_integral']) && $order_goods_item['gift_integral'] ? mb_number($order_goods_item['gift_integral']) : 0 ;//供货基地 的利润平台需要扣除1元
			$order_profit += $unit_profit * $order_goods_item['goods_number'];
			$goods_names []= $order_goods_item['goods_name'];
			$goods_ids[] =$order_goods_item['goods_id'];
			if ($order_profit <= 0){
				$this->write_log($order_info['order_sn'].'['.$order_info['order_id'].'] :  分配用户:'.$order_info['user_id'].' 积分错误'.$order_profit);
				continue;
			}
			M('g_goods')->where('goods_id='.$order_goods_item['goods_id'])->setInc('goods_salesvolume',$order_goods_item['goods_number']);
		}
		$log = array();
		$log['type']        = 5;//消费类型 1订单消费 2充值 3提现 4退款 5 收益 6认证消费
		$log['type5_type']  =  1;//收益类型  1.购物返积分
		$log['order_id']    = $order_info['order_id'];
		$log['des']         = '购买商品获得积分：' . $order_profit . ' 订单号：（'.$order_info['order_sn'].'）';
		$c_res = $commonob->set_member_points($order_info['user_id'], 1, $order_profit, $log);
		//收益记录
		$tuijian_data = array();
		$tuijian_data['member_id'] = $order_info['user_id'];
		$tuijian_data['order_id'] = $order_info['order_id'];
		$tuijian_data['status'] = 1;
		$tuijian_data['type'] = 1;
		$tuijian_data['money'] = 0;
		$tuijian_data['integral'] = $order_profit;
		$tuijian_data['des'] = '购买商品获得积分：' . $order_profit . ' （订单号：'.$order_info['order_sn'].'; 商品名称：'.implode(',',$goods_names).'('.implode(',',$goods_ids).')）';
		$tuijian_data['rebate_status'] = 1;

		$g_order_info->where(array('order_id' => $order_info['order_id']))->save(array('order_profit'=>$order_profit, 'is_rebate'=>1)); //完成分配标记

		if(!empty($tuijian_data)){ //写入收益记录
			$rebate_record_model = M('rebate_record');
			$tuijian_data['add_time'] = $time;
			 $rebate_record_model->add($tuijian_data);
		}
		$ret['status'] = 1;
		$ret['$tuijian_data'] =$tuijian_data;
		$ret['$order_goods'] =$order_goods;
		$ret['sql'] =$c_res;
		return $ret;
	}

/*	public function set_member_exchange($order_id = array()){
		$time = time();
		$ret = array();
		$ret['status'] = 0;
		if(!$order_id){
			$ret['msg'] = '参数错误！';
			$this->write_log($ret['msg']);
			return $ret;
		}
		$g_order_info = M('g_order_info');
		$g_order_goods = M('g_order_goods');
		$g_purchases = M('g_purchases');
		// $order_info = $g_order_info->where(array('order_id'=>409))->find();
		$order_info = $g_order_info->where(array('order_id'=>$order_id))->find();
		if (empty($order_info)){
			$ret['msg'] = '没有找到订单';
			$this->write_log($ret['msg'].'['.$order_id);
			return $ret;
		}

		if($order_info['pay_status'] != 2){
			$ret['msg'] = '订单没有付款';
			$this->write_log($order_info['order_sn'].'['.$order_info['order_id'].'] : '.$ret['msg']);
			return $ret;
		}

		if($order_info['is_rebate'] == 1){
			$ret['msg'] = '订单已经成功分配';
			$this->write_log($order_info['order_sn'].'['.$order_info['order_id'].'] : '.$ret['msg']);
			return $ret;
		}

		// $pre = C('DB_PREFIX');//表前缀
		$commonob = A('Common');
		$order_profit = 0;

		//订单包涵的商品
		$order_goods = $g_order_goods->where(array('order_id'=>$order_info['order_id']))->select();
		foreach($order_goods as $order_goods_item){
			$purchase_info = $g_purchases->where(array( 'id'=>$order_goods_item['purchases_id']))->find();
			if(empty($purchase_info)){
				$this->write_log($order_info['order_sn'].'['.$order_info['order_id'].'] : '.$order_goods_item['goods_id'].' 没有进货信息');
				continue;
			}
			$member_arr = [$purchase_info['supplier_member_id'],$purchase_info['share_member_id'],$purchase_info['member_id']];
			$member_arr = array_values(array_unique($member_arr));//进货单 基地-分销商-店主 id 数组；
			$profit_arr = explode(',',$purchase_info['profit']);  //进货单 利润数组
			if(count($member_arr) != ($purchase_info['share_degree']+1) || ($purchase_info['share_degree']+1) != count($profit_arr)){
				$ret['msg'] = '进货单'.$purchase_info['id'].'等级出错';
				$this->write_log($order_info['order_sn'].'['.$order_info['order_id'].'] : '.$ret['msg']);
				continue;
			}
			foreach ($member_arr as $m_k => $member_id){
				$unit_profit = ($m_k == 0) ? mb_number($profit_arr[$m_k] - 1.00) : $profit_arr[$m_k] ;//供货基地 的利润平台需要扣除1元
				$money = $unit_profit * $order_goods_item['goods_number'];
				if ($money <= 0){
					$this->write_log($order_info['order_sn'].'['.$order_info['order_id'].'] : '.$purchase_info['id'].' 分配用户:'.$member_id.' 金额错误'.$money);
					continue;
				}
				$log = array();
				$log['type']        = 5;//消费类型 1订单消费 2充值 3提现 4退款 5 收益 6认证消费
				$log['type5_type']  = $m_k > 0 ? 2 : 1;//收益类型  1供货商货款 2批发商差价
				$log['order_id']    = $order_info['order_id'];
				$log['des']         = '售出商品获得：' . $money . ' 订单号：（'.$order_info['order_sn'].'）';
				$commonob->set_member_balance($member_id, 1, $money, $log);

				//收益记录
				$tuijian_data = array();
				$tuijian_data['member_id'] = $member_id;
				$tuijian_data['order_id'] = $order_info['order_id'];
				$tuijian_data['status'] = $m_k > 0 ? 2 : 1;
				$tuijian_data['type'] = 1;
				$tuijian_data['money'] = $money;
				$tuijian_data['integral'] = 0;
				$tuijian_data['des'] = '售出商品获得：' . $money . ' （订单号：'.$order_info['order_sn'].'; 商品名称：'.$order_goods_item['goods_name'].'('.$order_goods_item['goods_id'].')）';
				$tuijian_data['rebate_status'] = 1;
				$member_tuijian_data[] = $tuijian_data;
			}

		}

		$g_order_info->where(array('order_id' => $order_info['order_id']))->save(array('order_profit'=>$order_profit, 'is_rebate'=>1)); //完成分配标记

		if(!empty($member_tuijian_data)){ //写入收益记录
			$rebate_record_model = M('rebate_record');
			foreach ($member_tuijian_data as $key => $value) {
				$value['add_time'] = $time;
				$rebate_record_model->add($value);
			}
		}
		$ret['status'] = 1;
		return $ret;
	}*/
}