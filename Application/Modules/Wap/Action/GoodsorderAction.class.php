<?php

// 上次订单
class GoodsorderAction extends UserAction
{
    //魔术方法
    public function __construct()
    {
        parent::__construct();
    }

    //订单列表
    public function index()
    {
        $where['user_id'] = $this->uid;
		$where['_string'] ="activity_id <> 1 or (activity_id = 1 and activity_status = 1)";
        if (isset($_REQUEST['order_state'])) {
            if ($_REQUEST['order_state'] != 'all') {
                if ($_REQUEST['order_state'] == 3) {//已完成
					// 支付状态；0，未付款；1，付款中 ；2，已付款
                    // $where[''] = 2;//商品配送情况，0，未发货； 1，已发货；2，已收货；3，备货中
                    //退货的也在已完成
                    $where['_string'] = "((pay_status = 2 and shipping_status=2 ) or (pay_status = 2 and shipping_status=1 and order_status in ('2','3','4')) or (pay_status = 0 and order_status = 2 )) and (activity_id = 0 or (activity_id > 0 and activity_status = 1))";//订单状态。0，未确认；1，已确 认；2，已取消；3，无效；4，退货；
                } else if ($_REQUEST['order_state'] == 1) {
                    $where['pay_status'] = 2;// 支付状态；0，未付款；1，付款中 ；2，已付款
                    $where['shipping_status'] = array('in', '0,3');//商品配送情况，0，未发货； 1，已发货；2，已收货；3，备货中
                    $where['order_status'] = array('in', '1,0');//订单状态。0，未确认；1，已确 认；2，已取消；3，无效；4，退货；
                    //$where['shipping_status'] =1;//商品配送情况，0，未发货； 1，已发货；2，已收货；3，备货中
                } else if ($_REQUEST['order_state'] == 2) {
                    $where['pay_status'] = 2;// 支付状态；0，未付款；1，付款中 ；2，已付款
                    $where['shipping_status'] = 1;//商品配送情况，0，未发货； 1，已发货；2，已收货；3，备货中
                    $where['order_status'] = array('in', '1,5');//订单状态。0，未确认；1，已确 认；2，已取消；3，无效；4，退货; 5,售后中；
                    //$where['shipping_status'] =1;//商品配送情况，0，未发货； 1，已发货；2，已收货；3，备货中
                } else {
                    $where['pay_status'] = $_REQUEST['order_state'];
					$where['order_status'] = 0;
					$where['shipping_status'] = 0;
                }
            }
        } else {
            $_GET['order_state'] = 'all';
        }
		$where['user_del'] = 0;
        $p = $_REQUEST['p'];
        $pagesize = 10;
        $p = !empty($p) ? $p : 1;
        $start = ($p - 1) * $pagesize;
        $pre = C('DB_PREFIX');
        $field = "info.order_id,info.order_sn,pay.pay_sn,info.order_status,info.shipping_status,info.pay_status,info.goods_amount,info.order_amount,info.shipping_fee,info.add_time,info.consignee,info.mobile,info.address,info.is_upgrade,info.share_uid,info.confirm_time,info.to_buyer";
        $order_model = M();
        $list = $order_model->table($pre . 'g_order_info info')//
        ->join($pre . 'g_order_pay pay on pay.pay_id=info.pay_record_id')//
        ->where($where)//
        ->field($field)->limit($start, $pagesize)->order('order_id desc')->select();
        // echo $order_model->getlastsql();die;
        //订单状态。0，未确认；1，已确 认；2，已取消；3，无效；4，退货；--order
        //商品配送情况，0，未发货； 1，已发货；2，已收货；3，备货中---shipping
        //支付状态；0，未付款；1，付款中 ；2，已付款----pay
        //待付款 0.0.(0,1)  待发货 0.0.2 待收货0.(1,3).2 已完成1.2.2 退货/返修
//        var_dump($list);die;
        //$order_state = array('0' => '已取消', '10' => '待付款', '20' => '已付款', '30' => '已完成');
        $order_state =array('0' => '已取消', '1' => '待付款', '2' => '待发货','3'=>'已发货', '4' => '已完成');
        $g_order_goods_refund = M('g_order_goods_refund');

        foreach ($list as $key => $value) {
            $order_goods = M('g_order_goods')->field('rec_id,goods_name,goods_image,goods_price,goods_id,yes_refund,is_refund,is_upgrade,goods_number,shipping_money')
                ->where(array('order_id' => $value['order_id']))->select();

            $order_show = $this->get_order_status($value);
            $list[$key]['order_state'] = $order_show['code'];
            $list[$key]['order_state_name'] = $order_show['name'];
            $list[$key]['add_time'] = date("Y/m/d", $value['add_time']);
            $list[$key]['is_refund'] = 0;///该订单是否可退货
            $list[$key]['rec_id'] = 0;///该订单是否可退货产品
            //
            $num=0;
            foreach ($order_goods as $k=>$v){
                $num+=$v['goods_number'];
                $order_goods[$k]['goods_image'] = thumbs_auto($v['goods_image'], 180, 180);
            }
            $list[$key]['goods'] = $order_goods;
            $list[$key]['num']=$num;
            $list[$key]['order_amount'] += $list[$key]['shipping_fee'];
            $list[$key]['ref_all'] = 0;
			$store_id=$list[$key]['share_uid'];
			$list[$key]['store_name']=M('member')->where('id='.$store_id)->getField('member_name');
			$list[$key]['status_remarks'] = $this->status_before_deliver( $value);
            $countgoods = 0;
			//输出商品是否可售后
			$after_sale=0;
			if(in_array($order_show['code'], array('delivered','after_sale','finish','received')) && $value['is_upgrade'] == 0){
                foreach ($order_goods as $keys => $val) {
					$refund_goods_status='';
					$refund_status_name ='';
					$refund_goods_status=M('g_order_refund')->where(array('order_goods_id'=>$val['rec_id']))->find();
					$refund_status=$this->get_refund_status($refund_goods_status);
					if(empty($refund_status)){
						$refund_status['code_name']='售后';
					}
					$list[$key]['goods'][$keys]['refund_status'] = $refund_status['code'];
					$list[$key]['goods'][$keys]['refund_status_name'] =$refund_status['code_name'];
					$after_sale = 1;
					if (in_array($order_show['code'],array('finish','received'))){
						if($refund_status['code'] == 5 ){
							$after_sale = 1;
						}else{
							$after_sale = 0;
						}
					}
					$list[$key]['goods'][$keys]['after_sale'] = $after_sale;
                }
/*                $f_where = array();
                $f_where['order_id'] = $value['order_id'];
				$f_where['refund_status'] = array('neq','5');
                $list[$key]['ref_all'] = M('g_order_refund')->where($f_where)->count();*/
            }
        }
        if (IS_AJAX) {
            echo json_encode($list);
            die;
        }
        $this->list = $list;
        $where = array();
		$where['_string'] ="activity_id = 0 or (activity_id > 0 and activity_status = 1)";
        $where['user_del'] = 0;
        $where['user_id'] = $this->uid;
        $this->count = $order_model->where($where)->count();
        $where = array();
        $where['user_del'] = 0;
		$where['_string'] ="activity_id = 0 or (activity_id > 0 and activity_status = 1)";
        $where['user_id'] = $this->uid;
		$where['pay_status'] = 0;
		$where['order_status'] = 0;
		$where['shipping_status'] = 0;
        $this->count00 = $order_model->where($where)->count();
        $where = array();
        $where['user_del'] = 0;
		$where['_string'] ="activity_id = 0 or (activity_id > 0 and activity_status = 1)";
        $where['user_id'] = $this->uid;
        $where['pay_status'] = '2';//待发货
        $where['shipping_status'] = array('in', '0,3');
        $where['order_status'] = array('in', '1,0');//订单状态。0，未确认；1，已确 认；2，已取消；3，无效；4，退货；
        $this->count10 = $order_model->where($where)->count();
        $where = array();
        $where['user_del'] = 0;
		$where['_string'] ="activity_id = 0 or (activity_id > 0 and activity_status = 1)";
        $where['user_id'] = $this->uid;
		$where['pay_status'] = 2;// 支付状态；0，未付款；1，付款中 ；2，已付款
		$where['shipping_status'] = 1;//商品配送情况，0，未发货； 1，已发货；2，已收货；3，备货中
		$where['order_status'] = array('in', '1,5');//订单状态。0，未确认；1，已确 认；2，已取消；3，无效；4，退货; 5,售后中；
		$this->count20 = $order_model->where($where)->count();
        $where = array();
        $where['user_del'] = 0;
        $where['user_id'] = $this->uid;
        //$where['shipping_status'] = '2';
		$where['_string'] = "((pay_status = 2 and shipping_status=2 ) or (pay_status = 2 and shipping_status=1 and order_status in ('2','3','4')) or (pay_status = 0 and order_status = 2 )) and (activity_id = 0 or (activity_id > 0 and activity_status = 1))";//订单状态。0，未确认；1，已确 认；2，已取消；3，无效；4，退货；
        $this->count30 = $order_model->where($where)->count();
        $this->webtitle = "FG峰购";
        $this->display();

    }

