<?php
// 上次订单
class GoodsrefundAction extends UserAction {
	//魔术方法
    public function __construct(){
        parent::__construct();
    }
    //订单列表
    public function index()
    {
        $menuwhere["rec_id"] =  array("eq",$_GET["id"]);
        $data=M("g_order_goods")->where($menuwhere)->find();
        $this->data=$data;
        $where=array();
        $where['order_goods_id']=$data['rec_id'];
        $refund= M("g_order_refund")->where($where)->find();
		$chat_where['type'] =2;
		$chat_where['refund_id'] =$refund['ref_id'];
		M('g_order_refund_chat')->where($chat_where)->setField('is_read','1');
        if($refund){
            if($refund['refund_img']){
                $refund['refund_img']=explode(',', $refund['refund_img']);
            }
            $list=M('g_order_refund_chat')->where('refund_id='.$refund['ref_id'])->select();
            foreach ($list as $k=>$v){
                if($v['img']){
                    $list[$k]['chat_img'] = explode(',', $v['img']);
                }
            }
            $where_chat['refund_id']=$refund['ref_id'];
            $last_chat=M('g_order_refund_chat')->where($where_chat)->order('id desc')->find();
            $this->refund=$this->get_refund_status($refund);
            $this->assign('last_chat',$last_chat);
            $this->list=$list;
          //显示快递公司
            $where_express['enabled'] = 1;
            $order_express = "shipping_order desc";
            $express = M("g_shipping")->where($where_express)->order($order_express)->select();
            $this->assign("express",$express);
            $this->display('refund_detail');
            die;
		  }
      $or_where['order_id']=$data['order_id'];
      $or_where['user_del']=0;
      $or_where['user_id']=$this->uid;
      $order = M("g_order_info")->where($or_where)->find();
      if($order){
       $order_show=$this->get_order_status($order);
       $order['order_state']=$order_show['code'];
       $order['order_state_name']=$order_show['name'];
      }
      $this->order=$order;
      $this->display();
    }
   
