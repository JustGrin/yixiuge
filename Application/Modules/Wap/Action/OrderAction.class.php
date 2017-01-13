<?php
// 订单
class OrderAction extends UserAction {
	//魔术方法
public function __construct(){
		parent::__construct();
		
	}
	    //订单列表
    public function index(){
         $where['buyer_id']=$this->uid;
         if(isset($_REQUEST['order_state'])){
           if($_REQUEST['order_state']!='all'){
             $where['order_state']=$_REQUEST['order_state'];
           }
         }
         $p=$_REQUEST['p'];
         $pagesize=10;
         $p=!empty($p)?$p:1;
         $start=($p-1)*$pagesize;    
         $field="order_id,pay_sn,store_id,store_name,goods_amount,order_amount,order_state,add_time";
         $list=M("order")->where($where)//
        ->field($field)->limit($start,$pagesize)->order('order_id desc')->select();
         $order_state=array('0'=>'已取消','10'=>'待付款','20'=>'已付款','30'=>'已完成'); 
        foreach ($list as $key => $value) {
          $list[$key]['order_state_name']=$order_state[$value['order_state']];
          $list[$key]['add_time']=date("Y/m/d",$value['add_time']);
        }
        if(IS_AJAX){
            echo json_encode($list);die;
        }
        $this->list=$list;
        $this->count=M("order")->where($where)->count();
        $where['order_state']='10';
        $this->count10=M("order")->where($where)->count();
         $where['order_state']='20';
        $this->count20=M("order")->where($where)->count();
         $where['order_state']='30';
        $this->count30=M("order")->where($where)->count();
    	  $this->webtitle="wqp米有项目框架";
		    $this->display();
    }
    //订单信息
  public function order_detail(){
      $menuwhere["order_id"] =  array("eq",$_GET["id"]);
      $data = M("order")->where($menuwhere)->find();
      
      $this->order_goods=M('order_goods')->where(array('order_id'=>$_GET["id"]))->select();
      
//订单状态：0(已取消)10(默认):未付款;20:已付款;30:已发货;40:已收货; 
     $order_state=array('0'=>'已取消','10'=>'待付款','20'=>'已付款','30'=>'已完成');
     $this->order_state=$order_state;
     $this->refund_state=array('0'=>'无退款','1'=>'部分退款','2'=>'全部退款');
     $title="订单信息";
     $this->assign("data",$data);
      
     $this->display();
  }
  //订单支付信息
  public function order_pay(){
      $menuwhere["pay_sn"] =  array("eq",$_GET["pay_sn"]);
      $data = M("order")->where($menuwhere)->find();
      
      $this->order_goods=M('order_goods')->where(array('order_id'=>$data["order_id"]))->select();
      
//订单状态：0(已取消)10(默认):未付款;20:已付款;30:已发货;40:已收货; 
     $order_state=array('0'=>'已取消','10'=>'待付款','20'=>'已付款','30'=>'已完成');
     $this->order_state=$order_state;
     $this->refund_state=array('0'=>'无退款','1'=>'部分退款','2'=>'全部退款');
     $title="订单信息";
     $this->assign("data",$data);
      
     $this->display();
  }
   ///服务 订单查看
  public function order_add(){
      $good_id=$_GET['good_id'];
      $shop_id=$_GET['shop_id'];
      $list=$this->get_goods_settlement($good_id,$shop_id);
      if(empty($list['status'])){
        $this->error($list['error']);
      }
      $this->list=$list['list'];

      $this->shop_id=$shop_id;
      $this->good_id=$good_id;

      $this->display();
  }
    ///服务 订单提交
  public function set_order(){
      $return_data['status']=0;
      $good_id=$_REQUEST['good_id'];
      $shop_id=$_REQUEST['shop_id'];
      $list=$this->get_goods_settlement($good_id,$shop_id);
      if(empty($list['status'])){
        $return_data['error']=$list['error'];
        echo json_encode($return_data);
      }
      $goods=$list['list'];
      $shop=$list['shop'];
      $member=$this->getMemberInfo();
      $uid=$this->uid;
      $time=time();
      $pay_sn=makeOrderSn($uid);
      $order_data['pay_sn']=$pay_sn;
      $order_data['store_id']=$shop['shop_id'];
      $order_data['store_name']=$shop['shop_name'];
      $order_data['buyer_id']=$uid;
      $order_data['buyer_name']=$member['member_name'];
      $order_data['buyer_mobile']=$member['mobile'];
      $order_data['goods_amount']=$goods['tolal'];//商品总价格
      $order_data['order_amount']=$goods['need_pay'];//订单总价格(折后价)
      $order_data['discount_consume']=$shop['discount_consume'];//用户消费时的折扣
      $order_data['discount_rebate']=$shop['discount_rebate'];//用户消费时的返利折扣
      $order_data['add_time']=$time;
      
       $order_pay_data['buyer_id']=$uid;
       $order_pay_data['pay_sn']=$pay_sn;


      $order_model=M('order');
      $order_goods_model=M('order_goods'); 
      $order_pay_model=M('order_pay'); 

      $order_model->startTrans();//开起事务
      $order_goods_model->startTrans();//开起事务
       $order_pay_model->startTrans();//开起事务

      $add_order=$order_model->add($order_data);
      if($add_order===false){
         $return_data['error']="订单生成失败，请稍后再试.";
           $order_model->rollback();//回滚事务
           $order_goods_model->rollback();//回滚事务
           $order_pay_model->rollback();//回滚事务
        echo json_encode($return_data);
      }
     
      $add_order_pay=$order_pay_model->add($order_pay_data);

      $good_list=$goods['goods_list'];
      foreach ($good_list as $key => $value) {
          $order_good_data['order_id']=$add_order;
          $order_good_data['goods_id']=$value['id'];
          $order_good_data['goods_name']=$value['goods_name'];//商品名
          $order_good_data['goods_price']=$value['goods_price'];//商品价格
          $order_good_data['goods_num']=$value['num'];//商品数量
          $order_good_data['goods_image']=$value['goods_image'];//
          $order_good_data['goods_pay_price']=$value['all_pay_price'];//商品实际成交价
          $order_good_data['store_id']=$value['shop_id'];//商家id
          $order_good_data['buyer_id']=$uid;//买家ID

          $add_order_goods[]=$order_goods_model->add($order_good_data);

      }
     $add_order_goods=array_unique($add_order_goods);//去重
     //in_array如果设置该参数为 true，则检查搜索的数据与数组的值的类型是否相同。
      if($add_order!==false && $add_order_pay!==false && in_array(false, $add_order_goods,true)===false){

           $order_model->commit();//提交事务
           $order_goods_model->commit();//提交事务
            $order_pay_model->commit();//提交事务
             //记录订单日志
                $data = array();
                $data['order_id'] = $add_order;
                $data['log_role'] = 'buyer';
                $data['log_msg'] ='订单生成';//L('order_log_pay').'( 支付平台交易号 : '.$trade_no.' ) ';
                $data['log_orderstate'] = '10';
                D('Order')->addOrderLog($data);
           $return_data['pay_sn']=$pay_sn;
      }else{
            $order_model->rollback();//回滚事务
            $order_goods_model->rollback();//回滚事务
             $order_pay_model->rollback();//回滚事务
            $return_data['error']="订单生成失败，请稍后再试.";
      }
      $return_data['status']=1;
      echo json_encode($return_data);
  }

}