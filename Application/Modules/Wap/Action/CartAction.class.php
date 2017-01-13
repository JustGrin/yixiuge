<?php

// 购物车
class CartAction extends BaseAction
{
    //魔术方法
    public function __construct()
    {

        parent::__construct();
        $this->set_cart();
        if (!IS_AJAX) {
            $this->now_menu = 'cart';//菜单
            $share_inof=$this->shop_info;
            $shar_title = $this->webseting['web_title'] .'-首页';//C('SHAR_TITLE');
            $this->shar_url = $url = "http://" . $_SERVER['HTTP_HOST'] . U('wap/index/index', array('share' => $this->shop_code));
            $this->shar_title = $shar_title;
            $this->shar_desc = C('SHAR_DESC');
            $this->shar_imgurl = "http://" . $_SERVER['HTTP_HOST'] . $share_inof['member_logo'];///分享图片地址
            $this->get_weixin();///获取微信 信息
        }
    }

    public function index()
    {
        $this->get_cart();
        $this->discount = D('Cart')->get_member_discount($this->uid);
        $this->display();

    }

    //加入购物车
    public function add_cart()
    {
        $return_data['status'] = 0;
        $goods_attr_id = $_GET['goods_attr_id'];
        $goods_number = $_GET['goods_num'];
        $goods_id = $_GET['goods_id'];
        $share = $_GET['share'];
        $activity_type = $_GET['activity_type']?$_GET['activity_type']:0;
        $data = M('g_goods')->where(array('goods_id' => $goods_id))->find();
        //如果是9.9大聚惠活动则商品时间按照统一设定
        if ($data['activity_id'] == 2) {
            $activity99 = M("activity")->where("id=2")->find();
            $data['activity_start_date'] = $activity99['start_time'];
            $data['activity_end_date'] = $activity99['end_time'];
        }
        $data = D('Goods')->getGoodsPrice($data,$activity_type);//计算商品价格 1元嗨购等
        if ($data['activity_id'] == 2) {
            $pre = C('DB_PREFIX');
            $activity99=M("activity")->where('id=2')->find();
            $where_goods_list1['info.add_time'] = array("egt", $activity99['start_time']);
            $where_goods_list1['info.add_time'] = array("lt", $activity99['end_time']);
            $where_goods_list1['goods.goods_id'] = array("eq", $data['goods_id']);
            $where_goods_list1['info.order_status'] = array("in", "0,1,5");
            $where_goods_list1['info.activity_id'] = array("eq", "2");
            $where_goods_list1['info.user_id'] = array("eq", $this->uid);
            $order_goods_list1 = M()->table($pre . 'g_order_goods goods')//
            ->join($pre . 'g_order_info info on info.order_id=goods.order_id')//
            ->where($where_goods_list1)//
            ->select();
            if (!empty($order_goods_list1)) {
                $return_data['error'] = "亲，每个商品只能购买一件";
                echo json_encode($return_data);
                die;
            }
        }
        if (empty($data)) {
            $return_data['error'] = "商品不存在";
            echo json_encode($return_data);
            die;
        }
		if($data['is_sell_out']){
			$return_data['error'] = "商品已售罄";
            echo json_encode($return_data);
            die;
		}
        if (empty($data['is_on_sale'])) {
            $return_data['error'] = "商品已下架";
            echo json_encode($return_data);
            die;
        }
        if (empty($data['is_auditing'])) {
            $return_data['error'] = "商品已下架";
            echo json_encode($return_data);
            die;
        }
        if ($data['activity_status'] == '-1') {///活动禁止购买
            $return_data['error'] = $data['is_buy_msg'];
            echo json_encode($return_data);
            die;
        }
        if ($data['activity_id'] != 0 && $data['activity_status'] == '0') {
            $return_data['error'] = $data['is_buy_msg'];
            echo json_encode($return_data);
            die;
        }

        $session_id = session('session_id');
        $add_data['user_id'] = $this->uid;
        $add_data['session_id'] = $session_id;
        $add_data['goods_id'] = $data['goods_id'];
        $add_data['goods_sn'] = $data['goods_sn'];
        $add_data['goods_name'] = $data['goods_name'];
        $add_data['goods_img'] = $data['goods_img'];
        $add_data['rec_type'] = $data['activity_id'];//活动类型0、无 1、1元嗨购 2、9.9大聚惠
        $add_data['share_card'] = $share;

        $add_data['goods_number'] = $goods_number;
        $add_data['goods_attr_id'] = $goods_attr_id;
        $add_data['add_time'] = time();
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
        $cart_where['session_id'] = $session_id;
        $cart_where['goods_id'] = $data['goods_id'];
        $cart_where['goods_attr_id'] = $goods_attr_id;
        $cart_where['rec_type'] = $activity_type;
        $cart = M('g_cart')->where($cart_where)->find();
        if ($cart) {//购物车已经有了
            $save_arr = array();
            $save_arr = array('goods_number' => $goods_number);
            $save_arr['share_money'] = $data['share_money'];
            if ($share) {
                $save_arr['share_card'] = $share;
            }
            $return_data['add_data'] = $save_arr;
            $res = M('g_cart')->where($cart_where)->save($save_arr);
            if ($res !== false) {//修改购物车
                $return_data['status'] = 1;
                $return_data['cart_id'] = $cart['rec_id'];
                echo json_encode($return_data);
                die;
            } else {
                $return_data['error'] = "加入失败";
                echo json_encode($return_data);
                die;
            }

        }
        //$add_data['rec_type'] = 0; 默认值零
        $return_data['add_data'] = $add_data;

        $res = M('g_cart')->add($add_data);
        if ($res !== false) {
            $return_data['status'] = 1;
            $return_data['cart_id'] = $res;
        } else {
            $return_data['error'] = "加入失败";
        }
        echo json_encode($return_data);
        die;
    }

