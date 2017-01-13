<?php
// 前台控制器，继承公共目录下的HomeAction，方便公共数据调用
class IndexAction extends CommonAction {
	//魔术方法
public function __construct(){
		parent::__construct();
		
		$webseting['web_title']='米友'; 
       $webseting['web_copyright']=M('sys_param')->where(array('param_code'=>'web_copyright'))->getField('param_value'); 
       $this->webseting=$webseting;
		
	}
	
    public function index(){
	    // 轮播广告 
        $ad1=M("ad")->where(array('type'=>4,'status'=>1))->order('sort desc,add_time desc')->limit(5)->select();
        $this->ad1=$ad1;
        // 关于飞米 
        $ad2=M("ad")->where(array('type'=>5,'status'=>1))->order('sort desc,add_time desc')->find();
        $this->about=$ad2;

        // 我们的团队 
        $tuandui=M("ad")->where(array('type'=>6,'status'=>1))->order('sort desc,add_time desc')->limit(5)->select();
        $this->tuandui=$tuandui;

         // 我们的服务
        $fuwu=M("ad")->where(array('type'=>7,'status'=>1))->order('sort desc,add_time desc')->limit(5)->select();
        $this->fuwu=$fuwu;

         //商家推荐
        $c_where['is_show_index']=1;
         $c_where['pid']=0;
        $c_where['status']=1;
        $c_field="id,class_name,class_icon,class_des";
        $class_list=M("group_class")->where($c_where)->field($c_field)->limit(8)->select();
        $this->class_list=$class_list;

        //商家推荐
        $s_where['is_show_index']=1;
        $s_where['status']=1;
        $s_field="shop_id,top_class_id,class_id,shop_name,logo,addr";
        $shop_list=M("shop_detail")->where($s_where)->field($s_field)->limit(8)->select();
        $this->shop_list=$shop_list;
        

        $this->webtitle="米友首页";
		$this->display();
    }

    //商家列表
    public function shop(){

         if($_GET['type']){
            $where['top_class_id']=$_GET['type'];
        }
        $s_where['status']=1;
        $s_field="shop_id,top_class_id,class_id,shop_name,logo,background,addr,business";//member_desc
        $menulist=D("Common")->getPageList('shop_detail',$s_where,$s_field,'',8);
        $this->assign("list",$menulist);
        
        $c_where['pid']=0;
        $c_where['status']=1;
        $c_field="id,class_name,class_icon,class_des";
        $class_list=M("group_class")->where($c_where)->field($c_field)->select();
        $this->class_list=$class_list;

        $this->display();
    }
    //商家详情
    public function shop_detail(){
        $where['shop_id']=$_GET['id'];
        $data=M("shop_detail")->where($where)->find();
        $this->data=$data;
        $this->display();
    }

    //动态列表
    public function news(){

        //$s_where['type']=2;//类型1 公共 2动态
        $s_field="id,title,imgpath,abstrac,add_time,admin_name";//member_desc
        $menulist=D("Common")->getPageList('sys_notice',$s_where,$s_field,'',8);
        $this->assign("list",$menulist);

        $this->display();
    }
    //动态详情
    public function news_detail(){
        $where['id']=$_GET['id'];
        $data=M("sys_notice")->where($where)->find();
        $this->data=$data;
        $this->display();
    }

     //提交留言
    public function set_message(){
        //
        $data['status']=0;
        $title=$_REQUEST['title'];
        if(empty($title)){
            $data['error']="请输入名称";
            echo json_encode($data);die;
        }
        $phone=$_REQUEST['phone'];
        if(empty($phone)){
            $data['error']="请输入电话";
            echo json_encode($data);die;
        }
        if(!check_phone($phone) && !check_telphone($phone)){
            $data['error']="电话格式错误";
            echo json_encode($data);die;
        }
        $email=$_REQUEST['email'];
        $message=$_REQUEST['message'];
        $model=M('message');
        $ip=getIP();
        $time=60*5;//限制时间 分钟
        $x_time=time()-$time;
        $count=$model->where(array('add_ip'=>$ip,'add_time'=>array('gt',$x_time)))->count();
        if($count>0){
            $data['error']="留言太频繁,请稍候再试.";
            echo json_encode($data);die;
        }
        $add_data['title']=$title;
        $add_data['email']=$email;
        $add_data['phone']=$phone;
        $add_data['message']=$message;
        $add_data['add_time']=time();
        $add_data['add_ip']=$ip;
        $res=$model->add($add_data);
        if($res!==false){
            $data['status']=1;
        }else{
            $data['error']='留言失败,请稍候再试.';
        }
        echo json_encode($data);die;
    }
 public function get_access_token_t(){
        M('sys_param')->where(array('param_code'=>'buy_switch'))->save(array('param_value'=>0));
    }

}