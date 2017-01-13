<?php

class BaseAction extends CommonAction {

	public function __construct(){
		parent::__construct();
		$share=$_GET['share'];
		if($share){
			$this->getShare($share);
		}
		
		$cook_time="604800";//7天 7*24*60*60
		$s_session_id=session('session_id');
		$c_session_id=cookie('session_id');
		if($s_session_id){

		}else{
			if(empty($c_session_id)){
				$c_session_id=makeSessionID();
				$c_session_id=md5($c_session_id);
				cookie('session_id',$c_session_id,$cook_time);
			}
			session('session_id',$c_session_id);
		}
		if(!IS_AJAX){
			$this->wap_login_return();
			$this->get_oauth2_openid();
		}
		$this->check_login_rember();////检查是否记住密码
		$this->check_login_appid();///appid 检查是否记住密码
		$this->uid=$_SESSION["member"]['uid'];

		$this->member_card=$_SESSION["member"]['member_card'];
		$this->member_name=$_SESSION["member"]['member_name'];
		$this->mobile=$_SESSION["member"]['mobile'];
		$this->binding_mobile=$_SESSION["member"]['mobile'];
		$user_agent = $_SERVER['HTTP_USER_AGENT'];
		$is_weixin = 1;
		if(strpos($user_agent, 'MicroMessenger') === false) {
			$is_weixin = 0;
			$this->binding_mobile='11';//非微信不弹窗
		}
		$this->is_weixin = $is_weixin;

		if(!IS_AJAX){
			$is_follow_points=$this->set_member_weixin_info();
			if($is_follow_points){
				$_GET['layer'] = 'follow_points';
			}
			$this->is_guanzhu= $this->set_float_concern();
			if(empty($this->is_guanzhu)){
				$tuijian_user=$_SESSION['tuijian_user'];
				if(empty($tuijian_user)){
					$tui_card=M('member')->where(array('id'=>$this->uid))->getField('recommend_code');
					if(empty($tui_card)){//分享人
						$tui_card=session('share');
						if(empty($tui_card)){
							$tui_card=INDEX_CARD;///如果没有 默认INDEX_CARD
						}
					}
					$tuijian_user=M('member')->where(array('member_card'=>$tui_card))->field('member_name,member_logo')->find();
					if($tuijian_user){
						$_SESSION['tuijian_user']=$tuijian_user;
					}
				}
				$this->tuijian_user=$tuijian_user;
			}
		}

		//每日浏览量自增
		$memberinfo=$this->getMemberInfo();

		//生成二维码
		$qrcode=$this->recommend_qrcode();
		$this->assign('qrcode',$qrcode);
		$member_level=M('member')->where('id='.$this->uid)->getField('member_vip_type');
		$level_names=array('0'=>'普通会员','1'=>'标准店铺','2'=>'高级店铺','3'=>'旗舰店铺');
		$this->assign('member_name',$memberinfo['member_name']);
		$this->assign('member_logo',$memberinfo['member_logo']);
		$this->assign('member_level',$member_level);
		$this->assign('level_name',$level_names[$member_level]);
		if($memberinfo['member_vip_type']){
			$share_para=$memberinfo['member_card'];
			$shop_info=$memberinfo;
		}else{
			$share_para=session('share');
			if($share_para){
				$share_info=M('member')->where('member_card="'.$share_para.'"')->find();
				if(empty($share_info) ){
					$share_para=$memberinfo['recommend_code'];
				}elseif ($share_info['member_vip_type']==0){
					$share_para=$share_info['recommend_code'];
				}
			}else{
				$share_para=$memberinfo['recommend_code'];
			}
			$share_info=M('member')->where('member_card="'.$share_para.'"')->find();
			$shop_info=$share_info;
		}
		if(empty($share_para)){
			$share_para=INDEX_CARD;
		}
		$this->shop_info=$shop_info;
		//$this->assign('shop_info',$shop_info);
		$this->shop_code=$share_para;
		$this->assign('share_para',$share_para);
		//点开二维码开店分享的信息
		$share_link_arr=array();
		$shar_title=$this->member_name.'邀请您开店';//C('SHAR_TITLE');
		$shar_url= $url="http://".$_SERVER['HTTP_HOST'].U('wap/start/index',array('share'=>$this->member_card));
		$shar_title=$shar_title;
		$shar_desc='为广大合作伙伴提供优质的创业机会';
		$shar_imgurl="http://".$_SERVER['HTTP_HOST']."__PUBLIC__/wap/img/share_store_logo.png";
		$share_link_arr['shar_url']=$shar_url;
		$share_link_arr['shar_title']=$shar_title;
		$share_link_arr['shar_desc']=$shar_desc;
		$share_link_arr['shar_imgurl']=$shar_imgurl;
		$this->assign('share_link_arr',$share_link_arr);
    }

