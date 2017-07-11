<?php
/****
** 会员action
** 
**/
class MembervipAction extends AuthAction{

    //会员列表
	public function index(){
		$where['mem.member_vip_type']=array('gt',0);
		$where['detail.vip_i_rank']=array('gt',0);
		$profit=$_GET['profit'];
		if($profit>0){
			$order='detail.id desc';
			$field="mem.id,mem.member_id,mem.member_card,mem.mobile,mem.login_count,mem.add_time,mem.member_freeze,mem.member_vip_type,mem.member_vip_order,mem.vip_time";
			$field.=",detail.balance,detail.balance_give,detail.points,detail.vip_i_rank,detail.vip_i_now,detail.vip_i_shares,detail.vip_i_lineup_time";
			$pre=C("DB_PREFIX");//获取表前缀
			import('ORG.Util.Page');// 导入数据页分类
			$model=M();
			$count = $model->table($pre.'member_detail detail')
					//->field($field)
					->where($where)
					->join($pre.'member mem on detail.member_id=mem.id')
					->count();
			$page = new page($count,20);
			$show = $page->show();   //输出分页
			$list = $model->table($pre.'member_detail detail')
					->field($field)
					->where($where)
					->join($pre.'member mem on detail.member_id=mem.id')
					->order($order)
					->limit($page->firstRow.','.$page->listRows)
					->select();
			///会员分红比例
			$dividend_payout_ratio=M('sys_param')->where(array('param_code' => 'dividend_payout_ratio'))->getField('param_value');//返利比例；
			if($dividend_payout_ratio>1){
				$dividend_payout_ratio=1;
		    }else if($dividend_payout_ratio<=0){
				$dividend_payout_ratio=0;
			}
			$profit_one=$profit*$dividend_payout_ratio/100000;
			$profit_one=sprintf("%6f",$profit_one);
			foreach($list as $key=>$val){
				if($val['member_vip_type']){
					$vip=getVipRank($val['vip_i_rank'],$val['vip_i_now']);
					$list[$key]['vip_name']='<span style="color:red;">'.$vip['vip_name'].'</span>';
				}else{
					$list[$key]['vip_name']='普通会员';
				}
				$profit_all=$val['vip_i_shares']*$profit_one;
				$list[$key]['profit_all']=sprintf("%6f",$profit_all);
			}

		}

		$menulist['list']=$list;
		$menulist['page']=$show;
		$this->dividend_payout_ratio=$dividend_payout_ratio;
		$this->assign("list",$menulist);
		$this->year_list=$this->get_years();///	///获取年份
		$this->display();
	}

