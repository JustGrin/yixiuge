<?php
// 用户控制器
class CollectAction extends UserAction {
	//魔术方法
public function __construct(){
		parent::__construct();
		
	}
	//收藏 服务收藏 
    public function index(){
		
         $pre=C('DB_PREFIX');//表前缀
         $where['coll.user_id']=$this->uid;
        $where['is_attention']=1;
         $p=$_REQUEST['p'];
         $pagesize=10;
         $p=!empty($p)?$p:1;
         $start=($p-1)*$pagesize;    
        $field="goods.goods_id,goods.goods_name,goods.shop_price,goods.market_price,goods.goods_img,goods.goods_brief,coll.rec_id";
        $list=M()->table($pre.'g_collect_goods coll')//
        ->join($pre.'g_goods goods on goods.goods_id=coll.goods_id')//
        ->where($where)//
        ->field($field)->limit($start,$pagesize)->order('coll.add_time desc')->select();

        foreach ($list as $key => $value) {
            $list[$key]['goods_img'] = thumbs_auto($value['goods_img'], 300, 300);
        }
        $this->is_ajax = IS_AJAX;
        $this->list=$list;
//        var_dump($list);die;
        $count['goods']=M()->table($pre.'g_collect_goods coll')//
        ->join($pre.'g_goods goods on goods.goods_id=coll.goods_id')//
        ->where($where)->count();
         
//         $s_where['coll.user_id']=$this->uid;
//        $count['shops']=M()->table($pre.'g_collect_goods coll')//
//        ->join($pre.'shop_detail shop on shop.shop_id=coll.shop_id')//
//        ->where($s_where)->count();
		$this->count=$count;
        $this->display();
    }
    //删除收藏产品
    public function del_collect()
    {
        $return_data['status'] = 0;
        $rec_id = $_GET['rec_id'];
        $col_where['rec_id'] = $rec_id;
        $col_where['user_id'] = $this->uid;
        $res = M('g_collect_goods')->where($col_where)->delete();
        if ($res !== false) {
            $return_data['status'] = 1;
        } else {
            $return_data['error'] = "收藏商品删除失败";
        }
        echo json_encode($return_data);
        die;
    }
  //收藏 店铺收藏
    public function shop_collect(){

         $pre=C('DB_PREFIX');//表前缀
         $where['coll.member_id']=$this->uid;
         $p=$_REQUEST['p'];
         $pagesize=10;
         $p=!empty($p)?$p:1;
         $start=($p-1)*$pagesize;    
         $field="shop.shop_id,shop.shop_name,shop.logo,shop.evaluation_good_star";
         $list=M()->table($pre.'g_collect_goods coll')//
        ->join($pre.'shop_detail shop on shop.shop_id=coll.shop_id')//
        ->where($where)//
        ->field($field)->limit($start,$pagesize)->order('coll.add_time desc')->select();
        
        $member_shop_collect=M('g_collect_goods');
        foreach ($list as $key => $value) {
          $coll=$member_shop_collect->where(array('shop_id'=>$value['shop_id']))->count();
          $list[$key]['collect']=$coll;
          $list[$key]['evaluation_good_star']=$value['evaluation_good_star']*20;
        }
        if(IS_AJAX){
            echo json_encode($list);die;
        }
        $this->list=$list;

         $count['shops']=M()->table($pre.'member_shop_collect coll')//
        ->join($pre.'shop_detail shop on shop.shop_id=coll.shop_id')//
        ->where($where)->count();

        
         
         $g_where['coll.member_id']=$this->uid;
         $count['goods']=M()->table($pre.'member_good_collect coll')//
        ->join($pre.'group_goods goods on goods.id=coll.goods_id')//
        ->where($g_where)->count();

        $this->count=$count;
        
        $this->webtitle="FG峰购";
        $this->display();
    }
}