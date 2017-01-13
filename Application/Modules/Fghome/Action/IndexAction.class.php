<?php
// 前台控制器，继承公共目录下的HomeAction，方便公共数据调用
class IndexAction extends CommonAction {
	//魔术方法
public function __construct(){
		parent::__construct();
		
	}
	
    public function index(){
	    // 轮播广告 
        $ad1=M("ad")->where(array('type'=>4,'status'=>1))->order('sort desc,add_time desc')->limit(5)->select();
        $this->ad1=$ad1;
        
        // 关于飞米 
        $ad2=M("ad")->where(array('type'=>5,'status'=>1))->order('sort desc,add_time desc')->find();
        $this->about=$ad2;
         //商家推荐
       /* $c_where['is_show_index']=1;
         $c_where['pid']=0;
        $c_where['status']=1;
        $c_field="id,class_name,class_icon,class_des";
        $class_list=M("group_class")->where($c_where)->field($c_field)->limit(8)->select();
        $this->class_list=$class_list;*/
        //商家推荐
      /*  $s_where['is_show_index']=1;
        $s_where['status']=1;
        $s_field="shop_id,top_class_id,class_id,shop_name,logo,addr";
        $shop_list=M("shop_detail")->where($s_where)->field($s_field)->limit(8)->select();
        $this->shop_list=$shop_list;*/
        //商品展示
        $g_order= $_REQUEST['order'];
        if($g_order=='salesvolume'){
            $g_order='goods_salesvolume desc,goods_id desc';
        }else{
            $g_order = "sort_order desc,goods_id desc";
        }
        $cat_id=$_REQUEST['cat_id'];
        if($cat_id){
            $pid=M('g_category')->where(array('cat_id'=>$cat_id))->getField('cat_id');
           if($pid){
            $type_str=$this->get_category_child($pid);
            if($type_str){
              $g_where['cat_id']=array('in',$type_str);
            }
           }
        }else{
             $cryp_cat_id =$this->get_category_child('19');//剔除 成人用品
            $g_where['cat_id'] = array('not in', $cryp_cat_id);
        }
        $p = $_REQUEST['p'];
        $pagesize = 8;
        $p = !empty($p) ? $p : 1;
        $start = ($p - 1) * $pagesize;
        $g_where['is_upgrade']=0;//剔除升级产品
        $g_where['is_on_sale'] = 1;//该商品是否开放销售，1，是；0， 否
        $g_where['is_delete'] = 0;//商品是否已经删除，0，否；1，已 删除
        $g_where['is_auditing'] = 1;//商品是否通过审核  1是0 否
        $g_where['is_show_index']=1;//是否显示
        $g_where['is_sell_out']=0;//是否售罄        1,是 0,否
        $g_field = "goods_id,goods_name,shop_price,market_price,goods_brief,goods_img";
        $goods_list = M("g_goods")->where($g_where)->field($g_field)//
        ->order($g_order)->limit($start, $pagesize)->select();
        $host_url=$url='HTTP://'.$_SERVER['HTTP_HOST'];
        if($goods_list){
            foreach ($goods_list as $key => $value) {
                $data=$host_url.U('wap/goods/goods_detail',array('id'=>$value['goods_id']));
                $goods_list[$key]['goods_qrcode']=$this->get_qrcode($data);
            }
        }
        if (IS_AJAX) {
            echo json_encode($goods_list);
            die;
        }


        //$class_where['show_in_nav']=1;//是否显示在导航栏，0，不；1，显示在导航 栏
        $class_where['is_show']=1;
        $class_where['parent_id']=0;
        $class_where['cat_id']=array('neq',19);
        $this->tuijian=M('g_category')->where($class_where)->order('sort_order desc,cat_id desc')->limit(6)->select();

        $this->sum=count($goods_list);
        $this->host_url=$host_url;
//        var_dump($goods_list);die;
        $this->list = $goods_list;
        $this->count = M("g_goods")->where($g_where)->count();
//        var_dump($goods_list);die;
        $this->webtitle="飞米首页";
		$this->display();
    }

   
    //获取分类 cat_id 组合
    public function get_category_child($pid = 0)
    {
        $list = M('g_category')->where(array('parent_id' => $pid, 'is_show' => 1))->field('cat_id')->select();
        if ($pid) {
            $type_str = ',' . $pid;
        } else {
            $type_str = ',';
        }
        if ($list) {//查下级
            $type_str1 = "";
            foreach ($list as $key => $value) {
                $type_str1 .= ',' . $value['cat_id'];
            }
            if ($type_str1) {
                $type_str .= $type_str1;
                $type_str1 = substr($type_str1, 1);
                if ($type_str1) {//查下级
                    $type_str2 = "";
                    $list = M('g_category')->where(array('parent_id' => array('in', $type_str1), 'is_show' => 1))->field('cat_id')->select();
                    foreach ($list as $key => $value) {
                        $type_str2 .= ',' . $value['cat_id'];
                    }
                    if ($type_str2) {
                        $type_str .= $type_str2;
                        unset($list);
                    }
                }
            }
        }
        if ($type_str) {
            $type_str = substr($type_str, 1);
        }
        return $type_str;
    }
    
}