    //加入购物车
    public function save_cart()
    {
        $return_data['status'] = 0;
        $session_id = session('session_id');
        $rec_id = $_GET['rec_id'];
        $goods_number = $_GET['goods_num'];

        $cart_where['rec_id'] = $rec_id;
        $cart = M('g_cart')->where($cart_where)->find();
        if (empty($cart)) {//购物车已经有了
            $return_data['error'] = "购物车里面没有此商品";
            echo json_encode($return_data);
            die;
        }
        $res = M('g_cart')->where($cart_where)->save(array('goods_number' => $goods_number));
        if ($res !== false) {
            $return_data['status'] = 1;
        } else {
            $return_data['error'] = "修改购物车失败";
        }
        echo json_encode($return_data);
        die;
    }

    //删除购物车产品
    public function del_cat()
    {
        $return_data['status'] = 0;
        $session_id = session('session_id');
        $rec_id = $_GET['rec_id'];
        $goods_number = $_GET['goods_number'];

        $cart_where['rec_id'] = $rec_id;
        if ($this->uid) {
            $cart_where['user_id'] = $this->uid;
        } else {
            $cart_where['session_id'] = $session_id;
        }
        $res = M('g_cart')->where($cart_where)->delete();
        if ($res !== false) {
            $return_data['status'] = 1;
        } else {
            $return_data['error'] = "修改购物车失败";
        }
        echo json_encode($return_data);
        die;
    }

    //删除购物车产品
    public function clean_cat()
    {
        $return_data['status'] = 0;
        $session_id = session('session_id');
        if ($this->uid) {
            $cart_where['user_id'] = $this->uid;
        } else {
            $cart_where['session_id'] = $session_id;
        }
        $res = M('g_cart')->where($cart_where)->delete();
        if ($res !== false) {
            $return_data['status'] = 1;
        } else {
            $return_data['error'] = "清空购物车失败";
        }
        echo json_encode($return_data);
        die;
    }

    //获取购物车
    public function get_cart()
    {
        $cart_model = D('Cart');
        if ($this->uid) {
            $cart_where['user_id'] = $this->uid;
        } else {
            $session_id = session('session_id');
            $cart_where['session_id'] = $session_id;
        }
        $list = $cart_model->getCartAll($cart_where);
        foreach ($list as $k=>$item){
            $list[$k]['goods_img'] = thumbs_auto($item['goods_img'], 200, 200);
        }
        //计算价格
        $return_arr = $cart_model->getCartSettlement($list);
        if (IS_AJAX) {
            echo json_encode($return_arr);
            die;
        }
        $this->list = $return_arr;
        //var_dump($return_arr);

    }

