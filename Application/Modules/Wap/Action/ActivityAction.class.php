<?php
// 产品
class ActivityAction extends BaseAction {
    //魔术方法
    public function __construct(){
        parent::__construct();

        if(!IS_AJAX){
            
        }
    }

    //产品列表
    public function index(){
        $order=$_REQUEST['order'];
        if($order){
            switch ($order) {
                case 'prices1':
                    //价格 倒序
                    $order="shop_price desc";
                    break;
                case 'prices2':
                    //价格 升序
                    $order="shop_price asc";
                    break;
                case 'browse1':
                    $order="goods_browse desc";
                    break;
                case 'browse2':
                    $order="goods_browse asc";
                    break;
                case 'sales1':
                    $order="goods_salesvolume desc";
                    break;
                case 'sales2':
                    $order="goods_salesvolume asc";
                    break;
                default:
                    unset($order);
                    $order="goods_id desc";
                    # code...
                    break;
            }
        }else{
            $order="goods_id desc";
        }
        $cryp_cat_id=$this->get_category_child('19');//剔除 成人用品
        $g_where['cat_id']=array('not in',$cryp_cat_id);
        $type=$_REQUEST['type'];
        $type=intval($type);
        if($type){
            $pid=M('g_category')->where(array('cat_id'=>$type))->getField('cat_id');

            if($pid){
                $type_str=$this->get_category_child($pid);
                if($type_str){
                    $g_where['cat_id']=array('in',$type_str);
                }
            }
        }
        $search =$_REQUEST['search'];
        if($search){  //查询关键词
            $g_where['goods_name'] =array('like','%'.$search.'%');
        }

        //商家推荐
        $p=$_REQUEST['p'];
        $pagesize=10;
        $p=!empty($p)?$p:1;
        $start=($p-1)*$pagesize;
        $g_where['is_on_sale']=1;//该商品是否开放销售，1，是；0， 否
        $g_where['is_delete']=0;//商品是否已经删除，0，否；1，已 删除
        $g_where['is_auditing']=1;//商品是否通过审核  1是0 否
        $g_where['is_upgrade']=0;//剔除升级产品

        $order = "is_sell_out asc,".$order;//将售罄商品排在最后
        $g_field="goods_id,goods_name,shop_price,market_price,goods_brief,goods_img,vip_price,unit_price,units,goods_browse,goods_salesvolume,base_name,base_logo,is_sell_out";
        $goods_list=M("g_goods")->where($g_where)->field($g_field)->limit($start,$pagesize)->order($order)->select();
        //判断是否会员
        $u_where['id']=$_SESSION['member']['uid'];
        $vip_type=M('member')->where($u_where)->getField('member_vip_type');
        $this->vip_type=$vip_type;
        if($vip_type ==1){
            foreach ($goods_list as $k => $v) {
                $goods_list[$k]['vip_p_show'] = 0;//vip 价格
                if ($v['vip_price'] > 0) {
                    $goods_list[$k]['market_price'] = $v['shop_price'];
                    $goods_list[$k]['shop_price'] = $v['vip_price'];
                    $goods_list[$k]['vip_p_show'] = 1;
                    $goods_list[$k]['units_price']=$v['unit_price'];
                    $goods_list[$k]['units']=$v['units'];
                }
            }
        }else{
            foreach ($goods_list as $k => $v) {
                $goods_list[$k]['vip_p_show'] = 0;//vip 价格
            }
        }
        if(IS_AJAX){
            echo json_encode($goods_list);die;
        }
        $this->list=$goods_list;
        $this->count=M("g_goods")->where($g_where)->count();

        $typename=M('g_category')->where(array('cat_id'=>$type))->getField('cat_name');
        $this->typename=$typename?'-'.$typename:'';

        $shop_info=$this->shop_info;
        $shar_title=$this->webseting['web_title'].'-商品';//C('SHAR_TITLE');//'商城--分享赚佣金';
        $this->shar_url= $url="http://".$_SERVER['HTTP_HOST'].U('wap/goods/index',array('share'=>$this->shop_code));
        $this->shar_title=$shar_title;
        $this->shar_desc=C('SHAR_DESC');
        $this->shar_imgurl="http://".$_SERVER['HTTP_HOST'].$shop_info['member_logo'];///分享图片地址
        $this->get_weixin();///获取微信 信息

        // $return_url=U('wap/goods/index');
        // if($_GET['type']=='made'){
        // $return_url=U('wap/goods/made');
        // }
        $this->return_url = U('wap/goods/index');

        $this->webtitle="峰购-商品列表";
        $this->display();
    }


