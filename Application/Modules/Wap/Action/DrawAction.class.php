<?php
// 抽奖
class DrawAction extends UserAction {
	//魔术方法
public function __construct(){
		parent::__construct();
		
	}
  public function index(){
      $this->data=$this->get_today_draw();
	    $this->display();
  }
  public function check_draw(){
      $return_data['status']=0;
      //没有绑定手机 不抽奖
      if(empty($_SESSION['member']['mobile'])){
        echo json_encode($return_data);die;
      } 
      $switch_draw=M('sys_param')->where(array('param_code'=>'switch_draw'))->getField('param_value');
      if(empty($switch_draw)){
        echo json_encode($return_data);die;
      } 
      $draw_data=$this->get_today_draw();//今日 抽奖记录
      if(!empty($draw_data)){
        echo json_encode($return_data);die;
      }    
      //今天第一次显示 今天已经抽了不显示
      $draw_count=$this->set_cookie_show('draw_member_home',$draw_data);
      if(!empty($draw_count)){
         $return_data['status']=2;
         //未抽奖
        echo json_encode($return_data);die;
      } 
      $return_data['status']=1;
      echo json_encode($return_data);die;
  }
    //获取 奖池奖品get_rand
  public function draw_list(){
    $p=$_REQUEST['p'];
     $pagesize=10;
     $p=!empty($p)?$p:1;
     $start=($p-1)*$pagesize;
     $where['member_id']=$this->uid; 
     $list=M('draw_log')->where($where)//
           ->limit($start,$pagesize)//
           ->order('add_time desc')->select();
     if($list){
      $draw_address_model=M('draw_address');
       foreach ($list as $key => $value) {
         $value['add_time']=date("Y/m/d",$value['add_time']);
         $value['draw_address']=0;
         if($value['draw_type']==5){
          $value['draw_address']=$draw_address_model->where(array('draw_log_id'=>$value['id']))->count();
         }
         $list[$key]=$this->get_draw_msg($value);
       }
     }
     if(IS_AJAX){
      echo json_encode($list);die;
     }
      $this->now_address=M('g_user_draw_address')->where(array('user_id'=>$this->uid))->find();
      $this->count=M('draw_log')->where($where)->count();
      $this->list=$list;
      $this->display();
  }
   //设置收货地址
  public function draw_address(){
    if (IS_AJAX) {
      $address_id=$_REQUEST['address_id'];
      $return_data['status']=1;
      $return_url=U('wap/draw/draw_list');
      $return_data['return_url']=$return_url;
      if(empty($address_id)){
        echo json_encode($return_data);die;
      }
      $address=M('g_user_address')->where(array('address_id'=>$address_id,'user_id'=>$this->uid))->find();
      if(empty($address)){
        $return_data['status']=0;
         $return_data['error']="该地址不存在";
         echo json_encode($return_data);die;
      }
      $add_data['address_name']=$address['address_name'];
      $add_data['user_id']=$address['user_id'];
      $add_data['consignee']=$address['consignee'];
      $add_data['email']=$address['email'];
      $add_data['country']=$address['country'];
      $add_data['province']=$address['province'];
      $add_data['city']=$address['city'];
      $add_data['area']=$address['area'];
      $add_data['address']=$address['address'];
      $add_data['zipcode']=$address['zipcode'];
      $add_data['tel']=$address['tel'];
      $add_data['mobile']=$address['mobile'];
      $add_data['sign_building']=$address['sign_building'];
      $add_data['best_time']=$address['best_time'];
      $count=M('g_user_draw_address')->where(array('user_id'=>$this->uid))->find();
      if($count){
        $res=M('g_user_draw_address')->where(array('id'=>$count['id']))->save($add_data);
      }else{
        $res=M('g_user_draw_address')->add($add_data);
      }
      if($res!==false){
        echo json_encode($return_data);die;
      }else{
         $return_data['status']=0;
         $return_data['error']="操作失败";
         echo json_encode($return_data);die;
      }
     }
     $this->now_address=M('g_user_draw_address')->where(array('user_id'=>$this->uid))->find();
     $this->address=M('g_user_address')->where(array('user_id'=>$this->uid))->order('is_default desc')->select();
     
     $this->display();
  }
   //设置收货地址
  public function set_draw_address(){
    if (IS_AJAX) {
      $address_id=$_REQUEST['address_id'];
      $draw_log_id=$_REQUEST['draw_log_id'];
      $return_data['status']=1;
      $return_url=U('wap/draw/draw_list');
      $return_data['return_url']=$return_url;
      if(empty($address_id)){
        echo json_encode($return_data);die;
      }
      $draw_address_model=M('draw_address');
       $draw_address=$draw_address_model->where(array('member_id'=>$this->uid,'draw_log_id'=>$draw_log_id))->find();
     if($draw_address){//地址已设置
        echo json_encode($return_data);die;
     }
      $address=M('g_user_address')->where(array('address_id'=>$address_id,'user_id'=>$this->uid))->find();
      if(empty($address)){
        $return_data['status']=0;
         $return_data['error']="该地址不存在";
         echo json_encode($return_data);die;
      }
      $g_user_draw_address=M('g_user_draw_address')->where(array('user_id'=>$this->uid))->count();
      if(empty($g_user_draw_address)){///添加默认收货地址
        $add_data=array();
         $add_data['address_name']=$address['address_name'];
        $add_data['user_id']=$address['user_id'];
        $add_data['consignee']=$address['consignee'];
        $add_data['email']=$address['email'];
        $add_data['country']=$address['country'];
        $add_data['province']=$address['province'];
        $add_data['city']=$address['city'];
        $add_data['area']=$address['area'];
        $add_data['address']=$address['address'];
        $add_data['zipcode']=$address['zipcode'];
        $add_data['tel']=$address['tel'];
        $add_data['mobile']=$address['mobile'];
        $add_data['sign_building']=$address['sign_building'];
        $add_data['best_time']=$address['best_time'];
         M('g_user_draw_address')->add($add_data);
      }
      $add_data=array();
        $add_data['draw_log_id']=$draw_log_id;
      $add_data['address_name']=$address['address_name'];
      $add_data['member_id']=$address['user_id'];
      $add_data['consignee']=$address['consignee'];
      $add_data['email']=$address['email'];
      $add_data['address']=$address['address'];
      $add_data['zipcode']=$address['zipcode'];
      $add_data['tel']=$address['tel'];
      $add_data['mobile']=$address['mobile'];
      $add_data['sign_building']=$address['sign_building'];
      $add_data['best_time']=$address['best_time'];

      $res=$draw_address_model->add($add_data);
      
      if($res!==false){
        echo json_encode($return_data);die;
      }else{
         $return_data['status']=0;
         $return_data['error']="操作失败";
         echo json_encode($return_data);die;
      }
     }
     $now_address=M('draw_address')->where(array('user_id'=>$this->uid,'draw_log_id'=>$_GET['draw_log_id']))->find();
     $this->now_address=$now_address;
     if(empty($now_address)){
      $this->address=M('g_user_address')->where(array('user_id'=>$this->uid))->order('is_default desc')->select();
     }
     $this->display();
  }
   