    ///服务 订单查看
    public function order_add()
	{
		$cart_id = $_GET['cart_id'];
		$discount = $_GET['discount'];
		$cart_model = D('Cart');
		if ($this->uid) {
			$where['user_id'] = $this->uid;
		} else {
			$session_id = session('session_id');
			$where['session_id'] = $session_id;
		}
		$where['rec_id'] = array('in', $cart_id);
		$list = $cart_model->getCartAll($where);
        foreach ($list as $k=>$item){
            $list[$k]['goods_img'] = thumbs_auto($item['goods_img'], 200, 200);
        }
        //计算价格
        $return_arr = $cart_model->getCartSettlement($list);
        /*if(empty($list['status'])){
          $this->error($list['error']);
        }*/
        $this->list = $return_arr;
        if ($this->uid) {
            $this->address = M('g_user_address')->where(array('user_id' => $this->uid))->order('is_default desc')->select();
            $this->default_address = M('g_user_address')->where(array('user_id' => $this->uid))->order('is_default desc')->find();
            $this->discount = D('Cart')->get_member_discount($this->uid);
        }
		$share=session('share');
		$member_info=$this->getMemberInfo();
		$share = $share ? $share : INDEX_CARD ;
		$code = $member_info['recommend_code'] ? $member_info['recommend_code'] :$share ;
        $recommend_info=M('member')->where('member_card="'.$code.'"')->find();
        $this->assign('recommend_info',$recommend_info);
        //活动类型

		$this->city_group = get_city_group();
        $this->display();
	}

    ///快速购买
    public function fastbuy()
    {
        $citydata = array('province' => 1);
        $citylist = $this->get_city_return($citydata);
        $this->citylist = $citylist;
        $this->display();
    }

    ///服务 订单查看
    public function upgrade_order_add()
    {
        $goods_attr_id = $_GET['goods_attr_id'];
        $goods_number = $_GET['goods_num'];
        $where['is_delete'] = 0;
        $where['goods_id']= $_GET['goods_id'];
        $data = M('g_goods')->field('*')->where($where)->find();
        $data = D('Goods')->getGoodsPrice($data);//计算商品价格 1元嗨购等
        $data['goods_num']= $goods_number;
        //运送方式及运费
        if($data['shipping_id']){
            $shipping = M('g_shipping')->where(array('shipping_id' => $data['shipping_id']))->find();
            $data['goods_shipping'] = $shipping['shipping_name'];
            $data['shipping_code'] = $shipping['shipping_code'];
        }
        //计算价格
        $goods_price = $data['shop_price'];
        $market_price = $data['market_price'];
        if($goods_attr_id){
            $goods_attr = M('g_goods_attr')->where(array('goods_attr_id' => array('in', $goods_attr_id)))->select();
            $attr_str='';
            $goods_price_arr=array();
            foreach ($goods_attr as $keys => $attr_value) { //取属性价格及属性值
                if ($attr_value) {
                    if ($attr_value['attr_price']) {
                        $goods_price_arr[] = $attr_value['attr_price'];
                    }
                    if(empty($attr_str)){
                        $attr_str = $attr_value['attr_value'];
                    }else{
                        $attr_str .= ',' . $attr_value['attr_value'];
                    }
                }
            }
        }
        if (!empty($goods_price_arr)) {
            $goods_price_arr = max($goods_price_arr);///属性价格取属性的最大值
        }
        if (empty($goods_price_arr)) {
            $goods_price_arr = 0;
        }
        if ($goods_price_arr > 0) {///如果属性价格大于0 取属性价格
            if ($goods_price < $goods_price_arr) {/////计算显示的原价价格
                $market_price = $market_price + ($goods_price_arr - $goods_price);
            }
            //如果属性价格小于商品价格 亦取属性价格
            $goods_price = $goods_price_arr;
        }
        $data['goods_attr']=$attr_str;
        $data['market_price'] = $market_price;
        $data['shop_price'] = $goods_price;
        $data['need_pay']=$goods_price* $goods_number;
//        if($data['goods_price'] !=)
        if ($this->uid) {
            $this->address = M('g_user_address')->where(array('user_id' => $this->uid))->order('is_default desc')->select();
           // $this->discount = D('Cart')->get_member_discount($this->uid);
        }
//        var_dump($data);
//        die;
        $this->uri='goods_id='.$_GET['goods_id'].'||goods_attr_id='.$_GET['goods_attr_id'].'||goods_num='.$_GET['goods_num'];
        $this->data=$data;
        $this->display();
    }

}