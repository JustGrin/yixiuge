<?php
// 前台控制器，继承公共目录下的HomeAction，方便公共数据调用
class IndexAction extends BaseAction {
	//魔术方法
	public function __construct(){
		parent::__construct();
	}

    public function index(){
        $member_info=$this->getMemberInfo();

       //商品推荐
        $p=$_REQUEST['p'];
        $pagesize=10;
        $p=!empty($p)?$p:1;
        $start=($p-1)*$pagesize;
        $g_where['is_on_sale']=1;//该商品是否开放销售，1，是；0， 否
        $g_where['is_delete']=0;//商品是否已经删除，0，否；1，已 删除
        $g_where['is_auditing']=1;//商品是否通过审核  1是0 否
        $g_where['is_upgrade']=0;//剔除升级产品
        $g_where['is_show_index']=1;//是否显示
        $g_where['activity_id']=0;//非活动商品
        $g_field="goods_id,goods_name,shop_price,market_price,goods_brief,goods_img,vip_price,unit_price,units,base_name,base_logo,subtitle,goods_browse";
        //$g_field="goods_id,goods_name,shop_price,goods_img,market_price";
        //$g_order="is_show_index desc,sort desc";
        $g_order="is_sell_out asc,sort_order desc,goods_id desc";
        $goods_list=M("g_goods")->where($g_where)->field($g_field)->order($g_order)->limit($start,$pagesize)->select();
    		foreach($goods_list as $k=>$item){
    			$goods_list[$k]['goods_img'] = thumbs_auto($item['goods_img'], 200, 200);
    			$goods_list[$k]['base_logo'] = thumbs_auto($item['base_logo'], 60, 60);
    		}

        $this->member_info=$member_info;

         if(IS_AJAX){
            echo json_encode($goods_list);die;
        }
        $this->goods_list=$goods_list;
		// 轮播广告 
        $ad1=M("ad")->where(array('type'=>1,'status'=>1))->order('sort desc,add_time desc')->select();
        $this->ad1=$ad1;
        // 分类下的广告 
        $ad2=M("ad")->where(array('type'=>2,'status'=>1))->order('sort desc,add_time desc')->find();
        $this->ad2=$ad2;
        //关联展示
        $field='id,type,end_time,value,title,image_path';
        $where=array();
        $time=time();
        $where['status']=1;
        $where['start_time']=array('elt',$time);
        $where['end_time']=array('gt',$time);
        $ad3=M('home_ad')->field($field)->where($where)->order('sort desc,add_time desc')->select();
        $time = time();
        foreach($ad3 as $k => $v){
            $remain_time =$v['end_time']-$time;
            if($remain_time<0){
                continue;
            }
            $day=$remain_time/86400;  //取得剩余天数
            if($day>1){  //剩余天数大于1天
                $ad3[$k]['remain_time']=intval($day).'天';
            }
            if($day<1){  //剩余天数小于1天
                $hour= $remain_time/3600;
                if($hour>1){  //剩余小时数大于1
                    $ad3[$k]['remain_time'] = intval($hour) . '小时';
                }else{   //剩余小时数小于1
                    $second= $remain_time/60;
                    $ad3[$k]['remain_time']=ceil($second).'分';
                }
            }
        }
        $this->ad3=$ad3;
        //中上部导航广告
        $ad4=M("ad")->where(array('type'=>9,'status'=>1))->order('sort desc,add_time desc')->select();
        for ($i=0; $i<count($ad4); $i++) { 
          if (!empty($ad4[$i]['link'])) {
            $ad4[$i]['link'] = "http://" . $_SERVER['HTTP_HOST'] . $ad4[$i]['link'] . '/share/' . $this->shop_code;
          }
        }
        $this->ad4=$ad4;
//        var_dump($ad3);die;
        //限时抢购商品
        /* $field='is_on_sale,is_auditing,is_promote,goods_id,goods_name,promote_img,promote_start_date,promote_end_date';
         $where=array();
         $time=time();
        $where['is_on_sale']=1;  //是否开放销售,
        $where['is_promote']=1;
        $where['is_auditing']=1;
        $where['is_delete']=0;
        $where['promote_start_date']=array('elt',$time);
        $where['promote_end_date']=array('egt',$time);
        $p = $_REQUEST['p'];
        $pagesize = 10;
        $p = !empty($p) ? $p : 1;
        $start = ($p - 1) * $pagesize;
        $data=M('g_goods')->field($field)->where($where)->limit($start,$pagesize)->select();
        foreach($data as $k=>$v){    //获得活动状态
            $data[$k] = D('Goods')->getGoodsPrice($v);
        }
        $this->data=$data;*/
        //行业分类
      
        //商家推荐
//        $s_where['is_show_index']=1;
//        $s_where['status']=1;
//        $s_field="shop_id,shop_name,logo,background,discount_consume";
//        $shop_list=M("shop_detail")->where($s_where)->field($s_field)->limit(6)->select();
//        $this->shop_list=$shop_list;
            
       //服务推荐
        /*$g_where['is_show_index']=1;
        $g_where['is_delete']=0;
        $g_field="goods_id,goods_name,shop_price,goods_img,market_price";
        $g_order="sort_order desc";
        $goods_list=M("g_goods")->where($g_where)->field($g_field)->order($g_order)->limit(6)->select();
        $this->goods_list=$goods_list;*/
        //商品分类
        $company_check = M('member_verification')->where('member_id='.$this->uid)->getField('status_c');
        $where_category['is_exhibition'] = 1;
        $g_category = M("g_category")->where($where_category)->order('sort_order asc, cat_id asc')->select();
        foreach ($g_category as $k => $v){
            $g_category[$k]['check_cc'] = ( $company_check != 1 && $v['need_check'] == 1) ? 1 : 0;
            $category_url =   $g_category[$k]['check_cc'] ? U('wap/goods/index', array( 'type' => 0)) : U('wap/goods/index', array( 'type' => $v['cat_id']))  ;
            $g_category[$k]['category_url'] =$category_url;
        }
        $this->g_category=$g_category;
        //判断是否完善收货地址,电话
		$add_address=array();
		$add_address['need_add']=0;
        $this->hongbao=$this->set_hongbao_show();
        $this->now_menu='home';////菜单
        $shar_title = $shop_info['member_name'].'的店铺';//C('SHAR_TITLE');//分享标题
        $this->shar_url = $url = "http://" . $_SERVER['HTTP_HOST'] . U('wap/index/index', array('share' => $this->shop_code));//分享地址
        $this->shar_title = $shar_title;///分享标题
        $this->shar_desc = C('SHAR_DESC');///分享内容
        $this->shar_imgurl = "http://" . $_SERVER['HTTP_HOST'] .$shop_info['member_logo'];///分享图片地址
        $this->get_weixin();///获取微信 信息

        //触发抢购成功
        /*A('Activity')->setWinners();*/

        $this->display();
    }
    //分类
    public function category(){
        
       // $class_where['is_show_index']=1;
        $class_where['status']=1;
        $class_where['is_del']=0;
        $group_class=M('group_class')->where($class_where)->select();
        $group_class=$this->gettree($group_class);
        foreach ($group_class as $key => $value) {
           if(empty($value['tree'])){
                unset($group_class[$key]);
           }
        }
        $this->group_class=$group_class;
        $class_where['is_show_index']=1;
        //$class_where['pid']=0;
        $this->tuijian=M('group_class')->where($class_where)->select();
        //跳转地址
        $return_url=U('wap/shop/index');
        if($_GET['type']=='shop_group'){
            //米品
            $return_url=U('wap/shop/shop_group');
        }elseif($_GET['type']=='find'){
            //米品
            $return_url=U('wap/shop/find');
        }
        $this->return_url=$return_url;
        $shar_title = $this->webseting['web_title'] . C('SHAR_TITLE');//分享标题
        $this->shar_url = $url = "http://" . $_SERVER['HTTP_HOST'] . U('wap/index/category', array('share' => $this->member_card));//分享地址
        $this->shar_title = $shar_title;///分享标题
        $this->shar_desc = C('SHAR_DESC');///分享内容
        $this->shar_imgurl = "http://" . $_SERVER['HTTP_HOST'] . '/Public/wap/img/logg.png';///分享图片地址
        $this->get_weixin();///获取微信 信息
        $this->display();
    }
    //城市选项
    public function set_city(){
        //热门城市
        $this->hot_city=$this->get_hot_city();
        //所有城市
         $all_city=$this->get_all_city();
         $city_arr=array();
         foreach ($all_city as $key => $value) {
            $city_arr[$value['first']][]=$value;
         }
         unset($all_city);
         ksort($city_arr);
         $this->call_city=$city_arr;
         $return_url=$_SERVER['HTTP_REFERER'];
         $this->return_url=$return_url?$return_url:U('wap/index/find');
       
        $this->display();
    }
    /**数据统计**/
/*      public function statistics(){
           $data=$this->set_count_data();
           $c=count($data)-1;
           $time=time();
           $time_all=0;
           for ($i=0; $i < 15; $i++) { 
              $arr=array();
              //时间
              $t=rand(0,600);//5 -1个小时
              $time_all+=$t;
              $now_time=$time-$time_all;
              $arr['time']=date("Y-m-d H:i:s",$now_time);
              $now_c=rand(0,$c);
              $arr['text']=$data[$now_c];
              $list[]=$arr;
           }
           $this->data= M("miyou_count")->find();
           $this->list=$list;
           $this->display();
      }*/

