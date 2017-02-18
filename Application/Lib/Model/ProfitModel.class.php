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
    public function set_member_exchange($data_info = array()){
		$time = time();
		$ret = array();
		$ret['status'] = 0;
		
		if(is_array($data_info)){
			$order_id = $data_info['order_id'];
		}else{
			$order_id = $data_info;
		}
		if(!$order_id){
			$ret['msg'] = '参数错误！';
            return $ret;
		}
		$g_order_info = M('g_order_info');
		// $order_info = $g_order_info->where(array('order_id'=>409))->find();
		$order_info = $g_order_info->where(array('order_id'=>$order_id))->find();
        if (empty($order_info)){
			$ret['msg'] = '没有找到订单';
            return $ret;
        }
		
		if($order_info['pay_status'] != 2){
			$ret['msg'] = '订单没有付款';
            return $ret;
		}
		
		if($order_info['is_rebate'] == 1){
			$ret['msg'] = '订单已经奖励积分';
            return $ret;
		}
		
		$pre = C('DB_PREFIX');//表前缀
		
        //用户自己返利  用户积分增加  返利日志 积分增加日志
		$field = 'mem.id, mem.member_card, mem.mobile, mem.recommend_code, mem.indirect_recommend_code, mem.indirect2_recommend_code, mem.member_status, mem.member_vip_type, mem.member_vip_order, md.provinceid, md.cityid, md.areaid, md.relevel, md.repath';
        $member_info = M()->table($pre . 'member mem')
		->join($pre . 'member_detail md on mem.id=md.member_id')
		->where(array('mem.id' => $order_info['user_id']))
		->field($field)->find();
		
		//shop_rebate_status  123 级返利状态 1 返利  0不返
        $shop_rebate_status = 0;//返利类型 1分享返利 0正常返利 2升级返利
        $member_tuijian_data = array();//返利记录
		
        $order_save_data = array();//订单返利 数据
        $order_save_data['profit_type'] = 0;//返利类型 1分享返利 0正常返利(商品送积分)
        $order_save_data['order_rebate'] = 0;//订单返利金额
		$order_save_data['order_rebate_integral'] = 0; //订单返利积分
		
		//订单利润
		$is_int_result = FALSE; //分配金额是否已经固定
		$is_rebate_flagship = TRUE; // 是否系统分配旗舰店收益
		$money = 0; $c_money = 0; $syb_money = 0;
		$province_id = $city_id = $town_id = 0;
		if($order_info['is_upgrade'] == 1){

		}else{
			$where_goods['order_id'] = $order_info['order_id'] ;
			$where_goods['yes_refund'] = 0;
			$where_goods['activity_id'] = array('neq','1');
			$order_goods_info=M('g_order_goods')->where($where_goods)->select();
			foreach ($order_goods_info as $k=>$v){
				$goods_id=$v['goods_id'];
				$goods_number=$v['goods_number'];
				$goods_price=$v['goods_price'];
				$purchase_price=M('g_goods')->where("goods_id=$goods_id")->getField('purchase_price');
				$money += (($goods_price-$purchase_price)*$goods_number);
			}
			$c_money = $money;
			
			//分配比例
			$rem1_money_ratio		= array(1=>0.28, 2=>0.30, 3=>0.30);
			$rem1_integral_ratio	= array(1=>0.02, 2=>0.05, 3=>0.05);
			$rem2_money_ratio		= array(1=>0.08, 2=>0.08, 3=>0.00);
			$rem2_integral_ratio	= array(1=>0.02, 2=>0.02, 3=>0.00);
			
			//旗舰店收益比例
			$flagship_money_ratio	= 0.2;
			$flagship_integral_ratio= 0.05;
			
			$province_id = $order_info['province'];
			$city_id = $order_info['city'];
			$town_id = $order_info['district'];
			
			$rebate_name = '消费收益';
		}
		
		if($syb_money){
			D('Jackpot')->set_jackpot($syb_money);
		}
		if($money <= 0){
			$ret['msg'] = '利润金额错误（'.$money.'）';
			return $ret;
		}
		$money_integral = 10; //1元钱 = 10积分
		
		$member_db = M('member');
		//推荐人查找
		if($order_info['is_upgrade'] == 0 && $order_info['share_uid'] > 0){ //如果用户的订单推荐人不是原推荐人，利润分配使用订单推荐人的分配路线
			$share_member = $member_db->where(array('id'=>$order_info['share_uid']))->find();
			$share_member_detail = M('member_detail')->where(array('member_id'=>$order_info['share_uid']))->find();
			if(isset($share_member['member_card']) && isset($share_member_detail['repath']) && $share_member['member_card'] != $member_info['recommend_code']){
				$member_info['repath'] = $share_member_detail['repath'].','.$member_info['id'];
				$member_info['relevel'] = $share_member_detail['relevel'] + 1;
			}
		}
		$repath = explode(',', $member_info['repath']);
		$member_list_array = $member_db->where(array('id' => array('in', $member_info['repath'])))->select();
		$ret['last_sql'] = $member_db->getLastSql();
		$member_list = array();
		foreach($member_list_array as $item){
			$member_list[$item['id']] = $item;
		}
		
		$i = 0;
		$num = 0;
		$member1_info = $member2_info = $member3_info = '';
		
		$org_member_info = array(); // 单位信息
		$flagship_member_info = array(); // 旗舰店
		$flagship_num = 0; //旗舰店数量
		$flagship_base = 0; // 旗舰店收益基数
		$is_add_money_flagship = true; // 是否继续累计旗舰店收益基数
		
		$recommend_member_info = array();
		while($i < $member_info['relevel']){
			$uid = array_pop($repath);
			$member_list_row = $member_list[$uid];
			if(($uid != $member_info['id'] || $i > 0) && $num <= 3){
				$num ++;
				$recommend_member_info[$num] = $member_list_row;
			}
			//找推荐办事处
			$org_type_array = explode(',', $member_list_row['org_type']);
			if(empty($org_member_info) && in_array(1, $org_type_array)){
				$org_member_info = $member_list_row;
			}
			
			//找旗舰店
			if($flagship_num < 2 && $member_list_row['member_vip_type'] == 3){
				$flagship_num ++ ;
				$flagship_member_info[$flagship_num] = $member_list_row;
			}
			if($num > 1 && $num <= 3 && isset($flagship_member_info[1]['id'])){
				$is_rebate_flagship = true;
			}
			if($num >= 3 && $flagship_num >= 2){
				break;
			}
			$i++;
		}
		
		$member1_info = isset($recommend_member_info[1]) ? $recommend_member_info[1] : '';
		$member2_info = isset($recommend_member_info[2]) ? $recommend_member_info[2] : '';
		// $member3_info = isset($recommend_member_info[3]) ? $recommend_member_info[3] : '';
		
		$commonob = A('Common');
		$left_money = $money;
		
		$member1_money = $member2_money = 0;
		$member1_integral = $member2_integral = 0;
		
		//直接推荐人
		if($member1_info && $member1_info['mobile'] && $member1_info['member_vip_type']){
			if(isset($rem1_money_ratio[$member1_info['member_vip_type']])){//余额
				$rem1_money = $rem1_money_ratio[$member1_info['member_vip_type']];
				$member1_money = $is_int_result ? $rem1_money : mb_number($money * $rem1_money);
			}
			if(isset($rem1_integral_ratio[$member1_info['member_vip_type']])){//积分
				$rem1_integral = $rem1_integral_ratio[$member1_info['member_vip_type']];
				if($is_int_result){//升级订单
					$member1_integral = $rem1_integral;
					$member1_integral_money = mb_number($member1_integral / $money_integral);
				}else{//购物订单
					$member1_integral_money = mb_number($money * $rem1_integral);
					$member1_integral = $member1_integral_money * $money_integral;
				}
			}
			
			if($member1_money > 0 || $member1_integral > 0){
				//累计旗舰店收益基数
				if(isset($flagship_member_info[1]['id']) && $flagship_member_info[1]['id'] == $member1_info['id']){
					$is_add_money_flagship = false;
				}
				if($is_add_money_flagship && isset($flagship_member_info[1]['id']) && $flagship_member_info[1]['id'] > 0){
					$flagship_base += $member1_money;
				}
				
				$order_save_data['order_rebate'] += ($member1_money + $member1_integral_money);
				$order_save_data['order_rebate_integral'] += $member1_integral;
				$log = array();
				$log['type'] = 5;//消费类型 1订单消费 2充值 3提现   4退款 5 收益 6认证消费
				$log['type5_type']=1;//收益类型  1直接推荐人 2间接推荐人 3 间接二级推荐人 4代理区域会员
				$log['order_id'] = $order_info['order_id'];
				$log['des'] = '直接推荐人('.$member_info['member_card'].')消费收益：￥' . $member1_money;
				
				//金额日志
				if($member1_money > 0){
					$commonob->set_member_balance($member1_info['id'], 1, $member1_money, $log);
				}
				
				//积分日志
				$log['des'] = '直接推荐人('.$member_info['member_card'].')消费积分：' . $member1_integral;
				if($member1_integral > 0){
					$commonob->set_member_points($member1_info['id'], 1, $member1_integral, $log);
				}
				
				//收益记录
				$tuijian_data = array();
				$tuijian_data['member_id'] = $member1_info['id'];
				$tuijian_data['order_id'] = $order_info['order_id'];
				$tuijian_data['type'] = 2;
				$tuijian_data['money'] = $member1_money;
				$tuijian_data['integral'] = $member1_integral;
				$tuijian_data['des'] = '直接推荐人('.$member_info['member_card'].')消费收益：￥' . $member1_money . '，消费积分：' . $member1_integral;
				$tuijian_data['rebate_status'] = 1;
				$member_tuijian_data[] = $tuijian_data;
			}
		}
		
		//间接推荐人
		if($member2_info && $member2_info['mobile'] && $member2_info['member_vip_type'] && $order_info['is_upgrade'] == 0 && $member2_info['member_vip_type'] != 3){
			if(isset($rem2_money_ratio[$member2_info['member_vip_type']])){//余额
				$rem2_money = $rem2_money_ratio[$member2_info['member_vip_type']];
				$member2_money = $is_int_result ? $rem2_money : mb_number($money * $rem2_money);
			}
			if(isset($rem2_integral_ratio[$member2_info['member_vip_type']])){//积分
				$rem2_integral = $rem2_integral_ratio[$member2_info['member_vip_type']];
				if($is_int_result){//升级订单
					$member2_integral = $rem2_integral;
					$member2_integral_money = mb_number($member2_integral / $money_integral);
				}else{//购物订单
					$member2_integral_money = mb_number($money * $rem2_integral);
					$member2_integral = $member2_integral_money * $money_integral;
				}
			}
			
			if($member2_money > 0 || $member2_integral > 0){
				
				//累计旗舰店收益基数
				if(isset($flagship_member_info[1]['id']) && $flagship_member_info[1]['id'] == $member2_info['id']){
					$is_add_money_flagship = false;
				}
				if($is_add_money_flagship && isset($flagship_member_info[1]['id']) && $flagship_member_info[1]['id'] > 0){
					$flagship_base += $member2_money;
				}
				
				$order_save_data['order_rebate'] += ($member2_money + $member2_integral_money);
				$order_save_data['order_rebate_integral'] += $member2_integral;
				
				$log = array();
				$log['type'] = 5;//消费类型 1订单消费 2充值 3提现   4退款 5 收益 6认证消费
				$log['type5_type'] = 2;//收益类型  1直接推荐人 2间接推荐人 3 间接二级推荐人 4代理区域会员
				$log['order_id'] = $order_info['order_id'];
				$log['des'] = '间接推荐人('.$member_info['member_card'].')消费收益：￥' . $member2_money;
				
				//金额日志
				if($member2_money > 0){
					$commonob->set_member_balance($member2_info['id'], 1, $member2_money, $log);
				}
				
				//积分日志
				$log['des'] = '间接推荐人('.$member_info['member_card'].')消费积分：' . $member2_integral;
				if($member2_integral > 0){
					$commonob->set_member_points($member2_info['id'], 1, $member2_integral, $log);
				}
				
				//收益记录
				$tuijian_data = array();
				$tuijian_data['member_id'] = $member2_info['id'];
				$tuijian_data['order_id'] = $order_info['order_id'];
				$tuijian_data['type'] = 3;
				$tuijian_data['money'] = $member2_money;
				$tuijian_data['integral'] = $member2_integral;
				$tuijian_data['des'] = '间接推荐人('.$member_info['member_card'].')消费收益：￥' . $member2_money . '，消费积分：' . $member2_integral;
				$tuijian_data['rebate_status'] = 1;
				$member_tuijian_data[] = $tuijian_data;
			}
		}
		
		//蓝钻旗舰店收益
		if(isset($flagship_member_info[1]['id']) && ($flagship_base > 0 || $is_int_result)){
			$flagship_money = $is_int_result ? $flagship_money_ratio : mb_number($flagship_base * $flagship_money_ratio);
			$flagship_integral = $is_int_result ? $flagship_integral_ratio : mb_number($flagship_base * $flagship_integral_ratio);
			$flagship_integral_money = mb_number($flagship_integral / $money_integral);
			if($flagship_money > 0 || $flagship_integral > 0){
				if($is_rebate_flagship){
					$order_save_data['order_rebate'] += ($flagship_money + $flagship_integral_money);
					$order_save_data['order_rebate_integral'] += $flagship_integral;
					
					$log = array();
					$log['type'] = 5;//消费类型 1订单消费 2充值 3提现   4退款 5 收益 6认证消费
					$log['type5_type'] = 5;
					$log['order_id'] = $order_info['order_id'];
					$log['des'] = '用户('.$member_info['member_card'].')消费，旗舰店获得收益：￥' . $flagship_money;
					//金额日志
					if($flagship_money > 0){
						$commonob->set_member_balance($flagship_member_info[1]['id'], 1, $flagship_money, $log);
					}
					
					//积分日志
					$log['des'] = '用户('.$member_info['member_card'].')消费，旗舰店获得积分：' . $flagship_integral;
					if($flagship_integral > 0){
						$commonob->set_member_points($flagship_member_info[1]['id'], 1, $flagship_integral, $log);
					}
				}
				
				//收益记录
				$tuijian_data = array();
				$tuijian_data['member_id'] = $flagship_member_info[1]['id'];
				$tuijian_data['order_id'] = $order_info['order_id'];
				$tuijian_data['type'] = 6;
				$tuijian_data['money'] = $flagship_money;
				$tuijian_data['integral'] = $flagship_integral;
				$tuijian_data['des'] = '用户('.$member_info['member_card'].')消费，蓝钻旗舰店获得收益：￥' . $flagship_money . '、积分：' . $flagship_integral;
				$tuijian_data['rebate_status'] = $is_rebate_flagship ? 1 : 0;
				$member_tuijian_data[] = $tuijian_data;
			}
		}
		
		//红钻旗舰店收益
		if($is_int_result && isset($flagship_member_info[2]['id'])){
			$flagship2_money = $is_int_result && isset($flagship2_money_ratio) && $flagship2_money_ratio > 0 ? $flagship2_money_ratio : 0;
			$flagship2_integral = $is_int_result && isset($flagship2_integral_ratio) && $flagship2_integral_ratio > 0 ? $flagship2_integral_ratio : 0;
			$flagship2_integral_money = mb_number($flagship2_integral / $money_integral);
			if($flagship2_money || $flagship2_integral){
				if($is_rebate_flagship){
					$order_save_data['order_rebate'] += ($flagship2_money + $flagship2_integral_money);
					$order_save_data['order_rebate_integral'] += $flagship2_integral;
					
					$log = array();
					$log['type'] = 5;//消费类型 1订单消费 2充值 3提现   4退款 5 收益 6认证消费
					$log['type5_type'] = 6;
					$log['order_id'] = $order_info['order_id'];
					$log['des'] = '用户('.$member_info['member_card'].')消费，旗舰店获得收益：￥' . $flagship2_money;
					//金额日志
					if($flagship2_money > 0){
						$commonob->set_member_balance($flagship_member_info[2]['id'], 1, $flagship2_money, $log);
					}
					
					//积分日志
					$log['des'] = '用户('.$member_info['member_card'].')消费，旗舰店获得积分：' . $flagship2_integral;
					if($flagship2_integral > 0){
						$commonob->set_member_points($flagship_member_info[2]['id'], 1, $flagship2_integral, $log);
					}
				}
				//收益记录
				$tuijian_data = array();
				$tuijian_data['member_id'] = $flagship_member_info[2]['id'];
				$tuijian_data['order_id'] = $order_info['order_id'];
				$tuijian_data['type'] = 7;
				$tuijian_data['money'] = $flagship2_money;
				$tuijian_data['integral'] = $flagship2_integral;
				$tuijian_data['des'] = '用户('.$member_info['member_card'].')消费，红钻旗舰店获得收益：￥' . $flagship2_money . '、积分：' . $flagship2_integral;
				$tuijian_data['rebate_status'] = $is_rebate_flagship ? 1 : 0;
				$member_tuijian_data[] = $tuijian_data;
			}
		}
		
		//办事处收益
		$now_month = date('ym', $time);
		$org_base = 0;
		if($order_info['is_upgrade'] == 1){
			$org_base = $order_info['order_amount'] * 0.24;
		}else{
			$org_base = mb_number($money - $order_save_data['order_rebate']);
		}
		$org_member_db = M('org_member');
		$org_money_db = M('org_money');
		$org_info_db = M('org_info');
		if(isset($org_member_info['id']) && $org_base > 0){ //推荐办事处（区外）
			$recommend_org_info = '';
			$outside_money_money = 0; //
			$recommend_org_id = $org_member_db->where(array('member_id'=>$org_member_info['id'], 'org_type'=>1, 'status'=>1))->getField('org_id');
			if($recommend_org_id){
				$recommend_org_info = $org_info_db->where(array('org_id'=>$recommend_org_id, 'status'=>1))->find();
			}
			if(!empty($recommend_org_info)){
				$outside_money_money = mb_number($org_base * 0.05);
				$order_save_data['order_rebate'] += $outside_money_money;
				$org_money = $org_money_db->where(array('org_id'=>$recommend_org_info['org_id'], 'month'=>$now_month))->find();
				if(!empty($org_money)){
					$org_money_db->where(array('org_money_id'=>$org_money['org_money_id']))->setInc('outside_money', $outside_money_money);
				}else{
					$org_money_data = array();
					$org_money_data['org_id'] = $recommend_org_info['org_id'];
					$org_money_data['org_type'] = $recommend_org_info['org_type'];
					$org_money_data['month'] = $now_month;
					$org_money_data['inside_money'] = 0;
					$org_money_data['outside_money'] = $outside_money_money;
					$org_money_data['train_money'] = 0;
					$org_money_data['c_time'] = $time;
					$org_money_db->add($org_money_data);
				}
				
				//收益记录
				$tuijian_data = array();
				$tuijian_data['member_id'] = 0;
				$tuijian_data['org_id'] = $recommend_org_info['org_id'];
				$tuijian_data['order_id'] = $order_info['order_id'];
				$tuijian_data['type'] = 11; //办事处
				$tuijian_data['money'] = $outside_money_money;
				$tuijian_data['integral'] = 0;
				$tuijian_data['des'] = '办事处收益：￥' . $outside_money_money . '，来自用户('.$member_info['member_card'].')';
				$tuijian_data['rebate_status'] = 1;
				$member_tuijian_data[] = $tuijian_data;
			}
		}
		
		if($province_id && $city_id && $town_id && $org_base > 0){ //地址办事处（区内）
			$inside_money_money = $train_money = 0;
			$org_info = $org_info_db->where(array('province_id'=>$province_id, 'city_id'=>$city_id))->find();
			if($org_info && isset($org_info['town_id']) && $org_info['town_id'] > 0){
				$org_info = $org_info_db->where(array('province_id'=>$province_id, 'city_id'=>$city_id, 'town_id'=>$town_id))->find();
			}
			if(!empty($org_info)){
				if(isset($recommend_org_info['org_id']) && ($org_info['org_id'] == $recommend_org_info['org_id'])){
					$train_money = mb_number($org_base * 0.03);
				}
				$inside_money_money = mb_number($org_base * 0.1);
				$order_save_data['order_rebate'] += ($inside_money_money + $train_money);
				$org_money = $org_money_db->where(array('org_id'=>$org_info['org_id'], 'month'=>$now_month))->find();
				if(!empty($org_money)){
					$org_money_db->where(array('org_money_id'=>$org_money['org_money_id']))->setInc('inside_money', $inside_money_money);
					if($train_money > 0){
						$org_money_db->where(array('org_money_id'=>$org_money['org_money_id']))->setInc('train_money', $train_money);
					}
				}else{
					$org_money_data = array();
					$org_money_data['org_id'] = $org_info['org_id'];
					$org_money_data['org_type'] = $org_info['org_type'];
					$org_money_data['month'] = $now_month;
					$org_money_data['inside_money'] = $inside_money_money;
					$org_money_data['outside_money'] = 0;
					$org_money_data['train_money'] = $train_money;
					$org_money_data['c_time'] = $time;
					$org_money_db->add($org_money_data);
				}
				
				//收益记录
				$tuijian_data = array();
				$tuijian_data['member_id'] = 0;
				$tuijian_data['org_id'] = $org_info['org_id'];
				$tuijian_data['order_id'] = $order_info['order_id'];
				$tuijian_data['type'] = 11;//办事处
				$tuijian_data['money'] = mb_number($inside_money_money + $train_money);
				$tuijian_data['integral'] = 0;
				if($train_money > 0){
					$tuijian_data['des'] = '办事处收益：￥' . $inside_money_money . '，培训费：￥'.$train_money.'，来自用户('.$member_info['member_card'].')';
				}else{
					$tuijian_data['des'] = '办事处收益：￥' . $inside_money_money . '，来自用户('.$member_info['member_card'].')';
				}
				$tuijian_data['rebate_status'] = 1;
			}
		}
		
		$order_save_data['profit_type'] = $shop_rebate_status;
		$order_save_data['order_profit_all'] = $money;
		//订单利润 order_profit
		//订单返利金额 order_rebate
		//订单总利润 order_profit_all
		$order_save_data['order_profit'] = $order_save_data['order_profit_all'] - $order_save_data['order_rebate'];
		$order_save_data['is_rebate'] = 1; //标记为已经分配
		
		M('g_order_info')->where(array('order_id' => $order_info['order_id']))->save($order_save_data);
		
		//累计业绩
		if($member_info['member_vip_type'] > 0 && $order_info['is_upgrade'] == 0){
			$yeji_user_ids = $member_info['repath'];
		}else{
			$index_num = strrpos($member_info['repath'], ',');
			$yeji_user_ids = substr($member_info['repath'], 0, $index_num);
		}
		if($syb_money > 0){ //累计创业业绩
			M('member_detail')->where(array('member_id'=>array('in', $yeji_user_ids)))->setInc('syb_yeji', $syb_money);
		}
		if($c_money > 0){ //累计消费业绩
			M('member_detail')->where(array('member_id'=>array('in', $yeji_user_ids)))->setInc('c_yeji', $c_money);
		}
		if($syb_money > 0 || $c_money > 0){
			$yeji_user_arr = explode(',',$yeji_user_ids);//将推荐人转换成数组
			$yeji_user_arr = array_unique($yeji_user_arr) ;
			$_this_y_m = date('ym',$time);//当前年月
			foreach ($yeji_user_arr as $v){
				$yeji_where = array();
				$yeji_where['year_month'] = $_this_y_m;
				$yeji_where['member_id'] = $v;
				$yeji_log_m = M('yeji_log')->where($yeji_where)->find();
				$_this_member_detail = M('member_detail')->where('member_id="'.$v.'"')->find();
				$data_yeji =array();
				$data_yeji['syb_yeji_total'] = $_this_member_detail['syb_yeji'];
				$data_yeji['c_yeji_total'] = $_this_member_detail['c_yeji'];
				if($yeji_log_m){//如果存在,只需修改
					M('yeji_log')->where($yeji_where)->save($data_yeji);
				}else{
					$data_yeji['member_id'] = $v;
					$data_yeji['year_month'] = $_this_y_m;
					M('yeji_log')->add($data_yeji);
				}
				M('yeji_log')->where($yeji_where)->setInc('syb_yeji_month',$syb_money);
				M('yeji_log')->where($yeji_where)->setInc('c_yeji_month',$c_money);
			}
		}

		if(!empty($member_tuijian_data)) { //写入收益记录
			$rebate_record_model = M('rebate_record');
			foreach ($member_tuijian_data as $key => $value) {
				$value['add_time'] = $time;
				$value['status'] = 1;//状态 1用户收益 2 商家返利
				$rebate_record_model->add($value);
			}
		}

		$ret['status'] = 1;
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