    //物流信息
    public function order_express()
    {
        $menuwhere["order_id"] = array("eq", $_GET["order_id"]);
        if ($_GET["is_store"] == 1) {
            $menuwhere['share_uid'] = $this->uid;
        }else{
            $menuwhere['user_id'] = $this->uid;
        }
        $data = M("g_order_info")->where($menuwhere)->find();
        $order_goods = M('g_order_goods')->where(array('order_id' => $data["order_id"]))->select();
        $this->order_goods = $order_goods;
        $express_data = $this->express_query($data['order_id'], $data['invoice_no']);
		$is_show_all = isset($_REQUEST['is_show_all']) && $_REQUEST['is_show_all'] > 0 ? $_REQUEST['is_show_all'] : 1;
		$this->assign('is_show_all', $is_show_all);
        $this->assign("express_data", $express_data);
        $this->assign("data", $data);
        $this->display();
    }

    //订单信息
    public function order_detail(){
        $memberinfo_de = $this->getMemberDetail();
        $memberinfo_de['balance'] += $memberinfo_de['balance_give'];
        $this->MemberDetail = $memberinfo_de;
        $balance = $memberinfo_de['balance'];
        $balance = sprintf("%.2f", substr(sprintf("%.3f", $balance), 0, -2));
        $this->balance = $balance;

        $menuwhere["order_id"] = array("in", $_GET["id"]);
        $menuwhere['user_id'] = $this->uid;
        $data = M("g_order_info")->where($menuwhere)->select();
		$data_sum = '';
		$pay_sn = '';
        if ($data) {
            for ($i=0; $i<count($data); $i++) { 
                if($data[$i]['pay_name']=='alipay'){
                    $data[$i]['payment']='支付宝支付';
                }else{
                    $data[$i]['payment']='微信支付';
                }
            
                $order_goods[$i] = M('g_order_goods')->where(array('order_id' => $data[$i]["order_id"]))->select();
                if ($order_goods[$i]) {
                    foreach ($order_goods[$i] as $key => $value) {
                        if($value['is_refund']){
                            $f_where = array();
                            $f_where['order_goods_id'] = $value['rec_id'];
                            $refund = M('g_order_goods_refund')->where($f_where)->find();

                            if($refund){
    							$refund = $this->get_refund_status($refund);
    							$order_goods[$i][$key]['code_name'] = $refund['code_name'];
                            }else{
    							$order_goods[$i][$key]['code_name'] = "退货";
                            }
                        }
                        $order_goods[$i][$key]['goods_image'] = thumbs_auto($value['goods_image'], 180, 180);
                    }
                }
                $offline_money[$i] = 0;
                if ($order_goods[$i]) {
                    foreach ($order_goods[$i] as $key => $value) {
                        if ($value['offline']) {
                            $offline_money[$i] = $offline_money[$i] + $value['goods_number'] * $value['goods_price'];
                        }
						if($value['activity_id'] > 0){
							$data[$i]['activity_end_date'] = $value['activity_end_date'];
						}
                    }
                }

    			if ($data[$i]['order_amount'] == $offline_money[$i]) {
    				$offline_money[$i] = $offline_money[$i] + $data[$i]['shipping_fee'];
    			}
    			$data[$i]['order_amount'] = $data[$i]['order_amount'] + $data[$i]['shipping_fee'];
    			$online_money[$i] = $data[$i]['order_amount'] - $data[$i]['surplus'] - $offline_money[$i];
    			
    			if ($online_money[$i] < 0) {///已经支付过了 不能货到付款
    				$offline_money[$i] = 0;
    			}
			    $order_show[$i] = $this->get_order_status($data[$i]);
    			$data[$i]['order_state'] = $order_show[$i]['code'];
    			$data[$i]['order_state_name'] = $order_show[$i]['name'];
    			if($data[$i]['order_state'] == "delivered"){
    				$f_where = array();
    				$f_where['order_id'] = $data[$i]['order_id'];
    				$f_where['refund_status'] = array('neq', '10');
    				$this->ref_all = M('g_order_goods_refund')->where($f_where)->count();
                }
                $data[$i] = $this->get_order_msg_data($data[$i]);////订单显示信息
				$data[$i]['status_remarks']= $this->status_before_deliver_list($data[$i]);

                $data_sum = $data_sum + $data[$i]['order_amount_all'] - $data[$i]['shipping_fee'];
                $pay_sn = $pay_sn.",".$data[$i]['order_id'];
            }
            $this->online_money1 = $online_money;
            $this->offline_money1 = $offline_money;
            $this->order_goods1 = $order_goods;
        }
        $pay_sn = substr($pay_sn, 1);
		// 支付状态；0，未付款；1，付款中 ；2，已付款
		$pay_status = array('0' => '未付款', '1' => '已支付', '2' => '已付款', '100'=>'等待买家付款', '101'=>'已支付，对账中', '102'=>'支付已完成');
		$this->pay_status = $pay_status;
		//商品配送情况，0，未发货； 1，已发货；2，已收货；3，备货中
		$shipping_status = array('0' => '未发货', '1' => '已发货', '2' => '已完成', '3' => '备货中');
		$this->shipping_status = $shipping_status;
		//订单状态。0，未确认；1，已确 认；2，已取消；3，无效；4，退货；
		$this->order_status = array('0' => '未确认', '1' => '已确认', '2' => '已取消', '3' => '无效', '4' => '退货');
        
		$title = "订单信息";
        $data_count = count($data);
        $where_order_pay['pay_id'] = $data[0]['pay_record_id'];
        $order_pay_sn = M('g_order_pay')->where($where_order_pay)->getField('pay_sn');
        $this->assign("pay_sn", $order_pay_sn);
        $this->assign("order_pay_sn", $pay_sn);
        $this->assign("data_count", $data_count);
        $this->assign("data_sum", $data_sum);
		$this->assign("data1", $data);
		$this->display();
    }

//发货前订单状态描述
	public function status_before_deliver($order_info){
		$status_remarks='';
		if($order_info['pay_status'] == 2){//已付款
			if($order_info['order_status'] == 1){//点单已确认
				
				if($order_info['to_buyer']){
					return $order_info['to_buyer'];
				}
				
				$confirm_time = $order_info['confirm_time'];//订单确认时间
				if(in_array($order_info['shipping_status'],array(0,3))){
					//假期
					$rest = array('0501','0502','0503','0504','1001','1002','1003','1004','1005','1006','1007'  );
					$status_remark_arr = array('您的订单已确认，信息接收中','信息已接收，备货中','您的产品正在进行打包~','正在等待快递揽件','您的产品已发货，请耐心等待包裹的到来');
					$time_ch=time() - $confirm_time;//订单确认到现在的实际时间
					$closing_time=mktime(18,0,0,date('m',$confirm_time),date('d',$confirm_time),date('Y',$confirm_time));//确认点单当天下班时间
					$time_ch1 = $closing_time - $confirm_time;//从确认订单到下班的——时间
					$time_ch2 = 0;
					$time_ch3 = 0;
					$days_ch =date('md',time()) - date('md',$confirm_time);//订单确认时间到现在的天数
					if($days_ch == 0){
						if(mb_number(date('H',time())) >= 18){
							$time_ch=$time_ch1;
						}
					}else{
						for($i=1;$i<=$days_ch;$i++){
							$_this_time = $confirm_time + 24*3600*$i;
							$_this_week = date('w',$_this_time);//这天是周几
							$_this_date = date('md',$_this_time);//这天几月几
							if(in_array($_this_week,array('6','0')) || in_array($_this_date,$rest)){

							}else{
								if($i == $days_ch){
									if(mb_number(date('Hi',time())) > 930){
										$starting_time = mktime(9,30,0,date('m',time()),date('d',time()),date('Y',time()));//当上班班时间
										$time_ch3 =time() - $starting_time ;
									}
								}else{
									$time_ch2 += 24*3600;
								}
							}
						}
						$time_ch = $time_ch1 +$time_ch2 +$time_ch3;
					}
					//判断 $status_remarks
					if($time_ch< 3600*2){
						$status_remarks = '您的订单已确认，信息接收中';
					}elseif ($time_ch>= 3600*2 && $time_ch < 3600*8){
						$status_remarks = '信息已接收，备货中';
					}elseif ($time_ch>= 3600*8 && $time_ch < 3600*10){
						$status_remarks = '您的产品正在进行打包~';
					}else{//$time_ch>= 3600*10
						$status_remarks = '正在等待快递揽件';
					}
				}elseif ($order_info['shipping_status'] == 1){
					$status_remarks = '您的产品已发货，请耐心等待包裹的到来';
				}else{
					$status_remarks = '已收货';
				}
			}

			$status_remarks = $order_info['to_buyer'] ? $order_info['to_buyer'] : $status_remarks;
		}
		return $status_remarks;
	}

