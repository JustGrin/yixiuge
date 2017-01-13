<?php
/****
**提现申请
** 
**/
class CashapplyAction extends AuthAction{

    //会员提现申请
	public function index(){
        if($_GET['mobile']){
			$where['mem.mobile']=array('eq','%'.$_GET['mobile'].'%');
		}
		 if($_GET['member_name']){
			$where['mem.member_name']=array('like','%'.$_GET['member_name'].'%');
		}
		if(isset($_GET['status'])){
			if($_GET['status']!='all'){
				$where['apply.status']=$_GET['status'];
			}
		}else{
			$_GET['status']='all';
		}
		if(isset($_GET['payment_no']) && $_GET['payment_no'] != ''){
			$where['apply.payment_no']=array('like','%'.$_GET['payment_no'].'%');
		}
		//type 1商家提现 2会员提现
		$where['apply.type']=2;
		//$menulist=D("Common")->getPageList('cash_apply',$where);

        $pre=C('DB_PREFIX');//表前缀
        $count=M()->table($pre.'cash_apply apply')//
        ->join($pre.'member mem on apply.member_id=mem.id')//
        ->where($where)->count();
        $page=D("Common")->getPage($count);//分页

        $field="apply.*,mem.member_name,mem.mobile";
        
    	$list =M()->table($pre.'cash_apply apply')
    	->join($pre.'member mem on apply.member_id=mem.id')//
    	->where($where)->field($field)//
    	->order('apply.add_time desc')->limit($page['start'].','.$page['pagesize'])->select();

		$menulist['list']=$list;
		$menulist['page']=$page['page'];
        //status 状态 0 拒绝提现  1提现申请中 2财务打款中 3已提现
		$this->status_arr=array('0'=>'拒绝提现','1'=>'提现申请中','2'=>'财务打款中','3'=>'已完成');

		$this->assign("list",$menulist);
		$this->display();
	}
	//商户提现申请
	public function shop_apply(){
         if($_GET['account']){
			$where['mem.account']=array('eq',$_GET['account']);
		}
		if($_GET['shop_name']){
			$where['shop.shop_name']=array('like',$_GET['shop_name'].'%');
		}
		if(isset($_GET['status'])){
			if($_GET['status']!='all'){
				$where['apply.status']=$_GET['status'];
			}
		}else{
			$_GET['status']='all';
		}
		//type  1商家提现 2会员提现
		$where['apply.type']=1;
		if(empty($_SESSION['topadmin'])){
           $where['apply.shop_id']=$_SESSION['afid'];
		}
		$menulist=D("Common")->getPageList('cash_apply',$where);

        $pre=C('DB_PREFIX');//表前缀
        $count=M()->table($pre.'cash_apply apply')//
        ->join($pre.'admin mem  on mem.id=apply.shop_id')//
        ->join($pre.'shop_detail shop  on apply.shop_id=shop.shop_id')//
        ->where($where)->count();
        $page=D("Common")->getPage($count);//分页

        $field="apply.*,shop.shop_name,mem.account";
        
    	$list =M()->table($pre.'cash_apply apply')//
    	   ->join($pre.'admin mem  on mem.id=apply.shop_id')//
    	  ->join($pre.'shop_detail shop  on apply.shop_id=shop.shop_id')//
    	 ->where($where)->field($field)//
    	->order('apply.add_time desc')->limit($page['start'].','.$page['pagesize'])->select();

		$menulist['list']=$list;
		$menulist['page']=$page['page'];
        //status 状态 0 拒绝提现  1提现申请中 2财务打款中 3已提现
		$this->status_arr=array('0'=>'拒绝提现','1'=>'提现申请中','2'=>'财务打款中','3'=>'已完成');

		$this->assign("list",$menulist);
		$this->display();
	}
   
