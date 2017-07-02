<?php
// 订单
class AboutAction extends BaseAction {
	//魔术方法
	public function __construct(){
		parent::__construct();	
	}
	
	//关于我们
    public function index(){
		$shar_title=$this->webseting['web_title'].C('SHAR_TITLE');
        $this->shar_url= $url="http://".$_SERVER['HTTP_HOST'].U('wap/about/index',array('share'=>$this->member_card));
        $this->shar_title=$shar_title;
        $this->shar_desc=C('SHAR_DESC');
        $this->shar_imgurl="http://".$_SERVER['HTTP_HOST'].'/Public/wap/img/logg.png';///分享图片地址
        $this->get_weixin();///获取微信 信息
		$this->display();
    }
	
	//功能介绍
	public function introduction(){
		$this->introduction=M('sys_param')->where(array('param_code'=>'introduction'))->getField('param_value'); 

        $shar_title=$this->webseting['web_title'].C('SHAR_TITLE');//'-功能介绍';
        $this->shar_url= $url="http://".$_SERVER['HTTP_HOST'].U('wap/about/introduction',array('share'=>$this->member_card));
        $this->shar_title=$shar_title;
        $this->shar_desc=C('SHAR_DESC');;
        $this->shar_imgurl="http://".$_SERVER['HTTP_HOST'].'/Public/wap/img/logg.png';///分享图片地址
		$this->get_weixin();///获取微信 信息
		$this->display();
	}
	
	//系统通知
	public function notice(){
		$p=$_REQUEST['p'];
		$pagesize=10;
		$p=!empty($p)?$p:1;
		$start=($p-1)*$pagesize;    
		$s_where['type']=1;//类型1 公告 2动态
		$s_field="id,title,imgpath,abstrac,add_time,admin_name";//member_desc
		$list=M("sys_notice")->where($s_where)//
		->field($s_field)->limit($start,$pagesize)->order('add_time desc')->select();
		foreach ($list as $key => $value) {
			$list[$key]['add_time']=date('Y-m-d',$value['add_time']);
		}
		if(IS_AJAX){
			echo json_encode($list);die;
		}
		$this->count=M("sys_notice")->where($s_where)->count();
		$this->list=$list;

		$shar_title=$this->webseting['web_title'].//C('SHAR_TITLE');'-系统通知';
		$this->shar_url= $url="http://".$_SERVER['HTTP_HOST'].U('wap/about/notice',array('share'=>$this->member_card));
		$this->shar_title=$shar_title;
		$this->shar_desc=C('SHAR_DESC');;
		$this->shar_imgurl="http://".$_SERVER['HTTP_HOST'].'/Public/wap/img/logg.png';///分享图片地址
		$this->get_weixin();///获取微信 信息

		$this->display();
	}

	//系统通知
	public function notice_detail(){
		$id=$_GET['id'];
		$list=M("sys_notice")->where(array('id'=>$id))->find();
		$this->data=$list;

		$shar_title=$this->webseting['web_title'].C('SHAR_TITLE').'-'.$list['title'];
		$this->shar_url= $url="http://".$_SERVER['HTTP_HOST'].U('wap/about/notice_detail',array('id'=>$_GET['id'],'share'=>$this->member_card));
		$this->shar_title=$shar_title;
		$this->shar_desc=C('SHAR_DESC');;
		$this->shar_imgurl="http://".$_SERVER['HTTP_HOST'].'/Public/wap/img/logg.png';///分享图片地址
		$this->get_weixin();///获取微信 信息

		$this->display();
	}

	//关于我们
	public function about_us(){
		$this->display();
	}
	
	//关于FG峰购
	public function about_fg(){
		$this->display();
	}
	
	//关于飞米
	public function about_fm(){
		$this->display();
	}
}