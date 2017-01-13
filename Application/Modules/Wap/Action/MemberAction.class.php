<?php
// 用户控制器
class MemberAction extends UserAction {
	//魔术方法
	public function __construct(){
		parent::__construct();
		
		//用户身份证信息
		$member_id_card=M('member_id_card')->where('member_id='.$this->uid)->find();
		if( empty($member_id_card) || $member_id_card['is_check']== 2 ){
			if ($member_id_card['is_check'] == 2){
				$member_id_card['msg']="用户认证未通过，请重新认证";
			}else{
				$member_id_card['msg']="用户未认证";
			}
		}elseif( $member_id_card['is_check']==0 ){
			$member_id_card['msg']="审核当中请耐心等待";
		}
		$this->assign('member_id_card', $member_id_card);
	}
	
	//个人中心
    public function index(){
        $memberinfo=$this->getMemberInfo();
		//如果不是通过连接进来就没有分享人，清空session('share')
		if(empty($_GET['share']) && empty($_GET['layer']) ){
			session('share',null);
			$share_card = $memberinfo['member_vip_type'] > 0 ? $memberinfo['member_card'] : ($memberinfo['recommend_code'] ? $memberinfo['recommend_code'] : INDEX_CARD);
			$this->redirect('wap/member/index',array('share'=>$share_card));
		}
        $this->data=$memberinfo;
        $memberinfo_de=$this->getMemberDetail();
        $memberinfo_de['balance']+=$memberinfo_de['balance_give'];
        //获取收藏商品总数
        $where_collect['coll.user_id']=$this->uid;
        $where_collect['is_attention']=1;
        $count_collect=M()->table('db_g_collect_goods coll')//
        ->join('db_g_goods goods on goods.goods_id=coll.goods_id')//
        ->where($where_collect)->count();
        $memberinfo_de['count_collect']=$count_collect;
        //得到客户
        $fans=get_fans_count($memberinfo['member_card']);
        $memberinfo_de['shar_r1']=$fans['shar_r1'];
        $memberinfo_de['shar_r2']=$fans['shar_r2'];
        //得到最近旗舰店的信息
        $flageinfo=getflageship($memberinfo['member_card']);
        $memberinfo_de['flageship_name']=$flageinfo['member_name'];
        $this->MemberDetail=$memberinfo_de;

        $this->member_count($memberinfo['member_card']);
        if($memberinfo['member_vip_type']==0){
          $vip_name='<p> 普通会员 <span style="margin-left: 10px; padding: 3px;background-color: #228B22;-moz-border-radius: 5px; -webkit-border-radius: 5px;  "> <a style="color:#fff;" href="'.U('wap/start/index').'">我要升级</a></span></p>';
            $vip_i=0;
        }elseif($memberinfo['member_vip_type']==1){
            $vip_name='<p> 标准店 <span style="margin-left: 10px; padding: 3px;background-color: #228B22;-moz-border-radius: 5px; -webkit-border-radius: 5px;  "> <a style="color:#fff;" href="'.U('wap/start/index').'">升级成高级店</a></span></p>';
            $vip_i=1;
        }elseif($memberinfo['member_vip_type']==2){
            $vip_name='<p> 高级店 </p>';
            $vip_i=2;
        }elseif ($memberinfo['member_vip_type']==3){
            $vip_name='<p> 旗舰店 </p>';
            $vip_i=3;
        }
        $vip_arr=array(0=>'会员',1=>'标准店铺',2=>'高级店铺',3=>'旗舰店铺');
        //$this->vip_name=$vip_name;
        $this->vip_name=$vip_arr[$vip_i];
        $this->vip_i=$vip_i;
         //var_dump($memberinfo);
        //抽奖开关
        $this->switch_draw=M('sys_param')->where(array('param_code'=>'switch_draw'))->getField('param_value'); 
        $this->draw_data=$this->get_today_draw();//今日 抽奖记录
        //今天第一次显示 今天已经抽了不显示
       // $this->draw_count=$this->set_cookie_show('draw_member_home');
        //var_dump($this->draw_count);
    	$this->webtitle="FG峰购";
        //判断是否微信浏览器
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        if (strpos($user_agent,'MicroMessenger') !== false) {
            //判断是否绑定微信
            $openid = M('member')->field('openid')->where(array('id' => $this->uid))->find();
            if(empty($openid['openid'])){
                $this->bind=$openid;
            }
        }
        $this->display();
    }