 //订单支付信息
	public function order_pay()
	{
		$memberinfo_de = $this->getMemberDetail();
		$memberinfo_de['balance'] += $memberinfo_de['balance_give'];
		$this->MemberDetail = $memberinfo_de;
		$balance = $memberinfo_de['balance'];
		$balance = sprintf("%.2f", substr(sprintf("%.4f", $balance), 0, -2));
		$this->balance = $balance;//账户余额
		$this->points = (int)$memberinfo_de['points']; //账户积分

		$menuwhere["order_id"] = array("in", $_GET["pay_sn"]);
		$menuwhere['user_id'] = $this->uid;
		$data = M("g_order_info")->where($menuwhere)->select();
        $data_info = M("g_order_info");
		if ($data) {
            $order_pay_where['pay_id'] = $data[0]['pay_record_id'];
            $order_pay = M("g_order_pay")->where($order_pay_where)->find();
            $order_sn = explode(',', $order_pay['order_sn']);
            //如果小订单单独支付将该订单从原大订单中分离出来
            if (count($data) == 1 && count($order_sn) > 1) {
				$order_sn_old ='';
                for ($i=0; $i<count($order_sn); $i++) {
                    if ($order_sn[$i] == $data[0]['order_sn']) {
                        $order_sn_new = $order_sn[$i];
                    }else{
                        $order_sn_old = $order_sn_old.','.$order_sn[$i];
                    }
                }
                $order_pay['order_sn'] = substr($order_sn_old, 1);
                $order_pay_model = M('g_order_pay');
                $order_pay_model->startTrans();//开起事务
                $order_pay_id = $order_pay['pay_id'];
                unset($order_pay['pay_id']);
                $result = $order_pay_model->where(array('pay_id' => $order_pay_id))->save($order_pay);
                $order_pay['order_sn'] = $order_sn_new;
                $order_pay['pay_sn'] = makePaySn($this->uid);
                $result1 = $order_pay_model->add($order_pay);
                $data[0]['pay_record_id'] = $result1;
                if ($result === false || $result1 === false) {
                    $order_pay_model->rollback();//回滚事务
                }else{
                    $order_pay_model->commit();//提交事务
                    $result2 = $data_info->where(array('order_id' => $data[0]['order_id']))->save($data[0]);
                }
                unset($data);
                $data = $data_info->where($menuwhere)->select();
            }
			$data_sum = 0;
            for ($i=0; $i<count($data); $i++) {
                if($data[$i]['pay_name']=='alipay'){
                    $data[$i]['payment']='支付宝支付';
                }else{
                    $data[$i]['payment']='微信支付';
                }
                $order_goods[$i] = M('g_order_goods')->where(array('order_id' => $data[$i]["order_id"]))->select();
                $offline_money[$i] = 0;
                if ($order_goods[$i]) {
                    foreach ($order_goods[$i] as $key => $value) {
                        if ($value['offline']) {
                            $offline_money[$i] = $offline_money[$i] + $value['goods_number'] * $value['goods_price'];
                        }
						if($value['activity_id'] > 0){
							$data[$i]['activity_end_date'] = $value['activity_end_date'];
						}
                        $order_goods[$i][$key]['goods_image'] = thumbs_auto($value['goods_image'], 180, 180);
                    }
                }
                $order_show = $this->get_order_status($data[$i]);
                if ($data[$i]['order_amount'] == $offline_money[$i]) {
                    $offline_money[$i] = $offline_money[$i] + $data[$i]['shipping_fee'];
                }
                $data[$i]['order_amount'] = $data[$i]['order_amount'] + $data[$i]['shipping_fee'];
//标记
                $online_money[$i] = $data[$i]['order_amount'] - $data[$i]['surplus'] - $offline_money[$i];
                if ($online_money[$i] < 0) {///已经支付过了 不能货到付款
                    $offline_money[$i] = 0;
                }
                //如果是9.9大聚惠则判断是否允许余额积分支付
                $member_info=$this->getMemberInfo();
                $vip_type=$member_info['member_vip_type'];
                if ($data[$i]['activity_id'] == 2 && $vip_type<1) {
                    $this->assign("only_weixinpay", 1);
                }

                $data[$i]['order_state'] = $order_show['code'];
                $data[$i]['order_state_name'] = $order_show['name'];
                if ($data[$i]['order_state'] == "delivered") {
                    $f_where = array();
                    $f_where['order_id'] = $data[$i]['order_id'];
                    $f_where['refund_status'] = array('neq', '10');
                    $this->ref_all = M('g_order_goods_refund')->where($f_where)->count();
                }
                $data[$i] = $this->get_order_msg_data($data[$i]);////订单显示信息
                $data_sum = $data_sum + $data[$i]['order_amount_all'];
            }

            $this->online_money1 = $online_money;
            $this->order_goods1 = $order_goods;
            $this->offline_money1 = $offline_money;
		}
		// 支付状态；0，未付款；1，付款中 ；2，已付款
		$pay_status = array('0' => '未付款', '1' => '已支付', '2' => '已付款', '100'=>'等待买家付款', '101'=>'已支付，对账中', '102'=>'支付已完成');
		$this->pay_status = $pay_status;
		//商品配送情况，0，未发货； 1，已发货；2，已收货；3，备货中
		$shipping_status = array('0' => '未发货', '1' => '已发货', '2' => '已完成', '3' => '备货中');
		$this->shipping_status = $shipping_status;
		//订单状态。0，未确认；1，已确 认；2，已取消；3，无效；4，退货；
		$this->order_status = array('0' => '未确认', '1' => '已确认', '2' => '已取消', '3' => '无效', '4' => '退货');

		$title = "订单信息";
        $data_count = count($data);
        $where_order_pay['pay_id'] = $data[0]['pay_record_id'];
        $pay_sn = M('g_order_pay')->where($where_order_pay)->getField('pay_sn');
        $this->assign("pay_sn", $pay_sn);
        $this->assign("order_pay_sn", $_GET["pay_sn"]);
        $this->assign("data_count", $data_count);
        $this->assign("data_sum", $data_sum);
		$this->assign("data1", $data);
		$this->display();
	}

    //订单显示信息
    public function get_order_msg_data($data = array())
    {
        if (empty($data)) {
            return $data;
        }
        switch ($data['discount_type']) {
            case '1':
                $data['discount_name'] = '抽奖';
                break;

            default:
                # code...
                break;
        }
        $time = time();
        $data['order_amount_all'] = $data['order_amount'];
        $data['discount_price'] = $data['discount'];
        if ($data['pay_status'] == 2) {
            $data['order_amount'] = PriceFormat(floatval($data['order_amount']) - floatval($data['discount']));
        } else {
            if ($data['discount_start_time'] <= $time && $data['discount_end_time'] >= $time) {
                $data['order_amount'] = PriceFormat(floatval($data['order_amount']) - floatval($data['discount']));
            } else {
                $data['discount_price'] = 0;
            }
        }

        return $data;
    }