    //获取 奖池奖品get_rand
  public function get_draw_list(){
     $where['draw_status']=1;
     $where['is_shenghe']=1;
     $list=M('draw_progressive')->where($where)->select();
     $return_data=array();
     if($list){
       foreach ($list as $key => $value) {
         $return_data[$value['id']]=$value['draw_chances_value'];
       }
     }
     return $return_data;
  }
   //验证抽奖权限
  public function cheack_draw(){
      $uid=$this->uid;
      $todaytime=strtotime(date('Y-m-d',time()));
      $tmdaytime=strtotime(date('Y-m-d',strtotime("+1 day")));
      $where['member_id']=$uid;
      $where['add_time']=array('between',array($todaytime,$tmdaytime));
      $data=M('draw_log')->where($where)->count();
     return $data;
  }
 
  //查询并设置抽奖记录
  public function set_draw_msg($draw_id=null){
      if(empty($draw_id)){
        return array();
      }
      $where=array('id'=>$draw_id);
     $draw_data=M('draw_progressive')->where($where)->find();
      $uid=$this->uid;
     if($draw_data){
      if(in_array($draw_data['draw_type'], array('1','2','3','4','5'))){
         $log_data=array();
         $log_data['member_id']=$uid;
         $log_data['draw_id']=$draw_data['id'];
         $log_data['draw_type']=$draw_data['draw_type'];
         $log_data['draw_value']=$draw_data['draw_value'];
         $log_data['add_time']=time();
         $res=M('draw_log')->add($log_data);//log_status
         if($res===false){
          return array();
         }
      }
       $is_huafei=0;//话费
      //1积分 2金币 3话费4折扣5商品
       switch ($draw_data['draw_type']) {
         case '1':
           $draw_data['draw_type_name']='积分';
             $points=$draw_data['draw_value'];
           if($points>0){
              $log_data=array();
              $log_data['des']="每日抽奖中奖".$points;
              $points=$this->set_member_points($uid,1,$points,$log_data);
             if($points){
              M('draw_log')->where(array('id'=>$res))->save(array('log_status'=>5));//log_status
             }
           }
           break;
         case '2':
           $draw_data['draw_type_name']='金币';
           $give_money=$draw_data['draw_value'];
           if($give_money>0){
              $log_data=array();
              $log_data['des']="每日抽奖中奖￥".$give_money;
              $log_data['type']=5;////消费类型 1订单消费 2充值 3提现   4退款 5 收益 6认证消费
              //$status 1 收入 2支持  
              $give=$this->set_member_balance($uid,1,$give_money,$log_data,$give_money);
             if($give){
              M('draw_log')->where(array('id'=>$res))->save(array('log_status'=>5));//log_status
             }
           }
           break;
         case '3':
           $draw_data['draw_type_name']='话费';
            $recharge_data=array();
            $recharge_data['member_id']=$uid;
            $recharge_data['draw_log_id']=$res;
            $recharge_data['mobile']=$this->mobile;
            $recharge_data['draw_value']=$draw_data['draw_value'];
            $recharge_data['status']=1;
            $recharge_data['add_time']=time();
            M('draw_recharge')->add($recharge_data);
           $is_huafei=1;
           break;
         case '4':
           $draw_data['draw_type_name']='折扣';
            $discount=array();
            $todaytime=strtotime(date('Y-m-d',time()));
            $tmdaytime=strtotime(date('Y-m-d',strtotime("+1 day")));
            $discount['member_id']=$uid;
            $discount['draw_log_id']=$res;
            $discount['type']=1;//0   1抽奖
            $discount['discount']=sprintf("%.2f", $draw_data['draw_value']/10);//折扣
            $discount['start_time']=$todaytime;
            $discount['end_time']=$tmdaytime;
            $discount['add_time']=time();
            M('member_discount')->add($discount);
            break; 
         case '5':
           $goods_name=M('g_goods')->where(array('goods_id'=>$draw_data['draw_value']))->getField('goods_name');
           //$draw_data['draw_type_name']='商品:';  
           $draw_data['draw_type_name']=$goods_name;  
            $address=M('g_user_draw_address')->where(array('user_id'=>$this->uid))->find();
            if($address){
                $add_data=array();
                $add_data['draw_log_id']=$res;
                $add_data['address_name']=$address['address_name'];
                $add_data['member_id']=$address['user_id'];
                $add_data['consignee']=$address['consignee'];
                $add_data['email']=$address['email'];
                $add_data['address']=$address['address'];
                $add_data['zipcode']=$address['zipcode'];
                $add_data['tel']=$address['tel'];
                $add_data['mobile']=$address['mobile'];
                $add_data['sign_building']=$address['sign_building'];
                $add_data['best_time']=$address['best_time'];
                M('draw_address')->add($add_data);
            }
           break; 
         default:
           return array();
           break;
       }
       /*if(empty($is_huafei)){

       }*/
     }
     return $draw_data;
  }
    //抽奖
  public function get_draw(){
     $return_data['status']=0;
     //抽奖开关
     $switch_draw=M('sys_param')->where(array('param_code'=>'switch_draw'))->getField('param_value'); 
     if(empty($switch_draw)){
       $return_data['error']="抽奖未开启";
       echo json_encode($return_data);die;
     }
     $cheack_draw=$this->cheack_draw();
     if($cheack_draw){
       $return_data['error']="每天只能抽一次奖";
       echo json_encode($return_data);die;
     }
     //奖品池
     $draw_list=$this->get_draw_list();
     if(empty($draw_list)){
       $return_data['error']="抽奖还未开始哦";
       echo json_encode($return_data);die;
     }
     $draw=$this->get_rand($draw_list);
     $draw_msg=$this->set_draw_msg($draw);
     if(empty($draw_msg)){
       $return_data['error']="未中奖";
       echo json_encode($return_data);die;
     }
     $draw_msg=$this->get_draw_msg($draw_msg);
     $return_data['status']=1;
     $return_data['draw']=$draw_msg;
     echo json_encode($return_data);die;
  }
	
}