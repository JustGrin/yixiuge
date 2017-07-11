<?php
/****
** 订单
** 
**/
class OrderAction extends AuthAction{

    //会员列表
	public function index(){
		if(empty($_SESSION['topadmin'])){
			$where['store_id']=session('afid');
		}
        if($_GET['pay_sn']){
			$where['pay_sn']=array('like',$_GET['pay_sn'].'%');
		}
		if($_GET['member_name']){
			$where['buyer_name']=array('like',$_GET['member_name'].'%');
		}
		if($_GET['mobile']){
			$where['buyer_mobile']=array('like',$_GET['mobile'].'%');
		}
		if(isset($_GET['order_state'])){
			if($_GET['order_state']!='all'){
				$where['order_state']=$_GET['order_state'];
			}
		}else{
			$_GET['order_state']='all';
		}
		
		if($_GET['start_time']){
			$start_time=strtotime($_GET['start_time']);
		}
		if($_GET['end_time']){
			$end_time=strtotime($_GET['end_time']);
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
		$menulist=D("Common")->getPageList('order',$where);
		$this->assign("list",$menulist);
//订单状态：0(已取消)10(默认):未付款;20:已付款;30:已发货;40:已收货; 
		$order_state=array('0'=>'已取消','10'=>'待付款','20'=>'已付款','30'=>'已完成');
		$this->order_state=$order_state;
		$this->display();
		//$this->display();
	}
	//编辑用户信息
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
			$menuwhere["order_id"] =  array("eq",$_GET["id"]);
			$data = M("order")->where($menuwhere)->find();
			
			$this->order_goods=M('order_goods')->where(array('order_id'=>$_GET["id"]))->select();
			
//订单状态：0(已取消)10(默认):未付款;20:已付款;30:已发货;40:已收货; 
		$order_state=array('0'=>'已取消','10'=>'待付款','20'=>'已付款','30'=>'已完成');
		$this->order_state=$order_state;
		$this->refund_state=array('0'=>'无退款','1'=>'部分退款','2'=>'全部退款');
			$title="订单信息";
			$this->assign("data",$data);
			
			$this->assign("msgtitle",$title);
			$this->display();
		}
	}