    ///服务 订单提交
    public function set_order()
    {
        $return_data['status'] = 0;
        $cart_id = $_REQUEST['cart_id'];
        $address_id = $_REQUEST['address_id'];
        $discount = $_REQUEST['discount'];
        $uid = $_SESSION['member']['uid'];
        //购买开关
        $buy_switch = M('sys_param')->where(array('param_code' => 'buy_switch'))->getField('param_value');
        if (empty($buy_switch)) {
            $return_data['error'] = '管理员已关闭交易';
            echo json_encode($return_data);
            die;
        }

        if (empty($address_id)) {
            $return_data['error'] = '请选择收货地址';
            echo json_encode($return_data);
            die;
        }
        $address = M('g_user_address')->where(array('address_id' => $address_id, 'user_id' => $uid))->find();
        if (empty($address)) {
            $return_data['error'] = '收货地址不存在';
            echo json_encode($return_data);
            die;
        }
        $cart_model = D('Cart');
        if ($discount) {
            $discount_msg = $cart_model->get_member_discount_one($discount, $uid);
            if (empty($discount_msg)) {
                $return_data['error'] = '该折扣已过期';
                echo json_encode($return_data);
                die;
            }
        }


        //$where['session_id']=session('session_id');
        $where['user_id'] = $uid;
        $where['rec_id'] = array('in', $cart_id);
        $list = $cart_model->getCartAll($where);
        //判断是否售罄
		$goods_id = '';
        for ($i=0; $i<count($list); $i++) {
            $goods_id = $goods_id.",".$list[$i]['goods_id'];
        }
        $goods_id = substr($goods_id,1);
        $where_sell['goods_id'] = array('in', $goods_id);
        $where_sell['is_sell_out'] = 1;
        $goods_sell_out = M('g_goods')->where($where_sell)->find();
        if ($goods_sell_out) {
            $return_data['error'] = "您购买的".$goods_sell_out['goods_name']."已售罄";
            echo json_encode($return_data);
            die;
        }
        //9.9活动商品的购买数量限制
        $pre = C('DB_PREFIX');
        $activity_msg = M('activity')->where("id=2")->find();
        for ($i=0; $i<count($list); $i++) { 
            if ($list[$i]['rec_type'] == 2 && time()>=$activity_msg['start_time'] && time()<$activity_msg['end_time']) {
                if ($list[$i]['goods_number'] != 1) {
                    $return_data['error'] = "亲，每个商品只能购买一件";
                    echo json_encode($return_data);
                    die;
                }
                $where_goods_list1['info.add_time'] = array("egt", $activity_msg['start_time']);
                $where_goods_list1['info.add_time'] = array("lt", $activity_msg['end_time']);
                $where_goods_list1['goods.goods_id'] = array("eq", $list[$i]['goods_id']);
                $where_goods_list1['info.order_status'] = array("in", "0,1,5");
                $where_goods_list1['info.activity_id'] = array("eq", "2");
                $where_goods_list1['info.user_id'] = array("eq", $uid);
                $order_goods_list1 = M()->table($pre . 'g_order_goods goods')//
                ->join($pre . 'g_order_info info on info.order_id=goods.order_id')//
                ->where($where_goods_list1)//
                ->select();
                if (!empty($order_goods_list1)) {
                    $return_data['error'] = "亲，每个商品只能购买一件";
                    echo json_encode($return_data);
                    die;
                }
            }
        }
		$province = $address['province'] ? $address['province'] : 0;
        //计算价格
        $list = $cart_model->getCartSettlement($list,$province);
        if (empty($list['status'])) {
            $return_data['error'] = $list['error'];
            echo json_encode($return_data);
            die;
        }
        //判断是否夹杂着活动商品
        foreach ($list['goods_list'] as $k) {
            $rec_types[] = $k['rec_type'];
        }
        $rec_types = array_unique($rec_types);
        $rec_types = array_merge($rec_types);
        //分单工作
        foreach ($list['goods_list'] as $k) {
            $base_ids[] = $k['base_id'];
        }
        $base_ids = array_unique($base_ids);
        $base_ids = array_merge($base_ids);
		$lists =array();
        //普通订单
        for ($i=0; $i<count($base_ids); $i++) {
            foreach ($list['goods_list'] as $k=>$v) {
                if ($v['base_id'] == $base_ids[$i] && $v['rec_type'] == 0) {
                    //var_dump($k['base_id']);
                    $lists[$i]['all_num'] = $lists[$i]['all_num'] + $v['goods_number'];
                    $lists[$i]['tolal'] = $lists[$i]['tolal'] + $v['all_market_price'];
                    $lists[$i]['need_pay'] = $lists[$i]['need_pay'] + $v['all_goods_price'];
					$lists[$i]['shipping_fee'] = $lists[$i]['shipping_fee'] + $v['shipping_money'];
                    $lists[$i]['goods_list'][] = $v;
                    unset($list['goods_list'][$k]);
                }
            }
            $lists[$i]['error'] = $list['error'];
            $lists[$i]['status'] = $list['status'];
            $lists[$i]['is_gift'] =  $list['is_gift'];
            $lists[$i]['is_upgrade'] = $list['is_upgrade'];
            if (empty($lists[$i]['goods_list'])) {
                unset($lists[$i]);
            }
        }
        //活动订单
        for ($i=count($lists); $i<count($lists)+count($list); $i++) { 
            foreach ($list['goods_list'] as $k=>$v) {
                for ($j=0; $j<count($rec_types); $j++) { 
                    if ($v['rec_type'] == $rec_types[$j] && $v['rec_type'] != 0) {
                        //var_dump($k['base_id']);
                        $lists[$i]['all_num'] = $lists[$i]['all_num'] + $v['goods_number'];
                        $lists[$i]['tolal'] = $lists[$i]['tolal'] + $v['all_market_price'];
                        $lists[$i]['need_pay'] = $lists[$i]['need_pay'] + $v['all_goods_price'];
                        $lists[$i]['shipping_fee'] = $lists[$i]['shipping_fee'] + $v['shipping_money'];
                        $lists[$i]['goods_list'][] = $v;
                        unset($list['goods_list'][$k]);
                        break 2;
                    }
                }
            }
            $lists[$i]['error'] = $list['error'];
            $lists[$i]['status'] = $list['status'];
            $lists[$i]['is_gift'] =  $list['is_gift'];
            $lists[$i]['is_upgrade'] = $list['is_upgrade'];
            if (empty($lists[$i]['goods_list'])) {
                unset($lists[$i]);
            }
        }
        $lists = array_merge($lists);
        $lists['all_num'] =  $list['all_num'];
        $lists['tolal'] =  $list['tolal'];
        $lists['need_pay'] =  $list['need_pay'];
        $lists['error'] =  $list['error'];
        $lists['status'] =  $list['status'];
        $lists['is_gift'] =  $list['is_gift'];
        $lists['is_upgrade'] =  $list['is_upgrade'];
		$lists['shipping_fee'] = $list['shipping_fee'];
        $goods = $list['goods_list'];
		//若有商品活动过期
		foreach ($goods as $key=>$value){
			if($value['activity_id'] > 0){
				if($value['activity_start_date'] > time()){
					$return_data['error'] = '活动未开始';
					echo json_encode($return_data);
					die;
				}elseif ($value['activity_end_date'] < time()){
					$return_data['error'] = '活动已结束';
					echo json_encode($return_data);
					die;
				}
			}
		}
        $time = time();
        $pay_sn = makePaySn($uid);
		$member = $this->getMemberInfo();

        $order_pay_data['buyer_id'] = $uid;
        $order_pay_data['pay_sn'] = $pay_sn;
        $order_pay_data['add_time'] = $time;

        $order_model = M('g_order_info');
        $order_goods_model = M('g_order_goods');
        $order_pay_model = M('g_order_pay');

        $order_model->startTrans();//开起事务
        $order_goods_model->startTrans();//开起事务
        $order_pay_model->startTrans();//开起事务

        //生成支付单号
        $add_order_pay = $order_pay_model->add($order_pay_data);
        $where_order_pay['pay_sn'] = $pay_sn;
        $order_pay_id = M('g_order_pay')->where($where_order_pay)->getField('pay_id');
        $order_sns ='';
        for ($i=0; $i<count($lists)-8; $i++) {
            if($member['member_vip_type']>0){
                $share_uid=$this->uid;
            }else{
                $share_uid=M('member')->where('member_card="'.$this->shop_code.'"')->getField('id');
            }
            $order_data[$i]['order_sn'] = makeOrderSn($uid);
            $order_sns = $order_sns.",".$order_data[$i]['order_sn'];
            $order_data[$i]['share_uid'] =$share_uid;
            $order_data[$i]['user_id'] = $uid;
            $order_data[$i]['pay_record_id'] = $order_pay_id;
            foreach ($lists[$i]['goods_list'] as $key => $value) {
                if ($value['rec_type'] == 2) {
                    $order_data[$i]['is_rebate'] = 1;//9.9大聚惠活动不返利
                    $order_data[$i]['activity_id'] = $value['rec_type'];
                }else{
                    $order_data[$i]['is_rebate'] = 0;//订单才生成，没有执行返利操作
                }
            }
            $order_data[$i]['consignee'] = $address['consignee'];
            $order_data[$i]['province'] = $address['province'];
            $order_data[$i]['city'] = $address['city'];//
            $order_data[$i]['district'] = $address['area'];//
            $order_data[$i]['address'] = $address['address_name'] . $address['address'];//用户消费时的折扣
            $order_data[$i]['zipcode'] = $address['zipcode'];//用户消费时的返利折扣
            $order_data[$i]['tel'] = $address['tel'];//
            $order_data[$i]['mobile'] = $address['mobile'];//
            $order_data[$i]['email'] = $address['email'];//
            $order_data[$i]['best_time'] = $address['best_time'];//收货人的最佳送货时间
            $order_data[$i]['sign_building'] = $address['sign_building'];//收货人的地址的标志性建筑
			$order_data[$i]['order_type'] = 2;//订单类型 0未知 1店铺订单 2商品订单 3基地订单
            $order_data[$i]['is_gift'] = $lists[$i]['is_gift'];//活动类型0 无 1 限时抢购

            $order_data[$i]['goods_amount'] = $lists[$i]['tolal'];//商品总金额
            $order_data[$i]['order_amount'] = $lists[$i]['need_pay'];//应付款金额

			$order_data[$i]['shipping_fee'] = $lists[$i]['shipping_fee'];//邮费
            //记录折扣信息
            if ($discount_msg && $discount_msg['discount'] > 0 && $discount_msg['discount'] < 1) {
                $order_data[$i]['discount_type'] = $discount_msg['type'];//折扣类型 1 抽奖
                $order_data[$i]['discount'] = $lists[$i]['need_pay'] - $lists[$i]['need_pay'] * $discount_msg['discount'];//discount 折扣金额
                $order_data[$i]['discount_val'] = $discount_msg['discount'];//折扣
                $order_data[$i]['discount_start_time'] = $discount_msg['start_time'];
                $order_data[$i]['discount_end_time'] = $discount_msg['end_time'];
            }
                /*  if ($list['need_pay'] < 68) {
                    $order_data['shipping_fee'] = 10;//订单金额小于 68 须支付运费10元
                }*/
            $order_data[$i]['add_time'] = $time;
        }
        $order_sns = substr($order_sns, 1);
        $order_pay_data1['order_sn'] = $order_sns;
        $add_order_pay = $order_pay_model->where($order_pay_data)->save($order_pay_data1);


		for ($i=0; $i<count($order_data); $i++) {
            $add_order[] = $order_model->add($order_data[$i]);
        }

        if ( in_array(false, $add_order, true) === true ) {
            $return_data['error'] = "订单生成失败，请稍后再试.";
            $order_model->rollback();//回滚事务
            $order_goods_model->rollback();//回滚事务
            $order_pay_model->rollback();//回滚事务
            echo json_encode($return_data);
            die;
        }
        for ($i=0; $i<count($add_order); $i++) {
            foreach ($lists[$i]['goods_list'] as $key => $value) {
                if ($value['rec_type'] == 1) {
                    $value['goods_name'] = '【限时抢购】' . $value['goods_name'];
                }elseif ($value['rec_type'] == 2) {
                    $value['goods_name'] = '【9.9大聚惠】' . $value['goods_name'];
                }else{
                    $value['goods_name'] =  $value['use_vip'] . $value['goods_name'];
                }
                $order_good_data['order_id'] = $add_order[$i];
                $order_good_data['goods_id'] = $value['goods_id'];
                $order_good_data['goods_name'] = $value['goods_name'];//商品名
                $order_good_data['goods_sn'] = $value['goods_sn'];//商品的唯一货号
                $order_good_data['goods_price'] = $value['goods_price'];//商品价格
                $order_good_data['market_price'] = $value['market_price'];//商品价格
                $order_good_data['goods_number'] = $value['goods_number'];//商品数量
                $order_good_data['goods_image'] = $value['goods_img'];//
                $order_good_data['goods_attr_id'] = $value['goods_attr_id'];//商品实际成交价
                $order_good_data['goods_attr'] = $value['goods_attr'];//商家id
                $order_good_data['share_card'] = $value['share_card'];//分享者 card
                $order_good_data['share_money'] = $value['share_money'];//分享返利金额
                $order_good_data['is_refund'] = $value['is_refund'];//是否可退货 1是 0否
                $order_good_data['goods_shipping'] = $value['goods_shipping'];//包邮方式 默认SF顺风包邮
                $order_good_data['shipping_code'] = $value['shipping_code'];//包邮方式code
                $order_good_data['offline'] = $value['offline'];//货到付款
				$order_good_data['order_type'] = 2;//订单类型 0未知 1店铺订单 2商品订单 3基地订单
                $order_good_data['is_exchange'] = $value['is_exchange'];////是否可退货 1是 0否
                $order_good_data['is_gift'] = $value['rec_type'];//活动类型0 无 1 限时抢购
                $order_good_data['promote_start_date'] = $value['promote_start_date'];
                $order_good_data['promote_end_date'] = $value['promote_end_date'];
                $order_good_data['activity_id'] = $value['rec_type'] ;//活动类型0 无 1、1元嗨购 2、9.9大聚惠
                $order_good_data['activity_status'] = 0 ; //初始值 如果  活动类型为1元嗨购 1 抢到  2 未抢到
                $order_good_data['activity_start_date'] = $value['activity_start_date'];
                $order_good_data['activity_end_date'] = $value['activity_end_date'];
				$order_good_data['shipping_money'] = $value['shipping_money'] ;//邮费
                $add_order_goods[] = $order_goods_model->add($order_good_data);
            }
        }
        $add_order_goods = array_unique($add_order_goods);//去重
        //in_array如果设置该参数为 true，则检查搜索的数据与数组的值的类型是否相同。
        if (in_array(false, $add_order, true) === false && $add_order_pay !== false && in_array(false, $add_order_goods, true) === false) {
            $return_data['status'] = 1;
            $order_model->commit();//提交事务
            $order_goods_model->commit();//提交事务
            $order_pay_model->commit();//提交事务
            //记录订单日志
            for ($i=0; $i<count($add_order); $i++) {
                $data = array();
                $data['order_id'] = $add_order[$i];
                $data['order_amount'] = $lists[$i]['need_pay'];
                $g_pay_log = M('g_pay_log')->add($data);
                //记录订单日志
                $data = array();
                $data['order_id'] = $add_order[$i];
                $data['log_role'] = 'buyer';
                $data['log_msg'] = '用户下单成功';
                $data['log_orderstate'] = '10';
                $insert = D('Mallorder')->addOrderLog($data);
            }
            $return_data['add_order'] = $add_order;
            // 删除购物车
            $del_where['session_id']=session('session_id');
            $del_where = array();
            $del_where['user_id'] = $uid;
            $del_where['rec_id'] = array('in', $cart_id);
            M('g_cart')->where($del_where)->delete();
        } else {
            $order_model->rollback();//回滚事务
            $order_goods_model->rollback();//回滚事务
            $order_pay_model->rollback();//回滚事务
            $return_data['error'] = "订单生成失败，请稍后再试.";
        }
        echo json_encode($return_data);
        die;
    }