    //店铺中心
    public function index_vip(){
        $memberinfo=$this->getMemberInfo();
		
		if($memberinfo['member_vip_type'] <= 0){
			$this->redirect('Member/index');
		}
		
        $this->data=$memberinfo;
        $memberinfo_de=$this->getMemberDetail();
        $memberinfo_de['balance']+=$memberinfo_de['balance_give'];

        //得到客户数
        $fans=get_fans_count($memberinfo['member_card']);
        $memberinfo_de['shar_r1']=$fans['shar_r1'];
        $memberinfo_de['shar_r0']=$fans['shar_r0'];
        //得到最近推荐人的名字
        $recommend_name=M('member')->where('member_card="'.$memberinfo['recommend_code'].'"')->getField('member_name');
        $memberinfo_de['recommend_name']=$recommend_name;
        $this->MemberDetail=$memberinfo_de;

        $this->member_count($memberinfo['member_card']);
        $vip_i=$memberinfo['member_vip_type'];
        $vip_arr=array(0=>'普通会员',1=>'标准店铺',2=>'高级店铺',3=>'旗舰店铺');
        //$this->vip_name=$vip_name;
        $this->vip_name=$vip_arr[$vip_i];
        $this->vip_i=$vip_i;
        //var_dump($memberinfo);
        //抽奖开关
        $this->switch_draw=M('sys_param')->where(array('param_code'=>'switch_draw'))->getField('param_value');
        $check_draw=A('Lottery')->check_draw();
        //今天第一次显示 今天已经抽了不显示
        $this->assign('check_draw',$check_draw);//今日 抽奖记录
        $this->webtitle="FG峰购";
		
        //判断是否微信浏览器
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        if (strpos($user_agent,'MicroMessenger') !== false) {
            //判断是否绑定微信
            $openid = M('member')->field('openid')->where(array('id' => $this->uid))->find();
            if(empty($openid['openid'])){
                $this->bind=$openid;
            }
        }
        $this->display();
    }
     //个人设置
    public function setup(){
		$memberinfo=$this->getMemberInfo();
        $this->data=$memberinfo;
          $memberinfo_de=$this->getMemberDetail();
          $memberinfo_de['balance']+=$memberinfo_de['balance_give'];
        $this->MemberDetail=$memberinfo_de;

        $this->member_count($memberinfo['member_card']);
        
    	$this->webtitle="FG峰购";
		$this->display();
    }
     // 
    public function member_count($member_card){
         $pre=C('DB_PREFIX');//表前缀
        $f_where1="recommend_code='{$member_card}' or indirect_recommend_code='{$member_card}' or indirect2_recommend_code='{$member_card}'";
        $count['fans']= M('Member')->where($f_where1)->count();
        //收藏
         $where_c['coll.member_id']=$this->uid;
         $count['goods']=M()->table($pre.'member_good_collect coll')//
        ->join($pre.'group_goods goods on goods.id=coll.goods_id')//
        ->where($where_c)->count();
         
          $pre=C('DB_PREFIX');//表前缀
          $where=array();
         $where['or_good.share_card']=$member_card;
          $where['ord.pay_status']=2;// 支付状态；0，未付款；1，付款中 ；2，已付款
          $where['ord.order_status']=array('in','1,4');//订单状态。0，未确认；1，已确 认；2，已取消；3，无效；4，退货；
          $where['ord.shipping_status']=2;//商品配送情况，0，未发货； 1，已发货；2，已收货；3，备货中
		 $where=array();
		 $where['member_id']=$this->uid;
		  $where['status']=1;
		$where['type']=5;
        $where['type5_type']=1;//收益类型  1直接推荐人 2间接推荐人 3 间接二级推荐人 4代理区域会员
		  $count_share=M('member_consume_record')->where($where)->sum('money');
        $count_share=sprintf("%.0f",substr(sprintf("%.3f", $count_share), 0, -3));
        $count['share']=$count_share;
         $order_model=M('g_order_info');
         $where=array();
         $where['user_del']=0;
        $where['user_id']=$this->uid;
        $where['_string'] ="activity_id = 0 or (activity_id > 0 and activity_status = 1)";
        $count['order']=$order_model->where($where)->count();//全部订单
        $where=array();
        $where['user_del']=0;
        $where['user_id']=$this->uid;
        $where['pay_status'] = 0;
        $where['order_status'] = 0;
        $where['shipping_status'] = 0;
        $where['_string'] ="activity_id = 0 or (activity_id > 0 and activity_status = 1)";
        $count['order0']=$order_model->where($where)->count();//

        $where=array();
        $where['user_del']=0;
        $where['user_id']=$this->uid;
        $where['pay_status'] = '2';//待发货
        $where['shipping_status'] = array('in', '0,3');
        $where['order_status'] = array('in', '1,0');//订单状态。0，未确认；1，已确 认；2，已取消；3，无效；4，退货；
        $where['_string'] ="activity_id = 0 or (activity_id > 0 and activity_status = 1)";
        $count['order1']=$order_model->where($where)->count();

        $where=array();
        $where['user_del']=0;
        $where['user_id']=$this->uid;
        $where['pay_status'] = 2;// 支付状态；0，未付款；1，付款中 ；2，已付款
        $where['shipping_status'] = 1;//商品配送情况，0，未发货； 1，已发货；2，已收货；3，备货中
        $where['order_status'] = array('in', '1,5');//订单状态。0，未确认；1，已确 认；2，已取消；3，无效；4，退货; 5,售后中；
        $count['order2']=$order_model->where($where)->count();

        $where=array();
        $where['user_del'] = 0;
        $where['user_id'] = $this->uid;
        //$where['shipping_status'] = '2';
        $where['_string'] = "((pay_status = 2 and shipping_status=2 ) or (pay_status = 2 and shipping_status=1 and order_status in ('2','3','4')) or (pay_status = 0 and order_status = 2 )) and (activity_id = 0 or (activity_id > 0 and activity_status = 1))";//订单状态。0，未确认；1，已确 认；2，已取消；3，无效；4，退货；

        $count['order3']=$order_model->where($where)->count();

        $where=array();///退货
        //$where['refund_status']=array('between',array(0,4));//包含1和4
        $where['is_read'] = 0;
        $where['member_id']=$this->uid;
        $where['type'] =2;
        $count['order4']=M('g_order_refund_chat')->where($where)->count();

        $this->member_count=$count;
    }
     //个人设置
    //头像设置
    public function setphoto(){
        if(IS_AJAX){
            $data['status']=0;
            $member_logo=$_REQUEST['member_logo'];
            if(empty($member_logo)){
                $data['error']="请上传图片";
                echo json_encode($data);die;
            }
            $res=M('member')->where(array('id'=>$this->uid))->save(array('member_logo'=>$member_logo));
            if($res!==false){
                $data['status']=1;
                echo json_encode($data);die;
            }
            $data['error']="修改头像失败";
            echo json_encode($data);
            die;
        }
        if(empty($_GET['img'])){
            $memberinfo=$this->getMemberInfo();
            $this->data=$memberinfo;
        }
        $this->webtitle="FG峰购";
        $this->display();
    }
    //背景图设置
    public function setbackimg(){
        if(IS_AJAX){
            $data['status']=0;
            $background_image=$_REQUEST['background_image'];//----------
            if(empty($background_image)){//----------
                $data['error']="请上传图片";
                echo json_encode($data);die;
            }
            $resob=M('member');
            $res=$resob->where(array('id'=>$this->uid))->save(array('background_image'=>$background_image));
            $sql=$resob->_sql();
            if($res!==false){
                $data['status']=1;
                $data['sql']=$sql;
                echo json_encode($data);die;
            }
            $data['error']="修改头像失败";
            echo json_encode($data);
            die;
        }
        if(empty($_GET['img'])){
            $memberinfo=$this->getMemberInfo();
            $this->data=$memberinfo;
        }
        $this->webtitle="FG峰购";
        $this->display();
    }
    //基本信息
    public function basic(){
        $memberinfo=$this->getMemberInfo();
        $this->member=$memberinfo;
        $member=$this->getMemberDetail();
        $this->data=$member;
        //通过推荐人会员号，得到推荐人名字
        if(!empty($memberinfo['recommend_code'])){
            $where['member_card']=$memberinfo['recommend_code'];
            $recommend_name=M('member')->where($where)->getField("member_name");
        }

        // $this->member['recommend_name']=$recommend_name;
        $this->assign('recommend_name', $recommend_name);


        //地区信息设置之后不允许修改
        if($member['provinceid'] && $member['cityid'] && $member['areaid']){
            //查询会员身份
            $city['province'] = M('city_province')
                ->where('provinceid='.$member['provinceid'])->find();
            //查询会员城市
            $city['city'] = M('city_city')
                ->where('cityid='.$member['cityid'])->find();
            //查询会员区县
            $city['area']=M('city_area')
                ->where('areaid='.$member['areaid'])->find();
            $this->city=$city;
        }else{
            $this->city_group = get_city_group();
        }

        $this->city_group = get_city_group();

        $this->webtitle="FG峰购";
        $this->display();
    }
    //基本信息 修改
    public function basic_set(){
		$memberinfo = $this->getMemberInfo();
		$memberDetail = $this->getMemberDetail();
        $data['status']=0;
        $member_name=$_REQUEST['member_name'];
		$provinceid		= $_REQUEST['provinceid'];
		$cityid			= $_REQUEST['cityid'];
		$areaid			= $_REQUEST['areaid'];
        $addr			= $_REQUEST['addr'];
		$area_info		= $_REQUEST['area_info'];
		
        if(empty($member_name)){
            $data['error']="请输入昵称";
            echo json_encode($data);die;
        }
		
		if($area_info){
			list($provinceid, $cityid, $areaid) = explode(',', $area_info);
		}
		if(!$memberDetail['provinceid']){
			if(empty($provinceid) || empty($cityid) || empty($areaid)){
				$data['error']="请选择完整的省市区";
				 echo json_encode($data);die;
			}
			if(empty($addr)){
				$data['error']="请输入详细地址";
				echo json_encode($data);die;
			}
		}
		
        $m_data['member_name']=$member_name;
        //M('city_province')->where(array('provinceid'=>$provinceid))->getField('province');
        //$m_d_data['city_now']=M('city_city')->where(array('cityid'=>$cityid))->getField('city');
        //$m_d_data['county_now']=M('city_area')->where(array('areaid'=>$areaid))->getField('area');
        //$m_d_data['areaid']=$areaid;
		
        $m_d_data['provinceid'] = isset($provinceid) && $provinceid > 0 ? $provinceid : ($memberDetail['provinceid'] ? $memberDetail['provinceid'] : 0);
        $m_d_data['cityid'] = isset($cityid) && $cityid > 0 ? $cityid : ($memberDetail['cityid'] ? $memberDetail['cityid'] : 0);
        $m_d_data['areaid'] = isset($areaid) && $areaid > 0 ? $areaid : ($memberDetail['areaid'] ? $memberDetail['areaid'] : 0);
        $m_d_data['addr'] = isset($addr) && $addr ? $addr : ($memberDetail['addr'] ? $memberDetail['addr'] : '');
		
        $m_d_data['birthday']=$_REQUEST['birthday'];
        $m_d_data['hobby']=$_REQUEST['hobby'];
        $uid=$this->uid;
        $res_m=M('member')->where(array('id'=>$uid))->save($m_data);
        $count=M('member_detail')->where(array('member_id'=>$uid))->count();
        if($count){
           $res_m_d=M('member_detail')->where(array('member_id'=>$uid))->save($m_d_data);
        }else{
           $m_d_data['member_id']=$uid;
           $res_m_d=M('member_detail')->add($m_d_data);
        }
        if($res_m===false || $res_m_d===false){
            $data['msg']="修改失败，请稍后再试。";
            echo json_encode($data);die;
        }
        //unset($_SESSION['send']);
        //发送成功
        $data['status']=1;
        echo json_encode($data);die;
    }
     //修改密码
    public function updatepwd(){
		//$memberinfo=$this->getMemberInfo();
        //$this->data=$memberinfo;
    	$this->webtitle="FG峰购";
		$this->display();
    }
    //找回密码
    public function changepwd(){
		$data['status']=0;
         $mem_password=$_POST['mem_password'];
         $new_mem_password=$_POST['new_mem_password'];
         $res_mem_password=$_POST['res_mem_password'];
        if(empty($mem_password)){
            $data['error']="密码不能为空";
            echo json_encode($data);die;
        }
        if(empty($mem_password)){
            $data['error']="密码不能为空";
            return $data;
        }
        if(empty($new_mem_password)){
            $data['error']="新密码不能为空";
            return $data;
        }
        if(empty($res_mem_password)){
            $data['error']="确认密码不能为空";
            return $data;
        }
        if($res_mem_password != $new_mem_password){
            $data['error']="两次密码不一致";
            return $data;
        }
        $member=$this->getMemberInfo();
        if($member['password']!=md5($mem_password)) {
            $data['error']="密码错误";
            echo json_encode($data);die;
        }
        if($mem_password==$new_mem_password) {
            $data['error']="新密码不能和当前密码一致";
            echo json_encode($data);die;
        }

        $s_data['password']=md5($new_mem_password);
        $res=M('member')->where(array('id'=>$member['id']))->save($s_data);
        if($res===false){
        	$data['error']="修改密码失败，请稍后再试。";
            echo json_encode($data);die;
        }
        $data['status']=1;         
        echo json_encode($data);die;
    }

