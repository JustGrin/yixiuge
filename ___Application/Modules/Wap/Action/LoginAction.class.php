<?php
//  登录注册 
class LoginAction extends BaseAction {
	//魔术方法
public function __construct(){
		parent::__construct();
		
	}
	
    public function index(){
        //$this->hongbao=$this->set_hongbao_show('login');
    	$this->webtitle="一休哥";
		  $this->display();
    }
     //验证手机号是否被存在
     public function cheack_account(){
         $account=$_REQUEST['mobile'];
        if(empty($account)){
            echo 0;die;
        }
        $where['mobile']=$account;
        $where['member_card']=$account;
        $where['_logic']='OR';
        $count= M("member")->where($where)->count();
        echo $count;die;
    }
    ///登录
    public function dologin(){
        $data['status']=0;
         $account=$_REQUEST['mobile'];
         $mem_password=$_POST['mem_password'];
        $mobile_code=$_REQUEST['mobile_code'];
         $rember=$_POST['rember'];

        if(empty($account)){
            $data['error']="邮箱/手机/用户名不能为空";
            echo json_encode($data);die;
        }
        if(empty($mem_password)){
            $data['error']="密码不能为空";
            echo json_encode($data);die;
        }
        $where['mobile']=$account;
        $where['member_card']=$account;
        //$where['member_card']=$account;
        $where['_logic']='OR';
        $member= M("member")->where($where)->find();
        if(empty($member)) {
            $data['error']="邮箱/手机/用户名不存在";
            echo json_encode($data);die;
        }
        if($member['member_freeze']==1) {
            $data['error']="帐号已冻结请联系管理员";
            echo json_encode($data);die;
        }
        if($member['password']!=md5($mem_password)) {
            $data['error']="密码错误";
            echo json_encode($data);die;
        }
        //记录session信息
        $user_data['uid']=$member['id'];
        $user_data['member_card']=$member['member_card'];
        $user_data['member_name']=$member['member_name'];
        $user_data['mobile']=$member['mobile'];
        $_SESSION['member']=$user_data;
        
        $this->set_cart();
        $user_open_id = 0;
        if($rember){//记住密码
          $this->set_session_id();//重设 session_id 时间
          $t_data=array();
          $t_data['member_id']=$member['id'];
          $t_data['session_id']=session('session_id');
          $user_open_id=session('user_open_id');
          if($user_open_id){
            $t_data['openid']=$user_open_id;
          }
          $time=time();
          $t_data['add_time']=$time;
          M('member_rember_token')->add($t_data);
           /*$cook_time=604800*2;
            $time=time()-$cook_time;
            $dwhere=array();*/
            if($user_open_id){
              $session_id=session('session_id');
              $dwhere['_string']="(openid='{$user_open_id}' or session_id='{$session_id}')";
            }else{
              $dwhere['session_id']=session('session_id');
            }
            $dwhere['add_time']=array('lt',$time);
            M('member_rember_token')->where($dwhere)->delete();
        }else{///不记住
          if($user_open_id){
              $session_id=session('session_id');
              $t_where['_string']="(openid='{$user_open_id}' or session_id='{$session_id}')";
          }else{
              $t_where['session_id']=session('session_id');
          }
          $member=M('member_rember_token')->where($t_where)->delete();
        }
        //记录登录信息
        $l_data['last_login_ip']=$member['login_ip'];
        $l_data['last_login_time']=$member['login_time'];
        $l_data['login_ip']=getIP();
        $l_data['login_time']=time();
        $l_data['login_count']=$member['login_count']+1;
        M('member')->where(array('id'=>$member['id']))->save($l_data);
         //登录日志 记录
        $this->set_member_login_log();
        session('share',null);

        $data['return_url']=$this->get_return_url();
        $data['status']=1;
        echo json_encode($data);die;
    }
     ///
    public function get_return_url(){
      $get_return_url=session('wap_login_return');
       if(empty($get_return_url)){
           $get_return_url=U('wap/member/index');
       }
       return $get_return_url;  

    }
     ///
    public function loginout(){
        M('member_rember_token')->where(array('member_id'=>$this->uid))->delete();
        unset($_SESSION['member']);
        session('wap_login_return',null);
        redirect(U('Wap/Login/index'));
    }
    