    //确认收货
    public function order_delivered()
    {
        $return_data['status'] = 0;
        $o_where["order_id"] = array("eq", $_POST["order_id"]);
        $o_where['user_id'] = $this->uid;
        $data_info = M("g_order_info")->where($o_where)->find();
        if (empty($data_info)) {
            $return_data['error'] = "该订单不存在";
            echo json_encode($return_data);
            die;
        }
        $order_show = $this->get_order_status($data_info);

        if ($order_show['code'] != 'delivered') {//已发货
            $return_data['error'] = "订单状态错误";
            echo json_encode($return_data);
            die;
        }
        $refund = M('g_order_goods_refund')->where(array('member_id' => $data_info['user_id'], 'order_id' => $data_info['order_id']))->find();
        if (!empty($refund) && $refund['refund_status'] != '10') {//
            $return_data['error'] = "订单已退货不能确认";
            echo json_encode($return_data);
            die;
        }
        $model_order = D('Mallorder');
        //查询商家信息
        $model_order->startTrans();
        //更改订单状态
        $data = array();
        $data['shipping_status'] = 2;
        $data['finnshed_time'] = time();
        $order_res = $model_order->editOrder($data, $o_where);

        //记录商家日志
        if ($order_res !== false) {
            //记录订单日志
            $data = array();
            $data['order_id'] = $data_info['order_id'];
            $data['log_role'] = 'buyer';
            $data['log_msg'] = '订单确认收货';
            $data['log_orderstate'] = '40';
            $insert = $model_order->addOrderLog($data);

            ///用户返利 返积分 认证等
			if($data_info['is_upgrade'] == 0){
				// $ress = $this->set_member_exchange($data_info);///用户 返利等操作
				$ress = D('Profit')->set_member_exchange($data_info['order_id']);
			}
            $model_order->commit();
            $return_data['error'] = "操作成功";
            $return_data['status'] = 1;
            $send_data['order_sn'] = $data_info['order_sn'];
            $this->weixin_send($this->uid, $send_data, 2);
            echo json_encode($return_data);
            die;
        } else {
            $model_order->rollback();
            $return_data['error'] = "操作失败";
            echo json_encode($return_data);
            die;
        }
    }


    //业绩   10-13修改只显示已完成的订单 gqh
    public function share()
    {
        /*
       $pre = C('DB_PREFIX');//表前缀
       $where['or_good.share_card'] = $this->member_card;
       $where['ord.order_status'] = 1;
       $where['ord.shipping_status'] = 2;
       //$where['is_attention']=1;
       $p = $_REQUEST['p'];
       $pagesize = 10;
       $p = !empty($p) ? $p : 1;
       $start = ($p - 1) * $pagesize;
       $field = "or_good.*,ord.order_status,ord.shipping_status,ord.pay_status";
       $list = M()->table($pre . 'g_order_goods or_good')//
       ->join($pre . 'g_order_info ord on ord.order_id=or_good.order_id')//
       ->where($where)//
       ->field($field)->limit($start, $pagesize)->order('or_good.rec_id desc')->select();
       foreach ($list as $key => $value) {
           $order_share_status = $this->get_order_share_status($value);
           $list[$key]['code'] = empty($value['share_money']) ? '0' : $value['share_money'];
           $list[$key]['code'] = $order_share_status['code'];
           $list[$key]['code_name'] = $order_share_status['name'];
       }
       if (IS_AJAX) {
           echo json_encode($list);
           die;
       }
       $this->list = $list;
       $count = M()->table($pre . 'g_order_goods or_good')//
       ->join($pre . 'g_order_info order on order.order_id=or_good.goods_id')//
       ->where($where)->count();
       //已支付 已完成
       //$where['or_good.share_card']=$this->member_card;
       $where['ord.pay_status'] = 2;// 支付状态；0，未付款；1，付款中 ；2，已付款
       $where['ord.order_status'] = array('in', '1,4');//订单状态。0，未确认；1，已确 认；2，已取消；3，无效；4，退货；
       $where['ord.shipping_status'] = 2;//商品配送情况，0，未发货； 1，已发货；2，已收货；3，备货中
       $this->count_share = M()->table($pre . 'g_order_goods or_good')//
       ->join($pre . 'g_order_info ord on ord.order_id=or_good.order_id')//
       ->where($where)->count();
       */
        $where['member_id']=$this->uid;
        $p=$_REQUEST['p'];
        $pagesize=10;
        $p=!empty($p)?$p:1;
        $start=($p-1)*$pagesize;
        $type=$_REQUEST['type'];//1积分 0 米值
        $field="*";
        $model=M('member_consume_record');
        $arr=array('1'=>'订单消费','2'=>'充值','3'=>'提现','4'=>'退款','5'=>'收益','6'=>'认证消费','7'=>'系统赠送');
        $where['status']=1;
        $where['type']=5;
        $where['type5_type']=1;//收益类型  1直接推荐人 2间接推荐人 3 间接二级推荐人 4代理区域会员
        $list=$model->where($where)//
        ->field($field)->limit($start,$pagesize)->order('add_time desc')->select();
        //消费类型 1订单消费 2充值 3提现   4退款 5 收益 6认证消费

        if(!empty($list)){
            foreach ($list as $key => $value) {
                $typename=$arr[$value['type']];
                $typename?$typename:'-';
                $list[$key]['typename']=$typename;
                $list[$key]['status']=$value['status']==1?'+':'-';
                $list[$key]['add_time']=date("Y/m/d",$value['add_time']);
            }
        }
        if(IS_AJAX){
            echo json_encode($list);die;
        }
        $this->list=$list;
        $data['balance_all']=sprintf("%.0f",substr(sprintf("%.3f", $data['balance_all']), 0, -3));
        $count=$model->where($where)->sum('money');
        $count=sprintf("%.0f",substr(sprintf("%.3f", $count), 0, -3));
        $this->count_share = $count;
        $this->display();
    }