	//提现申请审核 1
	public function apply_status1(){
		if($_POST){
			if(empty($_SESSION["topadmin"])){
			  $this->error("权限不足");
			}
			if(empty($_POST["id"])){
			  $this->error("系统错误");
			}
			$cash_apply_detail = M("cash_apply")->where("id=".$_POST["id"])->find();
			if(empty($cash_apply_detail)){
			 	$this->error("该申请不存在");
			}
			
			$auid_status1=$_POST['auid_status1'];
			$data['status']=0;
		    $data['auid1']=session('auid');
			$data['auid_status1']=$auid_status1;
			$data['op_time1'] = time();
			$data['remarks1']=$_POST['remarks1'];
			if($cash_apply_detail['type']==1){//1商家提现 2会员提现
               $url=U('Admin/Cashapply/shop_apply');
			}else{
               $url=U('Admin/Cashapply/index');
			}
			$cash_apply=M("cash_apply");
			if($auid_status1==1){//同意
			  //状态 0 拒绝提现  1提现申请中 2财务打款中 3已提现
              $data['status']=2;
              $res = $cash_apply->where("id=".$_POST["id"])->save($data);	
				if($res!==false){
					$this->success("操作成功",$url);
				}else{
					$this->error("操作失败");
				}
			}else{
				//拒绝提现 返还提现金额
			    if($cash_apply_detail['type']==1){//1商家提现 2会员提现
	               $user_model=M('shop_detail');
	               $user_where['shop_id']=$cash_apply_detail['shop_id'];
				}else{
	               $user_model=M('member_detail');
	               $user_where['member_id']=$cash_apply_detail['member_id'];
				}
				$balance=$user_model->where($user_where)->getField('balance');
				$money=$cash_apply_detail['money'];
				$user_data['balance']=$balance+$money;
                
                $cash_apply->startTrans();//开起事务
                $user_model->startTrans();//开起事务 
               
                $user_res=$user_model->where($user_where)->save($user_data);
				$c_res = $cash_apply->where("id=".$_POST["id"])->save($data);	
				if($c_res!==false && $user_res!==false){
					$cash_apply->commit();//提交事务
                    $user_model->commit();//提交事务
					if($cash_apply_detail['type']==1){//1商家提现 2会员提现
			            ///记录消费日志
			            $log_data['type']=4;//消费类型 1订单消费 2充值 3提现   4退款 5 收益 6认证消费
			            $log_data['shop_id']=$cash_apply_detail['shop_id'];
			            $log_data['money']=$money;
			            $log_data['status']=2;//状态 1收入 2支出
						if($cash_apply_detail['blank_id'] == 0){
							$log_data['des']="拒绝微信提现退还金额￥".$money;//消费介绍
						}else{
							$log_data['des']="拒绝银行卡提现退还金额￥".$money;//消费介绍
						}
			            $log_data['add_time']=time();//消费时间
			            $this->set_shop_consume_record($log_data);
					}else{
		                 ///记录消费日志
			            $log_data['type']=4;//消费类型 1订单消费 2充值 3提现   4退款 5 收益 6认证消费
			            $log_data['member_id']=$cash_apply_detail['member_id'];
			            $log_data['money']=$money;
			            $log_data['status']=1;//状态 1收入 2支出
						if($cash_apply_detail['blank_id'] == 0){
							$log_data['des']="拒绝微信提现退还金额￥".$money;//消费介绍
						}else{
							$log_data['des']="拒绝银行卡提现退还金额￥".$money;//消费介绍
						}
			            $log_data['add_time']=time();//消费时间
			            $this->set_member_consume_record($log_data);
					}
					$this->success("操作成功",$url);
				}else{
					$cash_apply->rollback();//提交事务
                    $user_model->rollback();//提交事务
					$this->error("操作失败");
				}
			}
			
		}else{
			if(empty($_SESSION["topadmin"])){
			  $this->error("权限不足");
			}
			if(empty($_GET["id"])){
				$this->error("系统错误");
			}
			$title="操作员审核提现申请";
			$gwhere["id"] =  array("eq",$_GET["id"]);
			$data = M("cash_apply")->where($gwhere)->find();
			 $pre=C('DB_PREFIX');//表前缀
            if($data['type']==1){//1商家提现 2会员提现
            	$swhere['adm.id']=$data['shop_id'];
            	$field='adm.account,adm.name,adm.mobile,shop.shop_name';
                $member=M()->table($pre.'admin adm')//
    	        ->join($pre.'shop_detail shop  on adm.id=shop.shop_id')//
		        ->where($swhere)->field($field)//
		        ->find();
		      
                $this->assign("shop",$member);
            }elseif($data['type']==2){
              $member=M('member')->where(array('id'=>$data['member_id']))->find();
              $this->assign("member",$member);
            }

			$this->assign("data",$data);
			$this->assign("msgtitle",$title);
			$this->display();
		}
	}