   //新增退货
   public function refund(){
         $return_data['status']=0;
         $order_id=$_POST['order_id'];
         $order_goods_id=$_POST['order_goods_id'];
         //$refund_type=$_POST['refund_type'];
	  	 $refund_type = 2;// 0换货 1 退款 2 未选择
         $refund_img=$_POST['refund_img']?implode(',', $_POST['refund_img']):'';
          $refund_remark=replace_special($_POST['refund_remark']);
         $refund_name=$_POST['refund_name'];
         $refund_tel=$_POST['refund_tel'];
         if(empty($refund_remark)){
            $return_data['error']="请填写问题描述";
            echo json_encode($return_data);die;
        }
        if(empty($refund_name)){
            $return_data['error']="请填写联系人";
            echo json_encode($return_data);die;
        }
        if(empty($refund_tel)){
            $return_data['error']="请填写联系电话";
            echo json_encode($return_data);die;
        }
        $where=array();
        $where['order_goods_id']=$order_goods_id;
        $refund= M("g_order_refund")->where($where)->find();
        if(!empty($refund)) {
            $return_data['status']=1;
            echo json_encode($return_data);die;
        }
        $where=array();
        $where["rec_id"] =  array("eq",$order_goods_id);
        $data=M("g_order_goods")->where($where)->find();
        if(empty($data)) {
            $return_data['error']="该订单产品不存在";
            echo json_encode($return_data);die;
        }

        $or_where=array();
        $or_where['order_id']=$data['order_id'];
        $or_where['user_del']=0;
        $or_where['user_id']=$this->uid;
        $order = M("g_order_info")->where($or_where)->find();
         if(empty($order)) {
            $return_data['error']="该订单不存在";
            echo json_encode($return_data);die;
        }
         $order_show=$this->get_order_status($order);
        if($order_show['code']!='delivered'){//商家已发货可退 确认收货之后不可退货
          $return_data['error']="订单不可退货";
            echo json_encode($return_data);die;
        }
        $add_data=array();
        $add_data['order_id']=$order_id;
        $add_data['order_goods_id']=$order_goods_id;
        $add_data['refund_type']=$refund_type;
        $add_data['refund_img']=$refund_img;
        $add_data['refund_remark']=$refund_remark;
        $add_data['refund_tel']=$refund_tel;
        $add_data['refund_name']=$refund_name;
        $add_data['member_id']=$this->uid;
        $add_data['return_goods']= 0;//默认无需退货直接售后
        $add_data['add_time']=time();
       $add_data['refund_sn'] = makeRefundSn($this->uid);
        $res=M('g_order_refund')->add($add_data);
        if($res!==false){
           $log=array();
           $log['order_id']=$order_id;
           $log['refund_id']=$res;
           $log['log_msg']='申请售后';
           $log['log_time']=time();
           $log['log_role']="买家";
           $log['log_orderstate']='0';
			//对话数据
			$chat=array();
            $chat['refund_id'] = $res;
			$chat['member_id'] = $this->uid;
			$chat['pid'] = 0;
			$chat['content'] = $refund_remark;
			$chat['img'] = $refund_img;
			$chat['type'] = 1 ;
			$chat['log_orderstate'] = '0';
			$chat['add_time'] = $log['log_time'];
            //改变订单状态为售后中
           M('g_order_info') ->where($or_where)->setField('order_status','5');

           M('g_order_refund_chat') ->add($chat);
           M('g_order_refund_log')->add($log);
           $return_data['status']=1;
           echo json_encode($return_data);die;
        }else{
          $return_data['error']="提交失败";
           echo json_encode($return_data);die;
        }
        
   }
   //退货 返修 发货
   public function refund_status(){
         $return_data['status']=0;
         $ref_id=$_POST['ref_id'];
         $invoice_express= M('g_shipping')->where('shipping_id='.$_POST['express_code'])->find();
         $invoice_no_user=$_POST['invoice_no_user'];
          $save_data=array();
            if(empty($_POST['express_code'])){
            $return_data['error']="请填写快递名称";
            echo json_encode($return_data);die;
            }
            if(empty($invoice_no_user)){
                $return_data['error']="请填写发货单号";
                echo json_encode($return_data);die;
            }
             $save_data['invoice_no_name']=$invoice_express['shipping_name'];
             $save_data['invoice_no_code']=$invoice_express['shipping_code'];
             $save_data['invoice_no_user']=$invoice_no_user;
             $log_msg='用户已发货,发货单:'.$invoice_express['shipping_name'].'-'.$invoice_no_user;
        $where=array();
        $where['ref_id']=$ref_id;
        $where['member_id']=$this->uid;
        $refund= M("g_order_refund")->where($where)->find();
        if($refund['refund_status']!=1) {
            $return_data['error']="退货单状态错误";
            $return_data['dd']=$refund;
            echo json_encode($return_data);die;
        }
        $save_data['refund_status']=2;
        $save_data['user_send_time']=time();
        $where=array();
        $where['ref_id']=$ref_id;
        $where['member_id']=$this->uid;
        $where['refund_status']=1;
        $res=M('g_order_refund')->where($where)->save($save_data);
        if($res!==false){
            $log=array();
           $log['order_id']=$refund['order_id'];
           $log['refund_id']=$ref_id;
           $log['log_msg']=$log_msg;
           $log['log_time']=time();
           $log['log_role']="买家";
           $log['log_orderstate']='2'; 
           M('g_order_refund_log')->add($log);
			
           $return_data['status']=1;
           echo json_encode($return_data);die;
        }else{
          $return_data['error']="提交失败";
           echo json_encode($return_data);die;
        }
        
   }
    //退货 返修 收货
   public function refund_status1(){
         $return_data['status']=0;
         $ref_id=$_POST['ref_id'];
        $where=array();
        $where['ref_id']=$ref_id;
        $where['member_id']=$this->uid;
        $refund= M("g_order_refund")->where($where)->find();
        if($refund['refund_status']!=4) {
            $return_data['error']="补货单状态错误";
            echo json_encode($return_data);die;
        }
        $save_data=array();
        $save_data['refund_status']=5;
        $save_data['receipt_time']=time();
        $where=array();
        $where['ref_id']=$ref_id;
        $where['member_id']=$this->uid;
        $where['refund_status']=4;
        $res=M('g_order_refund')->where($where)->save($save_data);
        if($res!==false){
            $log=array();
           $log['order_id']=$refund['order_id'];
           $log['refund_id']=$ref_id;
           $log['log_msg']='用户已收货';
           $log['log_time']=time();
           $log['log_role']="买家";
           $log['log_orderstate']='5'; 
           M('g_order_refund_log')->add($log);
            //该订单未完成售后数
			$where_r_c=array();
			$where_r_c['order_id']=$refund['order_id'];
			$where_r_c['refund_status']=array('neq','5');
			$where_r_c['member_id']=$this->uid;
			$re_unfinish_count=M('g_order_refund')->where($where_r_c)->count();
			//若该订单没有未完成
			if($re_unfinish_count == 0){
				$where_x='order_id='.$refund['order_id'];//该订单id
				//订单商品数
				$order_goods_count=M('g_order_goods')->where($where_x)->count();
				//退单数
				$re_count =M('g_order_refund')->where($where_x)->count();
				M('g_order_info')->where($where_x)->setField('order_status','1');//将订单状态改为 待收货
				if($order_goods_count>$re_count){//如果订单商品比退单多则还有商品未售后，应走原流程,并出发七天确认收货
				}else{//否则 直接触发确认收货
					$this->order_delivered($refund['order_id']);
				}
			}
           $return_data['status']=1;
           echo json_encode($return_data);die;
        }else{
          $return_data['error']="提交失败";
           echo json_encode($return_data);die;
        }
        
   }
 //退货列表
    public function refund_list()
    {
        $where['ref.member_id'] = $this->uid;
   /*     0 待确认 1已确认 2 用户已发货 
换货单 3商家已收货（待发货）  4商家已发货 5用户已收货(已完成) 
退货单 3商家已收货（待退款）  5已完成
10商户拒绝
*/
       // $where['ref.refund_status'] = array('in','0,1,2,3,4');//完成的不显示
        //$where['user_del']=0;
        $pre=C('DB_PREFIX');//表前缀
        $p = $_REQUEST['p'];
        $pagesize = 10;
        $p = !empty($p) ? $p : 1;
        $start = ($p - 1) * $pagesize;
        $field = "ref.*,ord.order_sn,good.goods_name,good.goods_attr,good.goods_image,good.goods_price,good.goods_number";
        $list = M()->table($pre.'g_order_refund ref')//
        ->join($pre.'g_order_info ord on ord.order_id=ref.order_id')//
        ->join($pre.'g_order_goods good on good.rec_id=ref.order_goods_id')//
        ->where($where)//
        ->field($field)->limit($start, $pagesize)->order('ref.add_time desc')->select();
        foreach ($list as $key => $value) {
            $list[$key]=$this->get_refund_status($value);    
            $list[$key]['add_time'] = date("Y/m/d", $value['add_time']);
            $chat_where['type'] =2;
            $chat_where['is_read'] = 0;
            $chat_where['refund_id'] =$value['ref_id'];
            $list[$key]['un_read']=M('g_order_refund_chat')->where($chat_where)->count();
			$list[$key]['goods_image']=thumbs_auto($value['goods_image'], '200', '200');
        }

        if (IS_AJAX) {
            echo json_encode($list);
            die;
        }
        $this->list = $list;
        $this->webtitle = "FG峰购";
        $this->display();
    }


