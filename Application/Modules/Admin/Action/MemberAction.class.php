<?php
/****
** 会员action
** 
**/
class MemberAction extends AuthAction{

    //会员列表
	public function index(){
		set_time_limit(0);
//        if($_GET['member_card']){
//			$where['mem.member_card']=array('like',$_GET['member_card'].'%');
//		}
		if($_GET['member_name']){
			$where['mem.name'] = array('like', '%' . $_GET['member_name'] . '%');
		}
		if($_GET['mobile']){
			$s_where['mem.mobile'] = array('like', '%' . $_GET['mobile'] . '%');
			$s_where['mem.member_card'] = array('like', '%' . $_GET['mobile'] . '%');
			$s_where['_logic'] = 'or';
			$where['_complex'] = $s_where;
		}
		/*if(isset($_GET['member_type'])){
			if($_GET['member_type']!='all'){
				$where['mem.member_type']=$_GET['member_type'];
			}
		}else{
			$_GET['member_type']='all';
		}
		if(isset($_GET['member_status'])){
			if($_GET['member_status']!='all'){
				$where['mem.member_status']=$_GET['member_status'];
			}
		}else{
			$_GET['member_status']='all';
		}*/
		if(isset($_GET['member_vip_type'])){
			if($_GET['member_vip_type']!='all'){
				$where['mem.member_vip_type']=$_GET['member_vip_type'];
			}
		}else{
			$_GET['member_type']='all';
		}
		if(isset($_GET['member_freeze'])){
			if($_GET['member_freeze']!='all'){
				$where['mem.member_freeze']=$_GET['member_freeze'];
			}
		}else{
			$_GET['member_freeze']='all';
		}
		if($_GET['binding']){
			if($_GET['binding']=='1'){//已绑定
				$where['_string']="( mem.mobile <>'' and mem.mobile is not null)";
			}else if($_GET['binding']=='2'){//未绑定
				$where['_string']="( mem.mobile ='' or mem.mobile is null)";
			}
		}
		if($_GET['start_time']){
			$start_time=strtotime($_GET['start_time']);
		}
		if($_GET['end_time']){
			$end_time=strtotime($_GET['end_time'].'23:59:59');
		}
		if($end_time>0 && $start_time>0){
			$where['mem.add_time']=array('between',array($start_time,$end_time));
		}else{
			if($start_time>0){
				$where['mem.add_time']=array('egt',$start_time);
			}
			if($end_time>0){
				$where['mem.add_time']=array('elt',$end_time);
			}
		}
		if(isset($_GET['order'])){
			$order=$_GET['order'];
			switch ($order) {
				case 'id':
					$order="mem.id desc";
					break;
				case 'login_count':
					$order="mem.login_count desc";
					break;	
				case 'balance':
					$order="detail.balance desc";
					break;
				case 'balance_give':
					$order="detail.balance_give desc";
					break;
				case 'points':
					$order="detail.points desc";
					break;		
				default:
					$order="mem.id desc";
					break;
			}
		}else{
			$order="mem.id desc";
		}
		//$menulist=D("Common")->getPageList('member',$where);
		/*$list=$menulist['list'];
		if($list){
			$mdetail=M('member_detail');
			foreach ($list as $key => $value) {
				$detail=$mdetail->where(array('member_id'=>$value['id']))->field('balance,points,balance_give')->find();
				$list[$key]['balance']=$detail['balance'];
				$list[$key]['balance_give']=$detail['balance_give'];
				$list[$key]['points']=$detail['points'];
			}
		}*/
		//分页数据查询
		//$field="mem.*,detail.balance,detail.balance_give,detail.points";
		$field="mem.id,mem.member_name,mem.member_id,mem.member_card,mem.mobile,mem.login_count,mem.add_time,mem.member_freeze,mem.member_vip_type,mem.member_vip_order";
		$field.=",detail.balance,detail.balance_give,detail.points";
		$pre=C("DB_PREFIX");//获取表前缀
		import('ORG.Util.Page');// 导入数据页分类
		$model=M();
		if(empty($where)){
			$count = $model->table($pre.'member_detail detail')
					->count();
		}else{
			$count = $model->table($pre.'member_detail detail')
					//->field($field)
					->where($where)
					->join($pre.'member mem on detail.member_id=mem.id')
					->count();
		}

			
		$page = new page($count,20);
		$show = $page->show();   //输出分页
		$list = $model->table($pre.'member_detail detail')
			->field($field)
			->where($where)
			->join($pre.'member mem on detail.member_id=mem.id')
			->order($order)
			->limit($page->firstRow.','.$page->listRows)
			->select();
		$vip_names=array('0'=>'普通会员','1'=>'标准店铺','2'=>'高级店铺','3'=>'旗舰店');
		$this->assign('vip_names',$vip_names);
/*		foreach($list as $key=>$val){
			if($val['member_vip_type']){
				$vip=getVipRank($val['vip_i_rank'],$val['vip_i_now']);
				$list[$key]['vip_name']='<span style="color:red;">'.$vip['vip_name'].'</span>';
			}else{
				$list[$key]['vip_name']='普通会员';
			}
		}*/
		$menulist['list']=$list;
			$menulist['page']=$show;
		$this->assign("list",$menulist);
        $s_num_day=date('Ymd');//美天
		$all_balance=S("all_balance_".$s_num_day);//获取缓存余额
		$is_all_balance=S("is_all_balance_".$s_num_day);//获取缓存余额
		if(empty($is_all_balance)){
			$all_balance=$model->table($pre.'member_detail detail ')
					->where($where)
					->join($pre.'member mem on detail.member_id=mem.id')
					->sum('detail.balance');//应付款金额
			S("all_balance_".$s_num_day,$all_balance);
			S("is_all_balance_".$s_num_day,1);
		}

          $this->all_balance=round($all_balance);
		$all_balance_give=S("all_balance_give_".$s_num_day);//获取缓存余额
		$is_all_balance_give=S("is_all_balance_give_".$s_num_day);//获取缓存余额
		if(empty($is_all_balance_give)){
			$all_balance_give=$model->table($pre.'member_detail detail')
					->where($where)
					->join($pre.'member mem on detail.member_id=mem.id')
					->sum('detail.balance_give');//应付款金额
			S("all_balance_give_".$s_num_day,$all_balance_give);
			S("is_all_balance_give_".$s_num_day,1);
		}

         $this->all_balance_give=round($all_balance_give); 
		 $m_member=M('member');
         $today_time=strtotime(date("Y-m-d"));
		 $today_where['add_time']=array('egt',$today_time);	                   	
		 $this->all_today_count=$m_member->where($today_where)->count();
         
         $today_where=array();
         $today_time=strtotime(date("Y-m-d"));
		 $today_where['add_time']=array('egt',$today_time);
		 //$today_where['_string']="( mobile <>'' and mobile is not null )";	                   	
		  $today_where['_string']="( mobile >'0')";	
		 $this->all_today_bin_count=$m_member->where($today_where)->count();

		 $this->all_count=$count;	                   	                   
		$this->display();
		//$this->display();
	}
	//编辑用户信息
	public function member_edit(){
		if($_POST){
			if(empty($_POST['p_name'])){
				$this->error("请填写部门名称");
			}
			if($_POST['id']){
                $res = M("member")->where("id=".$_POST["id"])->save($_POST);
			}else{
                $res = M("member")->add($_POST);
			}
			if($res!==false){
				$this->success("操作成功",U("Admin/Member/index"));
			}else{
				$this->error("操作失败");
			}
		}else{
			if($_GET["id"]){
				$menuwhere["id"] =  array("eq",$_GET["id"]);
				$data = M("member")->where($menuwhere)->find();
				$menuwhere_d["member_id"] =  array("eq",$_GET["id"]);
				$data_d = M("member_detail")->where($menuwhere_d)->find();
				if($data['member_vip_type']){
					$vip=getVipRank($data_d['vip_i_rank'],$data_d['vip_i_now']);
					$data['vip_name']='<span style="color:red;">'.$vip['vip_name'].'</span>';
				}else{
					$data['vip_name']='普通会员';
				}
			}else{
				$where['pid']=array('eq',0);
			}
			$title="会员信息";
			
			$this->assign("data",$data);
			
			$this->assign("msgtitle",$title);
			$this->display();
		}
	}

//用户vip
	public function set_member_vip(){
		$id = $_GET["id"];
		$res = M("member")->where("id=".$_GET["id"])->find();
		if(empty($res)){
			$this->error("该会员不存在");
		}
		if($res['member_vip_type']){
			$data['member_vip_type']=0;
			$data['member_vip_order']=0;
			$data['vip_time']=0;
		}else{
			$data['member_vip_type']=1;
			$data['member_vip_order']=1;
			$data['vip_time']=time();
		}
		$res = M("member")->where("id=".$id)->save($data);
		if($res!==false){
			$this->success("操作成功");
		}else{
			$this->error("操作失败");
		}
	}
	//用户冻结
	public function member_freeze(){
		$id = $_GET["id"];
		$res = M("member")->where("id=".$_GET["id"])->find();
		if(empty($res)){
			$this->error("该会员不存在");
		}
		if($res['member_freeze']){
			$data['member_freeze']=0;
		}else{
			$data['member_freeze']=1;
		}
		$res = M("member")->where("id=".$id)->save($data);
		if($res!==false){
				$this->success("操作成功");
		}else{
				$this->error("操作失败");
		}
	}
	