	//会员分紅
	public function member_bonus(){

		$profit=$_GET['profit'];
		$quarter=$_GET['quarter'];
		$year=$_GET['year'];
		$return_data['status']=0;
		///会员分红比例
		$dividend_payout_ratio=M('sys_param')->where(array('param_code' => 'dividend_payout_ratio'))->getField('param_value');//返利比例；
		if($dividend_payout_ratio>1){
			$dividend_payout_ratio=1;
		}else if($dividend_payout_ratio<=0){
			$dividend_payout_ratio=0;
		}
		if($dividend_payout_ratio==0){
			$return_data['error'] = '会员分红比例未设置';
			echo json_encode($return_data);die;
		}
		if($profit<=0){
			$return_data['error'] = '请输入盈利总额';
			echo json_encode($return_data);die;
		}
		if($year<=0){
			$return_data['error'] = '请选年份';
			echo json_encode($return_data);die;
		}
		if($quarter<=0){
			$return_data['error'] = '请选择季度';
			echo json_encode($return_data);die;
		}
		$this->set_member_bonus($profit,$year,$quarter);

		$return_data['status']=1;
		echo json_encode($return_data);die;

	}
	//会员分紅
	/*
	 * $profit 盈利总额
	 * $year 哪年
	 * $quarter  季度
	 * $p 页码
	 */
	public function set_member_bonus($profit,$year,$quarter,$p=0){

		$where['mem.member_vip_type']=array('gt',0);
		$where['detail.vip_i_rank']=array('gt',0);
		$order='detail.id desc';
		$field="mem.id,mem.member_id,mem.member_card,mem.mobile,mem.login_count,mem.add_time,mem.member_freeze,mem.member_vip_type,mem.member_vip_order,mem.vip_time";
		$field.=",detail.balance,detail.balance_give,detail.points,detail.vip_i_rank,detail.vip_i_now,detail.vip_i_shares,detail.vip_i_lineup_time";
		$pre=C("DB_PREFIX");//获取表前缀
		import('ORG.Util.Page');// 导入数据页分类
		$model=M();
		$pagesize=10;
		$p=!empty($p)?$p:1;
		$start=($p-1)*$pagesize;
		$list = $model->table($pre.'member_detail detail')
				->field($field)
				->where($where)
				->join($pre.'member mem on detail.member_id=mem.id')
				->order($order)
				->limit($start,$pagesize)
				->select();
		if(empty($list)){
          return false;
		}
		///会员分红比例
		$dividend_payout_ratio=M('sys_param')->where(array('param_code' => 'dividend_payout_ratio'))->getField('param_value');//返利比例；
		if($dividend_payout_ratio>1){
			$dividend_payout_ratio=1;
		}else if($dividend_payout_ratio<=0){
			$dividend_payout_ratio=0;
		}
		$profit_one=$profit*$dividend_payout_ratio/100000;
		$profit_one=sprintf("%6f",$profit_one);
		$member_vip_downgrade=M('member_vip_downgrade');
		$member_vip_bonus_log=M('member_vip_bonus_log');
		$add_time=time();
		$quarter_time=$this->get_quarter_time($year,$quarter);

		foreach($list as $key=>$val){
			///检查成为会员时间是否在此季度的范围内
			if($val['vip_time']>=$quarter_time['end_time']){
			  ///如果成为vip时间大于等于这个季度最后一天 那么跳出本次循环（不分红）
              continue;
			}
			$profit_all=$val['vip_i_shares']*$profit_one;
			$profit_all=sprintf("%6f",$profit_all);
			$list[$key]['profit_all']=$profit_all;
			$where=array();
			$where['member_id']=$val['id'];
			$where['year']=$year;
			$where['quarter']=$quarter;
			$bonus=$member_vip_bonus_log->where($where)->count();
			if(empty($bonus)){
				$log_bonus=array();
				$log_bonus['member_id']=$val['id'];
				$log_bonus['vip_rank']=$val['vip_i_rank'];
				$log_bonus['vip_shares']=$val['vip_i_shares'];
				$log_bonus['profit_one']=$profit_one;
				$log_bonus['money']=$profit_all;
				$log_bonus['year']=$year;
				$log_bonus['quarter']=$quarter;
				$log_bonus['add_time']=$add_time;
				$los_res=$member_vip_bonus_log->add($log_bonus);
				if($los_res!==false){
					$log = array();
					$log['type'] = 5;//消费类型 1订单消费 2充值 3提现   4退款 5 收益 6认证消费
					$log['des'] = 'vip会员'.$year.'第'.$quarter.'季度分红收益：￥' . $profit_all;
					//加减用户 记录日志
					$res=$this->set_member_balance($val['id'], 1, $profit_all, $log);
				}else{

				}
			}
			///降级查询
			/*$where=array();
			$where['member_id']=$val['id'];
			$where['year']=$year;
			$where['quarter']=$quarter;
			$downgrade=$member_vip_downgrade->where($where)->count();
			if(empty($downgrade)){
				///查询时间段 是否满足业绩
				$unm=$this->get_recommend_num($val['member_card'],$val['vip_time'],$quarter_time['start_time'],$quarter_time['end_time']);
				if(!$unm){//不满足 降级
					$log_downgrade=array();
					$log_downgrade['member_id']=$val['id'];
					$log_downgrade['vip_rank']=$val['vip_i_rank'];
					$log_downgrade['vip_shares']=$val['vip_i_shares'];
					$log_downgrade['year']=$year;
					$log_downgrade['quarter']=$quarter;
					$log_downgrade['add_time']=$add_time;
					$los_downgrade_res=$member_vip_downgrade->add($log_downgrade);
					if($los_downgrade_res!==false){
						$log = array();
						$log['type'] = 5;//消费类型 1订单消费 2充值 3提现   4退款 5 收益 6认证消费
						$log['des'] = 'vip会员'.$year.'第'.$quarter.'季度分红收益：￥' . $profit_all;
						//加减降级
						//加减vip 积分
						$log = array();
						$log['type'] = 1;//【获得】1 推荐vip升级 2 订单消费 【减少】1 降级 2 订单退货
						$log['des'] = '订单消费';
						$res=$this->set_member_vip_integral($val['id'], 2, 100, $log);
					}else{

					}
				}

			}*/

		}
		$p=$p+1;
		return $this->set_member_bonus($profit,$year,$quarter,$p);
	}
	//会员业绩列表
	public function feat_index(){
		$where['mem.member_vip_type']=array('gt',0);
		$where['detail.vip_i_rank']=array('gt',0);
		$year=$_GET['year'];
		$quarter=$_GET['quarter'];
		if($year>0 && $quarter>0){
			$quarter_time=$this->get_quarter_time($year,$quarter);
			$order='detail.id desc';
			$field="mem.id,mem.member_id,mem.member_card,mem.mobile,mem.login_count,mem.add_time,mem.member_freeze,mem.member_vip_type,mem.member_vip_order,mem.vip_time";
			$field.=",detail.balance,detail.balance_give,detail.points,detail.vip_i_rank,detail.vip_i_now,detail.vip_i_shares,detail.vip_i_lineup_time";
			$pre=C("DB_PREFIX");//获取表前缀
			import('ORG.Util.Page');// 导入数据页分类
			$model=M();
			$count = $model->table($pre.'member_detail detail')
					//->field($field)
					->where($where)
					->join($pre.'member mem on detail.member_id=mem.id')
					->count();
			$page = new page($count,20);
			$show = $page->show();   //输出分页
			$list = $model->table($pre.'member_detail detail')
					->field($field)
					->where($where)
					->join($pre.'member mem on detail.member_id=mem.id')
					->order($order)
					->limit($page->firstRow.','.$page->listRows)
					->select();
			foreach($list as $key=>$val){
				if($val['member_vip_type']){
					$vip=getVipRank($val['vip_i_rank'],$val['vip_i_now']);
					$list[$key]['vip_name']='<span style="color:red;">'.$vip['vip_name'].'</span>';
				}else{
					$list[$key]['vip_name']='普通会员';
				}
				$feat=$this->get_recommend_feat($val['member_card'],$val['vip_time'],$quarter_time['start_time'],$quarter_time['end_time']);
				$list[$key]['feat']=$feat;
			}

		}

		$menulist['list']=$list;
		$menulist['page']=$show;
		$this->assign("list",$menulist);
		$this->year_list=$this->get_years();///	///获取年份
		$this->display();
	}
	//会员业绩计算 不达标降级
	public function member_feat(){
		$quarter=$_GET['quarter'];
		$year=$_GET['year'];
		$return_data['status']=0;
		
		if($quarter<=0){
			$return_data['error'] = '请选择季度';
			echo json_encode($return_data);die;
		}
		$this->set_member_feat($year,$quarter);

		$return_data['status']=1;
		echo json_encode($return_data);die;

	}
	//会员分紅
	/*
	 * $profit 盈利总额
	 * $year 哪年
	 * $quarter  季度
	 * $p 页码
	 */
	public function set_member_feat($year,$quarter,$p=0){

		$where['mem.member_vip_type']=array('gt',0);
		$where['detail.vip_i_rank']=array('gt',0);
		$order='detail.id desc';
		$field="mem.id,mem.member_id,mem.member_card,mem.mobile,mem.login_count,mem.add_time,mem.member_freeze,mem.member_vip_type,mem.member_vip_order,mem.vip_time";
		$field.=",detail.balance,detail.balance_give,detail.points,detail.vip_i_rank,detail.vip_i_now,detail.vip_i_shares,detail.vip_i_lineup_time";
		$pre=C("DB_PREFIX");//获取表前缀
		import('ORG.Util.Page');// 导入数据页分类
		$model=M();
		$pagesize=10;
		$p=!empty($p)?$p:1;
		$start=($p-1)*$pagesize;
		$list = $model->table($pre.'member_detail detail')
				->field($field)
				->where($where)
				->join($pre.'member mem on detail.member_id=mem.id')
				->order($order)
				->limit($start,$pagesize)
				->select();
		if(empty($list)){
			return false;
		}
		$member_vip_downgrade=M('member_vip_downgrade');
		$add_time=time();
		$quarter_time=$this->get_quarter_time($year,$quarter);

		foreach($list as $key=>$val){
			///检查成为会员时间是否在此季度的范围内
			if($val['vip_time']>=$quarter_time['start_time']){
				///如果成为vip时间在这个季度之后 或者之中 那么跳出本次循环（不降级）
				continue;
			}
			///降级查询
			$where=array();
			$where['member_id']=$val['id'];
			$where['year']=$year;
			$where['quarter']=$quarter;
			$downgrade=$member_vip_downgrade->where($where)->count();
			if(empty($downgrade)){
				///查询时间段 是否满足业绩
				$unm=$this->get_recommend_num($val['member_card'],$val['vip_time'],$quarter_time['start_time'],$quarter_time['end_time']);
				if(!$unm){//不满足 降级
					$log_downgrade=array();
					$log_downgrade['member_id']=$val['id'];
					$log_downgrade['vip_rank']=$val['vip_i_rank'];
					$log_downgrade['vip_shares']=$val['vip_i_shares'];
					$log_downgrade['year']=$year;
					$log_downgrade['quarter']=$quarter;
					$log_downgrade['add_time']=$add_time;
					$los_downgrade_res=$member_vip_downgrade->add($log_downgrade);
					if($los_downgrade_res!==false){
						$log = array();
						$log['type'] = 5;//消费类型 1订单消费 2充值 3提现   4退款 5 收益 6认证消费
						$log['des'] = 'vip会员'.$year.'第'.$quarter.'季度分红收益：￥' . $profit_all;
						//加减降级
						//加减vip 积分
						$log = array();
						$log['type'] = 1;//【获得】1 推荐vip升级 2 订单消费 【减少】1 降级 2 订单退货
						$log['des'] = 'vip会员'.$year.'第'.$quarter.'季度业绩未达标降级';
						$res=$this->set_member_vip_integral($val['id'], 2, 100, $log);
					}else{

					}
				}

			}

		}
		$p=$p+1;
		return $this->set_member_feat($year,$quarter,$p);
	}
	///获取年份
	public  function  get_years(){
      $year=Date('Y');
	  $y=$year-2015;

	  if($y){
       for($i=$y;$i>0;$i--){
		   $year_data[]=2015+$i;
	   }
	  }
		$year_data[]=2015;
		return $year_data;
	}
	///获取季度的开始结束时间
	public  function get_quarter_time($year,$quarter){
		if(empty($year)|| empty($quarter)){
			return false;
		}
		$return_data=array();
		switch($quarter){
			case 1:
				$start_time=strtotime($year.'-1');
				$end_time=strtotime($year.'-3-31 23:59:59');
				break;
			case 2:
				$start_time=strtotime($year.'-4');
				$end_time=strtotime($year.'-6-30 23:59:59');
				break;
			case 3:
				$start_time=strtotime($year.'-7');
				$end_time=strtotime($year.'-9-30 23:59:59');
				break;
			case 4:
				$start_time=strtotime($year.'-10');
				$end_time=strtotime($year.'-12-31 23:59:59');
				break;
			default :
				return false;
				break;
		}
		$return_data['start_time']=$start_time;
		$return_data['end_time']=$end_time;
		$return_data['start_time_1']=date('Y-m-d H:i:s',$start_time);
		$return_data['end_time_1']=date('Y-m-d H:i:s',$end_time);;
		return $return_data;
	}
	///获取时间段 ///查询时间段 是否满足业绩
	public  function get_recommend_num($member_code,$vip_time,$start_time=0,$end_time=0){
		if(empty($member_code)){
			return false;
		}
		//recommend_code
		$where=array();
		$where['member_vip_type']=1;
		$where['recommend_code']=$member_code;
		if(!empty($start_time) && !empty($end_time)){
			$where['vip_time']=array('between',array($start_time,$end_time));
		}else{
			if(!empty($start_time)){
				$where['vip_time']=array('egt',$start_time);
			}
			if(!empty($end_time)){
				$where['vip_time']=array('elt',$end_time);
			}
		}
		if($vip_time>=$end_time){
          return true;
		}else{
			$res=M('member')->where($where)->count();
			if($res>=50){
				return true;
			}else{
				return false;
			}
		}
	}
	///获取时间段 ///查询时间段 所有业绩
	public  function get_recommend_feat($member_code,$vip_time,$start_time=0,$end_time=0){
		if(empty($member_code)){
			return 0;
		}
		//recommend_code
		$where=array();
		$where['member_vip_type']=1;
		$where['recommend_code']=$member_code;
		if(!empty($start_time) && !empty($end_time)){
			$where['vip_time']=array('between',array($start_time,$end_time));
		}else{
			if(!empty($start_time)){
				$where['vip_time']=array('egt',$start_time);
			}
			if(!empty($end_time)){
				$where['vip_time']=array('elt',$end_time);
			}
		}

		$res=M('member')->where($where)->count();
		return $res;

	}

