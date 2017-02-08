<?php
// 产品
class UsedcarAction extends BaseAction {
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
    
    //用户发布商品
    public function add_msg()
    {
        $_GET['goods_id'] = isset($_GET['goods_id']) ? $_GET['goods_id'] : 0;
       if($_POST){
           $return_data['status'] = 0;
            $data_save =$_POST;
           $data_save['is_auditing'] = 0;
           $model =M('g_goods');
           if($_POST['goods_id']){
            $res =$model->where('goods_id='.$_POST['goods_id'])->save($data_save);
           }else{
               $res =$model->save($data_save);
           }
           if($res !== false){
               $return_data['status'] = 1;
               echo json_encode($return_data);die;
           }else{
               $return_data['error'] = '编辑失败';
               echo json_encode($return_data);die;
           }
       }else{
           if($_GET['goods_id']){
                $data=M('g_goods')->where('goods_id='.$_GET['goods_id'])->find();
               $this->data = $data;
           }
       }
        $this->display();
    }
}