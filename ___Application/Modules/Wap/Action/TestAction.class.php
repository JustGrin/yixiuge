<?php
//  登录注册 
class TestAction extends CommonAction {
	//魔术方法
public function __construct(){
		parent::__construct();
		
	}
	
    public function index(){
        Log::write("riz日志记录".date("Y-m-d  H:i:s"));
    }
    ////成为会员时间
    public function get_time(){
        $where=array('member_vip_type'=>1);
        $model_m=M('member');
        $model_o=M('g_order_info');
        $list=$model_m->field('id,member_card')->where($where)->select();
        foreach($list as $key=>$val){
            $where=array(
                'user_id'=>$val['id'],
                'pay_status'=>2,// 支付状态；0，未付款；1，付款中 ；2，已付款
                'order_status'=>array('in','0,1'),// 订单状态。0，未确认；1，已确 认；2，已取消；3，无效；4，退货；
            );

            $paytime=$model_o->where($where)->field('pay_time')->order('pay_time asc')->find();
            if($paytime){
                $paytime=$paytime['pay_time'];
                $val['pay_time']=date('Y-m-d',$paytime);
                $val['save_pay_time']=$model_m->where(array('id'=>$val['id']))->save(array('vip_time'=>$paytime));
            }else{
                $val['pay_time']=$paytime;
            }
            var_dump($val);
            //var_dump($paytime);
        }
    }

   public function test(){
    
       var_dump(S('test_s'));
       var_dump(S('access_token'));
       
    }
   public function  productBuy(){
       $order_id = $_GET['order_id'];
       $member_id = $_GET['member_id'];
   }

     
}