	//会员收益记录
	public function rebate_record(){
        if($_GET['member_card']){
			$where['member_card']=array('like',$_GET['member_card'].'%');
		}
        if($_GET['mobile']){
			$where['mobile']=array('like',$_GET['mobile'].'%');
		}
		/*if($_GET['status']){
			$where['reb.status']=$_GET['status'];
		}*/
		if($_GET['type']){
			$where['reb.type']=$_GET['type'];
		}
		$where['reb.status']=1;//状态 1用户收益 2 商家返利
		//1消费者本身返点 2直接推荐人返点 3间接推荐人返点  
       //4 直接推荐人认证返点 5 间接推荐人认证返点 
        //10 商家返点
		$type_array=array(
			//'1'=>'消费者本身返点',
			'2'=>'直接推荐人返点',
			'3'=>'间接推荐人返点',
			'5'=>'间接二级推荐人返点',
			'6'=>'分享商品返点'
			//'4'=>'直接推荐人认证返点',
			//'5'=>'间接推荐人认证返点'//,
			//'10'=>'商家返点'
			);
		$this->assign("type_array",$type_array);

        $pre=C('DB_PREFIX');//表前缀
        $count=M()->table($pre.'rebate_record reb')//
        ->join($pre.'member mem on reb.member_id=mem.id')//
        ->where($where)->count();
        $page=D("Common")->getPage($count);//分页

        $field="reb.*,mem.member_name,mem.mobile,mem.member_card";
        
    	$list =M()->table($pre.'rebate_record reb')
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
		$this->assign("list",$menulist);
		$this->display();
	}