    //直接推荐人
    public function fans(){
        $memberinfo=$this->getMemberInfo();
        $this->data=$memberinfo;

        $model=M("member");
        //直接粉丝
        $f_where1['recommend_code']=$memberinfo['member_card'];
        $f_where1['member_vip_type']=array('gt','0');
         $p=$_REQUEST['p'];
         $pagesize=10;
         $p=!empty($p)?$p:1;
         $start=($p-1)*$pagesize; 
        // member_status 认证情况 1 已认证 0未认证
        $field="id,member_status,member_card,member_name,member_logo,add_time,member_vip_type,mobile,vip_time";
        //粉丝列表
        $list=$model->where($f_where1)->field($field)//
        ->limit($start,$pagesize)->order('vip_time desc')->select();
		$member_status_arr=array('普通会员','标准店铺','高级店铺','旗舰店');
        foreach ($list as $key => $value) {
            $list[$key]['member_logo']=get_member_logo_default($value['member_logo']);
                $list[$key]['member_status']=$member_status_arr["{$value['member_vip_type']}"];
           $list[$key]['vip_time']=date("Y.m.d",$value['vip_time']);
            $list[$key]['vip_type']=$value['member_vip_type'];
        }
		//推荐人信息
		$recommend_info=M('member')->where('member_card="'.$memberinfo['recommend_code'].'"')->find();
		$recommend_info['vip_name']=$member_status_arr[$recommend_info['member_vip_type']];
		$this->assign('recommend_info',$recommend_info);
        if(IS_AJAX){
         echo json_encode($list);die;
        }
        $this->list=$list;
        //直接粉丝统计
		//统计所有粉丝
        $fans_count=get_fans_count($this->member_card);
        $this->count=$fans_count;
        $this->webtitle="FG峰购";
        $this->display();
    }
    //我的钱包
    public function wallet(){
        //member_consume_record
         $where['m.member_id']=$this->uid;
         $p=$_REQUEST['p'];
         $pagesize=10;
         $p=!empty($p)?$p:1;
         $start=($p-1)*$pagesize;
         $type=$_REQUEST['type'];//1积分 0 米值
         $field="m.*";
		$type_order=$_REQUEST['type_order'];
		$join='';
		switch ($type_order){
			case 'store':
				$field="m.*,o.is_upgrade";
				$join='db_g_order_info as o on o.order_id=m.order_id';
				$where['is_upgrade']='1';
				break;
			case 'goods':
				$field="m.*,o.is_upgrade";
				$join='db_g_order_info as o on o.order_id=m.order_id';
				$where['is_upgrade']='0';
				break;
			case 'lottery':
				$where['type']=15;
				break;
			case 'cashapply':
				$where['type']=3;
				break;
			case 'refund':
				$where['type']=4;
				break;
			case 'other':
				$where['type']=array('neq',array('1','0','4'));
				break;
			default :
				break;
		}

         if(empty($type)){
             $model=M('member_consume_record');
              $arr=array('1'=>'订单消费','2'=>'充值','3'=>'提现','4'=>'退款','5'=>'收益','6'=>'认证消费','7'=>'系统赠送','15'=>'抽奖');
         }else{//积分
            $model=M('member_integral');
             $arr=array('0'=>'消费','1'=>'获得','2'=>'消费','4'=>'退款', '5'=>'收益');
         }

         $list=$model->where($where)->alias('m')->field($field)->join($join)//
        ->limit($start,$pagesize)->order('add_time desc')->select();
        //消费类型 1订单消费 2充值 3提现   4退款 5 收益 6认证消费
        if(!empty($list)){
             foreach ($list as $key => $value) {
               $typename=$arr[$value['type']];
               $typename?$typename:'-';
               $list[$key]['typename']=$typename;
               $list[$key]['status']=$value['status']==1?'+':'-';
               $list[$key]['add_time']=date("Y/m/d",$value['add_time']);
				 if($type == 1){
					 $list[$key]['money']=$list[$key]['integral'];
				 }
				// $list[$key]['type_order']=$type_order;
            }
        }
        if(IS_AJAX){
            echo json_encode($list);die;
        }
        $this->count=$model->where($where)->count();//统计
        $this->list=$list;
        $data=$this->getMemberDetail();
        // $data['balance']=sprintf("%.0f",substr(sprintf("%.3f", $data['balance']), 0, -3));
        // $data['balance_give']=sprintf("%.0f",substr(sprintf("%.3f", $data['balance_give']), 0, -3));
        $data['balance_all']+=$data['balance']+$data['balance_give'];
        // $data['balance_all']=sprintf("%.0f",substr(sprintf("%.3f", $data['balance_all']), 0, -3));
        $this->data=$data;
        $this->webtitle="FG峰购";
        $this->display();
    }

