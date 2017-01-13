<?php
// 商家控制器
class ShopAction extends BaseAction {
	//魔术方法
public function __construct(){
		parent::__construct();
		
	}

	  public function find(){
        $order=$_GET['order'];
        if($order){
           switch ($order) {
               case 'prices1':
                   //价格 倒序
                   $order="buy desc";
                   break;
               case 'prices2':
                   //价格 升序
                    $order="buy asc";
                   break;
               case 'zhekou1':
                   //折扣 倒序
                    $order="discount_consume desc";
               case 'zhekou2':
                   //折扣 升序
                    $order="discount_consume asc";     
                   break;
               default:
                   # code...
                   break;
           }
        }
        $type=$_GET['type'];
        if($type){
           $pid=M('group_class')->where(array('id'=>$type))->getField('pid');
           if($pid){
             $s_where['class_id']=$type;
           }else{
             $s_where['top_class_id']=$type;
           }
        }
        //当前城市
        $cityid=$this->now_city;
        if($cityid){
          $s_where['cityid']=$cityid;
        }
        //商家推荐
        //$s_where['recommend_status']=1;  //平台推荐 1 推荐 0未推荐
        $p=$_REQUEST['p'];
        $pagesize=10;
        $p=!empty($p)?$p:1;
        $start=($p-1)*$pagesize;    
        $s_where['status']=1;
        $field="shop_id,shop_name,logo,background,link_no,addr,business,member_desc,evaluation_good_star,discount_consume";
        $shop_list=M("shop_detail")->where($s_where)->field($field)//
        ->limit($start,$pagesize)->order($order)->select();
        $login=$this->get_member_login();
        foreach ($shop_list as $key => $value) {
            if(!$login){
                 $shop_list[$key]['link_no']='<span class="product-price">登陆可见联系方式</span>';
            }
        }
        if(IS_AJAX){
            echo json_encode($shop_list);die;
        }
        $this->list=$shop_list;

         // 店铺统计
        $count=M("shop_detail")->where($s_where)->count();
        $this->count=$count;
         // 页头广告 
        $ad2=M("ad")->where(array('type'=>3,'status'=>1))->order('sort desc,add_time desc')->find();
        $this->ad=$ad2;

        $typename=M('group_class')->where(array('id'=>$type))->getField('class_name');
         $this->typename=$typename?'-'.$typename:'';

		     $this->display();
    }
	//米铺
    public function index(){
		 $order=$_REQUEST['order'];
        if($order){
           switch ($order) {
               case 'zhekou1':
                   //折扣 倒序
                    $order="discount_consume desc";
                     break;
               case 'zhekou2':
                   //折扣 升序
                    $order="discount_consume asc";     
                   break;
               default:
                unset($order);
                   # code...
                   break;
           }
        }
        $type=$_REQUEST['type'];
        if($type){
           $pid=M('group_class')->where(array('id'=>$type))->getField('pid');
           if($pid){
             $s_where['class_id']=$type;
           }else{
             $s_where['top_class_id']=$type;
           }
        }
        //商家推荐
        //$s_where['recommend_status']=1;  //平台推荐 1 推荐 0未推荐
        $p=$_REQUEST['p'];
        $pagesize=10;
        $p=!empty($p)?$p:1;
        $start=($p-1)*$pagesize;    
        $s_where['status']=1;
        $field="shop_id,shop_name,logo,background,link_no,addr,business,member_desc,evaluation_good_star,discount_consume";
        $shop_list=M("shop_detail")->where($s_where)->field($field)//
        ->limit($start,$pagesize)->order($order)->select();
        //查询 服务
        $group_goods=M('group_goods');
        $g_field="id,goods_name,goods_image,goods_price";
        $good_url=U('wap/index/get_thumbs',array('auto'=>164));
        foreach ($shop_list as $key => $value) {
            $g_where['shop_id']=$value['shop_id'];
            $g_where['goods_status']=1;
            $g_where['goods_state']=1;
            $g_where['is_del']=0;
           
            $goods_list=$group_goods->where($g_where)//
            ->field($g_field)->order('id desc')->limit(3)->select();
             $html=" ";
            if($goods_list){
               $good_img="";
              foreach ($goods_list as  $val) {
                 $good_img.=' <li><img src="'.$good_url.'?img='.$val['goods_image'].'"></li>';
              }
              $html='<ul class="a-image-list">'.$good_img.'</ul>';
            }
           $shop_list[$key]['goods_list']=$goods_list;
           $shop_list[$key]['goods_html']=$html;
        }
        if(IS_AJAX){
            echo json_encode($shop_list);die;
        }
        $this->list=$shop_list;

        $this->count=M("shop_detail")->where($s_where)->count();

         $typename=M('group_class')->where(array('id'=>$type))->getField('class_name');
         $this->typename=$typename?'-'.$typename:'';

    	$this->webtitle="FG峰购";
		   $this->display();
    }

