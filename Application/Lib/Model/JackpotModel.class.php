<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/10/13
 * Time: 11:50
 */
class JackpotModel extends Model
{
	public function set_jackpot($syb_money){
		$add_money=((float)$syb_money)*(float)5/(float)100;
		$today=date('Ymd',time());
		$today_time=strtotime($today);
		//$gainer=M('member')->where('vip_time<"'.$today_time.'" and member_vip_type != 0')->count();
		$gainer=M('jackpot_member')->where('c_time<"'.$today_time.'"')->count('distinct(member_id)');
		$today_jackpot=M('jackpot')->where('add_time="'.$today_time.'"')->find();
		if(empty($today_jackpot)){
			$data_jack['add_time']=$today_time;
			$data_jack['total_money']=$add_money;
			$data_jack['gainer']=$gainer;
			$re=M('jackpot')->add($data_jack);
		}else{
			$data_jack['total_money']=$add_money+$today_jackpot['total_money'];
			$re=M('jackpot')->where('add_time="'.$today_time.'"')->save($data_jack);
		}
	}

	public function today_bonus($type=null)
	{
		$uid=$_SESSION['member']['uid'];
		$member_info=M('member')->where(array('id'=>$uid))->find();
		$where['member_id']=$uid;
		$last_scratch_time=M('draw_log')->where($where)->order('add_time desc')->getfield('add_time');
		//本次抽奖时间戳
		$beginday=$last_scratch_time?$last_scratch_time:$member_info['vip_time'];
		//昨日时间戳
		$endYesterday=mktime(0,0,0,date('m'),date('d'),date('Y'))-1;
		$where_j['add_time']=array('between',array($beginday,$endYesterday));
		$money_arr=M('jackpot')->where($where_j)->select();
		$money = 0;
		foreach ($money_arr as $k => $v){
			$data=array();
			$data['draw_times']=$v['draw_times']+1;
			$this_money=$v['total_money']/$v['gainer'];
			$this_money=mb_number($this_money);
			$data['draw_money']=$v['draw_money']+$this_money;
			if($data['draw_times']==$v['gainer']){
				$data['draw_money']=$v['total_money'];
				$this_money=$v['total_money']-$v['draw_money'];
			}
			if($data['draw_money']>$v['total_money']){
				continue;
			}
			$money+=$this_money;
			if($type=='change'){
				M('jackpot')->where('id='.$v['id'])->save($data);
			}
		}
		return  $money;
	}
}