<?php
/**
 * 购物车
 *
 * 
 *
 */
class CartModel extends Model {

     /**
     * 购物车列表
     * @param unknown $condition
     * @param string $pagesize
     * @param string $field
     * @param string $order
     * @param string $limit
     * @return Ambigous <multitype:boolean Ambigous <string, mixed> , unknown>
     */
    public function getCartList($condition, $pagesize = '', $field = '*', $order = '', $limit = ''){
        $list = M('g_cart')->field($field)->where($condition)->order($order)->limit($limit)->select();
        if (empty($list)) return array();
       
        return $list;
    }

    /**
     * 所有购物车
     * @param unknown $condition
     * @param string $field
     * @param string $order
     * @return Ambigous <multitype:boolean Ambigous <string, mixed> , unknown>
     */
    public function getCartAll($condition,$field = '*', $order = ''){
        $list = M('g_cart')->field($field)->where($condition)->order($order)->select();
        if (empty($list)) return array();
        return $list;
    }
       /**
     * 添加购物车
     *
     * @param unknown_type $data
     */
    public function addCart($data) {
        return M('g_cart')->add($data);
    }   
    /**
	 * 更改购物车
	 *
	 * @param unknown_type $data
	 * @param unknown_type $condition
	 */
	public function editCart($data,$condition) {
		return M('g_cart')->where($condition)->save($data);
	}
    