    //展示用钱包
    public function wallet_notice(){

        //member_consume_record
        $type=$_REQUEST['type'];//1积分 0 米值

        $type_order=$_REQUEST['type_order'];
        switch ($type_order){
            case 'store':
                $a = 0;
                $b = 19;
                break;
            case 'goods':
                $a = 20;
                $b = 39;
                break;
            case 'lottery':
                $a = 40;
                $b = 59;
                break;
            case 'cashapply':
                $a = 60;
                $b = 79;
                break;
            case 'refund':
                $a = 40;
                $b = 59;
                break;
            case 'other':
                $a = 60;
                $b = 79;
                break;
            default :
                $a = 0;
                $b = 79;
                break;
        }

        //钱包数据
        $all_balance = 0;
        $all_points = 0;
        $begin_time=mktime(0,0,0,date('m'),date('d')-7,date('Y'));
        $end_time=time();
        $date['balance_all'] = 0;//积分基数
        $date['points'] = 0;//钱包基数
        for ($i=$a; $i<=$b; $i++) { 
            if (empty($type)) {
                //钱包
                if ($i>=0 && $i<=19) {
                    //店铺
                    $des = rand(0,11);
                    if ($des == 0 || $des == 1) {
                        $list_1[$i]['money'] = rand(5000,60000)/100;
                        $list_1[$i]['des'] = "直接推荐人消费收益：￥".$list_1[$i]['money'];
                    }else if ($des == 2 || $des == 3){
                        $list_1[$i]['money'] = rand(100,2000)/100;
                        $list_1[$i]['des'] = "间接推荐人消费收益：￥".$list_1[$i]['money'];
                    }else if ($des == 4){
                        $arr=array(30,100,800);
                        $list_1[$i]['money'] = $arr[rand(0,2)];
                        $list_1[$i]['des'] = "推荐人升级VIP收益：￥".$list_1[$i]['money'];
                    }else if ($des == 5 || $des == 6) {
                        $list_1[$i]['money'] = rand(5,20);
                        $list_1[$i]['des'] = "注册赠送：￥".$list_1[$i]['money'];
                    }else if ($des == 7 || $des == 8) {
                        $list_1[$i]['money'] = 1;
                        $list_1[$i]['des'] = "推荐注册赠送：￥".$list_1[$i]['money'];
                    }else if ($des == 9 || $des == 10 || $des == 11) {
                        $list_1[$i]['money'] = rand(5,20);
                        $list_1[$i]['des'] = "绑定手机赠送：￥".$list_1[$i]['money'];
                    }
                    $list_1[$i]['time'] = rand($begin_time,$end_time);//排序用时间戳
                    $list_1[$i]['status'] = "+";
                    $list_1[$i]['add_time'] = date("Y/m/d",$list_1[$i]['time']);
                    $list_1[$i]['typename'] = "收益";
                    $all_balance = $all_balance + $list_1[$i]['money'];
                }elseif ($i>=20 && $i<=39) {
                    //商品
                    $list_1[$i]['money'] = rand(100,35000)/100;
                    $rand_points = rand(10,200);
                    $list_1[$i]['des'] = "用户支付：￥".$list_1[$i]['money']."；积分支付：￥".$rand_points."（".($rand_points*10)."积分）";
                    $list_1[$i]['time'] = rand($begin_time,$end_time);//排序用时间戳
                    $list_1[$i]['status'] = "-";
                    $list_1[$i]['add_time'] = date("Y/m/d",$list_1[$i]['time']);
                    $list_1[$i]['typename'] = "订单消费";
                    $all_points = $all_points - $list_1[$i]['money'];
                }elseif ($i>=40 && $i<=59) {
                    //抽奖
                    $list_1[$i]['money'] = rand(100,20000)/100;
                    $list_1[$i]['des'] = "每日抽奖中奖：￥".$list_1[$i]['money'];
                    $list_1[$i]['time'] = rand($begin_time,$end_time);//排序用时间戳
                    $list_1[$i]['status'] = "+";
                    $list_1[$i]['add_time'] = date("Y/m/d",$list_1[$i]['time']);
                    $list_1[$i]['typename'] = "抽奖";
                    $all_balance = $all_balance + $list_1[$i]['money'];
                }else {//提现
                    $list_1[$i]['money'] = (rand(1,4)*100);
                    $list_1[$i]['des'] = "申请提现扣除金额：￥".$list_1[$i]['money'];
                    $list_1[$i]['time'] = rand($begin_time,$end_time);//排序用时间戳
                    $list_1[$i]['status'] = "-";
                    $list_1[$i]['add_time'] = date("Y/m/d",$list_1[$i]['time']);
                    $list_1[$i]['typename'] = "提现";
                    $all_balance = $all_balance - $list_1[$i]['money'];
                }
            }else{
                //积分
                if ($i>=0 && $i<=19) {
                    //店铺
                    $des = rand(0,7);
                    if ($des == 0) {
                        $list_1[$i]['money'] = 500;
                        $list_1[$i]['des'] = "首次开店赠送+".$list_1[$i]['money'];
                    }else if ($des == 1 || $des == 2 || $des == 3 || $des == 4){
                        $list_1[$i]['money'] = 10;
                        $list_1[$i]['des'] = "注册赠送+".$list_1[$i]['money'];
                    }elseif ($des == 5 || $des == 6 || $des == 7){
                        $list_1[$i]['money'] = 10;
                        $list_1[$i]['des'] = "推荐注册赠送+".$list_1[$i]['money'];
                    }
                    $list_1[$i]['time'] = rand($begin_time,$end_time);//排序用时间戳
                    $list_1[$i]['status'] = "+";
                    $list_1[$i]['add_time'] = date("Y/m/d",$list_1[$i]['time']);
                    $list_1[$i]['typename'] = "获得";
                    $all_points = $all_points + $list_1[$i]['money'];
                }elseif ($i>=20 && $i<=39) {
                    //商品
                    $list_1[$i]['money'] = rand(1,100);
                    $list_1[$i]['des'] = "商城订单消费增加+".$list_1[$i]['money'];
                    $list_1[$i]['time'] = rand($begin_time,$end_time);//排序用时间戳
                    $list_1[$i]['status'] = "+";
                    $list_1[$i]['add_time'] = date("Y/m/d",$list_1[$i]['time']);
                    $list_1[$i]['typename'] = "获得";
                    $all_points = $all_points + $list_1[$i]['money'];
                }elseif ($i>=40 && $i<=59) {
                    //退款
                    $list_1[$i]['money'] = rand(1,100);
                    $list_1[$i]['des'] = "取消订单退积分+".$list_1[$i]['money'];
                    $list_1[$i]['time'] = rand($begin_time,$end_time);//排序用时间戳
                    $list_1[$i]['status'] = "+";
                    $list_1[$i]['add_time'] = date("Y/m/d",$list_1[$i]['time']);
                    $list_1[$i]['typename'] = "获得";
                    $all_points = $all_points + $list_1[$i]['money'];
                }elseif ($i>=60 && $i<=79) {
                    //其他
                    $des = rand(0,6);
                    if ($des == 0) {
                        $list_1[$i]['money'] = rand(1,100);
                        $list_1[$i]['des'] = "直接推荐人消费积分+".$list_1[$i]['money'];
                    }else if ($des == 1 || $des == 2){
                        $list_1[$i]['money'] = 10;
                        $list_1[$i]['des'] = "绑定手机赠送+".$list_1[$i]['money'];
                    }else{
                        $list_1[$i]['money'] = rand(1,50);
                        $list_1[$i]['des'] = "每日抽奖中+".$list_1[$i]['money'];
                    }
                    $list_1[$i]['time'] = rand($begin_time,$end_time);//排序用时间戳
                    $list_1[$i]['status'] = "+";
                    $list_1[$i]['add_time'] = date("Y/m/d",$list_1[$i]['time']);
                    $list_1[$i]['typename'] = "获得";
                    $all_points = $all_points + $list_1[$i]['money'];
                }
            }
        }

        $date['balance_all'] = round($all_balance,2) + $date['balance_all'] + 2000;
        $date['points'] = $all_points + $date['points'] + 8000;

        do{
            $date['points'] = 1250 + rand(0,1550);
            if ($date['balance_all']<500) {
                $date['balance_all'] = $date['balance_all'] + rand(300,700);
            }elseif ($date['balance_all']>2500) {
                $date['balance_all'] = $date['balance_all'] - rand(300,700);
            }
            if ($date['points'] < 500) {
                $date['points'] = $date['points'] + rand(800,1200);
            }elseif ($date['points'] > 3000) {
                $date['points'] = $date['points'] - rand(1,1000);
            }
        }while ($date['balance_all']<500 || $date['balance_all']>2500 || $date['points'] < 500 || $date['points'] > 3000);

        unset($rand_points);
        unset($rand_probability);
        unset($rand_money);

        for ($j=0; $j<count($list_1); $j++) { 
            for ($k=$j+1; $k<count($list_1); $k++) { 
                if ($list_1[$j]['time'] < $list_1[$k]['time']) {
                    // var_dump($list_1[$j]['des']);
                    $array = $list_1[$j];
                    $list_1[$j] = $list_1[$k];
                    $list_1[$k] = $array;
                }
            }
        }

        if(IS_AJAX){
            echo json_encode($list_1);die;
        }
        $this->list=$list_1;
        $this->data=$date;
        $this->webtitle="FG峰购";
        $this->display();
    }

