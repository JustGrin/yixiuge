<?php
// 产品
class GoodsAction extends BaseAction {
	//魔术方法
	public function __construct(){
		parent::__construct();

		if(!IS_AJAX){
		  $this->now_menu='shop';
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
        $g_where['activity_id']=0;//非活动商品
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
                $goods_list[$k]['goods_img'] = thumbs_auto($v['goods_img'], 300, 300);
                $goods_list[$k]['base_logo'] = thumbs_auto($v['base_logo'], 60, 60);
            }
        }else{
            foreach ($goods_list as $k => $v) {
                $goods_list[$k]['vip_p_show'] = 0;//vip 价格
                $goods_list[$k]['goods_img'] = thumbs_auto($v['goods_img'], 300, 300);
                $goods_list[$k]['base_logo'] = thumbs_auto($v['base_logo'], 60, 60);
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
    //赚取佣金
     public function made(){
       $order=$_REQUEST['order'];
        if($order){
           switch ($order) {
               case 'prices1':
                   //价格 倒序
                    $order="share_money desc";
                     break;
               case 'prices2':
                   //价格 升序
                    $order="share_money asc";
                   break;
               case 'browse1':
				   //浏览量 倒序
				   $order="goods_browse desc";
				   break;
			   case 'browse2':
				   //浏览量 顺序
				   $order="goods_browse asc";
				   break;
               default:
               unset($order);
                   # code...
                   $order="goods_id desc";
                   break;
           }
        }else{
           $order="goods_id desc";
        }
        $cryp_cat_id=$this->get_category_child('19');//剔除 成人用品
        $g_where['cat_id']=array('not in',$cryp_cat_id);

        $type=$_REQUEST['type'];
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
        if($search){
            $g_where['goods_name'] =array('like',$search.'%');
        }
        $g_where['share_money']=array('gt',0);
        //商家推荐
        $p=$_REQUEST['p'];
        $pagesize=10;
        $p=!empty($p)?$p:1;
        $start=($p-1)*$pagesize;
        $g_where['is_on_sale']=1;//该商品是否开放销售，1，是；0， 否
        $g_where['is_delete']=0;//商品是否已经删除，0，否；1，已 删除
        $g_where['is_auditing']=1;//商品是否通过审核  1是0 否
        $g_field="goods_id,goods_name,share_money,shop_price,market_price,goods_brief,goods_img,goods_browse";
        $goods_list=M("g_goods")->where($g_where)->field($g_field)
        ->limit($start,$pagesize)->order($order)->select();
//         var_dump($goods_list);die;
		 print_r($goods_list);
         foreach($goods_list as $k => $v){  //取佣金区间价
             $money = M('g_goods_attr')->field('attr_share_money')->where(array('goods_id' => $v['goods_id']))->select();
//             var_dump($money);die;
             $share_money=array();
             foreach($money as $key => $val){
                 if($val['attr_share_money']){
                     $share_money[] = $val['attr_share_money'];
                 }
             }
             $share_money= array_filter($share_money);
             if ($v['share_money']) {
                 $share_money[] = (int)$v['share_money'];
             }
             $share_money=array_unique($share_money);
             if(count($share_money)>1){
                 $low_money = min($share_money);
                 $high_money = max($share_money);
                 $goods_list[$k]['low_money'] = $low_money;
                 $goods_list[$k]['high_money'] = $high_money;
             }
         }
        if(IS_AJAX){
            echo json_encode($goods_list);die;
        }
        $this->list=$goods_list;

        $this->count=M("g_goods")->where($g_where)->count();

         $typename=M('g_category')->where(array('cat_id'=>$type))->getField('cat_name');
         $this->typename=$typename?'-'.$typename:'';
         $this->now_menu = 'member';

          $shar_title=$this->webseting['web_title'].C('SHAR_TITLE');
            $this->shar_url= $url="http://".$_SERVER['HTTP_HOST'].U('wap/goods/index',array('share'=>$this->member_card));
            $this->shar_title=$shar_title;
            $this->shar_desc=C('SHAR_DESC');
            $this->shar_imgurl="http://".$_SERVER['HTTP_HOST'].'/Public/wap/img/logg.png';///分享图片地址
            $this->get_weixin();///获取微信 信息


        $this->webtitle="FG峰购";
        $this->display();
    }

    public function category(){
      $cryp_cat_id=$this->get_category_child('19');//剔除 成人用品
      $category_model=D('Category');
      $class_where=array();
       $class_where['is_show']=1;
       $class_where['cat_id']=array('not in',$cryp_cat_id);
       $Category=$category_model->getCategoryAll($class_where);
        $Category=$this->gettree($Category,0,0,'parent_id','cat_id');
        foreach ($Category as $key => $value) {
           if(empty($value['tree'])){
               // unset($Category[$key]);
           }
        }
        $this->group_class=$Category;
        $class_where=array();
        $class_where['show_in_nav']=1;//是否显示在导航栏，0，不；1，显示在导航 栏
        $class_where['is_show']=1;

        $class_where['cat_id']=array('not in',$cryp_cat_id);
        //$class_where['pid']=0;
        $this->tuijian=$category_model->getCategoryAll($class_where);
        //跳转地址
        $return_url=U('wap/goods/index');
        if($_GET['type']=='made'){
           $return_url=U('wap/goods/made');
        }
        $this->return_url=$return_url;
			$member_info=$this->getMemberInfo();
         $shar_title=$this->webseting['web_title'].C('SHAR_TITLE');
            $this->shar_url= $url="http://".$_SERVER['HTTP_HOST'].U('wap/goods/index',array('share'=>$this->member_card));
            $this->shar_title=$shar_title;
            $this->shar_desc=C('SHAR_DESC');
            $this->shar_imgurl="http://".$_SERVER['HTTP_HOST'].$member_info['member_logo'];///分享图片地址
            $this->get_weixin();///获取微信 信息

        $this->display();
    }
    //服务商品详情
   public function goods_detail(){
       //微信分享后得回调  zm
       $uid = $this->uid;
      if($_POST["goods_id"]){
        $goods=M("g_goods")->where(array("goods_id"=>$_POST["goods_id"]))->setInc("goods_share");
        // echo M()->getLastSql();
         if($goods==true){
          $re["status"]= 1;
         echo json_encode($re);die;
         }
          $re["status"]= 0;
         echo json_encode($re);die;
      }

        //
        $g_where["goods_id"] =  array("eq",$_GET["id"]);
        $g_where['is_delete']=0;
         //$g_where['is_show']=1;//上架
         $data = D("Goods")->getGoodsInfo($g_where);


         //图片
         if($data){
          $data=D('goods')->getGoodsPrice($data);//计算商品价格 限时抢购等
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
             $data['vip_p_show'] = 0;//vip 价格
             //判断是否会员及是否设置会员价
             if($data['hot_type'] != 1){
                 $u_where['id'] = $_SESSION['member']['uid'];
                 $vip_type = M('member')->where($u_where)->getField('member_vip_type');
                 if ($vip_type == 1 && $data['vip_price'] > 0) {
                     $data['market_price'] = $data['shop_price'];
                     $data['shop_price'] = $data['vip_price'];
                     $data['vip_p_show'] =1;
                 }
             }
           //计算参考邮费
             $province = M('member_detail')->where('member_id='.$uid)->getField('provinceid');
			 if(empty($province) ||  $province == 0){
				 $province = '500000';
			 }
			 $postage_s = postage_trans($data['postage_json']);
			 $shipping_money = 0.00;
			 if( $province != 0){
				 $address_postage = array();
				 $postage_unit = $data['postage_unit'];// 邮费计价单位 0 件，1 kg
				 foreach ($postage_s  as $k => $v){
					 if($province == $v['provinceid']){
						 $address_postage =$v;
					 }
				 }
				 $by_unit = $address_postage['by_unit'];//包邮 单位 -1不包邮 0 件， 1 kg， 2 元 ,3 完全包邮
				 $by_condition = $address_postage['by_condition'];
				 $_this_piece = 1;//商品件数
				 $_this_weight = 1 * mb_number($data['goods_weight']);//商品重量
				 $_this_money = 1 * mb_number($data['shop_price']) ;//商品金额
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
			 $this->assign('shipping_money',$shipping_money);
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
                            if($vip_type==1 && $val['attr_vip_price']>0){
                                $val['attr_price']=$val['attr_vip_price'];
                            }
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
       if($data['shipping_id']==0){   //判断包邮快递
           $data['shipping_name']='SF顺丰快递';
       }else{
           $shipping_name = M('g_shipping')->field('shipping_name')->where(array('shipping_id' => $data['shipping_id']))->find();
           $data['shipping_name'] = $shipping_name['shipping_name'];
           if(empty($data['shipping_name'])){
            $data['shipping_name']='SF顺丰快递';
           }elseif($data['shipping_name'] == '包邮'){
            $data['shipping_name']='';
           }
       }

       if ($data['hot_type'] == 1 && $data['vip_p_show'] == 0) {
           $data['vip_p_show'] =1;
       }
       $data['goods_img'] = thumbs_auto($data['goods_img'], 630, 630);
       $data['base_logo'] = thumbs_auto($data['base_logo'], 60, 60);
       $this->data = $data;
//       var_dump($data);die;
        //收藏信息
        if ($uid) {
             $c_where['goods_id']=$data['goods_id'];
             $c_where['user_id']=$uid;
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

     //收藏商品  取消收藏
    public function goods_collect(){
        $data['status']=0;
        $login=$this->get_member_login();
        $goods_id=$_REQUEST['goods_id'];
        if(empty($goods_id)){
            $data['error']="商品不存在";
            echo json_encode($data);die;
        }
        if(empty($login)){
            $data['error']="请登录再试";
            echo json_encode($data);die;
        }
        $count=M("g_goods")->where(array('goods_id'=>$goods_id))->count();
        if(empty($count)){
            $data['error']="商品不存在";
            echo json_encode($data);die;
        }
        $member_good_collect=M("g_collect_goods");
         $c_where['goods_id']=$goods_id;
         $c_where['user_id']=$this->uid;
         $c_count=$member_good_collect->where($c_where)->find();
         $goods_collect=1;
        if(empty($c_count)){//不存在 添加
            $c_where['is_attention'] = 1;
            $c_where['add_time']=time();
            $res=$member_good_collect->add($c_where);
        }else{ //存在 取消
            if($c_count['is_attention']){//取消关注
              $save_data['is_attention']=0;
               $goods_collect=0;
            }else{
               $save_data['is_attention']=1;//关注
            }
            $res=$member_good_collect->where($c_where)->save($save_data);
        }
        if($res!==false){
          if($goods_collect){
             M("g_goods")->where(array("goods_id"=> $goods_id))->setInc("goods_collect");//收藏量 加一
           }else{
             M("g_goods")->where(array("goods_id"=> $goods_id))->setDec("goods_collect");//收藏量 减一
           }
            $data['status']=1;
        }else{
            $data['error']='操作失败,请稍候再试.';
        }
         echo json_encode($data);die;
    }
    //限时抢购
    public function limitedpurchase(){
        $field='is_on_sale,is_auditing,is_promote,goods_id,goods_name,promote_img,promote_start_date,promote_end_date';
        $where['is_on_sale']=1;  //是否开放销售,
        $where['is_promote']=1;
        $where['is_auditing']=1;
        $where['is_delete']=0;
        $order['sort_order']='desc';
        $p = $_REQUEST['p'];
        $pagesize = 10;
        $p = !empty($p) ? $p : 1;
        $start = ($p - 1) * $pagesize;
        $data=M('g_goods')->field($field)->where($where)->order($order)->limit($start,$pagesize)->select();
//        var_dump($data);die;
        foreach($data as $k=>$v){    //获得活动状态
            $data[$k] = D('Goods')->getGoodsPrice($v);
            if($data[$k]['promote_status'] != 1){
                unset($data[$k]);
            }
        }
//        var_dump($data);die;
        $this->count = M("g_goods")->where($where)->count();
        if(IS_AJAX){
            echo json_encode($data);die;
        }
        $this->data=$data;

        $shar_title=$this->webseting['web_title'].'商城--限时抢购';
            $this->shar_url= $url="http://".$_SERVER['HTTP_HOST'].U('wap/goods/limitedpurchase',array('share'=>$this->member_card));
            $this->shar_title=$shar_title;
            $this->shar_desc=C('SHAR_DESC');
            $this->shar_imgurl="http://".$_SERVER['HTTP_HOST'].'/Public/wap/img/logg.png';///分享图片地址
            $this->get_weixin();///获取微信 信息

        $this->display();
    }
    //关联商品详细页
    public function goods_relative(){
        if($_GET['id']){
            $where['id']=$_GET['id'];
        }
        $ad=M('home_ad')->field('value')->where($where)->find();
        $value=explode(',',$ad['value']);
        $p = $_REQUEST['p'];
        $pagesize = 4;
        $p = !empty($p) ? $p : 1;
        $start = ($p - 1) * $pagesize;
        $field='goods_id,goods_name,goods_img,shop_price,market_price';
        $where['goods_id']=array('in',$value);
        $data=M('g_goods')->field($field)->where($where)->limit($start,$pagesize)->select();
//        var_dump($data);die;
        if(IS_AJAX){
            echo json_encode($data);die;
        }
        $this->data=$data;

              $shar_title=$this->webseting['web_title'].C('SHAR_TITLE');
            $this->shar_url= $url="http://".$_SERVER['HTTP_HOST'].U('wap/goods/goods_relative',array('id'=>$_GET['id'],'share'=>$this->member_card));
            $this->shar_title=$shar_title;
            $this->shar_desc=C('SHAR_DESC');
            $this->shar_imgurl="http://".$_SERVER['HTTP_HOST'].'/Public/wap/img/logg.png';///分享图片地址
            $this->get_weixin();///获取微信 信息

//        var_dump($data);
        $this->display();
    }
    //商品佣金赚取详细页
    public function promo_detail(){
        //微信分享后得回调  zm
        if ($_POST["goods_id"]) {
            $goods = M("g_goods")->where(array("goods_id" => $_POST["goods_id"]))->setInc("goods_share");
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
        $browse = M("g_goods")->where(array("goods_id" => $_GET['id']))->setInc("goods_browse");
        //
        $g_where["goods_id"] = array("eq", $_GET["id"]);
        $g_where['is_delete'] = 0;
        //$g_where['is_show']=1;//上架
        $data = D("Goods")->getGoodsInfo($g_where);
        //图片
        if ($data) {
            $data = D('goods')->getGoodsPrice($data);//计算商品价格 限时抢购等
            // var_dump($data);die;
            $this->good_image_url = M('g_goods_gallery')->where(array('goods_id' => $data['goods_id']))->select();
            //商品属性
            $goods_type = $data['goods_type'];
            if (!empty($goods_type)) {
                $goods_id = $data['goods_id'];
                $pre = C('DB_PREFIX');//表前缀
                $goods_attr = M()->table($pre . 'g_goods_attr goods_attr')//
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
                                   if($val['attr_value']){
                                     $tree[] = $val;
                                   }
                                }
                                if($tree){
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
                foreach($goods_attr1 as $k =>$v){
                    if(!empty($v)){
                        foreach($v['tree'] as $k =>$v){
                            $money[]=$v['attr_share_money'];
                        }
                    }
                }
            }
        }
        $money= array_filter($money);
        $money[]=(int)$data['share_money'];
        $money=array_unique($money);
        if(count($money)>1){
            $low_money = min($money);
            $high_money = max($money);
            $data['low_money'] = $low_money;
            $data['high_money'] = $high_money;
        }
        if ($data['shipping_id'] == 0) {
            $data['shipping_name'] = 'SF顺丰快递';
        } else {
            $shipping_name = M('g_shipping')->field('shipping_name')->where(array('shipping_id' => $data['shipping_id']))->find();
            $data['shipping_name'] = $shipping_name['shipping_name'];
            if(empty($data['shipping_name'])){
             $data['shipping_name']='SF顺丰快递';
            }elseif($data['shipping_name'] == '包邮'){
            $data['shipping_name']='';
           }
        }
        $this->data = $data;
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
        $this->shar_url = $url = "http://" . $_SERVER['HTTP_HOST'] . U('wap/goods/goods_detail', array('id' => $_GET["id"], 'share' => $this->member_card));
        $this->shar_title = $shar_title;
        $this->shar_desc = $shar_title_desc . "￥" . $data['shop_price'];
        $this->shar_imgurl = "http://" . $_SERVER['HTTP_HOST'] . $data['goods_img'];
        $this->get_weixin();///获取微信 信息
        $this->webtitle = "FG峰购";
        $this->now_menu = 'member';
        $this->display();
    }

    //基地积分赠送
    public function base_points(){
      $data['status'] = 0;
      $where_goods['goods_id'] = $_POST['goods_id'];
      $where_goods['base_id'] = $_POST['base_id'];
      $where_base['base_id'] = $_POST['base_id'];
      $where_base_log['base_id'] = $_POST['base_id'];
      $where_base_log['member_id'] = $_SESSION['member']['uid'];
      $where_base_log['is_shelves'] = 1;

      $base = M("base");
      $member_detail = M("member_detail");
      $base_log = M("base_log");

      $goods_data = M("g_goods")->where($where_goods)->find();
      $base_data = $base->where($where_base)->find();
      $base_log_time = $base_log->where($where_base_log)->order("add_time desc")->find();

      if ($goods_data == false) {
        $data['error'] = "产品信息错误无法获得积分";
        echo json_encode($data);
        die;
      }
      if ($base_data['base_points'] <= 0) {
        $data['error'] = "基地积分已用完";
        echo json_encode($data);
        die;
      }
      //判断是否是当天领取
      if (!empty($base_log_time)) {
        if (strtotime(date('Y-m-d', $base_log_time['add_time'])) >= strtotime(date('Y-m-d', time()))) {
          $data['error'] = "当天已经领取过积分了";
          echo json_encode($data);
          die;
        }
      }

      $uid = $_SESSION['member']['uid'];
      //基地积分大于等于5时扣除随机积分，小于5时全部扣完
      $point_add = rand(1,5);
      if ($base_data['base_points']>=5) {
        $base_data['base_points'] = $base_data['base_points'] - $point_add;
      }else{
        $point_add = $base_data['base_points'];
        $base_data['base_points'] = 0;
      }
      $base_log_data['base_id'] = $base_data['base_id'];
      $base_log_data['member_id'] = $uid;
      $base_log_data['integral'] = $point_add;
      $base_log_data['add_time'] = time();

      $base->startTrans();//开启事务
      $base_log->startTrans();//开启事务

      $base_result = $base->where($where_base)->save($base_data);
      $base_log_result = $base_log->add($base_log_data);

      if ($base_result != false && $base_log_result != false) {
        $log['integral'] = $point_add;
        $log['des']="《".$base_data['base_name']."》赠送浏览用户：".$point_add."积分";
        $member_result = $this->set_member_points($uid,1,$point_add,$log);
        if ($member_result == true) {
          $base->commit();//提交事务
          $base_log->commit();//提交事务
          $data['status'] = 1;
          $data['msg'] = $point_add;
          echo json_encode($data);
        }else{
          $base->rollback();//回滚事务
          $base_log->rollback();//回滚事务
          $data['error'] = "积分添加错误";
          echo json_encode($data);
          die;
        }
      }else{
        $base->rollback();//回滚事务
        $base_log->rollback();//回滚事务
      }
    }

    //用户发布商品
    public function add_goods()
    {
        $this->display();
    }
}