    //获取订单状态
    public function get_order_share_status($order_info = array())
    {
        if (empty($order_info)) {
            return array();
        }
// pay_status支付状态；0，未付款；1，付款中 ；2，已付款
// shipping_status 商品配送情况，0，未发货； 1，已发货；2，已收货；3，备货中
//order_status订单状态。0，未确认；1，已确 认；2，已取消；3，无效；4，退货；
        $order_status = array('0' => '未确认', '1' => '已确认', '2' => '已取消', '3' => '无效', '4' => '退货');
        $shipping_status = array('0' => '未发货', '1' => '已发货', '2' => '已完成', '3' => '备货中');
        $code = $name = "";
        if ($order_info['order_status'] == 1) {//已确认
            $name = '进行中';
            switch ($order_info['shipping_status']) {
                case '1':
                    $code = "delivered";//已发货
                    break;
                case '2':
                    $code = "received";//已收货
                    $name = "已完成";
                    break;
                default:
                    $code = 'nondelivery';//未发货
                    break;
            }
            return array('code' => $code, 'name' => $name);
        } else {//未确认
            //2已取消；3无效；4，退货；
            if (in_array($order_info['order_status'], array('2', '3', '4'))) {
                if ($order_info['order_status'] == 4) {
                    return array('code' => '', 'name' => '已完成');
                } else {
                    return array('code' => '', 'name' => $order_status[$order_info['pay_status']]);
                }
            } else {////未确认
                if ($order_info['pay_status'] == 2) {//已付款 待确认
                    return array('code' => 'unconfirmed', 'name' => '进行中');
                } else {
                    return array('code' => 'nopay', 'name' => '未付款');
                }
            }
        }

    }

    ///支付
    public function go_pay(){
        $return_data['status'] = 0;
        $order_id = $_REQUEST['order_id'];
        $uid = $this->uid;
        $mem_password = $_REQUEST['mem_password'];
        $pay_type = $_REQUEST['pay_type'];
        $use_balance = $_REQUEST['use_balance'];
        $use_integral = $_REQUEST['use_integral'];
        //购买开关
        $buy_switch = M('sys_param')->where(array('param_code' => 'buy_switch'))->getField('param_value');
        if (empty($buy_switch)) {
            $return_data['error'] = '管理员已关闭交易';
            echo json_encode($return_data);
            die;
        }
        $model_order = D('Mallorder');

        //判断是否售罄
        $where_goods_list['order_id'] = array("in", $order_id);
        $order_goods_list = M("g_order_goods")->where($where_goods_list)->select();
		$goods_id = '';
        for ($i=0; $i<count($order_goods_list); $i++) {
            //顺便判断是否活动过期
            if ($order_goods_list[$i]['activity_id'] == 2) {
                $activity99 = M("activity")->where("id=2")->find();
                if ($activity99['start_time']>time()) {
                    $return_data['error'] = "活动未开始";
                    echo json_encode($return_data);
                    die;
                }elseif ($activity99['end_time']<=time()) {
                    $return_data['error'] = "活动已结束";
                    echo json_encode($return_data);
                    die;
                }elseif ($order_goods_list[$i]['goods_number'] != 1) {
                    $return_data['error'] = "亲，每个商品只能购买一件";
                    echo json_encode($return_data);
                    die;
                }else{//判断活动期间是否买过同样商品
                    $pre = C('DB_PREFIX');
                    $where_goods_list1['goods.order_id'] = array("neq", $order_goods_list[$i]['order_id']);
                    $where_goods_list1['info.add_time'] = array("egt", $activity99['start_time']);
                    $where_goods_list1['info.add_time'] = array("lt", $activity99['end_time']);
                    $where_goods_list1['goods.goods_id'] = array("eq", $order_goods_list[$i]['goods_id']);
                    $where_goods_list1['info.order_status'] = array("in", "0,1,5");
                    $where_goods_list1['info.activity_id'] = array("eq", "2");
                    $where_goods_list1['info.user_id'] = array("eq", $uid);
                    $order_goods_list1 = M()->table($pre . 'g_order_goods goods')//
                    ->join($pre . 'g_order_info info on info.order_id=goods.order_id')//
                    ->where($where_goods_list1)//
                    ->select();
                    if (!empty($order_goods_list1)) {
                        $return_data['error'] = "亲，每个商品只能购买一件";
                        echo json_encode($return_data);
                        die;
                    }
                }
            }
            $goods_id = $goods_id.",".$order_goods_list[$i]['goods_id'];
        }
        $goods_id = substr($goods_id,1);
        $where_sell['goods_id'] = array('in', $goods_id);
        $where_sell['is_sell_out'] = 1;
        $goods_sell_out = M('g_goods')->where($where_sell)->find();
        if ($goods_sell_out) {
            $return_data['error'] = "您购买的".$goods_sell_out['goods_name']."已售罄";
            echo json_encode($return_data);
            die;
        }

        if ($pay_type == "pay_online") {//在线支付
            $save_data = array();
            $save_data['offline'] = 0;//改变订单 货到付款状态
            $where_order_info['order_id'] = array("in", $order_id);
            $res = M('g_order_info')->where($where_order_info)->save($save_data);
            if ($res === false) {
                $return_data['error'] = '支付错误 请刷新页面重试';
                $return_data['status'] = 0;
                echo json_encode($return_data);
                die;
            }
			if($use_balance || $use_integral){
				$user_pwd = M('member')->where('id='.$uid)->getField('password');
				if(md5($mem_password) != $user_pwd){
					$return_data['error'] = '密码错误';
					$return_data['status'] = 0;
					echo json_encode($return_data);
					die;
				}
				$this->balance_pay(); //使用余额支付
				die;
            }else{
                $condition = array();
                $condition['order_id'] = array("in", $order_id);
                $condition['pay_status'] = '0';// 支付状态；0，未付款；1，付款中 ；2，已付款
                $order_list = $model_order->getOrderList($condition, '', 'order_id,order_sn,order_amount,shipping_fee,surplus,surplus_give,discount_start_time,discount_end_time,discount');
                if (empty($order_list)) {
                    $return_data['error'] = "该订单不存在";
                    echo json_encode($return_data);
                    die;
                }
                ////未使用余额支付
                $_GET['return'] = 1;
                for ($i=0; $i<count($order_list); $i++) {
                    $_GET['pay_sn'][] = $order_list[$i]['order_sn'];
                }
                $return_data['need_pay'] = 1; //需要支付 1  不需要支付0
                $return_data['status'] = 1;
                echo json_encode($return_data);
                die;
            }
        }
    }
	//订单数统计


