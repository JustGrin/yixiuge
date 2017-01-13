<?php
// 前台控制器，继承公共目录下的HomeAction，方便公共数据调用
class FGShopAction extends CommonAction {
	//魔术方法
	public function __construct(){
		parent::__construct();

	}
	public function index(){
      if (!IS_AJAX) {
          //顶部展示商品
          $g_where['is_on_sale']=1;//该商品是否开放销售，1，是；0， 否
          $g_where['is_delete']=0;//商品是否已经删除，0，否；1，已 删除
          $g_where['is_auditing']=1;//商品是否通过审核  1是0 否
          $g_where['is_upgrade']=0;//剔除升级产品
          $g_where['is_show_index']=1;//是否显示
          $g_where['is_sell_out']=0;//是否售罄        1,是 0,否
          $g_field="goods_id,goods_name,shop_price,market_price,goods_brief,goods_img,vip_price,unit_price,units,base_name,base_logo,subtitle,goods_browse";
          $g_order="sort_order desc,goods_id desc";
          $goods=M("g_goods")->where($g_where)->field($g_field)->order($g_order)->limit(0,4)->select();
          for ($i=0; $i<count($goods); $i++) { 
                  $url = "http://".$_SERVER['HTTP_HOST']."/wap/goods/goods_detail/id/".$goods[$i]['goods_id'];
                  $goods[$i]['qrcode'] = $this->get_qrcode($url);
          }
          //列表商品
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
          $count=M("g_goods")->where($g_where)->count();
          import('ORG.Util.Page');// 导入分页类
          $page = new Page($count,'15');
          $show = $page->show();
          $goods_list=M("g_goods")->where($g_where)->field($g_field)->limit($page->firstRow.','.$page->listRows)->order($g_order)->select();
          for ($i=0; $i<count($goods_list); $i++) { 
                  $url = "http://".$_SERVER['HTTP_HOST']."/wap/goods/goods_detail/id/".$goods_list[$i]['goods_id'];
                  $goods_list[$i]['qrcode'] = $this->get_qrcode($url);
          }
          
          $this->return_url = U('fghome/FGShop/index');
          $this->assign('page',$show);
          $this->assign('goods',$goods);
          $this->assign('goods_list',$goods_list);
          $this->display();
      }else{
          //顶部展示商品
          $g_where['is_on_sale']=1;//该商品是否开放销售，1，是；0， 否
          $g_where['is_delete']=0;//商品是否已经删除，0，否；1，已 删除
          $g_where['is_auditing']=1;//商品是否通过审核  1是0 否
          $g_where['is_upgrade']=0;//剔除升级产品
          $g_where['is_show_index']=1;//是否显示
          $g_where['is_sell_out']=0;//是否售罄        1,是 0,否
          $g_field="goods_id,goods_name,shop_price,market_price,goods_brief,goods_img,vip_price,unit_price,units,base_name,base_logo,subtitle,goods_browse";
          $g_order="sort_order desc,goods_id desc";
          //列表商品
          $cryp_cat_id=$this->get_category_child('19');//剔除 成人用品
          $g_where['cat_id']=array('not in',$cryp_cat_id);
          $type=$_REQUEST['type'];
          $p=$_REQUEST['p'];
          if (empty($p)) {
            $p=1;
          }
          $first=($p-1)*15;
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
          $count=M("g_goods")->where($g_where)->count();
          import('ORG.Util.Page');// 导入分页类
          $page = new Page($count,'15');
          $show = $page->show();
          $goods_list=M("g_goods")->where($g_where)->field($g_field)->limit($first.','.$page->listRows)->order($g_order)->select();
          for ($i=0; $i<count($goods_list); $i++) { 
                  $url = "http://".$_SERVER['HTTP_HOST']."/wap/goods/goods_detail/id/".$goods_list[$i]['goods_id'];
                  $goods_list[$i]['qrcode'] = $this->get_qrcode($url);
          }
          if (!empty($goods_list)) {
                  $goods_list[$i-1]['page'] = $show;
          }
          echo json_encode($goods_list);
      }
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

}