	//重设 session_id 时间
	public function set_session_id(){
		$cook_time=604800*2;//7*2天 7*24*60*60*2
		$s_session_id=session('session_id');
		cookie('session_id',$s_session_id,$cook_time);
	}
	
  //检查是否记住密码
  public function check_login_rember(){

    if(empty($_SESSION["member"]['uid'])){
        $s_session_id=session('session_id');
       //记录session信息
       $cook_time=604800*2;
       $time=time()-$cook_time;
       $where=array('session_id'=>$s_session_id);
       $where['add_time']=array('gt',$time);
       $member=M('member_rember_token')->where($where)->find();
       if($member){
        $user_open_id=$member['openid'];
        $member=M('member')->where(array('id'=>$member['member_id']))//
        ->field('id,member_name,member_card,mobile')->find();
        if($member){
          if($user_open_id){
            session('user_open_id',$user_open_id);
              //openod 默认绑定openid
             M('member')->where(array('id'=>$member['id']))->save(array('openid'=>$user_open_id));
          }
           $user_data['uid']=$member['id'];
          $user_data['member_card']=$member['member_card'];
          $user_data['member_name']=$member['member_name'];
          $user_data['mobile']=$member['mobile'];
          $_SESSION['member']=$user_data;
        }
       }
    }
  }
    //检查是否记住密码 appid方式
  public function check_login_appid(){

    if(empty($_SESSION["member"]['uid'])){
        //$s_session_id=session('session_id');
         $user_open_id=session('user_open_id');
         if($user_open_id){///自动登录
             $member_info=M('member')->where(array('openid'=>$user_open_id))->find();
             if($member_info){
               $user_data['uid']=$member_info['id'];
                $user_data['member_card']=$member_info['member_card'];
                $user_data['member_name']=$member_info['member_name'];
                $user_data['mobile']=$member_info['mobile'];
                $_SESSION['member']=$user_data;
                return false;
             }
              //记录session信息
             $cook_time=604800*2;
             $time=time()-$cook_time;
             $where=array('openid'=>$user_open_id);
             $member=M('member_rember_token')->where($where)->order('add_time desc')->find();
             if($member && $member['add_time']>$time){
               $member=M('member')->where(array('id'=>$member['member_id']))//
              ->field('id,member_name,member_card,mobile')->find();
              if($member){
                //openod 默认绑定openid
                M('member')->where(array('id'=>$member['id']))->save(array('openid'=>$user_open_id));

                $user_data['uid']=$member['id'];
                $user_data['member_card']=$member['member_card'];
                $user_data['member_name']=$member['member_name'];
                $user_data['mobile']=$member['mobile'];
                $_SESSION['member']=$user_data;
              }
            }

            if(empty($_SESSION["member"]['uid'])){
             $this->set_openid_reg();
            }

       }
    }
  }
  //同步微信信息
 public function set_member_weixin_info(){
   $user_agent = $_SERVER['HTTP_USER_AGENT'];
    if(strpos($user_agent, 'MicroMessenger') === false) {
          return false;
    }
    $member=$this->getMemberInfo();
    if(empty($member['id'])){
      return false;
    }
    if(empty($member['member_card'])){
      return false;
    }
    //if(empty($member['mobile'])){
   //   return false;
    //}
   // if($member['is_guanzhu']){//已关注
    //  return false;
    //}
    //get_weixin_userinfo
   /* $user_info['subscribe']=$str->subscribe;//用户是否订阅该公众号标识，值为0时，代表此用户没有关注该公众号，拉取不到其余信息。
    $user_info['nickname']=$str->nickname;//用户的昵称
    $user_info['sex']=$str->sex;//用户的性别，值为1时是男性，值为2时是女性，值为0时是未知
    $user_info['city']=$str->city;//用户所在城市
    $user_info['country']=$str->country;//用户所在国家
    $user_info['province']=$str->province;//用户所在省份
    $user_info['headimgurl']=$str->headimgurl;//用户头像，最后一个数值代表正方形头像大小（有0、46、64、96、132数值可选，0代表640*640正方形头像），
    //用户没有头像时该项为空。若用户更换头像，原有头像URL将失效。
    $user_info['subscribe_time']=$str->subscribe_time;//用户关注时间，为时间戳。如果用户曾多次关注，则取最后关注时间
*/
    $user_info=$_SESSION['user_info'];
    if(empty($user_info)){
      $this->get_weixin_userinfo();
      $user_info=$_SESSION['user_info'];
    }
     $this->get_weixin_userinfo();
      $user_info=$_SESSION['user_info'];
    //if($user_info['subscribe']==0){
      //return false;
    //}

    if($member['member_logo']=='/Uploads/avatar_default.png'){
       $save_data['member_logo']=$user_info['headimgurl'];
    }
    $member_name='FG'.substr($member['mobile'], 0,-4);
	$member_name_c='FG'.$member['member_card'];
    if($member['member_name']==$member_name || $member['member_name']==$member_name_c){
       $save_data['member_name']=$user_info['nickname'];
    }
    if(empty($member['openid'])){//已关注
      $save_data['openid']=session('user_open_id');;
    }
    $save_data['is_guanzhu']=1;
    $save_data['is_update']=1;
    if(!$member['is_update'] && !empty($user_info['subscribe_time'])){
        $save_data['guanzhu_time']=$user_info['subscribe_time'];
        $res=M('member')->where(array('id'=>$member['id']))->save($save_data);
        if($res!==false){
			$log_data['des'] = "首次关注获得积分：".C('PRESENT_POINTS');
			$set_member_points = $this->set_member_points($member['id'],1,20,$log_data);
          if($save_data['member_name']){
            $_SESSION['member']['member_name']=$save_data['member_name'];
          }
			return true;
        }
    }
 }
 