    //提现管理
    public function cashapply(){
        //member_consume_record
         $where['member_id']=$this->uid;//2会员提现
         $where['type']=2;//1商家提现 2会员提现
         $field="*";
        $list =M("cash_apply")
        ->where($where)->field($field)->order('add_time desc')->select();
		//查询是否绑定微信提现
		$where['account_no']=0;//2会员提现
		$where['blank_id'] =0;
		$where['is_del'] =0;
		$wx_cash_c=M('bank_account')->where($where)->find();
        $this->assign('wx_cash',$wx_cash_c);

		//
		$c_where['member_id']=$this->uid;
		$c_where['is_del']=0;
		$c_where['blank_id']=array('neq','0');
		$count=M('bank_account')->where($c_where)->count();
		$this->assign('count',$count);
        // 0拒绝提现  1提现申请中 2财务打款中 3已提现
        $arr=array('0'=>'拒绝提现','1'=>'提现申请中','2'=>'财务打款中','3'=>'已提现');
        if(!empty($list)){
             foreach ($list as $key => $value) {
               $typename=$arr[$value['status']];
               $typename?$typename:'-';
               $list[$key]['statusname']=$typename;
            }
        }
        if(IS_AJAX){
            echo json_encode($list);die;
        }
        $this->list=$list;
        $data=$this->getMemberDetail();
        $this->data=$data;

        $bwhere['member_id']=$this->uid;
        $bwhere['is_del']=0;
        $bwhere['blank_id']=array('neq',0);
        $bank_list=M("bank_account")->where($bwhere)->order('is_default desc,add_time desc')->select();
		$bwhere['is_default']=1;
		$default_bank=M("bank_account")->where($bwhere)->find();
		$this->assign('default_bank',$default_bank);
        $this->bank_list=$bank_list;
         //提现最低额度
        $this->cash_apply_min_money=M('sys_param')->where(array('param_code'=>'cash_apply_min_money'))->getField('param_value');
        //提现申请比例
        $cash_fee_ratio = M('sys_param')->where(array('param_code'=>'cash_fee_ratio'))->getField('param_value');
        $this->cash_fee_ratio=($cash_fee_ratio*100).'%';
        $this->webtitle="FG峰购";
        $this->display();
    }
	public function cashapply_detail(){
	//member_consume_record
		$where['member_id']=$this->uid;//2会员提现
		$where['type']=2;//1商家提现 2会员提现
		$pagesize=10;
		$p=($p>=1)?$p:1;
		$start=($p-1)*$pagesize;
		$field="*";
		$list =M("cash_apply")
			->where($where)->field($field)//
			->limit($start,$pagesize)->order('add_time desc')->select();
		// 0拒绝提现  1提现申请中 2财务打款中 3已提现
		$arr=array('0'=>'拒绝提现','1'=>'提现申请中','2'=>'财务打款中','3'=>'已提现');
		if(!empty($list)){
		foreach ($list as $key => $value) {
		$typename=$arr[$value['status']];
		$typename?$typename:'-';
		$list[$key]['statusname']=$typename;
		}
		}

		$this->list=$list;

		$this->display();
	}

     //提现管理
    public function cashapply_add(){
        $memberDetail=$this->getMemberDetail();
        $data['status']=0;
        $bank_account_id=$_POST['bank_account_id'];
        $money=$_POST['money'];
        if(!check_number($money)){
             $data['error']="请填写正确的提现金额";
             echo json_encode($data);die;
        }
        if($money>$memberDetail['balance']){
             $data['error']="提现金额不能大于余额";
             echo json_encode($data);die;
        }
        //提现最低额度
		$number_money=M('sys_param')->where(array('param_code'=>'cash_apply_min_money'))->getField('param_value');
        if($money<$number_money){
             $data['error']="提现金额不能低于{$number_money}";
             echo json_encode($data);die;
        }
        if($memberDetail['balance']<$number_money){
            $data['error']="您的余额小于{$number_money}不能提现";
            echo json_encode($data);die;
        }
        if($memberDetail['balance']<$number_money){
            $data['error']="您的余额小于{$number_money}";
             echo json_encode($data);die;
        }
       if(empty($bank_account_id)){
             $data['error']="请选择提现帐号";
             echo json_encode($data);die;
        }


        $bawhere['member_id']=$this->uid;
        $bawhere['is_del']=0;
		//清除该用户的默认卡号
		$this_bank_id=M('bank_account')->where('id='.$bank_account_id)->getField('blank_id');
		if($this_bank_id != 0){
			M('bank_account')->where($bawhere)->setField('is_default','0');
		}
		$bawhere=array('id'=>$bank_account_id);
		//将该用户该卡号变为默认卡号
		M('bank_account')->where($bawhere)->setField('is_default','1');
        $blank=M('bank_account')->where($bawhere)->find();

        if(empty($blank)){
            $data['error']="提现帐号不存在请重新选择";
            echo json_encode($data);die;
        }

		//提现申请比例
		$cash_fee_ratio = M('sys_param')->where(array('param_code'=>'cash_fee_ratio'))->getField('param_value');
		$cash_fee = mb_number($money * $cash_fee_ratio);

        $a_data['blank_id']=$blank['blank_id'];
        $a_data['account_bank']=$blank['account_bank'];
        $a_data['account_no']=$blank['account_no'];
        $a_data['account_name']=$blank['account_name'];
        $a_data['account_addr']=$blank['account_addr'];
		$a_data['openid'] = session('user_open_id');
		$a_data['cash_sn'] = makeCashSn($this->uid);
        $a_data['type']=2;//1商家提现 2会员提现
        $a_data['add_time']=time();
        $a_data['member_id']=$this->uid;
        $a_data['money'] = $money;
        $a_data['cash_fee'] = $cash_fee;
        $a_data['bank_account_id'] =$bank_account_id;
        $cash_apply=M("cash_apply");
        $member_detail=M('member_detail');
        $cash_apply->startTrans();//开起事务
        $member_detail->startTrans();//开起事务
        ///扣用户的余额
        $m_data['balance']=$memberDetail['balance']-$money;

        $m_res = $member_detail->where(array('member_id'=>$this->uid))->save($m_data);
        $a_res = $cash_apply->add($a_data);

        if($a_res!==false && $m_res!==false){
            $cash_apply->commit();//提交事务
            $member_detail->commit();//提交事务
            ///记录消费日志
            $log_data['type']=3;//消费类型 1订单消费 2充值 3提现   4退款 5 收益 6认证消费
            $log_data['member_id']=$this->uid;
            $log_data['money']=$money;
            $log_data['status']=2;//状态 1收入 2支出
			if($blank['blank_id'] == 0){
				$log_data['des']="申请微信提现扣除金额￥".$money;//消费介绍
			}else{
				$log_data['des']="申请银行卡提现扣除金额￥".$money;//消费介绍
			}
            $log_data['add_time']=time();//消费时间
            $this->set_member_consume_record($log_data);

            $data['status']=1;
            echo json_encode($data);die;
        }else{
            $cash_apply->rollback();//回滚事务
            $member_detail->rollback();//回滚事务
            $data['error']="操作失败,请稍后再试";
            echo json_encode($data);die;
        }
        die;
    }
	