	//会员消费记录
	public function member_consume_record(){
        if($_GET['mobile']){
			$where['mem.mobile']=array('like',$_GET['mobile']."%");
		}
		if($_GET['status']){
			$where['reb.status']=$_GET['status'];
		}
		if($_GET['type']){
			$where['reb.type']=$_GET['type'];
		}
			//1订单消费 2充值 3提现   4退款 5 收益 6认证消费
			$type_array=array(
				'1'=>'订单消费',
				//'2'=>'充值',
				'3'=>'提现',
				'4'=>'退款',
				'5'=>'收益',
				//'6'=>'认证消费'
				);
			$this->assign("type_array",$type_array);

        $pre=C('DB_PREFIX');//表前缀
        $count=M()->table($pre.'member_consume_record reb')//
        ->join($pre.'member mem on reb.member_id=mem.id')//
        ->where($where)->count();
        $page=D("Common")->getPage($count);//分页

        $field="reb.*,mem.member_name,mem.mobile";
        
    	$list =M()->table($pre.'member_consume_record reb')
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
		$this->assign("list",$menulist);
		$this->display();
	}
  
	//会员充值记录
	public function member_recharge(){
        if($_GET['mobile']){
			$where['mem.mobile']=array('eq',$_GET['mobile']);
		}
		if($_GET['status']){
			$where['reb.status']=$_GET['status'];
		}
		$where['reb.type']=1;//类型 1会员充值 2商户充值
		//0已退款 1 待支付 2已支付 3支付失败
		$type_array=array(
			'1'=>'待支付',
			'2'=>'已支付',
			'3'=>'支付失败'
			);
		$this->assign("type_array",$type_array);

        $pre=C('DB_PREFIX');//表前缀
        $count=M()->table($pre.'member_recharge reb')//
        ->join($pre.'member mem on reb.member_id=mem.id')//
        ->where($where)->count();
        $page=D("Common")->getPage($count);//分页

        $field="reb.*,mem.member_name,mem.mobile";
        
    	$list =M()->table($pre.'member_recharge reb')
    	 ->join($pre.'member mem on reb.member_id=mem.id')//
    	 ->where($where)->field($field)//
    	->order('reb.add_time desc')->limit($page['start'].','.$page['pagesize'])->select();
	
		$menulist['list']=$list;
		$menulist['page']=$page['page'];
		$this->assign("list",$menulist);
		$this->display();
	}