    public function set_num(){
        if($_GET['type']==1){
            //用户
             M("miyou_count")->where(array('id'=>1))->setInc('user',1);
              $data=M("miyou_count")->where(array('id'=>1))->getField('user');
        }else{
            M("miyou_count")->where(array('id'=>1))->setInc('shop',1);
            $data=M("miyou_count")->where(array('id'=>1))->getField('shop');
        }
        echo $data;
        
    }
   public function randomFloat($min = 0, $max = 1) {  
        return $min + mt_rand() / mt_getrandmax() * ($max - $min);  
    }
  public function set_count_data(){
    $share=array(
      5,5,5,10,10,10,20,
      30,40,50,60,70,80,100,
      5,5,5,10,10,10,20,
       5,5,5,10,10,10,20,
      30,40,50,60,70,80,100,
       5,5,5,10,10,10,20,
        5,5,5,10,10,10,20,
         5,5,5,10,10,10,20,
          5,5,5,10,10,10
      );
     $data=array('新用户注册','用户登录','用户推荐收益￥'.rand(1,200)/100,'用户分享佣金￥'.$share[array_rand($share,1)],
            '平台收益￥'.rand(10,1000)/100,'用户消费￥'.rand(100,1000)/100,'用户登录','用户登录','用户推荐收益￥'.rand(1,200)/100,'用户分享佣金￥'.$share[array_rand($share,1)],
            '平台收益￥'.rand(10,1000)/100,'用户消费￥'.rand(100,1000)/100,'用户登录','用户登录','用户推荐收益￥'.rand(1,200)/100,'用户登录','用户分享佣金￥'.$share[array_rand($share,1)],
            '平台收益￥'.rand(10,1000)/100,'用户消费￥'.rand(100,1000)/100,'用户登录','用户登录','用户推荐收益￥'.rand(1,200)/100,'用户登录','用户分享佣金￥'.$share[array_rand($share,1)],
            '平台收益￥'.rand(10,1000)/100,'用户消费￥'.rand(100,1000)/100,'用户登录','用户登录','用户推荐收益￥'.rand(1,200)/100,'用户分享佣金￥'.$share[array_rand($share,1)],
            '平台收益￥'.rand(10,1000)/100,'用户消费￥'.rand(100,1000)/100,'用户登录','用户推荐收益￥'.rand(1,200)/100,'用户分享佣金￥'.$share[array_rand($share,1)],
            '平台收益￥'.rand(10,1000)/100,'用户消费￥'.rand(100,1000)/100,'新用户注册');
     return $data;
  }    
  public function get_data(){
           $data=$this->set_count_data();
           $c=count($data)-1;
           $time=time();
           $arr=array();
          //时间
          $time_all=rand(0,20);//5 -1个小时
          $now_time=$time-$time_all;
          $arr['time']=date("Y-m-d H:i:s",$now_time);
          $now_c=rand(0,$c);
          $arr['text']=$data[$now_c];
          echo json_encode($arr);
      }
   //城市设置
    public function set_city_ajax(){
       echo 1;
    }

//php获取中文字符拼音首字母
public function getFirstCharter($str){
      if(empty($str)){return '';}
     $fchar=ord($str{0});
      if($fchar>=ord('A')&&$fchar<=ord('z')) return strtoupper($str{0});
      $s1=iconv('UTF-8','gb2312',$str);
      $s2=iconv('gb2312','UTF-8',$s1);
      $s=$s2==$str?$s1:$str;
     $asc=ord($s{0})*256+ord($s{1})-65536;
     if($asc>=-20319&&$asc<=-20284) return 'A';
     if($asc>=-20283&&$asc<=-19776) return 'B';
     if($asc>=-19775&&$asc<=-19219) return 'C';
     if($asc>=-19218&&$asc<=-18711) return 'D';
     if($asc>=-18710&&$asc<=-18527) return 'E';
     if($asc>=-18526&&$asc<=-18240) return 'F';
     if($asc>=-18239&&$asc<=-17923) return 'G';
     if($asc>=-17922&&$asc<=-17418) return 'H';
     if($asc>=-17417&&$asc<=-16475) return 'J';
     if($asc>=-16474&&$asc<=-16213) return 'K';
     if($asc>=-16212&&$asc<=-15641) return 'L';
     if($asc>=-15640&&$asc<=-15166) return 'M';
     if($asc>=-15165&&$asc<=-14923) return 'N';
     if($asc>=-14922&&$asc<=-14915) return 'O';
     if($asc>=-14914&&$asc<=-14631) return 'P';
     if($asc>=-14630&&$asc<=-14150) return 'Q';
     if($asc>=-14149&&$asc<=-14091) return 'R';
     if($asc>=-14090&&$asc<=-13319) return 'S';
     if($asc>=-13318&&$asc<=-12839) return 'T';
     if($asc>=-12838&&$asc<=-12557) return 'W';
     if($asc>=-12556&&$asc<=-11848) return 'X';
     if($asc>=-11847&&$asc<=-11056) return 'Y';
     if($asc>=-11055&&$asc<=-10247) return 'Z';
     return null;
 }
///显示红包
public function set_hongbao_show(){
  if($this->uid){
    cookie('hongbao',1);
    return 1;
  }else{
     $hongbao=cookie('hongbao');
      cookie('hongbao',1);
     return $hongbao;
   }
}
public function guide(){
    $this->display();
}
}