    //提现帐号
    public function bank_account(){

        $where['member_id']=$this->uid;
        $where['is_del']=0;
        $where['blank_id'] =array('neq','0');
        $list=M('bank_account')->where($where)->select();
        $this->list=$list;
        //bank_account
        $this->webtitle="FG峰购";
        $this->display();
    }
    //提现帐号
    public function bank_account_add(){
         if(IS_AJAX){
            $data['status']=0;
            $c_where['member_id']=$this->uid;
            $c_where['is_del']=0;
             $c_where['blank_id']=array('neq','0');
            $count=M('bank_account')->where($c_where)->count();
            if($count>=5){
                $data['error']="提现银行帐号最多添加5个";
                echo json_encode($data);die;
            }
            $blank_id=$_POST['blank_id'];
            $account_no=$_POST['account_no'];
            $account_name=$_POST['account_name'];
            $account_addr=$_POST['account_addr'];
          //  $account_IDcard=$_POST['ID_card'];
             if(empty($account_name)){
                 $data['error']="请填写开户名";
                 echo json_encode($data);die;
             }
/*             if(empty($account_IDcard)){
                 $data['error']="请填写身份证号码";
                 echo json_encode($data);die;
             }*/
            if(empty($blank_id)){
                $data['error']="请选择开户行";
                echo json_encode($_POST);die;
            }
            if(empty($account_no)){
               $data['error']="请填写开户帐号";
                echo json_encode($data);die;
            }

            if(empty($account_addr)){
               $data['error']="请填写开户地址";
                echo json_encode($data);die;
            }
            $blank=M('blank')->where(array('blank_id'=>$blank_id))->find();
			 if($count==0){
				 $a_data['is_default']=1;
			 }
            $a_data['blank_id']=$blank_id;
            //$a_data['ID_card']=$account_IDcard;
            $a_data['account_bank']=$blank['blank_name'];
            $a_data['account_no']=$account_no;
            $a_data['account_name']=$account_name;
            $a_data['account_addr']=$account_addr;
            if(!empty($_POST["id"])){
                $where['member_id']=$this->uid;
                $where['id']=$_POST["id"];
                $res = M("bank_account")->where($where)->save($a_data);
            }else{
                $a_data['add_time']=time();
                $a_data['member_id']=$this->uid;
                $res = M("bank_account")->add($a_data);
            }
            if($res!==false){
                if($_POST['is_default']){
                    if($_POST['id']){
                        $notwhere['id']=array('neq',$_POST['id']);
                    }else{
                        $notwhere['id']=array('neq',$res);
                    }
                    $notwhere['member_id']=$this->uid;
                    $notwhere['is_del']=0;
                    //取消之前的 默认提现帐号
                    M("bank_account")->where($notwhere)->save(array('is_default'=>0));
                 }
                 $data['status']=1;
                 echo json_encode($data);die;
            }else{
                $data['error']="操作失败，请稍后再试。";
                echo json_encode($data);die;
            }
            die;
         }

        $this->list=M('blank')->where(array('status'=>1,'is_del'=>0))->select();

        if($_GET['id']){
            $where['id']=$_GET['id'];
            $where['member_id']=$this->uid;
            $where['is_del']=0;
            $data=M('bank_account')->where($where)->find();
            $this->data=$data;
        } 
        $type=$_GET['type'];
         $url=U('Wap/Member/bank_account');
        if($type=="cashapply"){
           $url=U('Wap/Member/cashapply',array('type'=>1));
        }
        $this->return_url=$url;
        //bank_account
        $this->webtitle="FG峰购";
        $this->display();
    }

    //绑定微信提现账号
    public function wx_account_add($account_name=null){
        if(IS_AJAX){
            $data['status']=0;
            $account_name=$_POST['real_name']?$_POST['real_name']:$account_name;
            if(empty($account_name)){
                $data['error']="请填写开户名";
                echo json_encode($data);die;
            }

            $a_data['blank_id']=0;
			$a_data['account_no']='0';
			//定义微信提现 银行id和卡号都为0
            $a_data['account_bank']='微信提现';
            $a_data['account_name']=$account_name;
            $a_data['is_del'] = 0;
			$a_data['is_default'] = 0;
            //查询是否绑定过微信
            $c_where['member_id']=$this->uid;
            $c_where['blank_id']=0;
            $wx_account=M('bank_account')->where($c_where)->find();
            if(!empty($_POST['id']) || !empty($wx_account)){
                $where['member_id']=$this->uid;
                $where['id']=$_POST['id']?$_POST['id']:$wx_account['id'];
                $res = M("bank_account")->where($where)->save($a_data);
            }else{
                $a_data['add_time']=time();
                $a_data['member_id']=$this->uid;
                $res = M("bank_account")->add($a_data);
            }
            if($res!==false){
                $data['status']=1;
                echo json_encode($data);die;
            }else{
                $data['error']="操作失败，请稍后再试。";
                echo json_encode($data);die;
            }
        }
        if($_GET['id']){
            $where['id']=$_GET['id'];
            $where['member_id']=$this->uid;
            $where['is_del']=0;
            $data=M('bank_account')->where($where)->find();
            $this->data=$data;
        }
        $this->webtitle="FG峰购";
        $this->display();
    }
       ///提现帐号 删除
    public  function bank_account_del(){
        $data['status']=0;
        $id=$_POST['id'];
        if(empty($id)){
            $data['error']="操作失败，请稍后再试。";
            echo json_encode($data);die;
        }
        $where['member_id']=$this->uid;
        $where['id']=$id;
        $res=M("bank_account")->where($where)->save(array('is_del'=>1));
        if($res!==false){    
             $data['status']=1;
             echo json_encode($data);die;
        }else{
             $data['error']="操作失败，请稍后再试。";
             echo json_encode($data);die;
        }
    }
    ///会员升级
    public function  upgrade(){
       
       $this->upgrade=M('sys_param')->where(array('param_code'=>'upgrade'))->getField('param_value'); 
       $this->display();
    }
    //绑定微信
    public function bind_wx(){
        if($_POST){
            //获取用户Openid
            $data['openid'] = session('user_open_id');//
            $re=M('member')->where(array('id'=>$this->uid))->save($data);
            if($re){
                $data['status']=1;
            }else{
                $data['error']='操作失败,请稍后重试';
            }
            echo json_encode($data);die;
        }
        $this->display();
    }
    //用户vip积分信息
    public function member_vip_integral(){
        //member_consume_record
        $where['member_id']=$this->uid;
        $p=$_REQUEST['p'];
        $pagesize=10;
        $p=!empty($p)?$p:1;
        $start=($p-1)*$pagesize;
        $field="*";
        $field="integral as money,status,type,des,add_time";
        $model=M('member_vip_integral');
        $arr=array('1'=>'增加','2'=>'减少');
        $list=$model->where($where)//
        ->field($field)->limit($start,$pagesize)->order('add_time desc')->select();
        //消费类型 1订单消费 2充值 3提现   4退款 5 收益 6认证消费

        if(!empty($list)){
            foreach ($list as $key => $value) {
                $typename=$arr[$value['status']];
                $typename?$typename:'-';
                $list[$key]['typename']=$typename;
                $list[$key]['status']=$value['status']==1?'+':'-';
                $list[$key]['add_time']=date("Y/m/d",$value['add_time']);
            }
        }
        if(IS_AJAX){
            echo json_encode($list);die;
        }
        $this->count=$model->where($where)->count();//统计
        $this->list=$list;


        $memberinfo=$this->getMemberInfo();
        $memberinfo_de=$this->getMemberDetail();
        if($memberinfo['member_vip_type']==0){
            $vip_name='普通会员';
            $vip_i=0;
        }else{
            $vip=getVipRank($memberinfo_de['vip_i_rank'],$memberinfo_de['vip_i_now']);
            $vip_name=$vip['vip_name'];
            $vip_i=$vip['now_up_i'];
        }
        $this->vip_name=$vip_name;
        $this->vip_i=$vip_i;
      /*  $data['balance']=sprintf("%.0f",substr(sprintf("%.3f", $data['balance']), 0, -3));
        $data['balance_give']=sprintf("%.0f",substr(sprintf("%.3f", $data['balance_give']), 0, -3));
        $data['balance_all']+=$data['balance']+$data['balance_give'];
        $data['balance_all']=sprintf("%.0f",substr(sprintf("%.3f", $data['balance_all']), 0, -3));*/
        $this->member_vip_fuwu_switch=M('sys_param')->where(array('param_code'=>'member_vip_fuwu_switch'))->getField('param_value');
        $this->data=$memberinfo_de;
        $this->webtitle="FG峰购";
        $this->display();
    }
    //股权分配
   public function  member_vip_fuwu(){
         $this->fuwu=M('sys_param')->where(array('param_code'=>'member_vip_fuwu'))->getField('param_value');
         $this->display();
   }
    //证书页面
    public function certificate(){
        $member_info=$this->getMemberInfo();
        $this->data=$member_info;
        $date=date('Y-m-d',$member_info['vip_time']);
        $this->vip_name=M('member_level')->where('levelId='.$member_info['member_vip_type'])->getField('levelName');
        $this->date=$date;
        $this->display();
    }
    //设置手机
    public function set_mobile(){
        if(IS_AJAX) {
            $data['status'] = 0;
            //检测 手机验证码
            $send_return = $this->check_send_return();
            if ($send_return['error']) {
                $data['error'] = $send_return['error'];
                echo json_encode($data);
                die;
            }
            $mobile = $_REQUEST['mobile'];
            $count = M("member")->where(array('mobile' => $mobile))->count();
            if (!empty($count)) {
                $data['error'] = "=该手机号码已被绑定";
                echo json_encode($data);
                die;
            }
            $res= M("member")->where(array('id'=>$this->uid))->setField(array('mobile'=>$mobile));
            if($res!==false){
                $data['status']=1;
            }else{
                $data['error']="修改失败";
            }
            echo json_encode($data);die;
        }
        $memberinfo=$this->getMemberInfo();
        $this->member=$memberinfo;
        $this->display();
    }
	
