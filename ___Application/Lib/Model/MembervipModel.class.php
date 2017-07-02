<?php
/**
 * Membervip
 *
 * 
 *
 */
class MembervipModel extends Model {

	///加减vip积分 记录日志 $status //状态 1获得 2消费（减少）
	//$money 消费金额
	/*   $log_data['type']=1;//【获得】1 推荐vip升级 2 订单消费 【减少】1 降级 2 订单退货
      *         $log_data['$integral']=$integral;
      *         $log_data['status']=2;//状态 1收入 2支出
      *         $log_data['des']="推荐人升级vip获得".$integral;//消费介绍
      *         $log_data['add_time']=time();//消费时间
      */
	public function set_member_vip_integral($member_id=0,$status=0,$money=0,$log=array()){
		if(empty($member_id) || !in_array($status, array(1,2))|| empty($money) || $money<0 || empty($log)){
			return false;
		}
		$vip_integral_model=M('member_vip_integral');
		$member_detail_model=M('member_detail');
		$field="member_id,vip_i_max,vip_i_now,vip_i_rank,vip_i_shares,vip_i_lineup_time,vip_total_consumption";
		$where['member_id']=$member_id;
		$memberinfo=$member_detail_model->Where($where)->field($field)->find();
		if(empty($memberinfo)){
			return false;
		}
		if($status==1){//收入
			if($log['type']==2) {//1 推荐vip升级 2 订单消费
				$money=$memberinfo['vip_total_consumption']+$money;
				$m_s_data['vip_total_consumption']=$money;
			}
		}
		$data_integral=$this->get_member_vip_money_to_integral($memberinfo,$money,$status,$log);///获取消费金额获得的积分
		$integral=$data_integral['integral'];
		$vip_type=$data_integral['vip_type'];
		if($integral<=0){///积分不足 订单消费 累计消费
			if($status==1){//收入
				if($log['type']==2) {//1 推荐vip升级 2 订单消费
					$member_detail_model->where($where)->save($m_s_data);
				}
			}
			return false;
		}
		if($status==1){//收入
			$m_s_data['vip_i_max']=$memberinfo['vip_i_max']+$integral;
			$m_s_data['vip_i_now']=$memberinfo['vip_i_now']+$integral;
			if($log['type']==2) {//1 推荐vip升级 2 订单消费
				$i_money=0;
				if($vip_type==1){
					$i_money=$integral*200;
				}elseif($vip_type==2){
					$i_money=$integral*500;
				}elseif($vip_type==3){
					$i_money=$integral*1000;
				}
				$m_s_data['vip_total_consumption']=$money-$i_money;
				$des='￥'.$i_money.',增加'.$integral;
				$log['money'] = $i_money;///累计消费
			}else{
				$des='增加'.$integral;
			}
			$log['des']=$log['des'].$des;
		}else{
			$m_s_data['vip_i_now']=$memberinfo['vip_i_now']-$integral;
		}
		$vip_rank=$memberinfo['vip_i_rank'];
		$vip_up=floor($m_s_data['vip_i_now']/100);
		$vip=$vip_up-$vip_rank;
        if($vip>0){///升级
			///判断股权是否足够
			$all_shares=$this->get_member_all_shares();
			$now_shares=$this->get_member_vip_rank_now_shares($vip_up);
			if($all_shares+$now_shares<=20000){//股权足够
				$vip_i_lineup_time=0;
				/// 获取新股权
				$vip_shares=$this->get_member_vip_rank_all_shares($vip_up);
				$m_s_data['vip_i_rank']=$vip_up;//新等级
				$m_s_data['vip_i_shares']=$vip_shares;
			}else{
				//股权不足不升级
				$vip_i_lineup_time=time();
				///累积的积分不能大于当前积分的最大值
				$vip_nxt_i=($vip_rank+1)*100;
				if($m_s_data['vip_i_now']>$vip_nxt_i){
					$m_s_data['vip_i_now']=$vip_nxt_i;
				}
			}
			$m_s_data['vip_i_lineup_time']=$vip_i_lineup_time;
	    }elseif($vip<0){//降级
		  if(empty($memberinfo['vip_i_lineup_time'])){///暂停升级
			  $vip_i_lineup_time=0;
			  /// 获取新股权
			  $vip_shares=$this->get_member_vip_rank_all_shares($vip_up);
			  $m_s_data['vip_i_rank']=$vip_up;//新等级
			  $m_s_data['vip_i_shares']=$vip_shares;
		  }else{//

		  }
		  $m_s_data['vip_i_lineup_time']=$vip_i_lineup_time;
	    }
		$log['status']=$status;
		$log['add_time']=time();
		$log['member_id']=$member_id;
		$log['integral']=$integral;///总积分
		$member_detail_model->startTrans();//开起事务
		$vip_integral_model->startTrans();//开起事务

		$m_res=$member_detail_model->where($where)->save($m_s_data);
		$c_res=$vip_integral_model->add($log);
		if($c_res!==false && $m_res!==false){
			$vip_integral_model->commit();//提交事务
			$member_detail_model->commit();//提交事务

			return true;
		}else{
			$member_detail_model->rollback();//回滚事务
			$vip_integral_model->rollback();//回滚事务

			return false;
		}
	}