    public function register(){
        
            //未设置情况下查询出所有的城市数据
        $citydata = array('province' => 1, 'provinceid' => 0, 'cityid' => 0);
        $citylist = $this->get_city_return($citydata);
        $this->citylist = $citylist;
        $ShareMemberInfo=$this->getShareMemberInfo();
        $this->member_name=$ShareMemberInfo['member_name']?$ShareMemberInfo['member_name']:'一休哥';
        $this->member_card=$ShareMemberInfo['member_card']?$ShareMemberInfo['member_card']:INDEX_CARD;
    	//$this->hongbao=$this->set_hongbao_show('register');
		$this->display();
    }

    //注册
    public function doregister(){
        
        $data['status']=0;
        $code=$_POST['code'];
        $mem_password=$_POST['mem_password'];
        $rep_password=$_POST['rep_password'];
        $mobile=$_POST['mobile'];
        //$verify_code=$_POST['verify_code'];
        //检测验证码
        /*if(!$this->checkVerify_return()){
            $data['error']="验证码错误";
            echo json_encode($data);die;
        }*/
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


        /*address start*/
        $provinceid=$_POST['provinceid'];
        $cityid=$_POST['cityid'];
        $areaid=$_POST['areaid'];
        $address=$_POST['address'];

        if(empty($provinceid) || empty($cityid) || empty($areaid)){
            $data['error']="请选择完整的省市区";
            echo json_encode($data);die;
        }
        $m_data_detail['provinceid']=$provinceid;
        $m_data_detail['cityid']=$cityid;
        $m_data_detail['areaid']=$areaid;
        $m_data_detail['address']=$address;
        /*address end*/



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
        //获取用户Openid
        $openid= session('user_open_id');
        if($openid){
            $m_data['openid']=$openid;
        }
        //注册
        $m_data['mobile']=$mobile;
        $m_data['member_name']='YX'.substr($mobile, 0,-4);
        if($_POST['member_name']){
            $m_data['member_name']=$_POST['member_name'];
        }
        $m_data['add_time']=time();
        $m_data['password']=md5($mem_password);

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
            //设置卡号  
            $card=$this->getcard($add);
            $s_data=M('member')->where(array('id'=>$add))->save(array('member_card'=>$card));

             $member_model->commit();//提交事务
            $member_detail_model->commit();//提交事务
            $this->dologin();
        }else{
             $member_model->rollback();//回滚事务
            $member_detail_model->rollback();//回滚事务
            $data['error']="系统错误请稍候再试";
        }
       echo json_encode($data);
    }
    
    //查询 推荐人是否存在
    public function chk_member_code(){
       $code=$_REQUEST['code'];
       if(empty($code)){
           echo 1;die;
       }
       $data_code=$this->getMemberCode($code);
      if(!empty($data_code)){
           echo 1;die;
      }else{
          echo 0;die;
      }
    }
   
     //注册验证 密码 是否为空 两次密码是否一致
     public function cheack_password_return(){
         $mem_password=$_POST['mem_password'];
         $rep_password=$_POST['rep_password'];
        if(empty($mem_password)){
            $data['error']="密码不能为空";
            return $data;
        }
        if(empty($rep_password)){
            $data['error']="重复密码不能为空";
            return $data;
        }
        if($rep_password != $mem_password){
            $data['error']="两次密码不一致";
            return $data;
        }
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

  public function findpwd(){
    if(IS_AJAX){
         $data['status']=0;
        $rep_password=isset($_POST['rep_password']) ? $_POST['rep_password'] : '';
        if(empty($rep_password)){
            $data['error']="手机号码已不存在";
             echo json_encode($data);die;
        }
        ///手机 是否被注册
        
        //修改密码
        $m_data =array();
        $m_data['password']=md5($rep_password);
        $res= M("member")->where(array('id'=>$this->uid))->save($m_data);
        if($res!==false){
              $data['status']=1;
        }else{
           $data['error']="修改失败";
        }
          echo json_encode($data);die;

    }
    //$this->verify();

    $this->display();
  }
  //服务协议
  public function fuwu(){
     //$this->fuwu=M('sys_param')->where(array('param_code'=>'feimi_fuwu'))->getField('param_value'); 
    //用户协议member_reg_protocol
    $this->fuwu=M('sys_param')->where(array('param_code'=>'member_reg_protocol'))->getField('param_value'); 
     $this->display();
  }

  ///显示红包
public function set_hongbao_show($login=''){
  if($this->uid){
    cookie('hongbao_'.$login,1);
    return 1;
  }else{
     $hongbao=cookie('hongbao_'.$login);
      cookie('hongbao_'.$login,1);
     return $hongbao;
   }
}
    //绑定微信时验证密码
    public function check_password(){
        $password= $_REQUEST['password'];
        $member=M('member')->field('password')->where(array('id'=>$this->uid))->find();
        if($member['password'] != md5($password)){
            echo 0;die;
        }
        echo 1;
    }

//绑定判断 手机号是否被存在
     public function cheack_binding_mobile(){
        $account=$_REQUEST['mobile'];
        if(empty($account)){
          echo 0;die;
        }
        $where=array('mobile'=>$account);
        $count= M("member")->where($where)->count();
        echo $count;die;
    }
    //绑定
    public function binding(){
        $uid=$this->uid;
        $data['status']=0;
        $mobile=$_POST['mobile'];
        $real_name = $_POST['real_name'];
        $company = isset($_POST['company']) ? $_POST['company'] : null;
          //检测 手机验证码
         $_REQUEST['mobile']=$mobile;
       //  $_REQUEST['mobile_code']=$_POST['binding_mobile_code'];
 /*       $send_return=$this->check_send_return();
        if($send_return['error']){
            $data['error']=$send_return['error'];
            echo json_encode($data);die;
        }*/
        $openid=session('user_open_id');
        $member_info_now= M("member")->where(array('openid'=>$openid))->find();
      /*  if(empty($member_info_now)){
            $data['error']='用户信息错误';
            echo json_encode($data);die;
        }*/

       //绑定  手机号 已注册
       $where['mobile']=$mobile;
        $where['id'] = array('neq',$uid);
       $member_info_mobile= M("member")->where($where)->find();
       if($member_info_mobile){
         if($member_info_mobile['id']==$member_info_now['id']){
             $data['status']=1;
            echo json_encode($data);die;
         }
         if($member_info_mobile['openid'] ){
            $data['error']="该手机号已被绑定";
            echo json_encode($data);die;
         }
         $openid=session('user_open_id');
         ///绑定
         $member_model=M("member");
         $member_model->startTrans();//开起事务
         $res2= $member_model->where(array('openid'=>$openid))->save(array('openid'=>''));
         $res1= $member_model->where(array('mobile'=>$mobile))->save(array('openid'=>$openid));
        
          if($res1!==false && $res2!==false){
            //数据转移
            $member_model->commit();//提交事务
            //
            $now_uid=$member_info_now['id'];
            $set_uid=$member_info_mobile['id'];
            M('g_cart')->where(array('user_id'=>$now_uid))->save(array('user_id'=>$set_uid));
            M('bank_account')->where(array('member_id'=>$now_uid))->save(array('member_id'=>$set_uid));
            //M('member_rember_token')->where(array('member_id'=>$now_uid))->save(array('member_id'=>$set_uid));
            M('g_user_address')->where(array('user_id'=>$now_uid))->save(array('user_id'=>$set_uid));

             //记录session信息
            $user_data=array();
            $user_data['uid']=$member_info_mobile['id'];
            $user_data['member_card']=$member_info_mobile['member_card'];
            $user_data['member_name']=$member_info_mobile['member_name'];
            $user_data['mobile']=$member_info_mobile['mobile'];
            $_SESSION['member']=$user_data;
            $data['status']=1;
            echo json_encode($data);die;
          }else{
            $member_model->rollback();//提交事务
            $data['error']="绑定失败";
            echo json_encode($data);die;
          }
       }


        //注册
        $m_data['mobile']=$mobile;
        $m_data['real_name'] =$real_name;
		if(empty($member_info_now['member_name'])){
			 $m_data['member_name']='YX'.substr($mobile, 0,-4);
		}

        $member_model=M('member');


        $member_model->startTrans();//开起事务


        $add=$member_model->where(array('id'=>$_SESSION["member"]['uid']))->save($m_data);


        if($add!==false ){
              //绑定成功
            $data['status']=1;
            if($company){
                M('member_detail')->where('member_id='.$uid)->setField('company',$company);
            }
            //将信息存入用户审核中
            $ver_data = $_POST;
            $ver_data['status'] = 0;
            $time = time();
            $ver_data['change_time'] = $time;
            $ver_count = M('member_verification')->where('member_id='.$uid)->count();
            if ($ver_count){
                $ver_res = M('member_verification')->where('member_id='.$uid)->save($ver_data);
            }else{
                $ver_data['member_id'] = $uid;
                $ver_data['add_time'] = $time;
                $ver_res = M('member_verification')->add($ver_data);
            }
            //记录session信息
            $user_data=array();
            $user_data['uid']=$_SESSION["member"]['uid'];
            $user_data['member_card']=$_SESSION["member"]['member_card'];
			if(!empty($m_data['member_name'])){
				 $user_data['member_name']=$m_data['member_name'];
			}else{
				 $user_data['member_name']=$_SESSION['member']['member_name'];
			}
            $user_data['member_name']=$m_data['member_name'];
            $user_data['mobile']=$m_data['mobile'];
            $_SESSION['member']=$user_data;
              //登录日志 记录
            //$this->set_member_login_log();
            $member_model->commit();//提交事务
        }else{
             $member_model->rollback();//回滚事务
            $data['error']="系统错误请稍候再试";
        }
       echo json_encode($data);
    }

   //显示绑定弹窗
    public function get_check_binding(){
        $return_data['status']=0;
		 echo json_encode($return_data);die;
        $user_agent = $user_agent = $_SERVER['HTTP_USER_AGENT'];
        if(strpos($user_agent, 'MicroMessenger') === false){
          //不是微信浏览器
            echo json_encode($return_data);die;
        }
        if (empty($this->uid)){
          //未登录
           echo json_encode($return_data);die;
          }
          if(!empty($_SESSION['member']['mobile'])){
          //已绑定
           echo json_encode($return_data);die;
          }
           $is_check=$_REQUEST['binding_is_check'];// 重复再次弹出 1是 0否
          //今天第一次显示 今天已经抽了不显示
          $page=$_REQUEST['binding_page'];//绑定验证页面
          if($page){
             $page='check_binding_'.$page;
             if (empty($is_check)){// 重复不再次弹出
               $show=$this->set_cookie_show($page);
               if($show){
                $return_data['status']=0;
               }else{
                $return_data['status']=1;
               }
               echo json_encode($return_data);die;
              }
            $this->set_cookie_show($page);
          } 
          $return_data['status']=1;
         echo json_encode($return_data);die;
    }
	public function set_member_member_name(){
        $p = $_REQUEST['p'];
        $pagesize = 10;
        $p = !empty($p) ? $p : 1;
        $start = ($p - 1) * $pagesize;
        $member_model=M('member');
        $list=$member_model->where('member_name is null')->field('id,member_name,member_card')//
        ->limit($start, $pagesize)->order('id desc')->select();
        if($list){
          foreach ($list as $key => $value) {
            if(empty($value['member_name'])){
              $member_model->where(array('id'=>$value['id']))->save(array('member_name'=>'YX'.$value['member_card']));
            }
          }
          //sleep(秒)  usleep(毫秒)  让它睡上一会。
          sleep(1); 
          $_REQUEST['p'];
          $this->set_member_member_name();
        }

    }
    public function binding_phone(){
        $uid =$this->uid;
        $data =array();
        $count =  M('member_verification')->where('member_id='.$uid)->count();
        if($count){
            @$data = M('member_verification')->where('member_id='.$uid)->find();
        }
        $this->count = $count;
        $this->data =$data ;
        $this->display();
    }
}