    public function store_set(){
		$member_info=$this->getMemberInfo();
		$this->member=$member_info;
        $this->display();
	}
	
	public function member_name_set(){
		$data = array();
		$data['status'] = 0;
		$member_name=$_REQUEST['member_name'];
		if(!$member_name){
			$data['error'] = "请输入昵称";
			echo json_encode($data);
			die;
		}
		$res_m = M('member')->where(array('id'=>$this->uid))->save(array('member_name'=>$member_name));
		if($res_m !== false){
			$data['status'] = 1;
			// $data['status'] = $member_name.'___'.$this->uid;
			echo json_encode($data);exit;
		}else{
			$data['error'] = "修改失败，请稍后再试。";
			echo json_encode($data);exit;
		}
	}
	
    public function slide_image(){
        if(IS_AJAX){
			$data['status']=0;
			$img_url=$_REQUEST['slide_image'];
			if(empty($img_url)){
				$data['error']="请上传图片";
				echo json_encode($data);die;
			}
			$img_data['member_id'] = $this-> uid;
			$img_data['img_url'] = $img_url;
			$img_data['add_time'] = time();
			$img_data['is_check'] = 0;
			$slide_info=M('member_gallery')->where('member_id='.$this->uid)->find();
			$ob=M('member_gallery');
			if(empty($slide_info)){
				$res=$ob->add($img_data);
			}else{
				$ress=unlink('.'.$slide_info['img_url']);
				$res=$ob->where('member_id='.$this->uid)->save($img_data);
			}
			if($res!==false){
				$data['status']=1;
				echo json_encode($data);die;
			}
			$data['error']='上传失败';
			echo json_encode($data);
			die;
        }
        if(empty($_GET['img'])){
        $memberinfo=$this->getMemberInfo();
        $this->data=$memberinfo;
        }
        $this->display();
    }

    /**
     * @return string
     */
    public function store_order()
    {

        if (isset($_REQUEST['order_state'])) {
            if ($_REQUEST['order_state'] != 'all') {
                if ($_REQUEST['order_state'] == 3) {//已完成
                    $where['pay_status'] = 2;// 支付状态；0，未付款；1，付款中 ；2，已付款
                    // $where[''] = 2;//商品配送情况，0，未发货； 1，已发货；2，已收货；3，备货中
                    //退货的也在已完成
                    $where['_string'] = "shipping_status=2 or (shipping_status=1 and order_status in ('2','3','4'))";//订单状态。0，未确认；1，已确 认；2，已取消；3，无效；4，退货；
                } else if ($_REQUEST['order_state'] == 1) {
                    $where['pay_status'] = 2;// 支付状态；0，未付款；1，付款中 ；2，已付款
                    $where['shipping_status'] = array('in', '0,3');//商品配送情况，0，未发货； 1，已发货；2，已收货；3，备货中
                    $where['order_status'] = array('in', '1,0');//订单状态。0，未确认；1，已确 认；2，已取消；3，无效；4，退货；
                    //$where['shipping_status'] =1;//商品配送情况，0，未发货； 1，已发货；2，已收货；3，备货中
                } else if ($_REQUEST['order_state'] == 2) {
                    $where['pay_status'] = 2;// 支付状态；0，未付款；1，付款中 ；2，已付款
                    $where['shipping_status'] = 1;//商品配送情况，0，未发货； 1，已发货；2，已收货；3，备货中
                    $where['order_status'] = 1;//订单状态。0，未确认；1，已确 认；2，已取消；3，无效；4，退货；
                    //$where['shipping_status'] =1;//商品配送情况，0，未发货； 1，已发货；2，已收货；3，备货中
                } else {
                    $where['pay_status'] = $_REQUEST['order_state'];
                }
            }else{
				$where['pay_status'] =array('neq','0');
			}
        } else {
            $_GET['order_state'] = 'all';
			$where['pay_status'] =array('neq','0');
        }
        $where['user_del'] = 0;
        $p = $_REQUEST['p'];
        $pagesize = 10;
        $p = !empty($p) ? $p : 1;
        $start = ($p - 1) * $pagesize;
/*        $field = "order_id,order_sn,order_status,shipping_status,pay_status,goods_amount,order_amount,shipping_fee,add_time,consignee,mobile,address";
        $order_model = M('g_order_info');
        $list = $order_model->where($where)
        ->field($field)->limit($start, $pagesize)->order('order_id desc')->select();*/
	$order_model = M('g_order_info');
	$field = "o.order_id,o.order_sn,o.order_status,o.shipping_status,o.pay_status,o.goods_amount,o.order_amount,o.shipping_fee,o.add_time,o.consignee,o.share_uid,o.mobile,o.address,o.invoice_no,o.express_code,m.recommend_code,m.member_name";
		$list=$order_model->field($field)->alias('o')->where($where)->join('db_member m on m.id=o.user_id')
			->having('o.share_uid="'.$this->uid.'"')->limit($start, $pagesize)->order('order_id desc')->select();

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
            $order_goods = M('g_order_goods')->field('rec_id,goods_name,goods_image,goods_price,goods_id,yes_refund,is_refund,is_upgrade,goods_number')
                ->where(array('order_id' => $value['order_id']))->select();

            $order_show = $this->get_order_status($value);
            $list[$key]['order_state'] = $order_show['code'];
            $list[$key]['order_state_name'] = $order_show['name'];
            $list[$key]['add_time'] = date("Y-m-d H:i:s", $value['add_time']);
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

            $countgoods = 0;
            if ($order_show['code'] == 'delivered') {
                foreach ($order_goods as $keys => $val) {
                    if ($val['is_refund']) {//可退货
                        if (empty($val['yes_refund'])) {//未退货
                            $list[$key]['is_refund'] = 1;///该订单是否可退货
                            $countgoods++;
                            $list[$key]['rec_id'] = $val['rec_id'];///该订单可退货产品
                            if ($countgoods > 1) {
                                $list[$key]['rec_id'] = 0;///包含2个产品 取消快捷连接
                            }
                        }
                    }
                }
                $f_where = array();
                $f_where['order_id'] = $value['order_id'];
                $f_where['refund_status'] = array('neq', '10');
                $list[$key]['ref_all'] = $g_order_goods_refund->where($f_where)->count();
            }

        }