	//会员推荐查询
	public function member_tuijian(){
        if($_GET['mobile']){
			$where['_string']='mobile='.$_GET['mobile'].' or member_card='.$_GET['mobile'];
			 $data=M('member')->where($where)->find();
			  //查询推荐人信息
			 if($data['recommend_code']){
			 	$where=array();
			 	$where['member_card']=$data['recommend_code'];
			 	$this->recommend_data=M('member')->where($where)->find();
			 }
		}
        $this->data=$data;
	    if($data){
	    	if($_GET['recommend']){//间接推荐人
                 $tjwhere['indirect_recommend_code']=$data['member_card'];
	    	}else{
	    		 $tjwhere['recommend_code']=$data['member_card'];
	    	}
	    	
	        $count=M('member')->where($tjwhere)->count();
	        $page=D("Common")->getPage($count);//分页    
	    	$list =M('member')->where($tjwhere)
	    	->order('add_time desc')->limit($page['start'].','.$page['pagesize'])->select();
	    	
			$menulist['list']=$list;
			$menulist['page']=$page['page'];
			$this->list=$menulist;

	    }
		
		$this->display();
	}
	//会员登录记录  ZM
	public function member_login_log(){
		if($_GET["member_name"]){
			$where["member_name"] = array("like",$_GET["member_name"]."%");
		}
		if($_GET["member_card"]){
			$where["member_card"] = array("like",$_GET["member_card"]."%");
		}
		if($_GET["mobile"]){
			$where["mobile"] = array("like",$_GET["mobile"]."%");
		}
		if($_GET["add_time"]){
			$add_time = strtotime($_GET["add_time"]);
		}
		if($_GET["end_time"]){
			$end_time = strtotime($_GET["end_time"].'23:59:59');
		}
		if($add_time >0 && $end_time>0){
			$where["login_time"] = array("between",array($add_time,$end_time));
		}else{
			if($add_time > 0){
				$where["login_time"] = array("egt",$add_time);
			}
			if($end_time > 0){
				$where["login_time"] = array("egt",$end_time);
			}
		}
		$pre=C('DB_PREFIX');//表前缀
		$count=M()->table($pre."member_login_log log")
		          ->join($pre."member mem on log.member_id=mem.id")
		          ->where($where)->count();
		$page=D("Common")->getPage($count);
		$field = "log.*,mem.member_name,mem.mobile,mem.member_card,mem.add_time";
		$list = M()->table($pre."member_login_log log")
			       ->field($field)
			       ->join($pre."member mem on log.member_id=mem.id")
				   ->where($where)->limit($page["start"].",".$page["pagesize"])->select();
		$list["list"] = $list;
		$list["page"] = $page["page"];
		$this->assign("data",$list);
		$this->display();
	}

	/**
	 * @return config
	 */
	public function level_list()
	{
		$menulist=M('member_level')->select();
		$this->menulist=$menulist;
		$this->display();
	}
	public function level_list_edit(){
		if($_POST){
			if (!empty($_POST['levelId'])){
				$res=M('member_level')->where('levelId='.$_POST['levelId'])->save($_POST);
			}else{
				$res=M('member_level')->add($_POST);
			}
			if ($res){
				$this->success('操作成功',U('Admin/Member/level_list'));
			}else{
				$this->error('操作失败');
			}
		}else{
			if($_GET['levelId']){
				$title="会员等级信息编辑";
				$data=M('member_level')->where('levelId='.$_GET['levelId'])->find();
				$this->assign('data',$data);
			}else{
				$title="会员等级添加";
			}
			$this->assign('title',$title);
			$this->display();
		}
	}

	public function member_authentication()
	{	$members_IDcard=M('member_id_card')->select();
		$this->list=$members_IDcard;
		$this->display();
	}
	
	public function  do_authentication(){
		//是否审核通过
		if ($_GET['value'] != null) {
			$id = $_GET["id"];
			$type=$_GET['type'];
			$value=$_GET["value"];
			if ($type=='is_check'){
				$save_date['is_check'] = $value;
			}else{
				$save_date['reson'] = $value;
			}

			$where['id'] = $id;

			$res = M('member_id_card')->where($where)->save($save_date);
			if ($res !== false) {
				//$this->redirect("Admin/Goods/index");
				$data['status'] = 1;
				$data['is_check'] = $value;
				echo json_encode($data);
			} else {
				$data['error'] = '操作失败,请稍候再试';
				echo json_encode($data);
			}
		}
	}

}
?>