	/*
	 * 计算vip等级和获得的vip积分
	 * @param $memberinfo
	 * @param $money
	 * @param $status  ///加减vip积分 记录日志 $status //状态 1获得 2消费（减少）
	 * @param $log
	 *         $log_data['type']=1;//【获得】1 推荐vip升级 2 订单消费 【减少】1 降级 2 订单退货
	 *         $log_data['$integral']=$integral;
	 *         $log_data['status']=2;//状态 1收入 2支出
	 *         $log_data['des']="推荐人升级vip获得".$integral;//消费介绍
	 *         $log_data['add_time']=time();//消费时间
	 */
	public function  get_member_vip_money_to_integral($memberinfo,$money,$status,$log){
		$integral=0;
		$vip_type=1; ///1 vip 0-5  2 vip 5-10 3 vip 11-20
		if($status==1){//加vip积分
			//$vip_up=$memberinfo['vip_i_now']/100;
			$vip=$memberinfo['vip_i_rank'];
			if($log['type']==1){//1 推荐vip升级 2 订单消费
				if($vip<=5){
					$integral=5;
					$vip_type=1;
				}else if($vip>5 && $vip<=10){
					$integral=2;
					$vip_type=2;
				}else{
					$integral=1;
					$vip_type=3;
				}
			}else{
				if($vip<=5){
					$integral=floor($money/200);
					$vip_type=1;
				}else if($vip>5 && $vip<=10){
					$integral=floor($money/500);
					$vip_type=2;
				}else{
					$integral=floor($money/1000);
					$vip_type=3;
				}
			}
		}else{//减vip积分
			if($log['type']==1){//1 降级 2 订单退货
				$integral=$money;
			}else{
				//member_vip_integral
			}

		}
		$return_data['integral']=$integral;
		$return_data['vip_type']=$vip_type;
		return $return_data;
	}
	/***
	 * 获取当前vip等级的股权
	 */
	public function  get_member_vip_rank_now_shares($vip_rank){
		$vip_shares=0;
		if($vip_rank>0){//加vip积分
			if($vip_rank<=5){
				$vip_shares=1;
			}elseif($vip_rank>5 && $vip_rank<=10){
				$vip_shares=2;
			}else{
				$vip_shares=5;
			}
		}
		return $vip_shares;
	}
	/***
	 * 获取vip 等级的所有股权
	 */
	public function  get_member_vip_rank_all_shares($vip_rank){
		$vip_shares=0;
		if($vip_rank>0){//加vip积分
			for($i=1;$i<=$vip_rank;$i++){
				if($i<=5){
					$vip_shares+=1;
				}elseif($i>5 && i<=10){
					$vip_shares+=2;
				}else{
					$vip_shares+=5;
				}
			}
		}
		return $vip_shares;
	}
	/***
	 * 获取现有会员的所有 股权
	 */
	public function  get_member_all_shares(){
		$pre=C("DB_PREFIX");//获取表前缀
		$where=array(
			'mem.member_vip_type'=>1,
		);
		$all_shares=M()->table($pre.'member_detail detail')
		->join($pre.'member mem on mem.id=detail.member_id')->where($where)
		->sum('detail.vip_i_shares');
		$all_shares=$all_shares?$all_shares:0;
		return $all_shares;
	}
}