    ///余额支付
    public function balance_pay()
    {
        $return_data['status'] = 0;

        $uid = $this->uid;
        $order_id = $_REQUEST['order_id'];
		$use_balance = $_REQUEST['use_balance'];
        $use_integral = $_REQUEST['use_integral'];
        $mem_password = $_REQUEST['mem_password'];

        if (empty($mem_password)) {
            $return_data['error'] = "密码不能为空";
            echo json_encode($return_data);
            die;
        }
        $member = $this->getMemberInfo();
        if ($member['password'] != md5($mem_password)) {
            $return_data['error'] = "密码错误";
            echo json_encode($return_data);
            die;
        }
        $memberinfo_de = $this->getMemberDetail();
        if (empty($memberinfo_de)) {
            $return_data['error'] = "用户不存在";
            echo json_encode($return_data);
            die;
        }

		// //余额
        $balance = $memberinfo_de['balance'] + $memberinfo_de['balance_give'];
        $balance = sprintf("%.2f", substr(sprintf("%.4f", $balance), 0, -2));
        if($balance <= 0 && $use_balance){
            $return_data['error'] = "余额不足";
            echo json_encode($return_data);
            die;
        }
		//积分
		$integral = (int)$memberinfo_de['points']; //账户可用积分
		$integral_left = $memberinfo_de['points'] - $integral;//积分小数部分
		if($integral <= 0 && $use_integral){
			$return_data['error'] = "积分不足";
            echo json_encode($return_data);
            die;
		}
        $order_id = explode(",", $order_id);
        for ($i=0; $i<count($order_id); $i++) {
            $model_order = D('Mallorder');
            //重新计算在线支付且处于待支付状态的订单总额
            $condition = array();
            $condition['order_id'] = $order_id[$i];
            $condition['pay_status'] = '0';// 支付状态；0，未付款；1，付款中 ；2，已付款
            $order_list[$i] = $model_order->getOrderList($condition, '', 'order_id,pay_record_id,order_sn,user_id,order_amount,shipping_fee,is_upgrade,surplus,surplus_give,integral,integral_money,discount_start_time,discount_end_time,discount,offline,offline_money,share_uid');
            if (empty($order_list[$i])) {
                $return_data['error'] = "该订单不存在";
                echo json_encode($return_data);
                die;
            }

            //计算本次需要在线支付的订单总金额
            $time = time();
            $pay_amount[$i] = 0;
            $offline_money = 0;
            $fee_offline_money = 0;//加运费的货到付款总额

            foreach ($order_list[$i] as $order_info) {

                $pay_amount[$i] += PriceFormat(floatval($order_info['shipping_fee']));//运费
                ///减去余额支付部分 减去折扣部分
                ///折扣是否过期
                if ($order_info['discount_start_time'] <= $time && $order_info['discount_end_time'] >= $time) {
                    $pay_amount[$i] += PriceFormat(floatval($order_info['order_amount']) - floatval($order_info['surplus']) - floatval($order_info['integral_money']) - floatval($order_info['discount']));
                } else {
                    $pay_amount[$i] += PriceFormat(floatval($order_info['order_amount']) - floatval($order_info['surplus']) - floatval($order_info['integral_money']));
                }
                if ($order_info['offline']) {
                    $pay_amount[$i] -= $order_info['offline_money'];//
                }
            }
            $need_pay = 1;//还需要支付现金
            $m_s_data[$i] = array();
            $surplus_give = 0;///支付的赠送余额
    		$left_pay_money = $pay_amount[$i];
    		$money_to_integral = 10;
    		$money[$i] = 0;
    		//使用余额时扣除余额
    		if($use_balance){
    			if($left_pay_money <= $balance){//余额足够
    				if($memberinfo_de['balance_give'] < $left_pay_money){//赠送余额不足
    					$m_s_data[$i]['balance_give'] = 0;
    					$m_s_data[$i]['balance'] = $balance - $pay_amount[$i];
    					$surplus_give = $memberinfo_de['balance_give'];
    					$money[$i] = $pay_amount[$i];
    					$left_pay_money = 0;
                        $balance = $balance - $pay_amount[$i];
    				}else{//赠送余额足够
    					$m_s_data[$i]['balance_give'] = mb_number($memberinfo_de['balance_give'] - $pay_amount[$i]);
    					$m_s_data[$i]['balance'] = $memberinfo_de['balance'];
    					$surplus_give = $pay_amount[$i];
    					$money[$i] = $pay_amount[$i];
    					$left_pay_money = 0;
                        $balance = $balance - $pay_amount[$i];
    				}
    			}else{//余额不足
    				$m_s_data[$i]['balance_give'] = 0;
    				$m_s_data[$i]['balance'] = 0;
    				$surplus_give = $memberinfo_de['balance_give'];
    				$money[$i] = $balance;
    				$left_pay_money -= $balance;
                    $balance = 0;
    			}
    		}

    		$points[$i] = 0;
    		//使用积分支付
    		$integral_to_money[$i] = 0;

    		if($use_integral && $left_pay_money > 0){
    			$left_pay_integral = $left_pay_money * $money_to_integral;
    			if($left_pay_integral > $integral){ //积分不足
    				$m_s_data[$i]['points'] = $integral_left;
    				$points[$i] = $integral;
    				$left_pay_money -= ($integral / $money_to_integral);
                    $integral = 0;
    			}else{ //积分足够
    				$m_s_data[$i]['points'] = $integral - $left_pay_integral + $integral_left;
    				$points[$i] = $left_pay_integral;
    				$left_pay_money = 0;
                    $integral = $integral - $points[$i];
    			}
    			$integral_to_money[$i] = ($points[$i]/$money_to_integral);
    		}
    		if ($money[$i] != 0 || $points[$i] != 0) {
                $log_des[$i] = '用户使用余额支付：￥'.$money[$i].'；积分支付：￥'.$integral_to_money[$i].'（'.$points[$i].'积分）';
            }
    		if($left_pay_money == 0){
    			$need_pay = 0;
                $want_pay[$i] = 0;
    		}else{
                $need_pay = 1;
                $want_pay[$i] = 1;
            }

        }
        //如果最后一单需要支付的金额为0，说明已经都支付过了或已经取消或者是价格为0的商品订单，全部返回
        if (empty($pay_amount[$i - 1])) {
            $return_data['error'] = "订单金额为0，不需要支付";
            echo json_encode($return_data);
            die;
        }
        $consume_record_model = M('member_consume_record');
        $integral_model = M('member_integral');
        $member_detail_model = M('member_detail');
        $model_order = M('g_order_info');

        $member_detail_model->startTrans();//开起事务
        $consume_record_model->startTrans();//开起事务
        $integral_model->startTrans();////开起事务
        $model_order->startTrans();//开起事务
        for ($i=0; $i<count($order_id); $i++) {
            $time = time();
            $log[$i] = array();
            $log[$i]['status'] = 0;//支出
            $log[$i]['add_time'] = $time;
            $log[$i]['member_id'] = $uid;
            $log[$i]['money'] = $money[$i];
            $log[$i]['integral'] = $points[$i];
            $log[$i]['type'] = 1;
            $log[$i]['des'] = $log_des[$i];
    		$log[$i]['order_id']=$order_id[$i];
            $save_order[$i] = array();
            $save_order[$i]['surplus'] = $order_list[$i][0]['surplus'] + $money[$i];
            $save_order[$i]['surplus_give'] = $order_list[$i][0]['surplus_give'] + $surplus_give;//支付的赠送余额
            $save_order[$i]['integral'] = $order_list[$i][0]['integral'] + $points[$i];//支付的积分
            $save_order[$i]['integral_money'] = $order_list[$i][0]['integral_money'] + $integral_to_money[$i];//支付的积分相当于的金额
            if (empty($want_pay[$i])) {
                $save_order[$i]['pay_status'] = '2';
                $save_order[$i]['pay_time'] = $time;
            }
            $m_res = $member_detail_model->where(array('member_id' => $uid))->save($m_s_data[$i]);
            if (!empty($log[$i]['des'])) {
                $c_res = $consume_record_model->add($log[$i]);
            }
    		$log[$i]['type']=2;//积分type 1，获得 2，消费
    		$log[$i]['status']=2;//积分status 1，获得 2，消费
    		$i_res = $integral_model->add($log[$i]);
            $order_res = $model_order->where(array('order_id' => $order_id[$i]))->save($save_order[$i]);

        }

        //in_array如果设置该参数为 true，则检查搜索的数据与数组的值的类型是否相同。
        if ($m_res !== false && $c_res !== false && $order_res !== false) {
            $return_data['status'] = 1;
            $return_data['need_pay'] = $need_pay;
            for ($i=0; $i<count($order_id); $i++) {
                $log_msg[$i] = '用户使用余额支付￥' . $money[$i] . '；积分支付￥'. $integral_to_money[$i].'（'.$points[$i].'积分）';
                if ($want_pay[$i]) {//需要继续支付
                    $_GET['return'] = 1;
                    $_GET['pay_sn'][] = $order_list[$i][0]['order_sn'];
                    /* $get_pay_url = $this->get_pay_url();
                     if ($get_pay_url['status'] == 1) {
                         $return_data['pay_url'] = $get_pay_url['pay_url'];
                     } else {
                         $return_data['error'] = $get_pay_url['error'];
                         $return_data['status'] = 0;
                     }*/
                } else {//余额  支付完成
                    //支付完成 发送短信通知
                    $this->order_sendSMS($order_list[$i][0]['order_sn'], $save_order[$i]['surplus'], '余额支付');
                    //支付完成 给买家和卖家发送微信通知
                    $goods_msg = '';
                    $order_goods = M("g_order_goods")->where(array('order_id' => $order_id[$i]))->select();
                    for ($j=0; $j<count($order_goods); $j++) { 
                        $goods_msg = $goods_msg."，".$order_goods[$j]['goods_name'];
                    }
                    $send_data['member_id'] = $_SESSION['member']['uid'];
                    $send_data['goods_msg'] = substr($goods_msg, 3);
                    $this->weixin_send($order_list[$i][0]['share_uid'], $send_data, 1);
                    $send_data1['order_sn'] = $order_list[$i][0]['order_sn'];
                    $this->weixin_send($_SESSION['member']['uid'], $send_data1, 2);
                    //购买成功  查询是否是会员 不是会员则升级为会员
                    if ($order_list[$i][0]['is_upgrade']) {//升级订单
                        $member = M('member')->where(array('id' => $order_list[$i][0]['user_id']))->field('id,member_vip_type')->find();
                        if ($member && empty($member['member_vip_type'])) {
                            $reee = M('member')->where(array('id' => $order_list[$i][0]['user_id']))->save(array('member_vip_type' => 1,'vip_time'=>time()));
                            $log_msg[$i] .= '升级会员成功';
                        }
                    }
                }
            }

            $model_order->commit();//提交事务
            $consume_record_model->commit();//提交事务
            $member_detail_model->commit();//提交事务
            $integral_model->commit();//提交事务
            //记录订单日志
            /* if(empty($need_pay)){//订单支付日志
               $data = array();
               $data['order_id'] = $order_id;
               $data['order_amount'] =$money;
                M('g_pay_log')->add($data);
              }*/
            //记录订单日志
            for ($i=0; $i<count($order_id); $i++) {
                $data[$i] = array();
                $data[$i]['order_id'] = $order_id[$i];
                $data[$i]['log_role'] = 'buyer';
                $data[$i]['log_msg'] = $log_msg[$i];
                if (empty($want_pay[$i])) {
                    $data[$i]['log_orderstate'] = '20';
                } else {
                    $data[$i]['log_orderstate'] = '10';
                }
                $insert = D('Mallorder')->addOrderLog($data[$i]);
            }

        } else {
            $model_order->rollback();//回滚事务
            $consume_record_model->rollback();//回滚事务
            $member_detail_model->rollback();//回滚事务
            $integral_model->rollback();//回滚事务
            $return_data['error'] = "支付失败请稍候再试.";
        }
        echo json_encode($return_data);
        die;
    }

	//订单删除
	public function order_del(){
		$return_data['status'] = 0;
        $o_where["order_id"] = array("eq", $_POST["order_id"]);
        $o_where['user_id'] = $this->uid;
        $o_where['user_del'] = 0;
        $data_info = M("g_order_info")->where($o_where)->find();
        if (empty($data_info)) {
            $return_data['error'] = "该订单不存在";
            echo json_encode($return_data);
            die;
        }
        $order_show = $this->get_order_status($data_info);
        if (!in_array($order_show['code'],  array('finish', 'received'))){//已发货
            $return_data['error'] = "订单状态错误，删除失败！";
            echo json_encode($return_data);
            die;
        }

        $order_res = M("g_order_info")->where($o_where)->save(array('user_del' => 1));

		if($order_res !== false){
			 //记录订单日志
			$data = array();
			$data['order_id'] = $data_info['order_id'];
			$data['log_role'] = 'buyer';
			$data['log_msg'] = '删除订单';
			$data['log_orderstate'] = '0';
			$insert = D('Mallorder')->addOrderLog($data);

			$return_data['code'] = $order_show['code'];
            $return_data['status'] = 1;
            echo json_encode($return_data);
            die;
		}else{
			$return_data['error'] = "操作失败";
            echo json_encode($return_data);
            die;
		}
	}