	//提现申请审核 2
	public function apply_status2(){
		if($_POST){
			if(empty($_SESSION["topadmin"])){
			  $this->error("权限不足");
			}
			if(empty($_POST["id"])){
			  $this->error("系统错误");
			}
			$cash_apply_detail = M("cash_apply")->where("id=".$_POST["id"])->find();
			if(empty($cash_apply_detail)){
			 	$this->error("该申请不存在");
			}
			if($cash_apply_detail['status']!=2){//状态 0 拒绝提现  1提现申请中 2财务打款中 3已提现
               $this->error("审核状态不正确");
			}
			if($cash_apply_detail['type']==1){//1商家提现 2会员提现
               $url=U('Admin/Cashapply/shop_apply');
			}else{
               $url=U('Admin/Cashapply/index');
			}
			 $auid_status2=$_POST['auid_status2'];
			 $data['status']=0;
			 $data['auid2']=session('auid');
             $data['auid_status2']=$auid_status2;
			 $data['op_time2'] = time();
		     $data['remarks2']=$_POST['remarks2'];
			 $data['imgpath']=$_POST['imgpath'];
			 $data['payment_no'] =$_POST['payment_no'];
			$cash_apply=M("cash_apply");
			if($auid_status2==1){//同意
				$data['status']=3;
			   //状态 0 拒绝提现  1提现申请中 2财务打款中 3已提现
				$res = $cash_apply->where("id=".$_POST["id"])->save($data);	
				if($res!==false){
					$this->success("操作成功",$url);
				}else{
					$this->error("操作失败");
				}
			}else{
				//拒绝提现 返还提现金额
			    if($cash_apply_detail['type']==1){//1商家提现 2会员提现
	               $user_model=M('shop_detail');
	               $user_where['shop_id']=$cash_apply_detail['shop_id'];
				}else{
	               $user_model=M('member_detail');
	               $user_where['member_id']=$cash_apply_detail['member_id'];
				}
				$balance=$user_model->where($user_where)->getField('balance');
				$money=$cash_apply_detail['money'];
				$user_data['balance']=$balance+$money;
                
                $cash_apply->startTrans();//开起事务
                $user_model->startTrans();//开起事务 
               
                $user_res=$user_model->where($user_where)->save($user_data);
				$c_res = $cash_apply->where("id=".$_POST["id"])->save($data);	
				if($c_res!==false && $user_res!==false){
					$cash_apply->commit();//提交事务
                    $user_model->commit();//提交事务
					if($cash_apply_detail['type']==1){//1商家提现 2会员提现
			            ///记录消费日志
			            $log_data['type']=4;//消费类型 1订单消费 2充值 3提现   4退款 5 收益 6认证消费
			            $log_data['shop_id']=$cash_apply_detail['shop_id'];
			            $log_data['money']=$money;
			            $log_data['status']=2;//状态 1收入 2支出
						if($cash_apply_detail['blank_id'] == 0){
							$log_data['des']="拒绝微信提现退还金额￥".$money;//消费介绍
						}else{
							$log_data['des']="拒绝银行卡提现退还金额￥".$money;//消费介绍
						}

			            $log_data['add_time']=time();//消费时间
			            $this->set_shop_consume_record($log_data);
					}else{
		                 ///记录消费日志
			            $log_data['type']=4;//消费类型 1订单消费 2充值 3提现   4退款 5 收益 6认证消费
			            $log_data['member_id']=$cash_apply_detail['member_id'];
			            $log_data['money']=$money;
			            $log_data['status']=1;//状态 1收入 2支出
						if($cash_apply_detail['blank_id'] == 0){
							$log_data['des']="拒绝微信提现退还金额￥".$money;//消费介绍
						}else{
							$log_data['des']="拒绝银行卡提现退还金额￥".$money;//消费介绍
						}
			            $log_data['add_time']=time();//消费时间
			            $this->set_member_consume_record($log_data);
					}
					$this->success("操作成功",$url);
				}else{
					$cash_apply->rollback();//提交事务
                    $user_model->rollback();//提交事务
					$this->error("操作失败");
				}
			   
			}
			
		}else{
			if(empty($_SESSION["topadmin"])){
			  $this->error("权限不足");
			}
			if(empty($_GET["id"])){
				$this->error("系统错误");
			}
			$title="财务审核提现申请";
			$gwhere["id"] =  array("eq",$_GET["id"]);
			$data = M("cash_apply")->where($gwhere)->find();
			 $pre=C('DB_PREFIX');//表前缀
			if($data['type']==1){//1商家提现 2会员提现
            	$swhere['adm.id']=$data['shop_id'];
            	$field='adm.account,adm.name,adm.mobile,shop.shop_name';
                $member=M()->table($pre.'admin adm')//
    	        ->join($pre.'shop_detail shop  on adm.id=shop.shop_id')//
		        ->where($swhere)->field($field)//
		        ->find();
                 $this->assign("shop",$member);
            }elseif($data['type']==2){
              $member=M('member')->where(array('id'=>$data['member_id']))->find();
              $this->assign("member",$member);
            }
			$this->assign("data",$data);
			$this->assign("msgtitle",$title);
			$this->display();
		}
	}