	public function refund_chat(){
		$add_chat['pid']=$_POST['chat_id'];
		$add_chat['refund_id'] = $_POST['ref_id'];
		$add_chat['content'] =replace_special($_POST['content']);

		$refund_img=$_POST['chat_img']?implode(',', $_POST['chat_img']):'';
		$add_chat['img'] =$refund_img;
		$add_chat['add_time'] =time();
		$add_chat['type'] = 1 ;//2，商家 1，买家
		$add_chat['member_id'] = $this->uid;
		$add_chat['log_orderstate'] = M('g_order_refund')->where('ref_id='.$_POST['ref_id'])->getField('refund_status');
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
	public function order_delivered($order_id)
	{
		$return_data['status'] = 0;
		$o_where["order_id"] = array("eq", $order_id);
		$o_where['user_id'] = $this->uid;
		$data_info = M("g_order_info")->where($o_where)->find();
		if (empty($data_info)) {
			$return_data['error'] = "该订单不存在";
			echo json_encode($return_data);
			die;
		}
		$order_show = $this->get_order_status($data_info);

		if ($order_show['code'] != 'delivered') {//已发货
			$return_data['error'] = $order_show['code'];
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
			echo json_encode($return_data);
			die;
		} else {
			$model_order->rollback();
			$return_data['error'] = "操作失败";
			echo json_encode($return_data);
			die;
		}
	}
	public function refund_express()
	{
		$invoice_code=$_GET['invoice_code'];
		$invoice_no=$_GET['invoice_no'];
		$express_data = $this->express_query('0',$invoice_no ,$invoice_code);
		echo json_encode($express_data);die;
	}
}