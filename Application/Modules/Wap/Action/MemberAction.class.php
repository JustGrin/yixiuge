<?php
// 用户控制器
class MemberAction extends UserAction {
	//魔术方法
	public function __construct(){
		parent::__construct();
	}
	
	//个人中心
    public function index(){
        $memberinfo=$this->getMemberInfo();
        $this->data=$memberinfo;
        $memberinfo_de=$this->getMemberDetail();
        //获取收藏商品总数
        $where_collect['coll.user_id']=$this->uid;
        $where_collect['is_attention']=1;
        $count_collect=M()->table('db_g_collect_goods coll')//
        ->join('db_g_goods goods on goods.goods_id=coll.goods_id')//
        ->where($where_collect)->count();
        $memberinfo_de['count_collect']=$count_collect;
        $this->MemberDetail=$memberinfo_de;

        $this->member_count($memberinfo['member_card']);

    	$this->webtitle="一休哥";
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
        $this->webtitle="一休哥";
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
            $data['error']="修改背景失败";
            echo json_encode($data);
            die;
        }
        if(empty($_GET['img'])){
            $memberinfo=$this->getMemberInfo();
            $this->data=$memberinfo;
        }
        $this->webtitle="一休哥";
        $this->display();
    }
    //基本信息
    public function basic(){
        $memberinfo=$this->getMemberInfo();
        $this->member=$memberinfo;
        $member=$this->getMemberDetail();
        $this->data=$member;
        $verification = M('member_verification')->where('member_id='.$this->uid)->find();

        $this->verification = $verification ? $verification : array();//用户信息
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

        $this->webtitle="一休哥";
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
    	$this->webtitle="一休哥";
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
        $this->webtitle="一休哥";
        $this->display();
    }
    //我的钱包
    public function wallet(){
        //member_consume_record
         $where['member_id']=$this->uid;
         $p=$_REQUEST['p'];
         $pagesize=10;
         $p=!empty($p)?$p:1;
         $start=($p-1)*$pagesize;
        $where =array();
        isset($_REQUEST['type_order']) ? $where['status'] = $_REQUEST['type_order'] : $_REQUEST['type_order'] = 'order_all';


         //积分
            $model=M('member_integral');
             $arr=array('0'=>'消费','1'=>'获得','2'=>'消费','4'=>'退款', '5'=>'收益');

        $list=$model->where($where)->limit($start,$pagesize)->order('add_time desc')->select();
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
       $this->is_ajax = IS_AJAX;
        $this->count=$model->where($where)->count();//统计
        $this->list=$list;
        $data=$this->getMemberDetail();
        // $data['balance']=sprintf("%.0f",substr(sprintf("%.3f", $data['balance']), 0, -3));
        // $data['balance_give']=sprintf("%.0f",substr(sprintf("%.3f", $data['balance_give']), 0, -3));
        $data['balance_all']+=$data['balance']+$data['balance_give'];
        // $data['balance_all']=sprintf("%.0f",substr(sprintf("%.3f", $data['balance_all']), 0, -3));
        $this->data=$data;
        $this->webtitle="一休哥";
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
        $this->webtitle = "一休哥";
        $this->display();

    }
    
	public function show_shop_qrcode(){
		$qrcode_img=$this->shop_qrcode();
		$this->assign('qrcode_img',$qrcode_img);
		$this->display();
	}

}