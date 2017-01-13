<?php
// 用户控制器
class RecommendAction extends UserAction {
    //魔术方法
    public function __construct(){
        parent::__construct();

    }
    //我的二维码
    public function index(){
        $memberinfo=$this->getMemberInfo();
        $host_url=$url='HTTP://'.$_SERVER['HTTP_HOST'];
        $data=$host_url.U('wap/start/index',array('share'=>$memberinfo['member_card']));
        $water_url=".".$memberinfo['member_logo'];
        $recommend_qr=$this->get_qrcode($data,$water_url,$level='L',$size='5');
        return $recommend_qr;
    }

    //浏览量
    public function pv(){
        $memberinfo=$this->getMemberInfo();
        $this->data=$memberinfo;
        $where['id']=$memberinfo['id'];
        $access_time_arr=M('member')->where($where)->field('access_time')->find();
        if (date('Ym')>date('Ym',$access_time_arr['access_time'])){
            M('member')->where($where)->setField('month_page_view',0);
        }
        $this->display();
    }
}