    //米品
    public function shop_group(){
         $order=$_REQUEST['order'];
        if($order){
           switch ($order) {
               case 'prices1':
                   //价格 倒序
                    $order="goods_price desc";
                     break;
               case 'prices2':
                   //价格 升序
                    $order="goods_price asc";     
                   break;
               default:
               unset($order);
                   # code...
                   break;
           }
        }
        $type=$_REQUEST['type'];
        if($type){
           $pid=M('group_class')->where(array('id'=>$type))->getField('pid');
           if($pid){
             $g_where['goods_class']=$type;
           }else{
             $g_where['goods_top_class']=$type;
           }
        }
        //商家推荐
        $p=$_REQUEST['p'];
        $pagesize=10;
        $p=!empty($p)?$p:1;
        $start=($p-1)*$pagesize;    
        $g_where['goods_status']=1;
        $g_where['goods_state']=1;
        $g_where['is_del']=0;
        $g_field="id,goods_name,goods_image,goods_price";
        $goods_list=M("group_goods")->where($g_where)->field($g_field)//
        ->limit($start,$pagesize)->order($order)->select();
        
        if(IS_AJAX){
            echo json_encode($goods_list);die;
        }
        $this->list=$goods_list;

          $this->count=M("group_goods")->where($g_where)->count();

         $typename=M('group_class')->where(array('id'=>$type))->getField('class_name');
         $this->typename=$typename?'-'.$typename:'';

        $this->webtitle="FG峰购";
        $this->display();
    }
    ///商家详情
    public function shopdetail(){
        $s_where['shop_id']=$_GET['id'];
        $data=M("shop_detail")->where($s_where)->find();
        $this->data=$data;
        
        //收藏信息
        if ($this->uid) {
             $c_where['shop_id']=$data['shop_id'];
             $c_where['member_id']=$this->uid;
             $c_count=M('member_shop_collect')->where($c_where)->count();
             $this->collect=$c_count;
        }

        $this->webtitle="FG峰购";
        $this->display();
    }
    //收藏店铺  取消收藏
    public function shop_collect(){
        $data['status']=0;
        $login=$this->get_member_login();
        $shop_id=$_REQUEST['shop_id'];
        if(empty($shop_id)){
            $data['error']="商铺不存在";
            echo json_encode($data);die;
        }
        if(empty($login)){
            $data['error']="请登录再试";
            echo json_encode($data);die;
        }
        $count=M("shop_detail")->where(array('shop_id'=>$shop_id))->count();
        if(empty($count)){
            $data['error']="商铺不存在";
            echo json_encode($data);die;
        }
        $member_shop_collect=M("member_shop_collect");
        $c_where['shop_id']=$shop_id;
        $c_where['member_id']=$this->uid;
        $c_count=$member_shop_collect->where($c_where)->count();

        if(empty($c_count)){//不存在 添加
            $c_where['add_time']=time();
            $res=$member_shop_collect->add($c_where);
        }else{ //存在 取消
          $res=$member_shop_collect->where($c_where)->delete();
        }
        if($res!==false){
            $data['status']=1;
        }else{
            $data['error']='操作失败,请稍候再试.';
        }
         echo json_encode($data);die;
    }

   
    
  



}