     /**
     * 计算购物车价格
     *
     * @param unknown_type $data
     * @param unknown_type $condition
     */
    public function getCartSettlement($list=array(), $province=0) {

        $tolal=$need_pay=$all_num=0;
        $ret_good_data=array();
        $cart_str="";
        $error="";
        $status=0;
        $shipping_money_total = 0.00;
        if($list){
            $status=1;
            $model=M('g_goods');
            $goods_model=D('Goods');
            $goods_attr_model=M('g_goods_attr');
 /*
      $goods_field='shop_price,market_price,goods_id,is_on_sale,goods_weight,shipping_money,'//
             .'is_refund,shipping_id,is_exchange,is_auditing,is_activity,activity_price,'//
             .'activity_start_date,activity_end_date,is_end_activity_sale,offline';
 */
            $goods_field="*";
            $is_gift=$is_upgrade=0;
            $u_where['id']=$_SESSION["member"]['uid'];
            $activity_id= 0;
            foreach ($list as $key => $value) {
            // $shop=$value['shop'];
                $activity_id= $value['rec_type'];
                $where=array();
                $where['goods_id']=$value['goods_id'];
                $where['is_delete']=0;
                $goods=$model->where($where)->field($goods_field)->find();
                 $goods=$goods_model->getGoodsPrice($goods,$activity_id);//计算商品价格 1元嗨购等
                if(empty($goods) || empty($goods['is_on_sale']) || empty($goods['is_auditing'])){
                    $error.=$value['goods_id'].'【已下架】,';
                    $status=0;
                }else{
                    if($goods['is_buy']=='-1'){
                    $error.=$value['goods_id'].'【'.$goods['is_buy_msg'].'】,';
                    $status=0;
                    }
                }
                if($activity_id > 0){///活动活动价
                    $is_gift=1;
                }
                $goods_shipping="";
                $shipping_code="";
                if($goods['shipping_id']){
                    $shipping=M('g_shipping')->where(array('shipping_id'=>$goods['shipping_id']))->find();
                    $goods_shipping=$shipping['shipping_name'];
                    $shipping_code=$shipping['shipping_code'];
                }
                $value['goods_shipping']=$goods_shipping;//配送方式
                $value['shipping_code']=$shipping_code;//配送方式

                 $value['offline']=$goods['offline'];//货到付款
                 $value['is_upgrade']=$goods['is_upgrade'];//升级产品
                if($goods['is_upgrade']==1){
                    $is_upgrade=1;
                }
                 $value['rec_type'] = $activity_id;//活动类型0 无 1 1元嗨购
                 if($value['rec_type'] != 0){
                    $value['activity_start_date']=$goods['activity_start_date'];
                    $value['activity_end_date']=$goods['activity_end_date'];
                    $value['activity_name'] = $goods['activity_name'];
                 }
                 $goods_price=$goods['shop_price'];
                 $market_price=$goods['market_price'];
                 $goods_attr_id=$value['goods_attr_id'];
                 $share_money=array();///属性佣金
                  if($goods_attr_id){
                        $goods_price_arr=array();
                        $goods_attr=$goods_attr_model->where(array('goods_attr_id'=>array('in',$goods_attr_id)))->select();
                        $attr_str ='';
                         foreach ($goods_attr as $keys => $attr_value) {
                            if($attr_value){
                                if($attr_value['attr_price']){
                                    //判断是否会员  是否按照会员价结算
                                    if($value['is_vip']==1){
                                        $goods_price_arr[] = $attr_value['attr_vip_price'];
                                    }else{
                                        $goods_price_arr[] = $attr_value['attr_price'];
                                    }
                                }
                                if($attr_value['attr_share_money']){
                                    $share_money[]=$attr_value['attr_share_money'];///属性佣金
                                }
                                $attr_str.=','.$attr_value['attr_value'];
                            }
                        }
                    }
                    if(!empty($goods_price_arr)){
                        $goods_price_arr=max($goods_price_arr);///属性价格取属性的最大值
                    }
                    if(empty($goods_price_arr)){
                        $goods_price_arr=0;
                    }
                    if(!empty($share_money)){
                        $share_money=max($share_money);///属性佣金属性佣金的最大值
                    }
                    if(empty($share_money)){
                        $share_money=0;
                    }
                    if($goods_price_arr>0){///如果属性价格大于0 取属性价格
                        if($goods_price<$goods_price_arr){/////计算显示的原价价格
                          $market_price=$market_price+($goods_price_arr-$goods_price);
                        }
                        //如果属性价格小于商品价格 亦取属性价格
                        $goods_price=$goods_price_arr;
                    }
                    ///商品佣金 =属性佣金属性、商品佣金的最大值
                 //$goods['share_money']=($share_money>$goods['share_money'])?$share_money:$goods['share_money'];
                  //如果属性佣金属性小于商品属性 亦取属性佣金
                // $goods['share_money']=($share_money>0)?$share_money:$data['share_money'];
                 $goods['market_price']=$market_price;
                 $goods['shop_price']=$goods_price;    
                if($value['goods_price']!=$goods['shop_price'] || $value['share_money']!=$goods['share_money']){
                   $value['goods_price'] =$goods['shop_price'];
                   $value['market_price'] =$goods['market_price'];
                   $value['share_money'] =$goods['share_money'];
                   $cart_data=array('market_price'=>$goods['market_price'],'goods_price'=>$goods['shop_price']);
                   $cart_data['rec_type'] = $activity_id;//活动类型0 无 1 1元嗨购
                   $cart_data['share_money'] =$goods['share_money'];
                   M('g_cart')->where(array('rec_id'=>$value['rec_id']))->save($cart_data);//更新 购物车价格
                }
                $postage_s = postage_trans($goods['postage_json']);
				$shipping_money = 0.00;
                $shipping_money_add = 0.00;
                if( $province != 0){
                    $address_postage = array();
                    $postage_unit = $value['postage_unit'];// 邮费计价单位 0 件，1 kg
                    foreach ($postage_s  as $k => $v){
                        if($province == $v['provinceid']){
                            $address_postage =$v;
                        }
                    }
                    $by_unit = $address_postage['by_unit'];//包邮 单位 -1不包邮 0 件， 1 kg， 2 元 ,3 完全包邮
                    $by_condition = $address_postage['by_condition'];
                    $_this_piece = $value['goods_number'];//商品件数
                    $_this_weight = mb_number($value['goods_number']) * mb_number($goods['goods_weight']);//商品重量
                    $_this_money = mb_number($value['goods_number']) * mb_number($value['goods_price']) ;//商品金额
                    if( $by_unit == 3 ||($by_unit == 0 && $_this_piece >= $by_condition) || ($by_unit == 1 && $_this_weight >= $by_condition) || ($by_unit == 2 && $_this_money >= $by_condition )){
                        $shipping_money_add = 0.00;//如果满足条件  就包邮
                    }else{
                        $shipping_money += $address_postage['first_price'];//首重或首件 的运费
                        if($postage_unit == 1){
                            //如果以重量为单位寄送寄送
                            if($_this_weight > $address_postage['first']){//续重
                                $shipping_money_add = ceil(($_this_weight - $address_postage['first'])/$address_postage['add']) * $address_postage['add_price'];
                            }
                        }else{
                            //如果以件数未单位 寄送
                            if($_this_piece > $address_postage['first']){//续件
                                $shipping_money_add = ($_this_piece - $address_postage['first']) * $address_postage['add_price'];
                            }
                        }
                    }
                    $shipping_money += $shipping_money_add;
                }
                $pay_price=$value['goods_price'];
                $num=$value['goods_number'];
                $value['is_refund']=$goods['is_refund'];//是否可退货 1是 0否
                $value['is_exchange']=$goods['is_exchange'];//是否可退货 1是 0否
                $value['goods_weight']=$goods['goods_weight'];//是否可退货 1是 0否
                $value['gift_integral'] = $goods['gift_integral'];//购买送积分
                $value['shipping_money']=$shipping_money;//该商品邮费
                $value['base_id']=$goods['base_id'];
                $value['all_goods_price']=$pay_price*$num;//总价 商铺价
                $value['all_market_price']=$value['market_price']*$num;//总价 市场价
                $ret_good_data[]=$value;
                $all_num+=$num;
                $tolal+=$value['all_market_price'];
                $need_pay+=$value['all_goods_price'];
                $cart_str.=','.$value['rec_id'];
                $shipping_money_total += $shipping_money;
            }
        }
        if($cart_str){
            $cart_str=substr($cart_str, 1);
        }
        $return_arr['shipping_fee'] = $shipping_money_total;
        $return_arr['all_num']=$all_num;
        $return_arr['tolal']=$tolal;
        $return_arr['need_pay']=$need_pay ;
        $return_arr['goods_list']=$ret_good_data;
        $return_arr['cart_id']=$cart_str;
        $return_arr['error']=$error;
        $return_arr['status']=$status;
        $return_arr['is_gift']=$is_gift;
        $return_arr['activity_id'] = $activity_id;
        $return_arr['is_upgrade']=$is_upgrade;

        return $return_arr;
    }
    /**
     * 计算升级商品价格
     *
     * @param unknown_type $data
     * @param unknown_type $condition
     */
    public function getUpgradeSettlement($list=array()) {
        $tolal=$need_pay=$all_num=0;
        $ret_good_data=array();
        $cart_str="";
        $error="";
        $status=0;
        $rec_type=0;
        if(sizeof($list) == 1){
            $rec_type=$list['0']['rec_type'];
        }
        if($list){
            $status=1;
            $model=M('upgrade_goods');
            $goods_attr_model=M('g_goods_attr');
            $goods_field='shop_price,market_price,goods_id,is_on_sale,goods_weight,'//
                .'shipping_id,is_auditing';//
               // .'activity_start_date,activity_end_date,is_end_activity_sale,';
            $is_gift=0;
            foreach ($list as $key => $value) {
                // $shop=$value['shop'];
                $where=array();
                $where['goods_id']=$value['goods_id'];
                $where['is_delete']=0;
                $goods=$model->where($where)->field($goods_field)->find();
             /*   echo $model->getLastSql();
                var_dump($goods);*/
                if(empty($goods) || empty($goods['is_on_sale']) || empty($goods['is_auditing'])){
                    $error.=$value['goods_name'].'【已下架】,';
                    $status=0;
                }else{
                    if($goods['is_buy']=='-1'){
                        $error.=$value['goods_name'].'【'.$goods['is_buy_msg'].'】,';
                        $status=0;
                    }
                }
                if($goods['hot_type']==1){
                    $is_gift=1;
                }
                $goods_shipping="";
                $shipping_code="";
                if($goods['shipping_id']){
                    $shipping=M('g_shipping')->where(array('shipping_id'=>$goods['shipping_id']))->find();
                    $goods_shipping=$shipping['shipping_name'];
                    $shipping_code=$shipping['shipping_code'];
                }
                $value['goods_shipping']=$goods_shipping;//配送方式
                $value['shipping_code']=$shipping_code;//配送方式

              //  $value['offline']=$goods['offline'];//货到付款

                $value['rec_type'] =$rec_type;//活动类型0 无 1 1元嗨购
                if($value['rec_type'] != 0){
                    $value['activity_start_date']=$goods['activity_start_date'];
                    $value['activity_end_date']=$goods['activity_end_date'];
                }
                $goods_price=$goods['shop_price'];
                $market_price=$goods['market_price'];
                $goods_attr_id=$value['goods_attr_id'];
                $share_money=array();///属性佣金
                if($goods_attr_id){
                    $goods_price_arr=array();
                    $goods_attr=$goods_attr_model->where(array('goods_attr_id'=>array('in',$goods_attr_id)))->select();
                    $attr_str = '';
                    foreach ($goods_attr as $keys => $attr_value) {
                        if($attr_value){
                            if($attr_value['attr_price']){
                                $goods_price_arr[]=$attr_value['attr_price'];
                            }
                            if($attr_value['attr_share_money']){
                                $share_money[]=$attr_value['attr_share_money'];///属性佣金
                            }
                            $attr_str.=','.$attr_value['attr_value'];
                        }
                    }
                }
                if(!empty($goods_price_arr)){
                    $goods_price_arr=max($goods_price_arr);///属性价格取属性的最大值
                }
                if(empty($goods_price_arr)){
                    $goods_price_arr=0;
                }
                if(!empty($share_money)){
                    $share_money=max($share_money);///属性佣金属性佣金的最大值
                }
                if(empty($share_money)){
                    $share_money=0;
                }
                if($goods_price_arr>0){///如果属性价格大于0 取属性价格
                    if($goods_price<$goods_price_arr){/////计算显示的原价价格
                        $market_price=$market_price+($goods_price_arr-$goods_price);
                    }
                    //如果属性价格小于商品价格 亦取属性价格
                    $goods_price=$goods_price_arr;
                }
                ///商品佣金 =属性佣金属性、商品佣金的最大值
                //$goods['share_money']=($share_money>$goods['share_money'])?$share_money:$goods['share_money'];
                //如果属性佣金属性小于商品属性 亦取属性佣金
                //$goods['share_money']=($share_money>0)?$share_money:$data['share_money'];
                $goods['market_price']=$market_price;
                $goods['shop_price']=$goods_price;
                if($value['goods_price']!=$goods['shop_price'] || $value['share_money']!=$goods['share_money']){
                    $value['goods_price'] =$goods['shop_price'];
                    $value['market_price'] =$goods['market_price'];
                    $value['share_money'] =$goods['share_money'];
                }
                $pay_price=$value['goods_price'];
                $num=$value['goods_number'];
                $value['is_refund']=$goods['is_refund'];//是否可退货 1是 0否
                $value['is_exchange']=$goods['is_exchange'];//是否可退货 1是 0否
                $value['goods_weight']=$goods['goods_weight'];//是否可退货 1是 0否
                $value['shipping_money']=$goods['shipping_money'];//是否可退货 1是 0否
                $value['all_goods_price']=$pay_price*$num;//总价 商铺价
                $value['all_goods_price']=$pay_price*$num;//总价 商铺价
                $value['all_market_price']=$value['market_price']*$num;//总价 市场价

                $ret_good_data[]=$value;
                $all_num+=$num;
                $tolal+=$value['all_market_price'];
                $need_pay+=$value['all_goods_price'];
                $cart_str.=','.$value['rec_id'];
            }
        }
        if($cart_str){
            $cart_str=substr($cart_str, 1);
        }
        $return_arr['all_num']=$all_num;
        $return_arr['tolal']=$tolal;
        $return_arr['need_pay']=$need_pay;
        $return_arr['goods_list']=$ret_good_data;
        $return_arr['cart_id']=$cart_str;
        $return_arr['error']=$error;
        $return_arr['status']=$status;
        $return_arr['is_gift']=$is_gift;
        return $return_arr;
    }
    //折扣 
    public function get_member_discount($uid=null){
            if(empty($uid)){
                return array();
            }
            $time=time();
            $discount['member_id']=$uid;
            $discount['start_time']=array('elt',$time);
            $discount['end_time']=array('egt',$time);
            $discount['discount']=array('between',array(0.01,0.99));
            $res=M('member_discount')->where($discount)->order('discount asc')->select();
            if($res){
                foreach ($res as $key => $value) {
                   $value['type_name'] ="";
                    $value['discount_val'] =$value['discount']*10;
                    switch ($value['type']) {
                        case '1':
                            $value['type_name']="抽奖";
                            break;
                        default:
                            # code...
                            break;
                    }
                   $res[$key]=$value; 
                }
            }
            return $res;
    }
    //折扣 
    public function get_member_discount_one($id=null,$uid=null){
            if(empty($uid) || empty($id)){
                return array();
            }
            $time=time();
            $discount['id']=$id;
            $discount['member_id']=$uid;
            $discount['start_time']=array('elt',$time);
            $discount['end_time']=array('egt',$time);
            $discount['discount']=array('between',array(0.01,0.99));
            $res=M('member_discount')->where($discount)->order('discount asc')->find();
            if($res){
                 $res['type_name'] ="";
                    $res['discount_val'] =$res['discount']*10;
                    switch ($res['type']) {
                        case '1':
                            $res['type_name']="抽奖";
                            break;
                        default:
                            # code...
                            break;
                    }
            }
            return $res;
    }
}