	//查看
	public function apply_detail(){
		    if(empty($_GET["id"])){
				$this->error("系统错误");
			}
		   if(empty($_SESSION["topadmin"])){
			  $gwhere["shop_id"]=$_SESSION["afid"];
			}
			$title="财务审核提现申请";
			$gwhere["id"] =  array("eq",$_GET["id"]);
			$data = M("cash_apply")->where($gwhere)->find();
			 $pre=C('DB_PREFIX');//表前缀
			if($data['type']==1){//1商家提现 2会员提现
            	$swhere['adm.id']=$data['shop_id'];
            	$field='adm.account,adm.name,adm.mobile,shop.shop_name';
                $member=M()->table($pre.'admin adm')//
    	        ->join($pre.'shop_detail shop  on adm.id=shop.shop_id')//
		        ->where($swhere)->field($field)//
		        ->find();
                 $this->assign("shop",$member);
            }elseif($data['type']==2){
              $member=M('member')->where(array('id'=>$data['member_id']))->find();
              $this->assign("member",$member);
            }
			$this->assign("data",$data);
			$this->assign("msgtitle",$title);
			$this->display();
	}
   
	//提现申请
	public function apply_edit(){
		if($_POST){
			if(empty($_POST['bank_account_id'])){
                $this->error("请选择提现帐号");
			}
			$bawhere=array('id'=>$_POST['bank_account_id']);
			$bawhere['shop_id']=session('afid');
			$bawhere['is_del']=0;
			$blank=M('bank_account')->where($bawhere)->find();
			if(empty($blank)){
				$this->error("提现帐号不存在请重新选择");
			}
			$shop_where['shop_id']=session('afid');
			$shop=M('shop_detail')->where($shop_where)->find();
			if($shop['balance']<200){
				$this->error("帐户余额小于200不能提现");
			}
			if($shop['balance']<$_POST['money']){
				$this->error("提现金额不能大于帐户余额");
			}
			$_POST['blank_id']=$blank['blank_id'];
			$_POST['account_bank']=$blank['account_bank'];
			$_POST['account_no']=$blank['account_no'];
			$_POST['account_name']=$blank['account_name'];
			$_POST['account_addr']=$blank['account_addr'];
			$_POST['type']=1;//1商家提现 2会员提现
			/*if(!empty($_POST["id"])){
				$_POST['add_time']=time();
				$res = M("cash_apply")->where("id=".$_POST["id"])->save($_POST);
			}else{
				$_POST['add_time']=time();
				$_POST['shop_id']=session('afid');
				$res = M("cash_apply")->add($_POST);
			}*/
			$_POST['add_time']=time();
			$_POST['shop_id']=session('afid');

			$cash_apply=M("cash_apply");
	        $shop_detail=M('shop_detail');
	        $cash_apply->startTrans();//开起事务
	        $shop_detail->startTrans();//开起事务
	        ///扣商户的余额 
	        $money=$_POST['money'];
	        $shop_data['balance']=$shop['balance']-$money;
            $s_shop_where['shop_id']=session('afid');
	        $m_res = $shop_detail->where($s_shop_where)->save($shop_data);
			$c_res = $cash_apply->add($_POST);
			if($c_res!==false && $m_res!==false){
				$cash_apply->commit();//提交事务
	            $shop_detail->commit();//提交事务
	            ///记录消费日志
	            $log_data['type']=3;//消费类型 1订单消费 2充值 3提现   
	            $log_data['shop_id']=session('afid');
	            $log_data['money']=$money;
	            $log_data['status']=2;//状态 1收入 2支出
	            $log_data['des']="申请提现扣除金额￥".$money;//消费介绍
	            $log_data['add_time']=time();//消费时间
	            $this->set_shop_consume_record($log_data);

				$this->success("操作成功");
			}else{
				 $cash_apply->rollback();//回滚事务
                 $shop_detail->rollback();//回滚事务
				 $this->error("操作失败");
			}
		}else{
			if($_GET["id"]){
				$gwhere["id"] =  array("eq",$_GET["id"]);
				$data = M("cash_apply")->where($gwhere)->find();
			    if($data['bank_account_id']){
                  $bwhere='(shop_id='.$_SESSION['afid'].' and is_del =0) or id='.$data['bank_account_id'];
				}else{
					$bwhere['shop_id']=session('afid');
			        $bwhere['is_del']=0;
				}
			}else{
				$title="提现账户";
				$bwhere['shop_id']=session('afid');
			    $bwhere['is_del']=0;
			}

			$shop_where['shop_id']=session('afid');
			$this->shop=M('shop_detail')->where($shop_where)->find();

			$list=M("bank_account")->where($bwhere)->order('is_default desc,add_time desc')->select();
			
			$this->assign("data",$data);
			$this->assign("list",$list);
			$this->assign("msgtitle",$title);
			$this->display();
		}
	}


