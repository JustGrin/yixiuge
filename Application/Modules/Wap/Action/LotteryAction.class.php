<?php
// 抽奖
class LotteryAction extends BaseAction {
	//魔术方法
public function __construct(){
		parent::__construct();
	}

  public function index(){
      $this->data=$this->get_today_draw();
	  $this->set_lottery_member();
	  $status=1;
	  $uid=$this->uid;
	  //获取该用户奖池信息
	  $jackpot_info=M('jackpot_member')->where('member_id='.$uid)->find();
	  $check=$this->check_draw();
	  //如果已经抽过奖，显示今日中奖金额
	  if($check){
		  $img='/Uploads/gift/'.$uid.'.gif';
		  if($jackpot_info['last_money']==0){
			  $img='/Public/wap/img/choujiang/gift0.gif';
		  }
		  $msg="<img src='{$img}'/>";
	  }else{
		  $img=$this->set_prize_img($jackpot_info['money']);
		  if($jackpot_info['money']==0){
			  $img='/Public/wap/img/choujiang/gift0.gif';
		  }
		  $this->assign('img',$img);
	  }

	  if(empty($jackpot_info)){
		  $status=0;
		  $msg="您目前暂无抽奖资格";
	  }else{
		  //昨天是时间戳
		  $yesdaytime=strtotime(date('Y-m-d',strtotime("-1 day")));
		  if($yesdaytime<$jackpot_info['c_time']){
			  $status=2;
			  $write_time="亲，由于要统计奖池的金额，所以新成为店主的用户要两天后才可以抽奖喔！";
			  $msg=$write_time;
		  }else{
			  $status=1;
		  }
	  }

	  $this->assign('status',$status);
	  $this->assign('msg',$msg);
	  $this->assign('check',$check);
	  $this->display();
  }

  public function get_today_draw(){
        $uid=$this->uid;
        $todaytime=strtotime(date('Y-m-d',time()));
        $tmdaytime=strtotime(date('Y-m-d',strtotime("+1 day")));
        $where['member_id']=$uid;
        $where['draw_type']=15;
        $where['add_time']=array('between',array($todaytime,$tmdaytime));
        $data=M('draw_log')->where($where)->find();
        $data=$this->get_draw_msg($data);
        return $data;
    }
	//获取奖品信息
	public function get_draw_msg($draw_data=array()){
		if(empty($draw_data)){
			return array();
		}
		$draw_data['draw_type_name']='现金';
		$draw_data['draw_num']=$draw_data['draw_value'];
		$draw_data['draw_value_unit']="￥"; //单位
		return $draw_data;
	}
   //验证抽奖权限
  public function check_draw(){
      $uid=$this->uid;
      $todaytime=strtotime(date('Y-m-d',time()));
      $tmdaytime=strtotime(date('Y-m-d',strtotime("+1 day")));
      $where['member_id']=$uid;
      $where['last_time']=array('between',array($todaytime,$tmdaytime));
      $data=M('jackpot_member')->where($where)->count();
     return $data;
  }
 