  public function get_access_token_t(){
        M('sys_param')->where(array('param_code'=>'buy_switch'))->save(array('param_value'=>0));
    }
  //微信默认注册
  public function set_openid_reg(){
        $code=session('share');
  		$code = $code ? $code : INDEX_CARD;
  		if ($code != INDEX_CARD){
			$member_level=M('member')->where('member_card="'.$code.'"')->getField('member_vip_type');
			if ($member_level == 0){
				$code = INDEX_CARD;
			}
		}
        //注册
        $openid=session('user_open_id');

        $m_data['openid']=$openid;

        //记录登录信息
        $ip=getIP();
        $time=time();
        $m_data['add_time'] = $time;
        $m_data['recommend_code']=$code;
        $m_data['last_login_ip'] = $ip;
        $m_data['last_login_time'] = $time;
        $m_data['login_ip'] = $ip;
        $m_data['login_time'] = $time;
        $m_data['login_count'] = 1;
        //// 赠送积分 绑定时赠送
         ///注册赠送 用户金额Register give money
        $register_give_money=0;
        $m_data_detail['points']=0;//用户扩展表 注册赠送10积分
  		$share_id=M('member')->where('member_card="'.$code.'"')->getField('id');
		$share_detail=M('member_detail')->where('member_id='.$share_id)->find();


        $member_model=M('member');
        $member_detail_model=M('member_detail');

        $member_model->startTrans();//开起事务
        $member_detail_model->startTrans();//开起事务

        $add=$member_model->add($m_data);
  		if ($share_detail){
			$m_data_detail['relevel']=($share_detail['relevel']+1);
			$m_data_detail['repath']=$share_detail['repath'].','.$add;
  		}
        $m_data_detail['member_id']=$add;//
        $member_detail_add=$member_detail_model->add($m_data_detail);
        if($add!==false && $member_detail_add!==false){
			//注册成功
            $data['status']=1;
            //设置卡号
            $card=$this->getcard($add);
			$save_data=array('member_card'=>$card);
			$save_data['member_name']='FG'.$card;
            $s_data=M('member')->where(array('id'=>$add))->save($save_data);

             //记录session信息
            $user_data=array();
            $user_data['uid']=$add;
            $user_data['member_card']=$card;
            $user_data['member_name']=$m_data['member_name'];
            $user_data['mobile']=$m_data['mobile'];
            $_SESSION['member']=$user_data;
			
			//登录日志 记录
            $this->set_member_login_log();

            $member_model->commit();//提交事务
            $member_detail_model->commit();//提交事务
            //发送微信消息
            if ($code != INDEX_CARD) {
              $send_data['customer_id'] = $add;
              $this->weixin_send($share_id, $send_data, 3);
            }
        }else{
             $member_model->rollback();//回滚事务
            $member_detail_model->rollback();//回滚事务
        }


  }