    //1元购活动页面
    public function oneYuan(){
        $g_where['is_on_sale']=1;//该商品是否开放销售，1，是；0， 否
        $g_where['is_delete']=0;//商品是否已经删除，0，否；1，已 删除
        $g_where['is_auditing']=1;//商品是否通过审核  1是0 否
        $g_where['is_upgrade']=0;//剔除升级产品
        $g_where['activity_id'] = 1 ; //查询1元购活动商品
        $goods_list=M("g_goods")->where($g_where)->select();
        foreach ($goods_list as $k =>$v){
            $activity_goods=D('Goods')->getGoodsPrice($v,1);
            $end_time='';
             $h=intval($activity_goods['end_time']/3600);
             $i=intval(($activity_goods['end_time'] -$h*3600)/60);
            $s= $activity_goods['end_time'] -$h*3600 -$i*60;
            $i=$i<10 ? '0'.$i : $i;
            $s=$s<10 ? '0'.$s : $s;
            $end_time =$h.':'.$i.':'.$s;
            $activity_goods['end_time'] =$end_time;
            $goods_list[$k] =$activity_goods;
        }

        $this->list=$goods_list;
        $this->display();
    }
    //9.9元活动页面
    public function activity99(){
        $g_where['is_on_sale']=1;//该商品是否开放销售，1，是；0， 否
        $g_where['is_delete']=0;//商品是否已经删除，0，否；1，已 删除
        $g_where['is_auditing']=1;//商品是否通过审核  1是0 否
        $g_where['is_upgrade']=0;//剔除升级产品
        $g_where['activity_id'] = 2 ; //查询9.9元活动商品
        $goods_list=M("g_goods")->where($g_where)->select();
        $activity=M("activity")->where('id=2')->find();
        $para_arr= json_decode($activity['param'],true);
        foreach ($goods_list as $k =>$v){
            $activity_goods=D('Goods')->getGoodsPrice($v,2);
            foreach ($para_arr['activity_goods_msg'] as $key => $value) {
                if ($key == $activity_goods['goods_id']) {
                    $value = explode(",", $value);
                    //限定总量
                    $num[1] = $value[1];
                    $activity_goods_where['goods.goods_id'] = $activity_goods['goods_id'];
                    $activity_goods_where['info.pay_status'] = 2;//已付款
                    $activity_goods_where['info.activity_id'] = 2;//本次活动id
                    $activity_goods_where['info.pay_time'] = array('egt',$activity['start_time']);//开始时间
                    $activity_goods_where['info.pay_time'] = array('lt',$activity['end_time']);//结束时间
                    $pre = C('DB_PREFIX');
                    //销售量
                    $num[0]=M()->table($pre . 'g_order_goods goods')//
                    ->join($pre . 'g_order_info info on info.order_id=goods.order_id')//
                    ->where($activity_goods_where)//
                    ->sum('goods.goods_number');
                    $num[0] = ($num[0]>=$value[0]) ? $num[0] : $value[0];
                    $activity_goods['sell_count'] = intval($num[0]);
                    $activity_goods['sell_percent'] = ($num[0]*100)/($num[1]);
                    if ($activity_goods['sell_percent'] >= 100) {
                        $activity_goods['sell_percent'] = "100%";
                    }elseif ($activity_goods['sell_percent'] <= 0) {
                        $activity_goods['sell_percent'] = "0%";
                    }else{
                        $activity_goods['sell_percent'] = sprintf("%.2f", $activity_goods['sell_percent'])."%";
                    }
                }
            }
            $goods_list[$k] =$activity_goods;
        }
        if ($activity['end_time']<=time()) {//已结束
            $this->activity_start=2;
        }elseif ($activity['start_time']>time()) {//还未开始
            $this->activity_start=1;
        }else{
            $time = $activity['end_time'] - time();
            $h=intval($time/3600);
            $i=intval(($time -$h*3600)/60);
            $s= $time -$h*3600 -$i*60;
            $i=$i<10 ? '0'.$i : $i;
            $s=$s<10 ? '0'.$s : $s;
            $this->time=$activity['end_time'];
            $this->h=$h;
            $this->i=$i;
            $this->s=$s;
            $this->activity_start=3;
        }
        $this->shop_code;
        $this->list=$goods_list;
        $this->display();
    }
    //活动商品详情
    public function goods_detail(){
        //微信分享后得回调  zm
        if($_POST["goods_id"]){
            $goods=M("g_goods")->where(array("goods_id"=>$_POST["goods_id"]))->setInc("goods_share");
            if($goods==true){
                $re["status"]= 1;
                echo json_encode($re);die;
            }
            $re["status"]= 0;
            echo json_encode($re);die;
        }

        //
		$g_where['activity_type'] = array("eq",$_GET["activity_type"]);
        $g_where["goods_id"] =  array("eq",$_GET["id"]);
        $g_where['is_delete']=0;
        //$g_where['is_show']=1;//上架
        $data = D("Goods")->getGoodsInfo($g_where);
        $this->activity_type = !empty($_GET["activity_type"]) ? $_GET["activity_type"] : $data['activity_id'];
        //如果是9.9大聚惠活动则商品时间按照统一设定
        if ($this->activity_type == 2) {
            $activity99 = M("activity")->where("id=2")->find();
            $data['activity_start_date'] = $activity99['start_time'];
            $data['activity_end_date'] = $activity99['end_time'];
        }
		//活动信息
        //图片

        if($data){
            $data=D('goods')->getGoodsPrice($data,$_GET["activity_type"]);//计算商品价格 限时抢购/1元秒杀等
            // var_dump($data);die;
            $this->good_image_url=M('g_goods_gallery')->where(array('goods_id'=>$data['goods_id']))->select();
            if($data['goods_browse']>500){
                //浏览量 hkj
                $browse=M("g_goods")->where(array("goods_id"=>$_GET['id']))->setInc("goods_browse");
            }else{
                $goods_browse=rand(500,1000);
                M("g_goods")->where(array("goods_id"=>$_GET['id']))->save(array('goods_browse'=>$goods_browse));
                $data['goods_browse']=$goods_browse;
            }
            //商品属性
            $goods_type=$data['goods_type'];
            if(!empty($goods_type)){
                $goods_id=$data['goods_id'];
                $pre=C('DB_PREFIX');//表前缀
                $goods_attr=M()->table($pre.'g_goods_attr goods_attr')//
                ->join($pre.'g_attribute attr on attr.attr_id=goods_attr.attr_id')//
                ->where(array('goods_attr.goods_id'=>$goods_id))//
                ->field('goods_attr.*,attr.attr_name,attr.attr_input_type,attr.attr_type')
                    ->order('attr.sort_order desc')->select();
                //attr_type 属性是否多选；0，否；1，是；如 果可以多选，则可以自定义属性，并且可以根据值的不同定不同的价
                //attr_input_type当添加商品时，该属性的添 加类别；0，为手工输入；1，为选择输入；2，为多行文本输入
                if($goods_attr){
                    $goods_attr1=array();
                    foreach ($goods_attr as $key => $value) {
                        if($value['attr_type']){
                            $goods_attr1[]=$value;
                            unset($goods_attr[$key]);
                        }
                    }
                    if($goods_attr1){
                        $arr=array();
                        foreach ($goods_attr1 as $key => $value) {
                            $arr[$value['attr_id']][]=$value;
                        }
                        if($arr){
                            $goods_attr1=array();
                            foreach ($arr as $key => $value) {
                                $tree=array();
                                foreach ($value as $keys => $val) {
                                    if($val['attr_value']){
                                        $tree[] = $val;
                                    }
                                }
                                if($tree){
                                    $data_arr=array();
                                    $data_arr['attr_name']=$value[0]['attr_name'];
                                    $data_arr['tree']=$tree;
                                    $goods_attr1[]=$data_arr;
                                }
                            }
                        }
                    }
                }
                $this->goods_attr=$goods_attr;
                $this->goods_attr1=$goods_attr1;
            }
        }

        $this->data = $data;
//       var_dump($data);die;
        //收藏信息
        if ($this->uid) {
            $c_where['goods_id']=$data['goods_id'];
            $c_where['user_id']=$this->uid;
            $c_where['is_attention']=1;
            $c_count=M('g_collect_goods')->where($c_where)->count();
            $this->collect=$c_count;
        }
        $shar_title=$this->webseting['web_title'].'-'.$data['goods_name'];
        $shar_len=mb_strlen($shar_title,'utf-8');
        $rep=ceil($shar_len/13);
        $replen=$rep*13-$shar_len;
        $shar_title_desc=$shar_title;
        if($replen>0){
            $shar_title_desc.=str_repeat(' ',$replen);
        }
        //str_repeat
        $this->shar_url= $url="http://".$_SERVER['HTTP_HOST'].U('wap/goods/goods_detail',array('id'=>$_GET["id"],'share'=>$this->shop_code));
        $this->shar_title=$shar_title;
        $this->shar_desc=$shar_title_desc."￥".$data['shop_price'];
        $this->shar_imgurl="http://".$_SERVER['HTTP_HOST'].$data['goods_img'];
        $this->get_weixin();///获取微信 信息
        $this->webtitle="FG峰购";
        $this->display();
    }