//编辑用户信息
	public function order_exchange(){
		if($_POST){
			 $o_where['order_id']=$_POST['id'];
			if(empty($_SESSION['topadmin'])){
			    $o_where['store_id']=session('afid');
			}
			$o_where['order_state']='20';
			$model_order = D('Order');
			$data_info = M("order")->where($o_where)->find();	
			if(empty($data_info)){
				$this->error("操作失败,该订单不存在");
			}
			//查询商家信息
			$shop_model=M('shop_detail');
			$shop_detail=$shop_model->where(array('shop_id'=>$data_info['store_id']))//
			->field('balance,income')->find();

			$model_order->startTrans();
			$shop_model->startTrans();
			$data = array();
            $data['order_state']	= '30';
	        $data['finnshed_time']	= time();
	        $order_res = $model_order->editOrder($data,$o_where);
	        //增加商家营业额   //扣除商家返利金额
	        if($data_info['discount_rebate']>0){
	        	//商铺总价
	        	$discount_rebate=$data_info['goods_amount']*$data_info['discount_rebate'];//返利金额
	        	$shop_data['balance']=$shop_detail['balance']-$discount_rebate;
	        	//余额小于多少 自动报警 
	        }
	        if($data['order_amount']>0){
	        	$shop_data['income']=$shop_detail['balance']+$data_info['order_amount'];//商户收人
	        }
	         $shop_res = $shop_model->where(array('shop_id'=>$data_info['store_id']))->save($shop_data);
            //记录商家日志 
			if($order_res!==false && $shop_res!==false){
				//记录订单日志
                $data = array();
                $data['order_id'] = $data_info['order_id'];
                $data['log_role'] = 'seller';
                $data['log_msg'] = '订单消费';
                $data['log_orderstate'] = '30';
                $insert = $model_order->addOrderLog($data);
                
                 //记录商家返利日志
		        if($data['discount_rebate']>0){
		        	$data=array();
		        	$data['shop_id'] = $order_info['store_id'];
					$data['order_id'] = $order_info['order_id'];
					$data['status'] = 2;//状态 1用户收益 2 商家返利
					$data['money'] = $discount_rebate;
					//消费类型 1消费者本身返点 2直接推荐人返点 3间接推荐人返点  
					//4 直接推荐人认证返点 5 间接推荐人认证返点 
					//10 商家返点
					$data['type'] = 10;
					$data['add_time'] = time();
					$data['des']='订单返利 订单号：'.$order_info['pay_sn'];
		        	M('rebate_record')->add($data);
		        }
		        //记录商家营业额日志 消费日志
		        if($data['order_amount']>0){
		        	$shop_data['income']=$shop_detail['balance']+$data['order_amount'];//商户收人
		        	$data=array();
		        	$data['shop_id'] = $order_info['store_id'];
					$data['order_id'] = $order_info['order_id'];
					$data['status'] = 1;//状态 1收入 2支出
					$data['money'] = $data['order_amount'];//商户收人
					//消费类型 1订单消费 2充值 3提现  4退款  
					$data['type'] = 1;
					$data['add_time'] = time();
					$data['des']='订单消费 订单号：'.$order_info['pay_sn'];
		        	M('shop_consume_record')->add($data);
		        }
		        //记录订单日志
                $order_log_data = array();
                $order_log_data['order_id'] = $order_info['id'];
                $order_log_data['log_role'] = 'buyer';
                $order_log_data['log_msg'] ='订单完成';//L('order_log_pay').'( 支付平台交易号 : '.$trade_no.' ) ';
                $order_log_data['log_orderstate'] = '30';
                D('Order')->addOrderLog($order_log_data);
                ///用户返利 返积分 认证等
                $this->set_member_exchange($order_info);///设置用户兑换
                $model_order->commit();
                $shop_model->commit();
				$this->success("操作成功",U("Admin/Order/order_exchange"));
			}else{
				 $model_order->rollback();
				  $shop_model->rollback();
				$this->error("操作失败");
			}
		}else{
			$exchange_code=$_GET['exchange_code'];
			if($exchange_code){
				if(empty($_SESSION['topadmin'])){
					 $o_where['store_id']=session('afid');
				}
                $o_where['exchange_code']=$exchange_code;
				$data = M("order")->where($o_where)->find();
				$this->order_goods=M('order_goods')->where(array('order_id'=>$data["id"]))->select();
			}
//订单状态：0(已取消)10(默认):未付款;20:已付款;30:已发货;40:已收货; 
		$order_state=array('0'=>'已取消','10'=>'待付款','20'=>'已付款','30'=>'已完成');
		$this->order_state=$order_state;
		$this->refund_state=array('0'=>'无退款','1'=>'部分退款','2'=>'全部退款');
			$title="订单信息";
			$this->assign("data",$data);
			
			$this->assign("msgtitle",$title);
			$this->display();
		}
	}
		 ///用户返利 返积分 认证等