	 //提现银行设置
	public function blank_list(){
       
        $menulist=D("Common")->getAllList('blank',array('is_del'=>0));
        $this->list=$menulist;
		$this->display();
		//$this->display();
	}
   
	//提现银行新增 编辑
	public function blank_edit(){
		if($_POST){
			if(!empty($_POST["id"])){
				$res = M("blank")->where("blank_id=".$_POST["id"])->save($_POST);
			}else{
				$res = M("blank")->add($_POST);
			}
			if($res!==false){
				$this->success("操作成功",U('Admin/Cashapply/blank_list'));
			}else{
				$this->error("操作失败");
			}
		}else{
			if($_GET["id"]){
				$gwhere["blank_id"] =  array("eq",$_GET["id"]);
				$data = M("blank")->where($gwhere)->find();
				$title="编辑银行";
				$wherelist['blank_id']= array("neq",$_GET["id"]);
			}else{
				$title="新增银行";
			}
			$this->assign("data",$data);
			$this->assign("msgtitle",$title);
			$this->display();
		}
	}

	//提现银行删除
	public function blank_del(){
		$id = $_GET["id"];
		if(empty($id)){
			$this->error("操作失败");
		}
		$where['blank_id']=$id;
		$res = M("blank")->where($where)->save(array('is_del'=>1));
		if($res!==false){
			$this->success("操作成功");
		}else{
			$this->error("操作失败");
		}
	}

	
}
?>