	//vip会员分红纪录
	public function member_vip_bonus_log(){
		if($_GET['mobile']){
			$where['mem.mobile']=array('like',$_GET['mobile']."%");
		}
		if($_GET['year']){
			$where['reb.year']=$_GET['year'];
		}
		if($_GET['quarter']){
			$where['reb.quarter']=$_GET['quarter'];
		}
		$pre=C('DB_PREFIX');//表前缀
		$count=M()->table($pre.'member_vip_bonus_log reb')//
		->join($pre.'member mem on reb.member_id=mem.id')//
		->where($where)->count();
		$page=D("Common")->getPage($count);//分页

		$field="reb.*,mem.member_name,mem.mobile";

		$list =M()->table($pre.'member_vip_bonus_log reb')
				->join($pre.'member mem on reb.member_id=mem.id')//
				->where($where)->field($field)//
				->order('reb.add_time desc')->limit($page['start'].','.$page['pagesize'])->select();
		if($list){
			$m_member=M('member');
			foreach($list as $key=>$val){
				//
				$des=$val['des'];
				preg_match('/\(([a-z0-9]+)\)/', $des, $matches);
				if($matches){
					$rep=$matches[0];
					$member_card=$matches[1];
					$member_id=$m_member->where(array('member_card'=>$member_card))->getField('id');
					if($member_id){
						$rep_text='(<a href="'.U("Admin/Member/member_edit",array('id'=>$data['id'])).'" >'.$member_card.'</a>)';
						$list[$key]['des']=str_replace($rep,$rep_text,$des);
						unset($rep_text);
					}
				}
				unset($des);
				unset($matches);
			}
		}
		$menulist['list']=$list;
		$menulist['page']=$page['page'];
		$this->year_list=$this->get_years();///	///获取年份
		$this->assign("list",$menulist);
		$this->display();
	}