    //设置  1元购活动商品 是否抢购成功
    public function setWinners(){
        //查询1元购活动商品
        $goods_where['activity_id'] =  1;
        $goods_where['activity_end_date'] = array('lt',time()-60);
        $activity_goods = M('g_goods')->where($goods_where)->select();

        foreach ($activity_goods as $key => $value){
            $order_goods_where=array();
            //查询 1元购 产品订单
            $order_goods_where['activity_id'] = 1;
           	$order_goods_where['activity_status'] = 0;
            $order_goods_where['goods_id'] =$value['goods_id'];

            $activity_order_goods = M('g_order_goods')->where($order_goods_where)->select();
			$have_pay_arr=array();
			foreach ($activity_order_goods as $k=>$v){
				$pay_status= 0;
				$pay_status=M('g_order_info')->where('order_id='.$v['order_id'])->getField('pay_status');
				if($pay_status == 2){
					$have_pay_arr[]=$v['rec_id'];
				}
				//先将订单都改成 未中
				M('g_order_info')->where('order_id='.$v['order_id'])->setField('activity_status',2);
			}
			$Win_order=array_rand($have_pay_arr,1);//随机抽取一个中奖商品订单
            $winner_rec_id =$activity_order_goods[$Win_order]['rec_id'];
            //该产品订单中的状态都改成未中奖 除未中订单外
            $order_goods_where['rec_id']=array('neq',$winner_rec_id);
            M('g_order_goods')->where($order_goods_where)->setField('activity_status',2);


            //该产品订单中中奖订单状态改成中奖
            $order_goods_win_where['rec_id']=$winner_rec_id;
            $order_goods_win_where['activity_id'] = 1;
            $order_goods_win_where['activity_status'] = 0;
            $order_goods_win_where['goods_id'] =$value['goods_id'];
            $order_id = M('g_order_goods')->where($order_goods_win_where)->getField('order_id');
            M('g_order_goods')->where($order_goods_win_where)->setField('activity_status',1);
            M('g_order_info')->where('order_id='.$order_id)->setField('activity_status',1);

            //该商品 活动已结束 关闭活动类型
			// M('g_goods')->where('goods_id='.$value['goods_id'])->setField('activity_id','0');
        }
    }

