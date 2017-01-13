<?php
// 服务产品
class GroupAction extends BaseAction {
	//魔术方法
public function __construct(){
		parent::__construct();
		
	}
    public function index(){
		 $order=$_GET['order'];
        if($order){
           switch ($order) {
               case 'prices1':
                   //价格 倒序
                    $order="goods_price desc";
               case 'prices2':
                   //价格 升序
                    $order="goods_price asc";     
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
      
    	$this->webtitle="FG峰购";
		$this->display();
    }
    //商家产品 列表
     public function shopproduct(){
        $shop_id=$_GET['id'];
        if($shop_id){
           $g_where['shop_id']=$shop_id;
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
        ->limit($start,$pagesize)->order('id desc')->select();
        if(IS_AJAX){
            echo json_encode($goods_list);die;
        }
        $this->list=$goods_list;
        $this->data=$this->getShopDetail(array('shop_id'=>$shop_id));

        $this->webtitle="FG峰购";
        $this->display();
    }
    //服务商品详情
   public function goods_detail(){
        $g_where['id']=$_GET['id'];
        $g_where['goods_status']=1;//审核通过
        $g_where['is_del']=0;
        $data=M("group_goods")->where($g_where)->find();
        if($data['goods_image_url']){//产品多图
         $data['goods_image_url']=explode(',', $data['goods_image_url']);
        }
        if(!empty($data)){
            $shop=M('shop_detail')->where(array('shop_id'=>$data['shop_id']))->field('addr,link_no,shop_name,discount_consume')->find();
            if(!empty($shop) && $shop['discount_consume'] >0 && $shop['discount_consume'] <=1){
               $discount_consume=$shop['discount_consume'];
            }else{
                $discount_consume=1;
            }
            $data['goods_z_price']=$data['goods_price']*$discount_consume;
            $this->shop=$shop;
        }
        $this->data=$data;
        
        //收藏信息
        if ($this->uid) {
             $c_where['goods_id']=$data['id'];
             $c_where['member_id']=$this->uid;
             $c_count=M('member_good_collect')->where($c_where)->count();
             $this->collect=$c_count;
        }

    	$this->webtitle="FG峰购";
		$this->display();
    }
    
     //收藏商品  取消收藏
    public function good_collect(){
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
        $count=M("group_goods")->where(array('id'=>$goods_id))->count();
        if(empty($count)){
            $data['error']="商品不存在";
            echo json_encode($data);die;
        }
        $member_good_collect=M("member_good_collect");
        $c_where['goods_id']=$goods_id;
        $c_where['member_id']=$this->uid;
        $c_count=$member_good_collect->where($c_where)->count();

        if(empty($c_count)){//不存在 添加
            $c_where['add_time']=time();
            $res=$member_good_collect->add($c_where);
        }else{ //存在 取消
          $res=$member_good_collect->where($c_where)->delete();
        }
        if($res!==false){
            if(empty($c_count)){
                //收藏量加一
                M("group_goods")->where(array('id'=>$goods_id))->setInc('goods_collect',1);
            }else{
                //收藏量减一
               M("group_goods")->where(array('id'=>$goods_id))->setInc('goods_collect',-1);
            }
            $data['status']=1;
        }else{
            $data['error']='操作失败,请稍候再试.';
        }
         echo json_encode($data);die;
    }
   


}