  //查询并设置抽奖记录
  public function set_draw_msg($draw_data=null)
  {
	  if (empty($draw_data) ) {
		  return array();
	  }
	  $uid = $this->uid;
	  $log_data = array();
	  $log_data['member_id'] = $uid;
	  $log_data['draw_id'] = $draw_data['id'];
	  $log_data['draw_type'] = $draw_data['draw_type'];
	  $log_data['draw_value'] = $draw_data['draw_value'] ;
	  $log_data['add_time'] = $draw_data['add_time'];
      $draw_data['statuse']=1;
      $draw_data['is_shenghe']=1;
	  $res = M('draw_log')->add($log_data);//log_status
	  $draw_data['draw_type_name'] = '现金';
	  $balance = $draw_data['draw_value'];
	  if ($balance > 0) {
              $member_detail_model=M('member_detail');
		 	  $member_record=M('member_consume_record');
              $field="member_id,points,balance";
              $where['member_id']=$uid;
              $memberinfo=$member_detail_model->Where($where)->field($field)->find();
              if(empty($memberinfo)){
                  return false;
              }
		  		//收益记录
		  		$log_record['member_id']=$uid;
		  		$log_record['money']=$balance;
		  		$log_record['status']='1';
		  		$log_record['type']=15;
		  		$log_record['des']= "每日抽奖中奖" . $balance;
		  		$log_record['add_time']= $draw_data['add_time'];
              $member_detail_model->startTrans();//开起事务
		      $member_record->startTrans();//开启事务
              $m_res=$member_detail_model->where($where)->setInc('balance',$balance);
		  	  $r_res=$member_record->add($log_record);
              if($m_res!==false && $r_res !==false){
                  $member_detail_model->commit();//提交事务
              }else{
                  $member_detail_model->rollback();//回滚事务

              }
		  if ($m_res) {
			  M('draw_log')->where(array('id' => $res))->save(array('log_status' => 5));//log_status
		  }
	  }
	  return $draw_data;
  }
    //抽奖
  public function get_draw(){
     $return_data['status']=0;
	  $uid=$this->uid;
 /*    //抽奖开关
     $switch_draw=M('sys_param')->where(array('param_code'=>'switch_draw'))->getField('param_value');
     if(empty($switch_draw)){
       $return_data['error']="抽奖未开启";
       echo json_encode($return_data);die;
     }*/
     $check_draw=$this->check_draw();
     if($check_draw){
       $return_data['error']="每天只能抽一次奖";
       echo json_encode($return_data);die;
     }
     //奖品池
	  $draw=M('jackpot_member')->where('member_id='.$uid)->getField('money');
	  //取出自己奖池的奖金
	  $take_data['last_time']=time();
	  $take_data['last_money']=$draw;
	  $take_data['money']=0;
	  M('jackpot_member')->where('member_id='.$uid)->save($take_data);
	  M('jackpot_member')->where('member_id='.$uid)->setInc('total_money',$draw);
      $draw_data['id']=15;
	  $draw_data['draw_type']='15';
	  $draw_data['draw_value']=$draw;
      $draw_data['is_shenghe']=1;
	  $draw_data['add_time']=$take_data['last_time'];
/*	  if($draw == 0){
		  $return_data['error']="暂无抽奖资格";
		  echo json_encode($return_data);die;
	  }*/
     $draw_msg=$this->set_draw_msg($draw_data);
/*	 if(empty($draw_msg)||$draw_msg['draw_value']==0){
       $return_data['error']="未中奖";
       echo json_encode($return_data);die;
     }*/
     $draw_msg=$this->get_draw_msg($draw_msg);
     $return_data['status']=1;
     $return_data['draw']=$draw_msg;
     echo json_encode($return_data);die;
  }
	public function set_prize_img($text){
		import('ORG.Util.Image.ThinkImage');
		// 在图片右下角添加水印文字 ThinkPHP 并保存为new.jpg
		$img = new ThinkImage(THINKIMAGE_GD, './Public/wap/img/choujiang/gift.gif');
		$font='./Public/wap/css/font/arial.ttf';
		$size=20;
		$color = '#f00';
		$locate = THINKIMAGE_WATER_CENTER;
		$offset = 0;
		$angle = 0;
		$uid=$this->uid;
		$save='./Uploads/gift/'.$uid.'.gif';
		$img->text($text,$font, $size, $color ,$locate , $offset , $angle)->save($save);
		return '/Uploads/gift/'.$uid.'.gif';
	}
	
	/*
	* 每天把前一天的奖项分配到每个用户的记录
	* kkxx
	* 2016-10-17
	*/
	public function set_lottery_member(){
		$time = time();
		$todaytime = strtotime(date('Y-m-d', $time));
		$jackpot_list = M('jackpot')->where(array('add_time'=>array('lt', $time), 'is_check'=>0, 'gainer'=>array('gt', 0)))->select();
		foreach($jackpot_list as $item){
			$money = mb_number($item['total_money'] / $item['gainer']);
			if($money > 0){
				$lt_time = strtotime(date('Y-m-d', $item['add_time']));
				$num = M('jackpot_member')->where(array('c_time'=>array('lt', $lt_time)))->limit($item['gainer'])->save(array('update_time'=>$time));
				M('jackpot_member')->where(array('c_time'=>array('lt', $lt_time)))->limit($item['gainer'])->setInc('money', $money);
			}
			M('jackpot')->where(array('id'=>$item['id']))->save(array('draw_money'=>($money * $num), 'draw_num'=>$num, 'is_check'=>1));
		}
	}
}