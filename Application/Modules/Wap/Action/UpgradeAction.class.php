<?php

// 充值  升级会员
class UpgradeAction extends UserAction
{
    protected $share_info;
    //魔术方法
    public function __construct()
    {
        parent::__construct();
        $memberinfo=$this->getMemberInfo();
        if($memberinfo['member_vip_type'] == 0){
            $code=session('share');
            $code=$code ? $code :$memberinfo['recommend_code'];
            $share_vip_type=M('member')->where('member_card="'.$code.'"')->getField('member_vip_type');
            if($share_vip_type==0){
                $code=$memberinfo['recommend_code'];
            }
        }else{
            $code=$memberinfo['recommend_code'];
        }
        $share_info=M('member')->where('member_card="'.$code.'"')->find();
        $this->share_info = $share_info;
        $this->assign('share_info',$share_info);
    }

    //订单列表
    public function index()
    {
		redirect(U('wap/upgrade/shopkeeper_area'));
        $this->webtitle = "FG峰购";
        $this->display();
    }

    //店主专区
    public function shopkeeper_area(){
        $member_info = $this->getMemberInfo();
        $member_detail = $this->getMemberDetail();
        $this->data = $member_info ;
        $p = $_REQUEST['p'];
        $pagesize = 6;
        $p = !empty($p) ? $p : 1;
        $start = ($p - 1) * $pagesize;
        $where['is_delete'] = 0;//商品是否已经删除，0，否；1，已 删除
        $where['is_auditing'] = 1;//是否通过审核 1是0 否
        $where['is_upgrade']=1;//剔除非升级产品
        $where['is_on_sale'] = 1;//剔除下架 1上架 0 下架
        $field = 'goods_id,goods_name,shop_price,goods_img,feeling,subtitle';
        $count=M('g_goods')->where($where)->count();
        $list = M('g_goods')->field($field)->where($where)->limit($start, $pagesize)->select();

//        var_dump($list);die;
        if (IS_AJAX) {
            echo json_encode($list);
            die;
        }
//        var_dump($list);die;
        $this->count=$count;
        $this->list = $list;
        $is_binding =0;
        if($member_detail['provinceid'] && $member_detail['cityid'] && $member_detail['areaid'] && $member_info['mobile'] && $member_detail['user_name'] && $member_detail['addr']){
            $is_binding = 1; //如果以上信息都有，就不需要绑定
        }
        $levelinfo=M('member_level')->select();
        $this->assign("is_binding",$is_binding);
        $this->assign('levelinfo',$levelinfo);

        $this->display();
    }
    //绑定会员信息
    public function member_info_full(){
        $memberinfo=$this->getMemberInfo();
        $this->member=$memberinfo;
        $member_detail=$this->getMemberDetail();
        $this->data=$member_detail;
        //地区信息设置之后不允许修改
        if($member_detail['provinceid'] && $member_detail['cityid'] && $member_detail['areaid']){
            //查询会员身份
            $city['province'] = M('city_province')
                ->where('provinceid='.$member_detail['provinceid'])->find();
            //查询会员城市
            $city['city'] = M('city_city')
                ->where('cityid='.$member_detail['cityid'])->find();
            //查询会员区县
            $city['area']=M('city_area')
                ->where('areaid='.$member_detail['areaid'])->find();
            $this->city=$city;
        }
        $this->city_group = get_city_group();
        $this->display();
    }
    //执行绑定
    public function binding(){
        $data['status']=0;
        $code=$_POST['binding_code'];
        $mem_password=$_POST['binding_mem_password'];
        $rep_password=$_POST['binding_rep_password'];
        $user_name = $_POST['user_name'];
        $mobile=$_POST['binding_mobile'];
        $provinceid		= $_POST['provinceid'];
        $cityid			= $_POST['cityid'];
        $areaid			= $_POST['areaid'];
        $addr			= $_POST['addr'];
        $area_info		= $_POST['area_info'];
        $memberDetail = $this->getMemberDetail();
        //检测 手机验证码
        $_REQUEST['mobile']=$mobile;
        $_REQUEST['mobile_code']=$_POST['binding_mobile_code'];
        $send_return=$this->check_send_return();
        if($send_return['error']){
            $data['error']=$send_return['error'];
            echo json_encode($data);die;
        }
        $openid=session('user_open_id');
        $member_info_now= M("member")->where(array('openid'=>$openid))->find();
        /*      if(empty($member_info_now)){
				  $data['error']='用户信息错误';
				  echo json_encode($data);die;
			  }*/
        if(empty($code)){
            $code = $member_info_now['member_card'] ;
            if(empty($code)) {
                $share = session('share');
                $code = $share ? $share : INDEX_CARD;
            }
        }
        $uid=$this->uid;

        //注册

        //注册验证 密码 是否为空 两次密码是否一致
        if(empty($mem_password)){
            $data['error']="密码不能为空";
            echo json_encode($data);die;
        }
        if(empty($rep_password)){
            $data['error']="重复密码不能为空";
            echo json_encode($data);die;
        }
        if($rep_password != $mem_password){
            $data['error']="两次密码不一致";
            echo json_encode($data);die;
        }
        ///手机 是否被注册
        if(empty($mobile)){
            $data['error']="手机号码不存在";
            echo $data;
        }

        $mobile_user= M("member")->where(array('mobile'=>$mobile))->find();
        if($mobile_user && $mobile_user['id'] !== $uid){
            $data['error']="手机号码已被绑定";
            echo $data;
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
        if(empty($user_name)){
            $data['error']="请填写用户的姓名";
            echo json_encode($data);die;
        }
        if(empty($code)){
            $code=INDEX_CARD;
        }
        $data_code=$this->getMemberCode($code);
        if(!empty($data_code)){
            $m_data['recommend_code']=$data_code['recommend_code'];//推荐码 (推荐人的推荐码 )
            $m_data['indirect_recommend_code']=$data_code['indirect_recommend_code'];//间接推荐人推荐码 (推荐人的推荐人的推荐码 )
            $m_data['indirect2_recommend_code']=$data_code['indirect2_recommend_code'];//间接2层推荐人推荐码 (推荐人的推荐人的推荐码 )
        }
        //绑定用户 地址信息
        $m_d_data['provinceid'] = isset($provinceid) && $provinceid > 0 ? $provinceid : ($memberDetail['provinceid'] ? $memberDetail['provinceid'] : 0);
        $m_d_data['cityid'] = isset($cityid) && $cityid > 0 ? $cityid : ($memberDetail['cityid'] ? $memberDetail['cityid'] : 0);
        $m_d_data['areaid'] = isset($areaid) && $areaid > 0 ? $areaid : ($memberDetail['areaid'] ? $memberDetail['areaid'] : 0);
        $m_d_data['addr'] = isset($addr) && $addr ? $addr : ($memberDetail['addr'] ? $memberDetail['addr'] : '');
        $m_d_data['user_name'] = $user_name;
        $count=M('member_detail')->where(array('member_id'=>$uid))->count();
        if($count){
            $res_m_d=M('member_detail')->where(array('member_id'=>$uid))->save($m_d_data);
        }else{
            $m_d_data['member_id']=$uid;
            $res_m_d=M('member_detail')->add($m_d_data);
        }
        if($res_m_d===false){
            $data['msg']="修改失败，请稍后再试。";
            echo json_encode($data);die;
        }

        //注册
        $m_data['mobile']=$mobile;
        $m_data['password']=md5($mem_password);

        $recommend_id=M('member')->where('member_card='.$code)->getField('id');
        $recommend_detail=M('member_detail')->where('member_id='.$recommend_id)->find();
        $m_data_detail['relevel']=$recommend_detail['relevel']+1;
        $m_data_detail['repath']=$recommend_detail['repath'].','.$uid;
        $member_model=M('member');
        $member_detail_model=M('member_detail');

        $member_model->startTrans();//开起事务
        $member_detail_model->startTrans();//开起事务

        $add=$member_model->where(array('id'=>$uid))->save($m_data);

        $member_detail_add=$member_detail_model->where(array('member_id'=>$uid))->save($m_data_detail);

        if($add!==false && $member_detail_add!==false){

            //绑定成功
            $data['status']=1;
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
            $member_detail_model->commit();//提交事务

            //添加收货地址
            $address_num = M('g_user_address')->where(array('user_id'=>$uid))->count();
            if($address_num < 6){
                M('g_user_address')->where(array('user_id'=>$uid))->save(array('is_default'=>0));//所有地址都去除默认

                $province_name=M('city_province')->where(array('provinceid'=>$m_d_data['provinceid']))->getField('province');
                $city_name=M('city_city')->where(array('cityid'=>$m_d_data['cityid']))->getField('city');
                $area_name=M('city_area')->where(array('areaid'=>$m_d_data['areaid']))->getField('area');
                $address_name=$province_name.$city_name.$area_name;
                $save_data['address_name']=$address_name;
                $save_data['consignee']=$user_name;
                $save_data['province']=$m_d_data['provinceid'];
                $save_data['city']=$m_d_data['cityid'];
                $save_data['area']= $m_d_data['areaid'];
                $save_data['address']=$m_d_data['addr'];
                $save_data['mobile']=$mobile;
                $save_data['user_id']= $uid;
                $save_data['is_default']= 1;//改地址为默认地址
                $res=M('g_user_address')->add($save_data);
            }
        }else{
            $member_model->rollback();//回滚事务
            $member_detail_model->rollback();//回滚事务
            $data['error']="系统错误请稍候再试";
        }
        echo json_encode($data);
    }


    //订单信息
    public function upgrade_detail()
    {
        $memberinfo_de = $this->getMemberDetail();
        $this->assign("data", $memberinfo_de);
        $this->display();
    }
    //订单详情页

    ///计算 产品价格
    public function cheak_goods()
    {
        $return_data['status'] = 0;
        $goods_attr_id = I('goods_attr_id');
        $goods_number = I('goods_num');
        $goods_id = I('goods_id');
        $share = I('share');
        $data = M('g_goods')->where(array('goods_id' => $goods_id))->find();
        $data = D('Goods')->getGoodsPrice($data);//计算商品价格 限时抢购等
        if (empty($data)) {
            $return_data['error'] = "商品不存在";
            echo json_encode($return_data);
            die;
        }
        if (empty($data['is_on_sale']) || empty($data['is_auditing']) ) {
            $return_data['error'] = "商品已下架";
            echo json_encode($return_data);
            die;
        }
        if ($data['is_buy'] == '-1') {///活动禁止购买
            $return_data['error'] = $data['is_buy_msg'];
            echo json_encode($return_data);
            die;
        }
        $add_data['goods_id'] = $data['goods_id'];
        $add_data['goods_sn'] = $data['goods_sn'];
        $add_data['goods_name'] = $data['goods_name'];
        $add_data['goods_img'] = $data['goods_img'];
        $add_data['rec_type'] = $data['hot_type'];//活动类型0 无 1 限时抢购
        $add_data['share_card'] = $share;

        $add_data['goods_number'] = $goods_number;
        $add_data['goods_attr_id'] = $goods_attr_id;
        $goods_price = $data['shop_price'];
        $market_price = $data['market_price'];
        $attr_str = '';
        $share_money = array();///属性佣金
        if ($goods_attr_id) {
            $goods_attr_model = M('g_goods_attr');

            $goods_price_arr = array();
            $goods_attr = $goods_attr_model->where(array('goods_attr_id' => array('in', $goods_attr_id)))->select();
            foreach ($goods_attr as $keys => $attr_value) {
                if ($attr_value) {
                    if ($attr_value['attr_price']) {
                        $goods_price_arr[] = $attr_value['attr_price'];
                    }
                    if ($attr_value['attr_share_money']) {
                        $share_money[] = $attr_value['attr_share_money'];///属性佣金
                    }
                    $attr_str .= ',' . $attr_value['attr_value'];
                }
            }
        }
        if ($attr_str) {
            $attr_str = substr($attr_str, 1);
        }
        if (!empty($goods_price_arr)) {
            $goods_price_arr = max($goods_price_arr);///属性价格取属性的最大值
        }
        if (empty($goods_price_arr)) {
            $goods_price_arr = 0;
        }
        if (!empty($share_money)) {
            $share_money = max($share_money);///属性佣金属性佣金的最大值
        }
        if (empty($share_money)) {
            $share_money = 0;
        }
        if ($goods_price_arr > 0) {///如果属性价格大于0 取属性价格
            if ($goods_price < $goods_price_arr) {/////计算显示的原价价格
                $market_price = $market_price + ($goods_price_arr - $goods_price);
            }
            //如果属性价格小于商品价格 亦取属性价格
            $goods_price = $goods_price_arr;
        }
        ///商品佣金 =属性佣金属性、商品佣金的最大值

        //$data['share_money']=($share_money>$data['share_money'])?$share_money:$data['share_money'];
        //如果属性佣金属性小于商品属性 亦取属性佣金
        $data['share_money'] = ($share_money > 0) ? $share_money : $data['share_money'];
        $add_data['share_money'] = $data['share_money'];
        $add_data['goods_attr'] = $attr_str;
        $add_data['market_price'] = $market_price;
        $add_data['goods_price'] = $goods_price;
        $add_data['purchase_price'] = $data['purchase_price'];//进价
        $res_data[] = $add_data;
        return $res_data;
    }

    //会员升级商品列表页面
    public function upgrade_goods()
    {
        $p = $_REQUEST['p'];
        $pagesize = 6;
        $p = !empty($p) ? $p : 1;
        $start = ($p - 1) * $pagesize;
        $where['is_delete'] = 0;//商品是否已经删除，0，否；1，已 删除
        $where['is_auditing'] = 1;//是否通过审核 1是0 否
        $where['is_upgrade']=1;//剔除非升级产品
        $field = 'goods_id,goods_name,shop_price,goods_img';
        $count=M('g_goods')->where($where)->count();
        $list = M('g_goods')->field($field)->where($where)->limit($start, $pagesize)->select();

//        var_dump($list);die;
        if (IS_AJAX) {
            echo json_encode($list);
            die;
        }
//        var_dump($list);die;
        $this->count=$count;
        $this->list = $list;
        $this->display();
    }

    //会员商品详细页
    public function goods_detail()
    {
        //微信分享后得回调  zm
        if ($_POST["goods_id"]) {
            $goods = M("upgrade_goods")->where(array("goods_id" => $_POST["goods_id"]))->setInc("goods_share");
            // echo M()->getLastSql();
            if ($goods == true) {
                $re["status"] = 1;
                echo json_encode($re);
                die;
            }
            $re["status"] = 0;
            echo json_encode($re);
            die;
        }
        //浏览量 zm
        $browse = M("upgrade_goods")->where(array("goods_id" => $_GET['id']))->setInc("goods_browse");
        //
        $g_where["goods_id"] = array("eq", $_GET["id"]);
        $g_where['is_delete'] = 0;
        //$g_where['is_show']=1;//上架
        $data = D("Upgrade_goods")->getGoodsInfo($g_where);
        //图片
        if ($data) {
            $data = D('Upgrade_goods')->getGoodsPrice($data);//计算商品价格 限时抢购等
            // var_dump($data);die;
            $this->good_image_url = M('g_goods_gallery')->where(array('goods_id' => $data['goods_id']))->select();
            //商品属性
            $goods_type = $data['goods_type'];
            if (!empty($goods_type)) {
                $goods_id = $data['goods_id'];
                $pre = C('DB_PREFIX');//表前缀
                $goods_attr = M()->table($pre . 'upgrade_goods_attr goods_attr')//
                ->join($pre . 'g_attribute attr on attr.attr_id=goods_attr.attr_id')//
                ->where(array('goods_attr.goods_id' => $goods_id))//
                ->field('goods_attr.*,attr.attr_name,attr.attr_input_type,attr.attr_type')
                    ->order('attr.sort_order desc')->select();
                //attr_type 属性是否多选；0，否；1，是；如 果可以多选，则可以自定义属性，并且可以根据值的不同定不同的价
                //attr_input_type当添加商品时，该属性的添 加类别；0，为手工输入；1，为选择输入；2，为多行文本输入
                if ($goods_attr) {
                    $goods_attr1 = array();
                    foreach ($goods_attr as $key => $value) {
                        if ($value['attr_type']) {
                            $goods_attr1[] = $value;
                            unset($goods_attr[$key]);
                        }
                    }
                    if ($goods_attr1) {
                        $arr = array();
                        foreach ($goods_attr1 as $key => $value) {
                            $arr[$value['attr_id']][] = $value;
                        }
                        if ($arr) {
                            $goods_attr1 = array();
                            foreach ($arr as $key => $value) {
                                $tree = array();
                                foreach ($value as $keys => $val) {
                                    if ($val['attr_value']) {
                                        $tree[] = $val;
                                    }
                                }
                                if ($tree) {
                                    $data_arr = array();
                                    $data_arr['attr_name'] = $value[0]['attr_name'];
                                    $data_arr['tree'] = $tree;
                                    $goods_attr1[] = $data_arr;
                                }
                            }
                        }
                    }
                }
                $this->goods_attr = $goods_attr;
                $this->goods_attr1 = $goods_attr1;
            }
        }
        if ($data['shipping_id'] == 0) {   //判断包邮快递
            $data['shipping_name'] = 'SF顺丰快递';
        } else {
            $shipping_name = M('g_shipping')->field('shipping_name')->where(array('shipping_id' => $data['shipping_id']))->find();
            $data['shipping_name'] = $shipping_name['shipping_name'];
            if (empty($data['shipping_name'])) {
                $data['shipping_name'] = 'SF顺丰快递';
            } elseif ($data['shipping_name'] == '包邮') {
                $data['shipping_name'] = '';
            }
        }
        $this->data = $data;
//       var_dump($data);die;
        //收藏信息
        if ($this->uid) {
            $c_where['goods_id'] = $data['goods_id'];
            $c_where['user_id'] = $this->uid;
            $c_where['is_attention'] = 1;
            $c_count = M('g_collect_goods')->where($c_where)->count();
            $this->collect = $c_count;
        }
        $shar_title = $this->webseting['web_title'] . '-' . $data['goods_name'];
        $shar_len = mb_strlen($shar_title, 'utf-8');
        $rep = ceil($shar_len / 13);
        $replen = $rep * 13 - $shar_len;
        $shar_title_desc = $shar_title;
        if ($replen > 0) {
            $shar_title_desc .= str_repeat('    ', $replen);
        }
        //str_repeat
        $this->shar_url = $url = "http://" . $_SERVER['HTTP_HOST'] . U('wap/upgrade/goods_detail', array('id' => $_GET["id"], 'share' => $this->member_card));
        $this->shar_title = $shar_title;
        $this->shar_desc = $shar_title_desc . "￥" . $data['shop_price'];
        $this->shar_imgurl = "http://" . $_SERVER['HTTP_HOST'] . $data['goods_img'];
        $this->get_weixin();///获取微信 信息
        $this->webtitle = "FG峰购";
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

    //付款页面
    public function  upgrade_pay(){
		$order_id=$_REQUEST['order_id'];//这里传的值 其实是 order_id
		$order_info = M('g_order_info')->where('order_id='.$order_id)->find();
		$order_sn = $order_info['order_sn'];
		$level_order_info=M('member_level_order')->where(array('id'=>$order_sn))->find();
		$pay_sn = M('g_order_pay')->where('order_sn="'.$order_sn.'"')->getField('pay_sn');
		$level_info=M('member_level')->where('levelId='.$level_order_info['levelId'])->find();
		$order_goodses = M('g_order_goods')->where('order_id='.$order_id)->select();
		$this->list = $order_goodses;
		$share_info=$this->share_info;
		$data=$order_info;
		$data['levelName']=$level_info['levelName'];
		$data['member_name']=$this->member_name;//用户名
		$data['pay_sn'] = $pay_sn;
		$data['levelId'] = $level_order_info['levelId'];
		//$data['order_id']=$order_id;
		$memberinfo_de = $this->getMemberDetail();
		$memberinfo_de['recommend_name'] = $share_info['member_name'];
		$memberinfo_de['balance'] += $memberinfo_de['balance_give'];
		$this->MemberDetail = $memberinfo_de;
		$balance = $memberinfo_de['balance'];
		$balance = sprintf("%.2f", substr(sprintf("%.3f", $balance), 0, -2));
		$this->balance = $balance;
		// var_dump($data);
		// exit;

		// 支付状态；0，未付款；1，付款中 ；2，已付款
		$pay_status = array('0' => '未付款', '1' => '已支付，对账中', '2' => '已付款');
		$this->pay_status = $pay_status;

		$title = "订单信息";
		//$this->assign("data", $data);
		$this->data=$data;
		$this->display();
    }
	//创建订单
	public function set_order()
	{
		$goods_ids = $_POST['goods_ids'];
		$return_data['status'] = 0;
		if (empty($goods_ids)){
			$return_data['error'] = '选择商品';
			echo json_encode($return_data);
			die;
		}
		$uid = $this->uid;
		if (empty($_SESSION['member']['mobile'])) {
			//未绑定  手机
			$return_data['status'] = 2;
			$return_data['error'] = '请完善资料';
			echo json_encode($return_data);
			die;
		}
		$level_info=$this->get_up_level($goods_ids);
		$levelId = $level_info['levelId'];
        if($levelId === 0){
            $is_upgrade = 0;
        }else{
            $is_upgrade = 1;
        }
		$member_info=$this->getMemberInfo();
		$member_detail = $this->getMemberDetail();
		$member_level=$member_info['member_vip_type'];
		$time = time();
		$order_sn = makeOrderSn($uid); //生成订单号
		$pay_sn = makePaySn($uid); //生成支付单号
		//订单信息
		//升级订单信息
		$level_order_data['id']=$order_sn;
		$level_order_data['levelId']=$levelId;//要升级的用户等级
		$level_order_data['member_id'] = $uid;//升级用户
		$level_order_data['upgrade_time'] = date("Y-m-d H:i:s",$time);//升级时间
		$level_order_data['last_level']=$member_level;//当前用户等级
		//拼出地址名
		$province_name=M('city_province')->where(array('provinceid'=>$member_detail['provinceid']))->getField('province');
		$city_name=M('city_city')->where(array('cityid'=>$member_detail['cityid']))->getField('city');
		$area_name=M('city_area')->where(array('areaid'=>$member_detail['districtid']))->getField('area');
		$address_name=$province_name.$city_name.$area_name.$member_detail['addr'];
		//商品订单信息
		$order_data['mobile'] = $member_info['mobile'];
		$order_data['consignee'] = $member_detail['user_name'];
		$order_data['province'] =$member_detail['provinceid'];
		$order_data['city'] =$member_detail['cityid'];
		$order_data['district'] =$member_detail['areaid'];
		$order_data['address'] =$address_name;

		$order_data['order_sn'] = $order_sn;
		$order_data['user_id'] = $uid;
		$order_data['is_gift'] = 0;//活动类型0 无 1 限时抢购
		$order_data['is_upgrade'] = $is_upgrade;//升级产品
		$order_data['order_type'] = 1;//订单类型 0未知 1店铺订单 2商品订单 3基地订单
		$order_data['goods_amount'] = $level_info['pay_price'];//商品总金额
		$order_data['order_amount'] = $level_info['pay_price'];//应付款金额
		$order_data['order_cost'] = 0;//成本
		$order_data['pay_note'] = I('pay_note');//备注
		$order_data['add_time'] = $time;
		if($this->share_info){
			$share_info = $this->share_info;
			$order_data['share_uid'] = isset($share_info['id']) ? $share_info['id'] : 0;
		}elseif (isset($member_info['recommend_code']) && $member_info['recommend_code']){
			$share_info = M('member')->where('member_card="'.$member_info['recommend_code'].'"')->find();
			$order_data['share_uid'] = isset($share_info['id']) && $share_info['id'] ? $share_info['id'] : 0;
		}

		//订单付款信息
		$order_pay_data['buyer_id'] = $uid;
		$order_pay_data['pay_sn'] = $pay_sn;
		$order_pay_data['order_sn'] = $order_sn;

		$order_pay_data['add_time'] = time();

		$level_order_data['pay_price']=$level_info['pay_price'];//要支付的价格
		$level_order_data['pay_status']=0;
		//获取要用的数据库对象
		$level_order_model=M('member_level_order');
		$order_model = M('g_order_info');
		$order_goods_model = M('g_order_goods');
		$order_pay_model = M('g_order_pay');

		$level_order_model->startTrans();//开启事务
		$order_model->startTrans();//开起事务
		$order_goods_model->startTrans();//开起事务
		$order_pay_model->startTrans();//开起事务

		$add_order_pay = $order_pay_model->add($order_pay_data);
		$order_data['pay_record_id'] = $add_order_pay;
		$add_order = $order_model->add($order_data);
		$add_level_order = $level_order_model->add($level_order_data);
		if ($add_order === false||$add_level_order === false) {
			$return_data['error'] = "订单生成失败，请稍后再试.";
			$level_order_model->rollback();//回滚事务
			$order_model->rollback();//回滚事务
			$order_goods_model->rollback();//回滚事务
			$order_pay_model->rollback();//回滚事务
			echo json_encode($return_data);
			die;
		}
		//g_order_goods的信息
		$goods_where['is_upgrade'] = 1;
		$goods_where['goods_id'] =array('in',$goods_ids);
		$order_goods_list = M('g_goods')->where($goods_where)->select();
		foreach ($order_goods_list as $k =>$v){
			$order_good_data['order_id'] = $add_order;
			$order_good_data['goods_id'] = $v['goods_id'];
			$order_good_data['goods_name'] = $v['goods_name'];//商品名
			$order_good_data['goods_sn'] = 0;//商品的唯一货号
			$order_good_data['goods_price'] = $v['shop_price'];//商品价格
			$order_good_data['market_price'] =$v['market_price'];//商品价格

			$order_good_data['goods_number'] = 1;//商品数量
			$order_good_data['goods_image'] = $v['goods_img'];//
			$order_good_data['goods_attr_id'] =  $v['shop_price'];//商品实际成交价
			$order_good_data['goods_attr'] = '';//商家id
			$order_good_data['share_card'] = $share_info['recommend_code'];//分享者 card
			$order_good_data['share_id']  = $share_info['id'];//分享者 id

			$order_good_data['share_money'] ;//分享返利金额
			$order_good_data['is_refund'] = 0;//是否可退货 1是 0否
			$order_good_data['goods_shipping'] ;//包邮方式 默认SF顺风包邮
			$order_good_data['shipping_code'];//包邮方式code
			$order_good_data['offline'] = 0;//货到付款
			$order_good_data['is_exchange'] = 0;////是否可退货 1是 0否
			$order_good_data['is_gift'] =0;//活动类型0 无 1 限时抢购
			$order_good_data['promote_start_date'] ;
			$order_good_data['promote_end_date'] ;
			$order_good_data['is_upgrade'] = $is_upgrade;//升级产品
			$order_good_data['order_type'] = 1;//订单类型 0未知 1店铺订单 2商品订单 3基地订单
			$add_order_goods[] = $order_goods_model->add($order_good_data);
		}




		if ($add_order !== false && $add_order_pay !== false&&$add_level_order !== false) {
			$return_data['status'] = 1;
			//记录订单日志
			$level_order_model->commit();
			$order_model->commit();//提交事务
			$order_goods_model->commit();//提交事务
			$order_pay_model->commit();//提交事务
			$data = array();
			$data['order_id'] = $add_order;
			$data['order_amount'] = 1;
			M('g_pay_log')->add($data);
			//记录订单日志
			$data = array();
			$data['order_id'] = $add_order;
			$data['log_role'] = 'buyer';
			$data['log_msg'] = '用户下单成功';
			$data['log_orderstate'] = '10';
			D('Mallorder')->addOrderLog($data);
			$return_data['need_pay'] = 1;///需要支付 1  不需要支付0
			//$return_data['pay_sn'] = $pay_sn;
			$return_data['order_id'] = $add_order;
		} else {
			$order_model->rollback();//回滚事务
			$order_pay_model->rollback();//回滚事务
			$order_goods_model->rollback();//回滚事务
			$level_order_model->rollback();//回滚事务
			$return_data['error'] = "订单生成失败，请稍后再试.";
		}
		echo json_encode($return_data);
		die;
	}

	//计算当前选择商品可升级的等级信息
	public function get_up_level($goods_ids){
		$where_goods['goods_id'] = array('in',$goods_ids);
		$field = 'sum(shop_price)';
		$goods_price = M('g_goods')->field($field)->where($where_goods)->getField('sum(shop_price)');
		$memberinfo=$this->getMemberInfo();
		$level_1 = M('member_level')->where('levelId=1')->find();
		$level_2 = M('member_level')->where('levelId=2')->find();
		$level = $memberinfo['member_vip_type'];
		$level_info['levelId'] = 0;
		if($level == 0){
			if($goods_price >= $level_2['price']){
				$level_info = $level_2;
			}elseif($goods_price >= $level_1['price']){
				$level_info = $level_1 ;
			}
		}elseif($level == 1){
			if($goods_price >= $level_1['upgrade_price']){
				$level_info = $level_2;
			}
		}
		$level_info['pay_price'] = $goods_price;
		return $level_info ;
	}

	//执行
	public function go_pay(){

		$return_data['status'] = 0;
		$order_id = $_REQUEST['order_id'];
		$uid = $this->uid;
		$mem_password = $_REQUEST['mem_password'];
		$pay_type = $_REQUEST['pay_type'];
		$use_balance = $_REQUEST['use_balance'];
		//购买开关
		$buy_switch = M('sys_param')->where(array('param_code' => 'buy_switch'))->getField('param_value');
		if (empty($buy_switch)) {
			$return_data['error'] = '管理员已关闭交易';
			echo json_encode($return_data);
			die;
		}
		$model_order = D('Mallorder');
		if ($pay_type == "pay_online") {//在线支付
			if ($use_balance) {//使用余额支付
				$return_data['error'] = '店铺订单无法用余额支付';
				echo json_encode($return_data);
				die;
				$this->balance_pay();
			} else {
				$order_list = $model_order->getOrderList(array('order_id'=>$order_id), '', 'order_id,order_sn,order_amount,shipping_fee,surplus,surplus_give,discount_start_time,discount_end_time,discount');

				$level_where['id'] = $order_list[0]['order_sn'];
				$level_order_list=M('member_level_order')->where($level_where)->find();
				if( empty($level_order_list) || empty($order_list)){
					$return_data['error'] = "该订单不存在";
					echo json_encode($return_data);
					die;
				}
				////未使用余额支付
				$_GET['return'] = 1;
				$_GET['pay_sn'] = $level_order_list['id'];
				//$get_pay_url = $this->get_pay_url();
				$return_data['need_pay'] = 1;///需要支付 1  不需要支付0
				$return_data['status'] = 1;
				/* if ($get_pay_url['status'] == 1) {
					 $return_data['status'] = 1;
					 $return_data['pay_url'] = $get_pay_url['pay_url'];
				 } else {
					 $return_data['error'] = $get_pay_url['error'];
					 $return_data['status'] = 0;
				 }*/
				echo json_encode($return_data);
				die;
			}
		}
	}
}