    //活动专区 --显示活动订单

    public function activity_area()
    {
        $where['user_id'] = $this->uid;
        $where['activity_id'] = array('gt','0');
        $where['user_del'] = 0;
		$where['pay_status'] = 2;
        $p = $_REQUEST['p'];
        $pagesize = 10;
        $p = !empty($p) ? $p : 1;
        $start = ($p - 1) * $pagesize;
        $field = "order_id,order_sn,order_status,shipping_status,pay_status,goods_amount,order_amount,shipping_fee,add_time,consignee,mobile,address,is_upgrade,share_uid,activity_status,activity_id";
        $order_model = M('g_order_info');
        $list = $order_model->where($where)//
        ->field($field)->limit($start, $pagesize)->order('order_id desc')->select();
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
            if ($list[$key]['activity_id'] == 1) {
                $order_show = $this->get_activity_stats($value);
                $list[$key]['order_state'] = $order_show['code'];
                $list[$key]['order_state_name'] = $order_show['name'];
                $list[$key]['end_time'] = $order_show['end_time'] ? $order_show['end_time'] : 0;
            }
            $list[$key]['add_time'] = date("Y/m/d", $value['add_time']);
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
            $store_id=$list[$key]['share_uid'];
            $list[$key]['store_name']=M('member')->where('id='.$store_id)->getField('member_name');
        }
        if (IS_AJAX) {
            echo json_encode($list);
            die;
        }
        $this->list = $list;
        $this->webtitle = "FG峰购";
        $this->display();

    }
	//活动订单状态
	public function get_activity_stats($order_info=array()){
		if(empty($order_info)){
			return array();
		}
// pay_status支付状态；0，未付款；1，付款中 ；2，已付款
// shipping_status 商品配送情况，0，未发货； 1，已发货；2，已收货；3，备货中
//order_status订单状态。0，未确认；1，已确 认；2，已取消；3，无效；4，退货；
		$a_g_where['activity_id']=$order_info['activity_id'];
		$a_g_where['order_id']=$order_info['order_id'];
		$a_goods_info = M('g_order_goods')->where($a_g_where)->find();
		$order_status=array('0'=>'未确认','1'=>'已确认','2'=>'已取消','3'=>'无效','4'=>'已退货','5'=>'售后申请中');
		$shipping_status=array('0'=>'待发货','1'=>'待收货','2'=>'已完成','3'=>'备货中');
		$code=$name="";
		if($order_info['order_status']==1){//已确认
			switch ($order_info['shipping_status']) {
				case '1':
					$code="delivered";//已发货
					break;
				case '2':
					$code="received";//已收货
					break;
				default:
					$code='nondelivery';//未发货
					break;
			}
			return array('code'=>$code,'name'=>$shipping_status[$order_info['shipping_status']]);
		}else{//未确认
			//2已取消；3无效；4，退货；
			if(in_array($order_info['order_status'], array('2','3','4'))){
				return array('code'=>'finish','name'=>$order_status[$order_info['order_status']]);
			}elseif ($order_info['order_status'] == 5){
				return array('code'=>'after_sale','name'=>$order_status[$order_info['order_status']]);
			}else{////未确认
				if($order_info['pay_status']==2){//已付款 待确认
					if($order_info['activity_status'] == 0 && $order_info['activity_id'] > 0){
						if($a_goods_info['activity_end_date'] > time()+60){
							$end_time = $a_goods_info['activity_end_date'] - time();
							$h=intval($end_time/3600);
							$i=intval(($end_time -$h*3600)/60);
							$s= $end_time -$h*3600 -$i*60;
							$i=$i<10 ? '0'.$i : $i;
							$s=$s<10 ? '0'.$s : $s;
							$end_time_name =$h.':'.$i.':'.$s;
							return array('code'=>'underway','name'=>'距结束:'.$end_time_name,'end_time'=>$a_goods_info['activity_end_date']);
						}else{
							return array('code'=>'waiting','name'=>'即将出结果');
						}
					}elseif($order_info['activity_status'] == 1 && $order_info['activity_id'] > 0){
						return array('code'=>'win','name'=>'嗨到手');
					}else{
						return array('code'=>'not_win','name'=>'遗憾咯');
					}
				}else{
					return array('code'=>'nopay','name'=>'未付款');
				}
			}
		}
	}
}