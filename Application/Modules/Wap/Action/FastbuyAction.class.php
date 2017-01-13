<?php
//  快速购买 
class FastbuyAction extends BaseAction {
	//魔术方法
public function __construct(){
		parent::__construct();
		
	}
	
    public function index(){
		  ///注册 
      $this->doregister();
      $address=$this->set_address();

      //$cart_id=$_REQUEST['cart_id'];
      $address_id=$address['address_id'];
      $_REQUEST['address_id']=$address_id;
      A('Goodsorder')->set_order();
    }    
    //注册
    public function doregister(){
        $data['status']=0;
        $code=$_POST['code'];
        $mem_password=$_POST['mem_password'];
        $rep_password=$_POST['rep_password'];
        $mobile=$_POST['mobile'];
        //检测 手机验证码
          $send_return=$this->check_send_return();
        if($send_return['error']){
            $data['error']=$send_return['error'];
            echo json_encode($data);die;
        }
          //注册验证 密码 是否为空 两次密码是否一致
        $password_return=$this->cheack_password_return();
        if($password_return['error']){
           $data['error']=$password_return['error'];
            echo json_encode($data);die;
        }
        ///手机 是否被注册
         $mobile_return=$this->cheack_mobile_return();
        if($mobile_return['error']){
           $data['error']=$mobile_return['error'];
            echo json_encode($data);die;
        }
         if(!empty($code)){
            $data_code=$this->getMemberCode($code);
            if(!empty($data_code)){
                 $m_data['recommend_code']=$data_code['recommend_code'];//推荐码 (推荐人的推荐码 )
                 $m_data['indirect_recommend_code']=$data_code['indirect_recommend_code'];//间接推荐人推荐码 (推荐人的推荐人的推荐码 )
                 $m_data['indirect2_recommend_code']=$data_code['indirect2_recommend_code'];//间接2层推荐人推荐码 (推荐人的推荐人的推荐码 )
            }
        }else{
          $code=INDEX_CARD;
           $data_code=$this->getMemberCode($code);
           if(!empty($data_code)){
                 $m_data['recommend_code']=$data_code['recommend_code'];//推荐码 (推荐人的推荐码 )
                 $m_data['indirect_recommend_code']=$data_code['indirect_recommend_code'];//间接推荐人推荐码 (推荐人的推荐人的推荐码 )
                 $m_data['indirect2_recommend_code']=$data_code['indirect2_recommend_code'];//间接2层推荐人推荐码 (推荐人的推荐人的推荐码 )
          }
        }
        //注册
        $m_data['mobile']=$mobile;
        $m_data['member_name']='FG'.substr($mobile, 0,-4);
        $m_data['add_time']=time();
        $m_data['password']=md5($mem_password);
        $ip=getIP();
        $m_data['last_login_ip']=$ip;
        $m_data['last_login_time']=time();
        $m_data['login_ip']=$ip;
        $m_data['login_time']=time();
        $m_data['login_count']=1;
       
         ///注册赠送 用户金额Register give money
        $register_give_money=M('sys_param')->where(array('param_code'=>'register_give_money'))->getField('param_value');//返利比例；
        if($register_give_money>0){
            ///随机5到XX //赠送金额随机 5到X
            $register_give_money=$this->set_register_give_money($register_give_money);
            $m_data_detail['balance_give']=$register_give_money;//用户扩展表赠送金额 注册赠送20块钱  米值
        }else{
             $m_data_detail['balance_give']=0;//用户扩展表 注册赠送20块钱  米值
        }

        $m_data_detail['points']=10;//用户扩展表 注册赠送10积分
        //获取用户Openid
        $openid = session('user_open_id');
        if ($openid) {
            $m_data['openid'] = $openid;
        }
        $member_model=M('member');
        $member_detail_model=M('member_detail');

        $member_model->startTrans();//开起事务
        $member_detail_model->startTrans();//开起事务

        $add=$member_model->add($m_data);

        $m_data_detail['member_id']=$add;//
        $member_detail_add=$member_detail_model->add($m_data_detail);
        
        if($add!==false && $member_detail_add!==false){
              //注册成功
            $data['status']=1;
            $data['uid']=$add;
            //设置卡号  
            //$card=$this->getcard($add);
            $card=$this->getcard($add);
            $s_data=M('member')->where(array('id'=>$add))->save(array('member_card'=>$card));
           
            //记录session信息 自动登录
            $user_data['uid']=$add;
            $user_data['member_card']=$card;
            $user_data['member_name']=$m_data['member_name'];
            $user_data['mobile']=$m_data['mobile'];
            $_SESSION['member']=$user_data;
            $this->set_cart();

            $member_model->commit();//提交事务
            $member_detail_model->commit();//提交事务
            if($m_data_detail['balance_give']>0){
                 ///记录消费日志
                $log_data['type']=5;//消费类型 1订单消费 2充值 3提现   4退款 5 收益 6认证消费
                $log_data['member_id']=$add;
                $log_data['money']=$m_data_detail['balance_give'];
                $log_data['status']=1;//状态 1收入 2支出
                $log_data['des']="注册赠送￥".$log_data['money'];//消费介绍
                $log_data['add_time']=time();//消费时间
                $this->set_member_consume_record($log_data);
            }
            //注册赠送10积分 
             if($m_data_detail['points']>0){
                 ///记录日志
                $log_integral_data['type']=1;//消费类型 1获得 2消费
                $log_integral_data['member_id']=$add;
                $log_integral_data['integral']=$m_data_detail['points'];
                $log_integral_data['status']=1;//状态 1收入 2支出
                $log_integral_data['des']="注册赠送+".$m_data_detail['points'];//消费介绍
                $log_integral_data['add_time']=time();//消费时间
                $this->set_member_integral($log_integral_data);
            }
             //推荐人 赠送 积分 10
            if($m_data['recommend_code']){
                $i_member_id=M('member')->where(array('member_card'=>$m_data['recommend_code']))->getField('id');
                if($i_member_id){
                   $lgo_i_data['des']="推荐注册赠送+10";
                   //$status 1 收入 2支持  integral 积分
                   $this->set_member_points($i_member_id,1,10,$lgo_i_data);
                     // 推荐注册直接人赠送金额
                    $give_money=M('sys_param')->where(array('param_code'=>'rebate_register_give_money'))->getField('param_value');
                    if($give_money>0){
                       $log_data=array();
                       $log_data['des']="推荐注册赠送￥".$give_money;
                       $log_data['type']=5;////消费类型 1订单消费 2充值 3提现   4退款 5 收益 6认证消费
                     //$status 1 收入 2支持  
                      $this->set_member_balance($i_member_id,1,$give_money,$log_data);
                    }
                }
            }
        }else{
             $member_model->rollback();//回滚事务
            $member_detail_model->rollback();//回滚事务
            $data['error']="系统错误请稍候再试";
            echo json_encode($data);die;
        }
        return $data;
       //echo json_encode($data);
    }
     ///收获地址列表
    public function set_address(){
        $data['status']=0;
        $consignee=$_POST['consignee'];
        $email=$_POST['email']?$_POST['email']:'';
        $province=$_POST['province'];
        $city=$_POST['city'];
        $area=$_POST['area'];
        $address=$_POST['address'];
        $zipcode=$_POST['zipcode']?$_POST['zipcode']:'';
        $tel=$_POST['tel']?$_POST['tel']:'';
        $mobile=$_POST['mobile'];
        $sign_building=$_POST['sign_building']?$_POST['sign_building']:'';
        $best_time=$_POST['best_time']?$_POST['best_time']:'';
        if(empty($consignee)){
          $data['error']="请填写收货人的名字";
          echo json_encode($data);die;
        }
        if(empty($province) || empty($city) || empty($area)){
          $data['error']="请选择完整的省市区";
          echo json_encode($data);die;
        }
        if(empty($address)){
          $data['error']="请填写详细地址";
          echo json_encode($data);die;
        }
        if(empty($tel) && empty($mobile)){
          $data['error']="请填写收货人的电话或手机";
          echo json_encode($data);die;
        }
        $province_name=M('city_province')->where(array('provinceid'=>$province))->getField('province');
        $city_name=M('city_city')->where(array('cityid'=>$city))->getField('city');
        $area_name=M('city_area')->where(array('areaid'=>$area))->getField('area');
        $address_name=$province_name.$city_name.$area_name;
        $save_data['address_name']=$address_name;
        $save_data['consignee']=$consignee;
        $save_data['email']=$email;
        $save_data['province']=$province;
        $save_data['city']=$city;
        $save_data['area']=$area;
        $save_data['address']=$address;
        $save_data['zipcode']=$zipcode;
        $save_data['tel']=$tel;
        $save_data['mobile']=$mobile;
        $save_data['sign_building']=$sign_building;
        $save_data['best_time']=$best_time;
        
       $save_data['user_id']=$_SESSION["member"]['uid'];
       $res=M('g_user_address')->add($save_data);
       
        if($res!==false){
              $data['status']=1;
              $data['address_id']=$res;
        }else{
              $data['error']="操作失败";
               echo json_encode($data);die;
        }
        return $data;
    }
     //注册验证 密码 是否为空 两次密码是否一致
     public function cheack_password_return(){
         $mem_password=$_POST['mem_password'];
         $rep_password=$_POST['rep_password'];
        if(empty($mem_password)){
            $data['error']="密码不能为空";
            return $data;
        }
        /*if(empty($rep_password)){
            $data['error']="重复密码不能为空";
            return $data;
        }
        if($rep_password != $mem_password){
            $data['error']="两次密码不一致";
            return $data;
        }*/
        return array('status'=>1);
    }
    //验证手机号是否被注册
     public function cheack_mobile_return(){
         $account=$_REQUEST['mobile'];
        if(empty($account)){
            $data['error']="手机号码不存在";
            return $data;
        }
        $count= M("member")->where(array('mobile'=>$account))->count();
        if($count){
            $data['error']="手机号码已被注册";
            return $data;
        }
        return array('status'=>1);
    }
     //验证手机号是否被存在
     public function cheack_mobile(){
		 $account=$_REQUEST['mobile'];
        if(empty($account)){
        	echo 1;die;
        }
        $count= M("member")->where(array('mobile'=>$account))->count();
        echo $count;die;
    }
 //获取卡号
    public function getcard($member_id=null){
        $member=M("member")->where(array('member_card'=>array('neq',INDEX_CARD)))->order('member_card desc')->find();
        if(!empty($member)){
            if($member['member_card']){
              $card=$member['member_card']+1;
            }
        }
        if(empty($card)){
          $card=$member_id;
        }
        $strlen=mb_strlen($card,'utf-8');//计算字符串长度
        if($strlen<7){
            $rep=7-$strlen;
            $card=str_repeat('0',$rep).$card;
        }
        return $card;
    }
  

}