	//vip积分获取记录
	public function member_vip_integral(){
		if($_GET['mobile']){
			$where['mem.mobile']=array('eq',$_GET['mobile']);
		}
		if($_GET['status']){
			$where['reb.status']=$_GET['status'];
		}
		if($_GET['type']){
			$where['reb.type']=$_GET['type'];
		}
		$type_array1=array(
				'1'=>'推荐vip升级',
				'2'=>'订单消费'
		);
		$type_array2=array(
				'1'=>'降级',
				'2'=>'订单退货'
		);
		$status_array=array('1'=>'获得','2'=>'扣除');
		$this->status_array=$status_array;
		//【获得】1 推荐vip升级 2 订单消费 【减少】1 降级 2 订单退货
		if($where['reb.status']==1){//状态 1获得 2消费（减少）
			$type_array=$type_array1;
		}elseif($where['reb.status']==2){
			$type_array=$type_array2;
		}
		$this->assign("type_array",$type_array);
		$this->type_array1=$type_array1;
		$this->type_array2=$type_array2;

		$pre=C('DB_PREFIX');//表前缀
		$count=M()->table($pre.'member_vip_integral reb')//
		->join($pre.'member mem on reb.member_id=mem.id')//
		->where($where)->count();
		$page=D("Common")->getPage($count);//分页

		$field="reb.*,mem.member_name,mem.mobile";

		$list =M()->table($pre.'member_vip_integral reb')
				->join($pre.'member mem on reb.member_id=mem.id')//
				->where($where)->field($field)//
				->order('reb.add_time desc')->limit($page['start'].','.$page['pagesize'])->select();
		foreach($list as $key=>$val){
			//【获得】1 推荐vip升级 2 订单消费 【减少】1 降级 2 订单退货
			if($val['status']==1){//状态 1获得 2消费（减少）
				$list[$key]['status_name']='获得';
				$list[$key]['type_name']=$type_array1[$val['type']];
			}elseif($val['status']==2){
				$list[$key]['status_name']='减少';
				$list[$key]['type_name']=$type_array2[$val['type']];
			}
		}
		$menulist['list']=$list;
		$menulist['page']=$page['page'];
		$this->assign("list",$menulist);
		$this->display();
	}


	public function	slide_image()
	{
		$list=M('member_gallery')
			->field('g.id ,g.img_url,g.member_id,m.member_name,g.add_time,g.is_check')
			->alias('g')
			->join('db_member as m on m.id = g.member_id')
			->order('add_time desc' )
			->select();
		$this->list=$list;
		$this->display();
	}
	public function is_check_slide()
	{
		//是否审核通过
		$data['is_check'] = 1;
		if ($_GET['value'] != null) {
			$id = $_GET["id"];
			$is_check = $_GET["value"];
			$where['id'] = $id;
			$save_date['is_check'] = $is_check;
			$res = M('member_gallery')->where($where)->save($save_date);
			if ($res !== false) {
				//$this->redirect("Admin/Goods/index");
				$data['status'] = 1;
				echo json_encode($data);
			} else {
				$data['error'] = '操作失败,请稍候再试';
				echo json_encode($data);
			}
		}
	}
	public function slide_image_del(){
		$imaging = M("member_gallery")->where(array("id" => $_GET["id"]))->delete();
		if ($imaging !== false) {
			$this->success("删除成功", U("Membervip/slide_image"));
		}else{
			$this->error("删除失败");
		}
	}

}
?>