        if (IS_AJAX) {
            echo json_encode($list);
            die;
        }
        $this->list = $list;

        $where = array();
        $where['user_del'] = 0;
		$where['pay_status']=array('neq',0);
		$where['recommend_code']=$this->member_card;
		$this->count = $order_model->alias('o')->where($where)->join('db_member m on m.id=o.user_id')->count();

        $where = array();
        $where['user_del'] = 0;
        $where['pay_status'] = '2';//待发货
        $where['shipping_status'] = array('in', '0,3');
        $where['order_status'] = array('in', '1,0');//订单状态。0，未确认；1，已确 认；2，已取消；3，无效；4，退货；
		$where['recommend_code']=$this->member_card;
		$this->count10 = $order_model->alias('o')->where($where)->join('db_member m on m.id=o.user_id')->count();
        $where = array();
        $where['user_del'] = 0;
        $where['pay_status'] = 2;// 支付状态；0，未付款；1，付款中 ；2，已付款
        $where['shipping_status'] = 1;//商品配送情况，0，未发货； 1，已发货；2，已收货；3，备货中
        $where['order_status'] = 1;//订单状态。0，未确认；1，已确 认；2，已取消；3，无效；4，退货；
		$where['recommend_code']=$this->member_card;
		$this->count20 = $order_model->alias('o')->where($where)->join('db_member m on m.id=o.user_id')->count();
        $where = array();
        $where['user_del'] = 0;
        $where['pay_status'] = '2';//已完成
        //$where['shipping_status'] = '2';
        $where['_string'] = "shipping_status=2 or (shipping_status=1 and order_status in ('2','3','4'))";//订单状态。0，未确认；1，已确 认；2，已取消；3，无效；4，退货；
		$where['recommend_code']=$this->member_card;
		$this->count30 = $order_model->alias('o')->where($where)->join('db_member m on m.id=o.user_id')->count();
        $this->webtitle = "FG峰购";
        $this->display();

    }
	public function authentication(){
		if(IS_AJAX){
			$real_name=$_POST['real_name'];
			//$id_number=$_POST['ID_card'];
            $id_card_front=$_POST['id_card_front'];
            $id_card_back=$_POST['id_card_back'];
			$save['member_id']=$this->uid;
            $save['id_card_front']=$id_card_front;
            $save['id_card_back']=$id_card_back;
			$save['add_time']=time();
			$save['real_name']=$real_name;
			//$save['id_number']=$id_number;
            //$save['is_check']=0;暂时改成直接通过审核
			$save['is_check']=1;
			$bo=M('member_id_card');
			$having=$bo->where('member_id='.$this->uid)->find();
            M('member_detail')->where('member_id='.$this->uid)->setField('user_name',$real_name);
			if(!empty($having)){
				$res=M('member_id_card')->where('member_id='.$this->uid)->save($save);
			}else{
				$res=M('member_id_card')->add($save);
			}
			if($res){
				//姓名修改成功 修改绑定微信的姓名
				$this->wx_account_add($real_name);
				$data['status']=1;
			}else{
				$data['error'] = '提交失败';
			};
			echo json_encode($data);die;
		}
		$member_id_card=M('member_id_card')->where('member_id='.$this->uid)->find();
        $phone=array('front'=>1,'back'=>1);
        if(empty($member_id_card['id_card_front'])){
            $phone['front']=0;
        }
        if(empty($member_id_card['id_card_back'])){
            $phone['back']=0;
        }
        $this->assign('phone',$phone);
		$this->data=$member_id_card;
		$this->display();
	}

	public function set_id_card()
	{	$type=$_GET['type'];
		if(IS_AJAX){
			$data['status']=0;
			$id_card=$_REQUEST['id_card'];
			if(empty($id_card)){
				$data['error']="请上传图片";
				echo json_encode($data);die;
			}
			$save['id_card_'.$type]=$id_card;
			$save['member_id']=$this->uid;
			$id_card_id=M('member_id_card')->where('member_id='.$this->uid)->getField('id');
			if(empty($id_card_id)){
				$res = M('member_id_card')->add($save);
			}else{
				$res = M('member_id_card')->where('id='.$id_card_id)->save($save);
			}
			if($res!==false){
				$data['status']=1;
				echo json_encode($data);die;
			}
			$data['error']="上传身份证照片失败";
			echo json_encode($data);
			die;
		}
        $member_id_card=M('member_id_card')->where('member_id='.$this->uid)->find();
        $this->data=$member_id_card;
        $this->display();

		if($type){
			$type_array=array('front'=>'正面照','back'=>'背面照');
			$types['type']=$type;
			$types['type_name']=$type_array[$type];
			$this->data=$types;
		}
		$this->webtitle="FG峰购";
		$this->display();
	}
	public function show_shop_qrcode(){
		$qrcode_img=$this->shop_qrcode();
		$this->assign('qrcode_img',$qrcode_img);
		$this->display();
	}
	
	public function member_ecard_style1(){
		$res = array('status'=>1);
		$res['msg'] = '';
		$card_bg = $_REQUEST['card_bg'] ? $_REQUEST['card_bg'] : 1;
		$card_bg_array = array(1=>'bg1_01.jpg', 2=>'bg1_02.jpg', 3=>'bg1_03.jpg', 4=>'bg1_04.jpg', 5=>'bg1_05.jpg');
		$this->card_bg_img = isset($card_bg_array[$card_bg]) ? 'e_card/'.$card_bg_array[$card_bg] : 'e_card/bg1_01.jpg';
		$memberinfo = $this->getMemberInfo();
		// if($memberinfo['member_vip_type'] <= 0){
			// $res['status'] = 0;
			// $res['msg'] = '哎呀！走错了！';
			// $this->display();
		// }
		$this->memberinfo = $memberinfo;
		$this->next_card_bg = $card_bg > count($card_bg_array) ? 1 : ($card_bg + 1);
		$qrcode_img = $this->shop_qrcode();
		$this->assign('qrcode_img', $qrcode_img);
		$this->display();
	}
	
	public function member_ecard_style2(){
		$res = array('status'=>1);
		$res['msg'] = '';
		$card_bg = $_REQUEST['card_bg'] ? $_REQUEST['card_bg'] : 1;
		$card_bg_array = array(1=>'bg1_01.jpg', 2=>'bg1_02.jpg', 3=>'bg1_03.jpg', 4=>'bg1_04.jpg', 5=>'bg1_05.jpg');
		$this->card_bg_img = isset($card_bg_array[$card_bg]) ? 'e_card/'.$card_bg_array[$card_bg] : 'e_card/bg1_01.jpg';
		$memberinfo = $this->getMemberInfo();
		// if($memberinfo['member_vip_type'] <= 0){
			// $res['status'] = 0;
			// $res['msg'] = '哎呀！走错了！';
			// $this->display();
		// }
		$this->memberinfo = $memberinfo;
		$qrcode_img = $this->shop_qrcode();
		$this->assign('qrcode_img', $qrcode_img);
		$this->display();
	}
}