    //订单取消
    public function order_cancel()
    {
        $return_data['status'] = 0;
        $o_where["order_id"] = array("eq", $_POST["order_id"]);
        $o_where['user_id'] = $this->uid;
        $o_where['user_del'] = 0;
        $data_info = M("g_order_info")->where($o_where)->find();
        if (empty($data_info)) {
            $return_data['error'] = "该订单不存在";
            echo json_encode($return_data);
            die;
        }
        $order_show = $this->get_order_status($data_info);
        if ($order_show['code'] != 'nopay') {//已发货
            $return_data['error'] = "订单状态错误，取消失败！";
            echo json_encode($return_data);
            die;
        }

		$order_res = M("g_order_info")->where($o_where)->save(array('order_status' => 2));
        //记录商家日志
        if ($order_res !== false) {
            if ($order_show['code'] == 'nopay' && ($data_info['surplus'] > 0 || $data_info['integral'] > 0)) {//未付款 已支付的订单退款 （余额支付）
				$log_integral = $log = array();

				$log_msg = '取消订单退款 ';
				if($data_info['surplus'] > 0){
					$log['type'] = 4;//消费类型 1订单消费 2充值 3提现   4退款 5 收益 6认证消费
					$log['order_id'] = $data_info['order_id'];
					$log['des'] = '取消订单退余额支付金额：￥' . $data_info['surplus'];
					//加减用户 记录日志  状态 1收入 2支出  如果有赠送余额支付 扣除赠送余额
					$return_data['user_b'] = $this->set_member_balance($data_info['user_id'], 1, $data_info['surplus'], $log, $data_info['surplus_give']);
					$log_msg .= '余额支付金额：￥' . $data_info['surplus'] .'；';
				}
				if($data_info['integral'] > 0){
					$log_integral['type'] = 4;//消费类型 1订单消费 2充值 3提现   4退款 5 收益 6认证消费
					$log_integral['order_id'] = $data_info['order_id'];
					$log_integral['des'] = '取消订单退积分：' . $data_info['integral'];
					$return_data['user_c'] = $this->set_member_points($data_info['user_id'], 1, $data_info['integral'], $log_integral);
					$log_msg .= '积分支付金额：￥' . $data_info['integral_money'] . '（'.$data_info['integral'].'）；';
				}
                if ($return_data['user_b'] || $return_data['user_c']) {
                    //记录订单日志
                    $data = array();
                    $data['order_id'] = $data_info['order_id'];
                    $data['log_role'] = 'buyer';
                    $data['log_msg'] = $log_msg;
                    $data['log_orderstate'] = '0';
                    $insert = D('Mallorder')->addOrderLog($data);
                }
            } elseif ($order_show['code'] == 'nopay') {
                //记录订单日志
                $data = array();
                $data['order_id'] = $data_info['order_id'];
                $data['log_role'] = 'buyer';
                $data['log_msg'] = '取消订单';
                $data['log_orderstate'] = '0';
                $insert = D('Mallorder')->addOrderLog($data);
            }

            $return_data['code'] = $order_show['code'];
            $return_data['status'] = 1;
            echo json_encode($return_data);
            die;
        } else {
            $return_data['error'] = "操作失败";
            echo json_encode($return_data);
            die;
        }
    }

    ///支付
    public function go_wx_pay(){
        $return_data['status']=0;
        $order_sn=I('order_sn');
        $pay_type=I('pay_type');
        $pay_name = I('pay_name');
        //购买开关
        $buy_switch=M('sys_param')->where(array('param_code'=>'buy_switch'))->getField('param_value');
        if(empty($buy_switch)){
            $return_data['error']='管理员已关闭交易';
            echo json_encode($return_data);die;
        }
        $model_order = D('Mallorder');
        $save_data=array();
        $save_data['pay_name']=$pay_name;//改变订单 支付方式
        $res=M('g_order_info')->where(array('order_sn'=>$order_sn))->save($save_data);
        if($res===false){
            $return_data['error']='支付错误，请刷新页面重试';
            $return_data['status']=0;
            echo json_encode($return_data);die;
        }
        //在线支付
        $condition = array();
        $condition['order_sn'] = $order_sn;
        $condition['pay_status'] = '0';// 支付状态；0，未付款；1，付款中 ；2，已付款
        $order_list = $model_order->getOrderList($condition,'','*');
        if (empty($order_list)) {
            $return_data['error']="该订单不存在";
            echo json_encode($return_data);die;
        }
        //计算本次需要在线支付的订单总金额
        $time=time();
        $pay_amount = 0;
        $offline_money=0;
        foreach ($order_list as $order_info) {
            ///减去余额支付部分 减去折扣部分
            ///折扣是否过期
            if($order_info['discount_start_time']<=$time && $order_info['discount_end_time']>=$time){
                $pay_amount += PriceFormat(floatval($order_info['order_amount']) - floatval($order_info['first_discount'])  - floatval($order_info['surplus']) - floatval($order_info['discount']));
            }else{
                $pay_amount += PriceFormat(floatval($order_info['order_amount']) - floatval($order_info['first_discount']) - floatval($order_info['surplus']));
            }
        }
        //如果为空，说明已经都支付过了或已经取消或者是价格为0的商品订单，全部返回
        if (empty($pay_amount)) {
            $return_data['error']="订单金额为0，不需要支付";
            echo json_encode($return_data);die;
        }
        $pay_sn=$order_list[0]['order_sn'];
        $get_pay_url=$this->get_pay_url($pay_sn,$pay_amount);
        $return_data['need_pay']=1;///需要支付 1  不需要支付0
        if($get_pay_url['status']==1){
            $return_data['status']=1;
            $return_data['pay_url']=$get_pay_url['pay_url'];
        }else{
            $return_data['error']=$get_pay_url['error'];
            $return_data['status']=0;
        }
        echo json_encode($return_data);die;

    }
    ///获取支付链接
    public function get_pay_url($pay_sn = '', $pay_amount = 0)
    {
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        $return = $_GET['return'];
		$data['status'] = 0;
        if (strpos($user_agent, 'MicroMessenger') === false) {
            $data['status'] = 0;
            $data['error'] = '请在微信上打开支付';
            if ($return == 1) {
                return $data;
            }
            echo json_encode($data);
            die;
        }
        $payment_code = 'wxpay';
        if ($payment_code == "wxpay") {
            $user_open_id = $_SESSION['user_open_id'];
            if (empty($user_open_id)) {
                $data['status'] = 0;
                $data['error'] = "拉起支付失败，请刷新页面再试";
                session('user_open_error', null);
                if ($return == 1) {
                    return $data;
                }
                echo json_encode($data);
                die;
            }
            $url = 'pay_sn=' . $pay_sn
                . '&pay_amount=' . $pay_amount
                //    .'&subject='.$order_pay_info['subject']
                . '&openid=' . $user_open_id;

            $url = "http://" . $_SERVER['HTTP_HOST'] . '/api/payment/wpay/get_pay_fenxiao.php?' . $url;
            $data['status'] = 1;
            $data['pay_url'] = $url;
            return $data;
        }

        echo json_encode($data);
        die;
    }



    ///支付宝
    public function go_alipay_pay(){
        $return_data['status']=0;
        $order_sn=I('order_sn');
        $pay_name ='alipay';
        //购买开关
        $buy_switch=M('sys_param')->where(array('param_code'=>'buy_switch'))->getField('param_value');
        if(empty($buy_switch)){
            $return_data['error']='管理员已关闭交易';
            echo json_encode($return_data);die;
        }
        $model_order = D('Mallorder');
        $save_data=array();
        $save_data['pay_name']=$pay_name;//改变订单 支付方式
        $res=M('g_order_info')->where(array('order_sn'=>$order_sn))->save($save_data);
        if($res===false){
            $return_data['error']='支付错误，请刷新页面重试';
            $return_data['status']=0;
            echo json_encode($return_data);die;
        }
        //在线支付
        $condition = array();
        $condition['order_sn'] = $order_sn;
        $condition['pay_status'] = '0';// 支付状态；0，未付款；1，付款中 ；2，已付款
        $order_list = $model_order->getOrderList($condition,'','*');

        if (empty($order_list)) {
            $return_data['error']="该订单不存在";
            echo json_encode($return_data);die;
        }
        //计算本次需要在线支付的订单总金额
        $time=time();
        $pay_amount = 0;
        $offline_money=0;
        foreach ($order_list as $order_info) {
            ///减去余额支付部分 减去折扣部分
            ///折扣是否过期
            if($order_info['discount_start_time']<=$time && $order_info['discount_end_time']>=$time){
                $pay_amount += PriceFormat(floatval($order_info['order_amount']) - floatval($order_info['first_discount'])  - floatval($order_info['surplus']) - floatval($order_info['discount']));
            }else{
                $pay_amount += PriceFormat(floatval($order_info['order_amount']) - floatval($order_info['first_discount']) - floatval($order_info['surplus']));
            }
        }
        //如果为空，说明已经都支付过了或已经取消或者是价格为0的商品订单，全部返回
        if (empty($pay_amount)) {
            $return_data['error']="订单金额为0，不需要支付";
            echo json_encode($return_data);die;
        }
        $return_data['need_pay']=1;///需要支付 1  不需要支付0
        $return_data['status']=1;
        echo json_encode($return_data);die;

    }
}