<?php
/*
 *
 * 公告
 */
class UsernoticeAction extends BaseAction{

    public function index(){
        $qrcode=$_GET['qrcode'];
        if(empty($qrcode)) {
             echo '没有传入参数';die;
        }
       $url=$this->get_qrcode($qrcode);
       if($url){
            echo '<img src="'.$url.'">';
       }else{
            echo '生成失败';
       }
    }
    public function notice_detail(){
        if($_GET['id']){
            $where['id']=$_GET['id'];
            $data = M('user_notice')->where($where)->find();
        }else{
            $this->error('文章不存在');
        }
        $this->data=$data;
        $this->shar_url = $url = "http://" . $_SERVER['HTTP_HOST'] . U('wap/Usernotice/notice_detail', array('id' => $data['id'], 'share' => $this->member_card));//分享地址
        $this->shar_title = $data['title'];///分享标题
        $this->shar_desc = C('SHAR_DESC');///分享内容
        $this->shar_imgurl = "http://" . $_SERVER['HTTP_HOST'] . '/Public/wap/img/logg.png';///分享图片地址
        $this->get_weixin();///获取微信 信息
        $this->display();
    }
	
    //文章列表页
    public function tweet_list(){
        $p = $_REQUEST['p'];
        $pagesize = 10;
        $p = !empty($p) ? $p : 1;
        $start = ($p - 1) * $pagesize;
        $where['is_check']=1; //已审核
        $where['type']=0; //0普通文章,1活动文章
        $data = M('article_tweet')->order('add_time desc')->where($where)->limit($start, $pagesize)->select();
//        echo M()->_sql();die;
//        var_dump($data);die;
        if(IS_AJAX){
            if(!empty($data)){
                foreach($data as $k => $v){
                    $data[$k]['add_time']=date('Y-m-d H:i:s',$v['add_time']);
                }
            }
            echo json_encode($data);die;
        }
        $this->count = M('g_goods')->count();
        $this->now_menu = 'member';
        $this->assign('data', $data);
        $shar_title = '峰购趣闻';//分享标题
        $this->shar_url = $url = "http://" . $_SERVER['HTTP_HOST'] . U('wap/Usernotice/tweet_list',array('share' => $this->member_card));//分享地址
        $this->shar_title = $shar_title;///分享标题
        $this->shar_desc = C('SHAR_DESC');///分享内容
        $this->shar_imgurl = "http://" . $_SERVER['HTTP_HOST'] . '/Public/wap/img/logg.png';///分享图片地址
        $this->get_weixin();///获取微信 信息
        $this->display();
    }
	
    //推文内容
    public function tweet_detail(){
        if($_GET['id']){
            $where['id']=$_GET['id'];
        }
        $where['is_check']=1;
//        $where['type']=0;
        $data=M('article_tweet')->where($where)->find();
//        //判断是否有推文
//        if($data['tui_article']){
//            $arr=explode(',',$data['tui_article']);
//            foreach($arr as $v){
//                $data['tweet_msg'][]=M('article_tweet')->field('id,title')->where(array('id'=>$v))->find();
//            }
//        }
        //判断是否有推广商品
//        if($data['tui_goods']){
//            $arr=explode(',',$data['tui_goods']);
//            $field='goods_id,goods_name,shop_price,market_price,goods_img';
//            foreach($arr as $v){
//                $data['goods_msg'][]=M('g_goods')->field($field)->where(array('goods_id'=>$v))->find();
//            }
//        }
//        var_dump($data);die;
        if($data['type']==1){
              $user_agent = $_SERVER['HTTP_USER_AGENT'];
              if(strpos($user_agent, 'MicroMessenger') === false) {
                   die('请在微信中打开');
              }
        }
        $this->data=$data;
        //获取推文广告
        $p = $_REQUEST['p'];
        $pagesize=3;
        $p = !empty($p) ? $p : 1;
        $start = ($p - 1) * $pagesize;
        $filed='image_path,link';
        $a_where['type']=8;  //广告类型为推文广告
        $a_where['status']=1;  //广告状态为有效
        $ad=M('ad')->field($filed)
            ->where($a_where)
            ->order('add_time desc')
            ->limit($start,$pagesize)
            ->select();
        $this->count=M('ad')->where($a_where)->count();
        if(IS_AJAX){
            echo json_encode($ad);die;
        }
        $this->ad=$ad;
        $this->now_menu = 'member';
        $this->shar_url = $url = "http://" . $_SERVER['HTTP_HOST'] . U('wap/Usernotice/tweet_detail', array('id' =>$data['id'],'share' => $this->member_card));//分享地址
        $this->shar_title = $data['title'];///分享标题
        $this->shar_desc = C('SHAR_DESC');///分享内容
        $this->shar_imgurl = "http://" . $_SERVER['HTTP_HOST'] . $data['img'];///分享图片地址
        $this->get_weixin();///获取微信 信息
		$this->mobile="11";
        $this->display();
    }
	
    //浏览量统计
    public function browse_stat(){
        $where['id'] = $_POST['id'];
        M('article_tweet')->where($where)->setInc('page_view');
    }
	
	//微信SEO列表
	public function seo_list(){
        $pagesize = 10;
        $p = isset($_REQUEST['p']) && $_REQUEST['p'] ? $_REQUEST['p'] : 1;
        $start = ($p - 1) * $pagesize;
		
        $where['is_check'] = 1;//已审核
        $where['type'] = 2; //0普通文章,1活动文章,2微信SEO
		
		$list = M('article_tweet')->order('add_time desc')->where($where)->limit($start, $pagesize)->select();
		foreach($list as $key=>$val){
			$list[$key]['imgs'] = unserialize($val['imgs_a']);
		}
		$this->assign('list', $list);
		$this->display();
	}
	
	public function seo_detail(){
		$this->display();
	}
}