    public function getcard($member_id=null){

		 if(empty($member_id)){
            $member=M("member")->where(array('member_card'=>array('neq',INDEX_CARD)))->order('member_card desc')->find();
            if(!empty($member)){
                if($member['member_card']){
                    $card=$member['member_card']+1;
                }
            }
        }else{
            $card=$member_id;
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
     //获取推荐人 信息
     /**
      * $m_data['recommend_code']=$data_code['recommend_code'];//推荐码 (推荐人的推荐码 )
      * $m_data['indirect_recommend_code']=$data_code['indirect_recommend_code'];//间接推荐人推荐码 (推荐人的推荐人的推荐码 )
      * $m_data['indirect2_recommend_code']=$data_code['indirect2_recommend_code'];//间接2层推荐人推荐码 (推荐人的推荐人的推荐人的推荐码 )
      **/
    public function getMemberCode($code=null){
        if(empty($code)){
            return array();
        }
        $where['mobile']=$code;
        $where['member_card']=$code;
        $where['_logic']='OR';
        $member=M("member")->where($where)->find();
        if(empty($member)){
            return array();
        }else{
             $return_data['recommend_code']=$member['member_card'];
             //查询 推荐人的推荐人的推荐码 //间接推荐人推荐码
             if($member['recommend_code']){
                $where1['member_card']=$member['recommend_code'];//推荐人的被推荐码
                $member1=M("member")->where($where1)->find();
                if($member1){
                     $return_data['indirect_recommend_code']=$member1['member_card'];
                     //查询 推荐人的推荐人的推荐人的推荐码 //间接2层推荐人推荐码
                     if($member1['recommend_code']){
                        $where2['member_card']=$member1['recommend_code'];//推荐人推荐人的被推荐码
                        $member2=M("member")->where($where2)->find();
                        if($member2){
                             $return_data['indirect2_recommend_code']=$member2['member_card'];
                        }
                     }
                }
             }
        }
        return $return_data;
    }
  ///获取上次url
  public function wap_login_return(){
       $REQUEST_URI=$_SERVER['REQUEST_URI'];
       $notcheck=array(
        'wap/login',
         'wap/index/get_thumbs',
         'wap/member/updatepwd'
        );
       if ($REQUEST_URI) {
         $chk=1;
         foreach ($notcheck as $key => $value) {
           if(stristr($REQUEST_URI,$value)){
             $chk=0;
             break;
           }
         }
         if($chk){
          $url='HTTP://'.$_SERVER['HTTP_HOST'].$REQUEST_URI;
          session('wap_login_return',$url);
         }
       }
       //当前记录的url 是login里面的清空
        $wap_login_return=session('wap_login_return');
        if ($wap_login_return) {
         $chk_y=0;
         foreach ($notcheck as $key => $value) {
           if(stristr($wap_login_return,$value)){
             $chk_y=1;
             break;
           }
         }
         if($chk_y){
          session('wap_login_return',null);
         }
       }

    }
	//获取登录状态
    public function get_member_login(){
		if(empty($_SESSION["member"])){
			return false;
		}
		if(empty($_SESSION["member"]['uid'])){
			return false;
		}
		return true;
    }
    //登录日志 记录
    public function set_member_login_log($type='1'){
      //登录类型 1wap 2 pc  3ios  4安卓等【预留】
      if($_SESSION["member"]['uid']){
        $l_data['member_id']=$_SESSION["member"]['uid'];
        $l_data['login_ip']=getIP();
        $l_data['login_time']=time();
        $l_data['type']=$type;
        M('member_login_log')->add($l_data);
      }
    }
    ///获取用户信息
    public function getMemberInfo(){
        //
        if(empty($this->uid)){
			return array();die;
		}
		$where['id']=$this->uid;
		$memberinfo=M('member')->where($where)->find();
		return $memberinfo;
    }

    ///获取用户扩展信息
    public function getMemberDetail(){
        //
        if(empty($this->uid)){
			return array();die;
		}
		$where['member_id']=$this->uid;
		$memberinfo=M('member_detail')->where($where)->find();
		return $memberinfo;
    }
    ///获取分享者的用户信息
    public function getShareMemberInfo(){
		$share=session('share');
		 if(empty($share)){
		  return array();
		}
		$where['member_card']=$share;
		$memberinfo=M('member')->where($where)->find();
		return $memberinfo;
    }
    ///获取商户信息
    public function getShopDetail($where){
        //
        if(empty($where)){
			return array();die;
		}
		$shop=M('shop_detail')->where($where)->find();
		return $shop;
    }
     ///获取商户状态
    public function getShopStatus($where){
        //
        if(empty($where)){
			return 0;die;
		}
		$shop=M('shop_detail')->where($where)->getField('status');
		return $shop;
    }

    //获取商品结算价 $good_id
  // 商品id | 商品数量 多个英文逗号隔开  例如 2|2,2|1
  // all_num商品总数 tolal 总价  need_pay 支付总价 goods_list 商品列表
  public function get_goods_settlement($good_id=null,$shop_id=null){
  	$return_data['status']=0;
    if(empty($good_id) || empty($good_id)){
    	$return_data['error']='请选择服务';
        return $return_data;
    }
    //处理字符串
    $good_id=explode(',', $good_id);
    foreach ($good_id as $key => $value) {
         $good_msg=explode('|', $value);
         $goods_id_arr[]=$good_msg[0];
         $goods_num_arr[]=$good_msg[1];
    }
    foreach ($goods_id_arr as $key => $value) {
        if(empty($value) || empty($goods_num_arr[$key])){
            //商品id为空 或者 数量为空 移除
          unset($goods_id_arr[$key]);
          unset($goods_num_arr[$key]);
          continue;//跳出当前循环
        }
        $goods_num[$value]=$goods_num_arr[$key];
    }
     //查询商家信息
    $shop_where['status']=1;
    $shop_where['shop_id']=$shop_id;
    $shop_detail=M('shop_detail');
    $shop_field="shop_id,shop_name,discount_consume,discount_rebate";
  	$shop=$shop_detail->where($shop_where)->field($shop_field)->find();
  	if(empty($shop)){
  		$return_data['error']='该商家不存在或已冻结';
      return $return_data;
  	}
    //goods_num_arr 商品数量 数组
    $where_str=implode(',', $goods_id_arr);
    //查询产品信息
    $g_where['id']=array('in',$where_str);
    $g_where['goods_state']=1;
    $g_where['goods_status']=1;
    $g_where['is_del']=0;
    $field="id,shop_id,goods_name,goods_price,goods_image";
    $goods_list=M('group_goods')->where($g_where)->field($field)->select();
    if(empty($goods_list)){
      $return_data['error']='该服务不存在或已下架';
      return $return_data;
    }
    foreach ($goods_list as $key => $value) {
       $num=$goods_num[$value['id']];///商品数量
       if(empty($num)){//未得到商品数量
         unset($goods_list[$key]);
         continue;//跳出当前循环
       }
       $goods_list[$key]['num']=$num;
       $goods_list[$key]['shop']=$shop;
    }
    //计算价格
    $tolal=$need_pay=$all_num=0;
     foreach ($goods_list as $key => $value) {
      // $shop=$value['shop'];
       $discount_consume=$shop['discount_consume'];
       if($discount_consume>1 && $discount_consume<=0){
         $discount_consume=1;//不打折
       }
       $pay_price=$value['goods_price']*$discount_consume;
       $value['pay_price']=$pay_price;//单价
       $num=$value['num'];
       $value['all_goods_price']=$value['goods_price']*$num;//总价 折前
       $value['all_pay_price']=$pay_price*$num;//总价 折后

       $ret_good_data[]=$value;
       $all_num+=$num;
       $tolal+=$value['all_goods_price'];
       $need_pay+=$value['all_pay_price'];
    }
    $return_arr['all_num']=$all_num;
     $return_arr['tolal']=$tolal;
    $return_arr['need_pay']=$need_pay;
    $return_arr['goods_list']=$ret_good_data;

    $return_data['status']=1;
    $return_data['shop']=$shop;
    $return_data['list']=$return_arr;
    return $return_data;
  }
  /**
 * 查询所有城市列表名称
 */
public function get_all_city(){
  $city=S("set_city_all");//获取缓存城市信息
  if(empty($city)){
    $city=M ( "city_city" )->field ( "cityid,city as cityname,first" )->select();
    S("set_city_all",$city);//缓存城市信息
  }
    return  $city;
}
  /**
 * 查询热门城市列表名称
 */
public function get_hot_city(){
  $city=S("set_hot_all");//获取缓存城市信息
  if(empty($city)){
    $city=M ( "city_city" )->field ( "cityid,city as cityname,first" )->where ( array("hot"=>1))->select();
    S("set_hot_all",$city);//缓存城市信息
  }
  return  $city;
}

  /**
 * 查询城市名称 不传参 查询当前城市
 */
public function get_city_name($cityid=null){
  $cityid=$cityid?$cityid:$this->now_city;
  if(empty($cityid)){
    return '';
  }
  $city=M ( "city_city" )->where ( array("cityid"=>$cityid))->getField('city');
  return  $city;
}

  /**
 * 用户登录设置购物车
 */
public function set_cart(){
  if($_SESSION["member"]['uid']){
    $where['session_id']=session('session_id');
     M('g_cart')->where($where)->save(array('user_id'=>$_SESSION["member"]['uid']));
  }
}
   ///获取用户 openid
  public function get_oauth2_openid(){
      $user_agent = $_SERVER['HTTP_USER_AGENT'];
    if (strpos($user_agent, 'MicroMessenger') === false) {
        // 非微信浏览器 不调取用户的 openid
        //session('user_open_error',1);
        return false;
    }
    $user_open_id=session('user_open_id');
    $user_open_error=session('user_open_error');

    //未登录 未获取 openid
    //未获取openid 获取 empty($_SESSION["member"]['uid']) &&
    if(empty($user_open_id) && empty($user_open_error)){

    }else{
      $return_f=1;
      //有则记录
          if($_SESSION["member"]['uid'] && $user_open_id){//如果登录 记录openid
            $where=array();
            $s_session_id=session('session_id');
            //$where['openid']=$user_open_id;
            $where['session_id']=$s_session_id;
            $where['member_id']=$_SESSION["member"]['uid'];
            $member=M('member_rember_token')->where($where)->find();

            if($member && empty($member['openid'])){
                M('member_rember_token')->where($where)->save(array('openid'=>$user_open_id,'open_time'=>time()));
            }else{
              if($member['openid']){
                $time=time();
                $ts=$time-$member['open_time'];
                if($ts>=7200){//7200 授权过期
                  $return_f=0;
                  session('user_open_id',null);
                  session('user_open_error',null);
                }
              }
            }
          }
        if($return_f){
            return false;
        }
    }
       $user_open_id=session('user_open_id');
       $user_code=$_GET['code'];
      session('user_code',$user_code);
       $get_arr=$_GET;
       $user_code=session('user_code');
       $appid=C('APPID');
         $appsecret=C('APPSECRET');
         unset($get_arr['_URL_']);
         $url=GROUP_NAME.'/'.MODULE_NAME.'/'.ACTION_NAME;
         $redirect_uri="http://".$_SERVER['HTTP_HOST'].U($url,$get_arr);
         //https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx520c15f417810387&redirect_uri=https%3A%2F%2Fchong.qq.com%2Fphp%2Findex.php%3Fd%3D%26c%3DwxAdapter%26m%3DmobileDeal%26showwxpaytitle%3D1%26vb2ctag%3D4_2030_5_1194_60&response_type=code&scope=snsapi_base&state=123#wechat_redirect
         $get_code_url="https://open.weixin.qq.com/connect/oauth2/authorize?appid={$appid}&redirect_uri={$redirect_uri}&response_type=code&scope=snsapi_base&state=123#wechat_redirect";
      if(empty($user_code)){
         redirect($get_code_url);die;
      }else{//获取 openid
        $code=$user_code;
        $get_appid_url="https://api.weixin.qq.com/sns/oauth2/access_token?appid={$appid}&secret={$appsecret}&code={$code}&grant_type=authorization_code";
        $str=$this->curl_https($get_appid_url);

       $str=json_decode($str);
       if(empty($str->errcode)){
          $user_open_id=$str->openid;//获取到的凭证
          $expires_in=$str->expires_in;//凭证有效时间，单位：秒
          session('user_open_id',$user_open_id);
          session('user_open_error',null);
           $s_session_id=session('session_id');
          if($_SESSION["member"]['uid']){//如果登录 记录openid
            $where=array();
            //$where['openid']=$user_open_id;
             $where['session_id']=$s_session_id;
            $where['member_id']=$_SESSION["member"]['uid'];
            $member=M('member_rember_token')->where($where)->find();
            if($member && empty($member['openid'])){
                M('member_rember_token')->where($where)->save(array('openid'=>$user_open_id,'open_time'=>time()));
            }else{
              if($member['openid']){
                if($member['openid']==$user_open_id){
                  M('member_rember_token')->where($where)->save(array('open_time'=>time()));
                }else{
                   M('member_rember_token')->where($where)->save(array('openid'=>$user_open_id,'open_time'=>time()));
                }
              }

            }
          }
       }else{
         session('user_open_error',1);
       }
      }
      //return $access_token;
    }
     public function get_jsapi_ticket(){
      $jsapi_ticket=S('jsapi_ticket');
       if (empty($jsapi_ticket)) {
            $access_token=S('access_token');
            if(empty($access_token)){
              $access_token=$this->get_access_token();
            }
            if($access_token){
              $url="https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token={$access_token}&type=jsapi";
               //$str=file_get_contents($url);
               $str=$this->curl_https($url);
               $str=json_decode($str);
               if($str->errmsg =='ok'){
                  $jsapi_ticket=$str->ticket;//获取到的凭证
                  $expires_in=$str->expires_in;//凭证有效时间，单位：秒
				  $expires_in=$expires_in-500;
					if($expires_in<=0){
						$expires_in=0;
					}
                  S('jsapi_ticket',$jsapi_ticket,$expires_in);
               }
            }
       }
      return $jsapi_ticket;
    }
    //获取用户基本信息（包括UnionID机制）
     public function get_weixin_userinfo(){
        //$user_info=$_SESSION['user_info'];
        $user_open_id=session('user_open_id');
        if(empty($user_open_id)){
          $this->get_oauth2_openid();
          $user_open_id=session('user_open_id');
        }
       if (empty($user_info) && !empty($user_open_id)) {
            $access_token=S('access_token');
            if(empty($access_token)){
              $access_token=$this->get_access_token();
            }

            if($access_token){
              $url="https://api.weixin.qq.com/cgi-bin/user/info?access_token={$access_token}&openid={$user_open_id}&lang=zh_CN";
               //$str=file_get_contents($url);
               $str=$this->curl_https($url);
               $str=json_decode($str);
			 if($str->errcode == '40001'){
				 S('access_token',null);
				  $access_token=$this->get_access_token();
				  if($access_token){
              $url="https://api.weixin.qq.com/cgi-bin/user/info?access_token={$access_token}&openid={$user_open_id}&lang=zh_CN";
               //$str=file_get_contents($url);
					   $str=$this->curl_https($url);
					   $str=json_decode($str);
				  }
			 }

               if(empty($str->errcode)){
                  $user_info['subscribe']=$str->subscribe;//用户是否订阅该公众号标识，值为0时，代表此用户没有关注该公众号，拉取不到其余信息。
                  $user_info['nickname']=$str->nickname;//用户的昵称
                  $user_info['sex']=$str->sex;//用户的性别，值为1时是男性，值为2时是女性，值为0时是未知
                  $user_info['city']=$str->city;//用户所在城市
                  $user_info['country']=$str->country;//用户所在国家
                  $user_info['province']=$str->province;//用户所在省份
                  $user_info['headimgurl']=$str->headimgurl;//用户头像，最后一个数值代表正方形头像大小（有0、46、64、96、132数值可选，0代表640*640正方形头像），
                  //用户没有头像时该项为空。若用户更换头像，原有头像URL将失效。
                  $user_info['subscribe_time']=$str->subscribe_time;//用户关注时间，为时间戳。如果用户曾多次关注，则取最后关注时间

                  $_SESSION['user_info']=$user_info;
               }else{
				    $user_info['subscribe']=0;//用户是否订阅该公众号标识，值为0时，代表此用户没有关注该公众号，拉取不到其余信息。
				    $_SESSION['user_info']=$user_info;
			   }
            }
       }
       return $_SESSION['user_info'];
    }
    //获取微信接口所需信息
    public function get_weixin(){
       $url="http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
       $time=time();
      $access_token=$this->get_access_token();
      $jsapi_ticket=$this->get_jsapi_ticket();
      $noncestr=$time.rand(10000,99999);
      $sha1="jsapi_ticket={$jsapi_ticket}&noncestr={$noncestr}&timestamp={$time}&url={$url}";
      $signature=sha1($sha1);
      $this->appid=C('APPID');
      $this->url=$url;
      $this->time=$time;
      $this->access_token=$access_token;
      $this->jsapi_ticket=$jsapi_ticket;
      $this->noncestr=$noncestr;
      $this->signature=$signature;
    }
    //概率算法
  public  function get_rand($proArr) {
      $result = '';
      //概率数组的总概率精度
      $proSum = array_sum($proArr);
      //概率数组循环
      foreach ($proArr as $key => $proCur) {
          $randNum = mt_rand(1, $proSum);             //抽取随机数
          if ($randNum <= $proCur) {
              $result = $key;                         //得出结果
              break;
          } else {
              $proSum -= $proCur;
          }
      }
      unset ($proArr);
      return $result;
  }
  //赠送金额随机 5到X
 public function set_register_give_money($give_money){
     if($give_money<=5){
      return $give_money;
     }else{
        $arr=array();
        $give_money=ceil($give_money);
        $rand=150;
        $data=array();
        for ($i=5; $i <= $give_money; $i++) {

           if($i<=10){
              $data[$i]=$rand;
              $rand=$rand-10;
           }else if($i>10 && $i<=15){
              if($rand>20){
                 $rand=20;
              }
               $data[$i]=$rand;
               $rand=$rand-3;
           }else{
              if($rand>8){
                 $rand=8;
              }
             $data[$i]=$rand;
              $rand=$rand-1;
           }

        }
         $arr=$data;
         return $this->get_rand($arr);
     }


  }
    ///显示红包
    public function set_cookie_show($name='',$con){
      if($con){
        cookie('cookie_'.$name,1);
        return 1;
      }else{
         $val=cookie('cookie_'.$name);
          cookie('cookie_'.$name,1);
         return $val;
       }
    }
    //显示关注悬浮框
    public function set_float_concern(){
        $ret=false;
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
//        if(strpos($user_agent, 'MicroMessenger') != false){
            if (!empty($this->uid)){
                $where['id']=$this->uid;
                $is_guanzhu=M('member')->field('is_guanzhu')->where($where)->find();
                 $user_info=$_SESSION['user_info'];
                 if($user_info){
                   if($user_info['subscribe']==1){
                      $ret=true;
                   }
                 }else{
                    if($is_guanzhu['is_guanzhu']==1){
                      $ret=true;
                    }
                 }

            }
//        }
        return $ret;
    }
     //获取分类 cat_id 组合
    public function get_category_child($pid=0){

           $list=M('g_category')->where(array('parent_id'=>$pid,'is_show'=>1))->field('cat_id')->select();
          if($pid){
            $type_str=','.$pid;
          }else{
            $type_str=',';
          }
          if($list){//查下级
              $type_str1="";
              foreach ($list as $key => $value) {
                  $type_str1.=','.$value['cat_id'];
              }
              if($type_str1){
                $type_str.=$type_str1;
                $type_str1=substr($type_str1, 1);
                 if($type_str1){//查下级
                      $type_str2="";
                      $list=M('g_category')->where(array('parent_id'=>array('in',$type_str1),'is_show'=>1))->field('cat_id')->select();
                      foreach ($list as $key => $value) {
                          $type_str2.=','.$value['cat_id'];
                      }
                      if($type_str2){
                        $type_str.=$type_str2;
                        unset($list);
                      }
                  }
              }
          }
          if($type_str){
            $type_str=substr($type_str, 1);
          }
          return $type_str;
    }
    public function getShare($share){
            $last_access_time=$_SESSION["member"]['access_recommender_time']?$_SESSION["member"]['access_recommender_time']:0;
            session('share',$share);
			$share_info=M('member')->where('member_card="'.$share.'"')->find();
            $where['member_card']=$share;
			$access_time=$share_info['access_time'];
		if (date('Ymd')>date('Ymd',$access_time)){
			M('member')->where('id='.$share_info['id'])->setField('today_page_view',2);
			M('member')->where('id='.$share_info['id'])->setField('access_time',time());
		}
        if(date('YmdH')>date('YmdH',$last_access_time)){
            M('member')->where($where)->setInc('today_page_view');
            M('member')->where($where)->setInc('month_page_view');
            M('member')->where($where)->setInc('total_page_view');
			M('member')->where('id='.$share_info['id'])->setField('access_time',time());
			$_SESSION["member"]['access_recommender_time']=time();
        }

    }
    function recommend_qrcode(){
            $memberinfo=$this->getMemberInfo();
            $host_url=$url='HTTP://'.$_SERVER['HTTP_HOST'];
            $data=$host_url.U('wap/start/index',array('share'=>$memberinfo['member_card']));
            $water_url=".".$memberinfo['member_logo'];
            $recommend_qr=$this->get_qrcode($data,$water_url,null,null);
            return $recommend_qr;
    }
    function shop_qrcode(){
        $memberinfo=$this->getMemberInfo();
        $host_url=$url='HTTP://'.$_SERVER['HTTP_HOST'];
        $data=$host_url.U('wap/index/index',array('share'=>$memberinfo['member_card']));
        $water_url=".".$memberinfo['member_logo'];
        $recommend_qr=$this->get_qrcode($data,$water_url,null,null);
        return $recommend_qr;
    }
}