public function set_member_exchange($order_info){
           //用户自己返利  用户积分增加  返利日志 积分增加日志
	  $member_info=M('member')->where(array('id'=>$order_info['buyer_id']))//
	  ->field('recommend_code,indirect_recommend_code,indirect2_recommend_code,member_status')->find();
	        //
	  $member_tuijian_data=array();//返利记录
	        if($data_info['discount_rebate']>0){
	        	$rebate_consumer=M('sys_param')->where(array('param_code'=>'rebate_consumer'))->getField('param_value');//返利比例；
	        	//总返利金额
	        	$discount_rebate=$data_info['goods_amount']*$data_info['discount_rebate'];//返利金额
	        	
	        	$discount_rebate_my=$discount_rebate*$rebate_consumer;//返利金额
	        	if($discount_rebate_my>0){
	        		$log=array();
		        	$log['type']=5;//消费类型 1订单消费 2充值 3提现   4退款 5 收益 6认证消费
		        	$log['order_id']=$order_info['order_id'];
		        	$log['des']='订单'.$order_info['pay_sn'].'收益：￥'.$discount_rebate_my;
		        	//加减用户 记录日志
		        	$this->set_member_balance($data_info['buyer_id'],1,$discount_rebate_my,$log);
		        	//返利记录
                    $tuijian_data=array();
                    $tuijian_data['member_id']=$data_info['buyer_id'];
                    $tuijian_data['order_id']=$data_info['order_id'];
                    $tuijian_data['type']=1;
                    $tuijian_data['money']=$discount_rebate_my;
                    $tuijian_data['des']='订单'.$order_info['pay_sn'].'收益：￥'.$discount_rebate_my;
                    $member_tuijian_data[]=$tuijian_data;
	        	}
	        	
	        }
	        //增加用户积分 1：1
	        $integral=round($data_info['order_amount']);
             if($integral>0){
	        	$log=array();
	        	$log['order_id']=$order_info['order_id'];
	        	$log['des']='订单'.$order_info['pay_sn'].'增加：'.$integral;
	        	//加减用户 记录日志
	        	$this->set_member_points($data_info['buyer_id'],1,$integral,$log);
	        }
            //直接人返利
	        if($member_info['recommend_code']){
               $member_info_1=M('member')->where(array('member_card'=>$member_info['recommend_code']))//
               ->field('id,member_status')->find();
               //认证返利
               if (!empty($member_info_1) && !empty($member_info_1['member_status'])) {
               	 $rebate_direct=M('sys_param')->where(array('param_code'=>'rebate_direct'))->getField('param_value');//返利比例；
               	 $discount_rebate_1=$discount_rebate*$rebate_direct;//返利金额
               	 if($discount_rebate_1>0){
               	 	$log=array();
		        	 $log['type']=5;//消费类型 1订单消费 2充值 3提现   4退款 5 收益 6认证消费
		        	 $log['order_id']=$order_info['order_id'];
		        	 $log['des']='直接人消费收益：￥'.$discount_rebate_1;
		        	 //加减用户 记录日志
		        	 $this->set_member_balance($member_info_1['id'],1,$discount_rebate_1,$log);
               	 	//返利记录
                    $tuijian_data=array();
                    $tuijian_data['member_id']=$member_info_1['id'];
                     $tuijian_data['order_id']=$order_info['order_id'];
                    $tuijian_data['type']=2;
                    $tuijian_data['money']=$discount_rebate_1;
                    $tuijian_data['des']='直接推荐人收益：￥'.$discount_rebate_1;
                    $member_tuijian_data[]=$tuijian_data;
               	 }
	        	 
               }
	        }
	         //间接人返利
	        if($member_info['indirect_recommend_code']){
               $member_info_2=M('member')->where(array('member_card'=>$member_info['indirect_recommend_code']))//
               ->field('id,member_status')->find();
               //认证返利
               if (!empty($member_info_2) && !empty($member_info_2['member_status'])) {
               	 $rebate_indirect=M('sys_param')->where(array('param_code'=>'rebate_indirect'))->getField('param_value');//返利比例；
               	 $discount_rebate_2=$discount_rebate*$rebate_indirect;//返利金额
	        	 if($discount_rebate_2){
                     $log=array();
		        	 $log['type']=5;//消费类型 1订单消费 2充值 3提现   4退款 5 收益 6认证消费
		        	 $log['order_id']=$order_info['order_id'];
		        	 $log['des']='间接推荐人消费收益：￥'.$discount_rebate_2;
		        	 //加减用户 记录日志
		        	 $this->set_member_balance($member_info_2['id'],1,$discount_rebate_2,$log);
	        	    //返利记录
                    $tuijian_data=array();
                    $tuijian_data['member_id']=$member_info_2['id'];
                     $tuijian_data['order_id']=$order_info['order_id'];
                    $tuijian_data['type']=3;
                    $tuijian_data['money']=$discount_rebate_2;
                    $tuijian_data['des']='间接推荐人收益：￥'.$discount_rebate_2;
                    $member_tuijian_data[]=$tuijian_data;
	        	 }
               }
	        }
	         //间接二级返利
	        if($member_info['indirect2_recommend_code']){
               $member_info_3=M('member')->where(array('member_card'=>$member_info['indirect2_recommend_code']))//
               ->field('id,member_status')->find();
               //认证返利
               if (!empty($member_info_3) && !empty($member_info_3['member_status'])) {
               	 $rebate_indirect_two=M('sys_param')->where(array('param_code'=>'rebate_indirect_two'))->getField('param_value');//返利比例；
               	 $discount_rebate_3=$discount_rebate*$rebate_indirect_two;//返利金额
	        	 if($discount_rebate_3>0){
	        	 	$log=array();
		        	 $log['type']=5;//消费类型 1订单消费 2充值 3提现   4退款 5 收益 6认证消费
		        	 $log['order_id']=$order_info['order_id'];
		        	 $log['des']='间接二级推荐人消费返利：￥'.$discount_rebate_3;
		        	 //加减用户 记录日志
		        	 $this->set_member_balance($member_info_3['id'],1,$discount_rebate_3,$log);
	        	  //返利记录
                    $tuijian_data=array();
                    $tuijian_data['member_id']=$member_info_3['id'];
                     $tuijian_data['order_id']=$order_info['order_id'];
                    $tuijian_data['type']=5;
                    $tuijian_data['money']=$discount_rebate_3;
                    $tuijian_data['des']='间接二级推荐人收益：￥'.$discount_rebate_3;
                    $member_tuijian_data[]=$tuijian_data;
	        	 }
               }
	        }
	        //未认证用户 消费大于 188 
	        if($order_info['order_amount']>=188 && empty($member_info['member_status'])){
	        	//认证
	        	M('member')->where(array('id'=>$order_info['buyer_id']))->save(array('member_status'=>1));//
	        	//+10积分
	        	$log=array();
	        	$log['order_id']=$order_info['order_id'];
	        	$log['des']='订单'.$order_info['pay_sn'].'增加：200';
	        	//加减用户 记录日志
	        	$this->set_member_points($data_info['buyer_id'],1,200,$log);
                 
	        	//直接推荐人+10积分[认证赠送]
	        	//认证返利
               if (!empty($member_info_1) && !empty($member_info_1['member_status'])) {
               	 $direct_auth_rebate=M('sys_param')->where(array('param_code'=>'direct_auth_rebate'))->getField('param_value');//返利比例；
               	//direct_auth_rebate返利金额
               	 if($direct_auth_rebate>0){
               	 	$log=array();
		        	 $log['type']=5;//消费类型 1订单消费 2充值 3提现   4退款 5 收益 6认证消费
		        	 $log['order_id']=$order_info['order_id'];
		        	 $log['des']='推荐人认证收益￥'.$direct_auth_rebate;
		        	 //加减用户 记录日志
		        	 $this->set_member_balance($member_info_1['id'],1,$direct_auth_rebate,$log);
		        	  //返利记录
                    $tuijian_data=array();
                    $tuijian_data['member_id']=$member_info_1['id'];
                    $tuijian_data['order_id']=$order_info['order_id'];
                    $tuijian_data['type']=4;
                    $tuijian_data['money']=$direct_auth_rebate;
                    $tuijian_data['des']='推荐人认证收益：￥'.$direct_auth_rebate;
                    $member_tuijian_data[]=$tuijian_data;
               	 }
	        	  $log=array();
	        	 $log['order_id']=$order_info['order_id'];
	        	 $log['des']='推荐人认证增加：10';
	        	//加减用户积分 记录日志 直接推荐人+10积分[认证赠送]
	        	 $this->set_member_points($member_info_1['id'],1,10,$log);
               }
           }

           if(!empty($member_tuijian_data)){
           	$rebate_record_model=M('rebate_record');
           	$time=time();
           	foreach ($member_tuijian_data as $key => $value) {
           		$value['add_time']=$time;
           		$value['status']=1;//状态 1用户收益 2 商家返利
           		$rebate_record_model->add($value);
           	}
           }

              
}
	//用户冻结
	public function member_freeze(){
		$id = $_GET["id"];
		$res = M("member")->where("id=".$_GET["id"])->find();
		if(empty($res)){
			$this->error("该会员不存在");
		}
		if($res['member_freeze']){
			$data['member_freeze']=0;
		}else{
			$data['member_freeze']=1;
		}
		$res = M("member")->where("id=".$id)->save($data);
		if($res!==false){
				$this->success("操作成功");
		}else{
				$this->error("操作失败");
		}
	}
	
	//会员收益记录
	public function rebate_record(){
        if($_GET['mobile']){
			$where['mem.mobile']=array('eq',$_GET['mobile']);
		}
		/*if($_GET['status']){
			$where['reb.status']=$_GET['status'];
		}*/
		if($_GET['type']){
			$where['reb.type']=$_GET['type'];
		}
		$where['reb.status']=1;//状态 1用户收益 2 商家返利
		//1消费者本身返点 2直接推荐人返点 3间接推荐人返点  
       //4 直接推荐人认证返点 5 间接推荐人认证返点 
        //10 商家返点
		$type_array=array(
			'1'=>'消费者本身返点',
			'2'=>'直接推荐人返点',
			'3'=>'间接推荐人返点',
			'4'=>'直接推荐人认证返点',
			'5'=>'间接推荐人认证返点'//,
			//'10'=>'商家返点'
			);
		$this->assign("type_array",$type_array);

        $pre=C('DB_PREFIX');//表前缀
        $count=M()->table($pre.'rebate_record reb')//
        ->join($pre.'member mem on reb.member_id=mem.id')//
        ->where($where)->count();
        $page=D("Common")->getPage($count);//分页

        $field="reb.*,mem.member_name,mem.mobile";
        
    	$list =M()->table($pre.'rebate_record reb')
    	 ->join($pre.'member mem on reb.member_id=mem.id')//
    	 ->where($where)->field($field)//
    	->order('reb.add_time desc')->limit($page['start'].','.$page['pagesize'])->select();
	
		$menulist['list']=$list;
		$menulist['page']=$page['page'];
		$this->assign("list",$menulist);
		$this->display();
	}

	//会员消费记录
	public function member_consume_record(){
        if($_GET['mobile']){
			$where['mem.mobile']=array('eq',$_GET['mobile']);
		}
		if($_GET['status']){
			$where['reb.status']=$_GET['status'];
		}
		if($_GET['type']){
			$where['reb.type']=$_GET['type'];
		}
		//1订单消费 2充值 3提现   4退款 5 收益 6认证消费  
		$type_array=array(
			'1'=>'订单消费',
			'2'=>'充值',
			'3'=>'提现',
			'4'=>'退款',
			'5'=>'收益',
			'6'=>'认证消费'
			);
		$this->assign("type_array",$type_array);

        $pre=C('DB_PREFIX');//表前缀
        $count=M()->table($pre.'member_consume_record reb')//
        ->join($pre.'member mem on reb.member_id=mem.id')//
        ->where($where)->count();
        $page=D("Common")->getPage($count);//分页

        $field="reb.*,mem.member_name,mem.mobile";
        
    	$list =M()->table($pre.'member_consume_record reb')
    	 ->join($pre.'member mem on reb.member_id=mem.id')//
    	 ->where($where)->field($field)//
    	->order('reb.add_time desc')->limit($page['start'].','.$page['pagesize'])->select();
	
		$menulist['list']=$list;
		$menulist['page']=$page['page'];
		$this->assign("list",$menulist);
		$this->display();
	}
  
	//会员充值记录
	public function member_recharge(){
        if($_GET['mobile']){
			$where['mem.mobile']=array('eq',$_GET['mobile']);
		}
		if($_GET['status']){
			$where['reb.status']=$_GET['status'];
		}
		$where['reb.type']=1;//类型 1会员充值 2商户充值
		//0已退款 1 待支付 2已支付 3支付失败
		$type_array=array(
			'1'=>'待支付',
			'2'=>'已支付',
			'3'=>'支付失败'
			);
		$this->assign("type_array",$type_array);

        $pre=C('DB_PREFIX');//表前缀
        $count=M()->table($pre.'member_recharge reb')//
        ->join($pre.'member mem on reb.member_id=mem.id')//
        ->where($where)->count();
        $page=D("Common")->getPage($count);//分页

        $field="reb.*,mem.member_name,mem.mobile";
        
    	$list =M()->table($pre.'member_recharge reb')
    	 ->join($pre.'member mem on reb.member_id=mem.id')//
    	 ->where($where)->field($field)//
    	->order('reb.add_time desc')->limit($page['start'].','.$page['pagesize'])->select();
	
		$menulist['list']=$list;
		$menulist['page']=$page['page'];
		$this->assign("list",$menulist);
		$this->display();
	}

	//会员推荐查询
	public function member_tuijian(){
        if($_GET['mobile']){
			$where['mobile']=array('eq',$_GET['mobile']);
		}
	    $data=M('member')->where($where)->find();
        $this->data=$data;
	    if($data){
	    	if($_GET['recommend']){//间接推荐人
                 $tjwhere['indirect_recommend_code']=$data['my_recommend_code'];
	    	}else{
	    		 $tjwhere['recommend_code']=$data['my_recommend_code'];
	    	}
	    	
	        $count=M('member')->where($where)->count();
	        $page=D("Common")->getPage($count);//分页    
	    	$list =M('member')->where($where)
	    	->order('add_time desc')->limit($page['start'].','.$page['pagesize'])->select();
			$menulist['list']=$list;
			$menulist['page']=$page['page'];
			$this->menulist=$menulist;

	    }
		
		$this->display();
	}


	
}
?>