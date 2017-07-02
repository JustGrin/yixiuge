<?php
/****
** 商家action
** 
**/
class ShopAction extends AuthAction{

    //商户列表
	public function index(){
      
		if($_GET['shop_name']){
			$where['shop_name']=array('like',$_GET['shop_name'].'%');
		}
		if($_GET['link_no']){//联系电话
			$where['link_no']=array('like',$_GET['link_no'].'%');
		}

		if(isset($_GET['recommend_status'])){//平台推荐 1 推荐 0未推荐
			if($_GET['recommend_status']!='all'){
				$where['recommend_status']=$_GET['recommend_status'];
			}
		}else{
			$_GET['recommend_status']='all';
		}
		if(isset($_GET['status'])){
			if($_GET['status']!='all'){
				$where['status']=$_GET['status'];
			}
		}else{
			$_GET['status']='all';
		}
		if($_GET['start_time']){
			$start_time=strtotime($_GET['start_time']);
		}
		if($_GET['end_time']){
			$end_time=strtotime($_GET['end_time']);
		}
		if($end_time>0 && $start_time>0){
			$where['add_time']=array('between',array($start_time,$end_time));
		}else{
			if($start_time>0){
				$where['add_time']=array('egt',$start_time);
			}
			if($end_time>0){
				$where['add_time']=array('elt',$end_time);
			}
		}
		if(isset($_GET['order'])){
			$order=$_GET['order']." desc";
		}
		$menulist=D("Common")->getPageList('shop_detail',$where);
		$list=$menulist['list'];
		if($list){
			$model=M('admin');
			foreach ($list as $key => $value) {
				$list[$key]['account']=$model->where(array('id'=>$value['shop_id']))->getField('account');
			}
		}
		$menulist['list']=$list;
		$this->assign("list",$menulist);
		$this->display();
		//$this->display();
	}
	//编辑商户信息
	public function shop_detail(){
		    if($_GET["id"]){
				$where["id"] =  array("eq",$_GET["id"]);
			}else{
				$topadmin=session("topadmin");
				if(empty($topadmin)){//商家
	               $shop_id=session("afid");
				}else{
					$shop_id=$_GET['shop_id'];
    			}
				$where['shop_id']=$shop_id;
			}
			$data = M("shop_detail")->where($where)->find();
            if(empty($data)){
            	$data['shop_id']=$shop_id;
            }
			$citydata=array('province'=>1,'provinceid'=>$data['provinceid'],'cityid'=>$data['cityid']);
            $citylist=$this->get_city_return($citydata);
            $this->citylist=$citylist;

			$title="商户信息";
			
			$menulist=D("Common")->getAllList('group_class',array('status'=>1,'is_del'=>0));
            $menulist=$this->gettree($menulist);
		
			$this->assign("list",$menulist);

			$this->assign("data",$data);
			
			$this->assign("msgtitle",$title);
			$this->display();
	}
	//编辑商户信息
	public function shop_edit(){
		if($_POST){
			if(empty($_POST['id']) || $_SESSION['topadmin']>0){
                if(empty($_POST['shop_name'])){
				$this->error("请填写商户名称");
			     }
			}
			
			if(empty($_POST['member_desc'])){
				$this->error("请填写商户介绍");
			}
			
			if($_POST['class_id']){
				$pid=M('group_class')->where(array('id'=>$_POST['class_id']))->getField('pid');
				if(empty($pid)){
					$_POST['top_class_id']=$_POST['class_id'];
				}else{
					$_POST['top_class_id']=$pid;
				}
			}
			if(!empty($_POST['shop_image_url'])){
				$_POST['shop_image_url']=implode(',', $_POST['shop_image_url']);
			}
			
			if($_POST['id']){
				if($_POST['add_time']){
					$_POST['add_time']=strtotime($_POST['add_time']);
				}
                $res = M("shop_detail")->where("id=".$_POST["id"])->save($_POST);
			}else{
				$topadmin=session("topadmin");
				if(empty($topadmin)){//商家
	               $_POST['shop_id']=session("afid");
				}else{
                    //添加帐号
                    if($_POST['add_adm']){
                      $m_data['account']=$_POST['account'];
                      $m_data['password']=md5($_POST["password"]);
                      $m_data['name']=$_POST["shop_name"];
                      $m_data['status']=$_POST["status"];
                      $m_data['type']=0;//0商家 1 管理员 
                      $m_data['create_time']=time();
	                   $res_adm=M("admin")->add($m_data);
	                   if($res_adm===false){
                         $this->error("商家帐号添加失败");
	                   }
	                    $_POST['shop_id']=$res_adm;
	                   //修改fid
                       M("admin")->where(array('id'=>$res_adm))->save(array('fid'=>$res_adm));
                        //添加分组 默认商家
                       $add_access['auid']=$res_adm;
			    	   $add_access['group_id']=2;
			    	   M('admin_group_access')->add($add_access);
                    }
				}
				if($_POST['add_time']){
					$_POST['add_time']=strtotime($_POST['add_time']);
				}
                $res = M("shop_detail")->add($_POST);
			}
			if($res!==false){
				$topadmin=session("topadmin");
				if(!empty($topadmin)){//商家
	               $url=U("Admin/Shop/index");
				}
				$this->success("操作成功",$url);
			}else{
				$this->error("操作失败");
			}
		}else{
			
			
			if($_GET["id"]){
				$where["id"] =  array("eq",$_GET["id"]);
			}else{
				$topadmin=session("topadmin");
				if(empty($topadmin)){//商家
	               $shop_id=session("afid");
				}else{
					$shop_id=$_GET['shop_id'];
    			}
				$where['shop_id']=$shop_id;
			}
			$data = M("shop_detail")->where($where)->find();
            if(empty($data)){
            	$data['shop_id']=$shop_id;
            }
			$citydata=array('province'=>1,'provinceid'=>$data['provinceid'],'cityid'=>$data['cityid']);
            $citylist=$this->get_city_return($citydata);
            $this->citylist=$citylist;
            if($data['shop_image_url']){
				$data['shop_image_url']=explode(',', $data['shop_image_url']);
			}
			$title="商户信息";
			
			$menulist=D("Common")->getAllList('group_class',array('status'=>1,'is_del'=>0));
            $menulist=$this->gettree($menulist);
		
			$this->assign("list",$menulist);

			$this->assign("data",$data);
			
			$this->assign("msgtitle",$title);
			$this->display();
		}
	}

	//商户冻结
	public function shop_freeze(){
		$id = $_GET["id"];
		$res = M("shop_detail")->where("id=".$_GET["id"])->find();
		if(empty($res)){
			$this->error("该商家不存在");
		}
		if($res['status']){
			$data['status']=0;
		}else{
			$data['status']=1;
		}
		$res = M("shop_detail")->where("id=".$id)->save($data);
		if($res!==false){
				$this->success("操作成功");
		}else{
				$this->error("操作失败");
		}
	}

	//商户推荐
	public function shop_tuijian(){
		$id = $_GET["id"];
		$res = M("shop_detail")->where("id=".$_GET["id"])->find();
		if(empty($res)){
			$this->error("该商家不存在");
		}
		if($res['recommend_status']){
			$data['recommend_status']=0;
		}else{
			$data['recommend_status']=1;
		}
		$res = M("shop_detail")->where("id=".$id)->save($data);
		if($res!==false){
				$this->success("操作成功");
		}else{
				$this->error("操作失败");
		}
	}

	//商户首页推荐
	public function shop_show_index(){
		$id = $_GET["id"];
		$res = M("shop_detail")->where("id=".$_GET["id"])->find();
		if(empty($res)){
			$this->error("该商家不存在");
		}
		if($res['is_show_index']){
			$data['is_show_index']=0;
		}else{
			$data['is_show_index']=1;
		}
		$res = M("shop_detail")->where("id=".$id)->save($data);
		if($res!==false){
				$this->success("操作成功");
		}else{
				$this->error("操作失败");
		}
	}
	
	//商户返点记录
	public function rebate_record(){
		$where['reb.type']=10;//状态 1用户收益 2 商家返利
		$where['reb.status']=2;//状态 1用户收益 2 商家返利

        if($_GET['account']){
			$where['mem.account']=array('eq',$_GET['account']);
		}
		if($_GET['shop_name']){
			$where['shop.shop_name']=array('like',$_GET['shop_name'].'%');
		}
	
		//1消费者本身返点 2直接推荐人返点 3间接推荐人返点  
       //4 直接推荐人认证返点 5 间接推荐人认证返点 
        //10 商家返点
		$type_array=array(
			/*'1'=>'消费者本身返点',
			'2'=>'直接推荐人返点',
			'3'=>'间接推荐人返点',
			'4'=>'直接推荐人认证返点',
			'5'=>'间接推荐人认证返点',//,*/
			'10'=>'商家返点'
			);
		$this->assign("type_array",$type_array);

        $pre=C('DB_PREFIX');//表前缀
        $count=M()->table($pre.'rebate_record reb')//
        ->join($pre.'admin mem on reb.shop_id=mem.id')//
        ->join($pre.'shop_detail shop  on reb.shop_id=shop.shop_id')//
        ->where($where)->count();
        $page=D("Common")->getPage($count);//分页

        $field="reb.*,mem.account,shop.shop_name";
        
    	$list =M()->table($pre.'rebate_record reb')
    	 ->join($pre.'admin mem on reb.shop_id=mem.id')//
    	  ->join($pre.'shop_detail shop  on reb.shop_id=shop.shop_id')//
    	 ->where($where)->field($field)//
    	->order('reb.add_time desc')->limit($page['start'].','.$page['pagesize'])->select();
	
		$menulist['list']=$list;
		$menulist['page']=$page['page'];
		$this->assign("list",$menulist);
		$this->display();
	}

	//商户消费记录
	public function shop_consume_record(){
        if($_GET['account']){
			$where['mem.account']=array('eq',$_GET['account']);
		}
		if($_GET['shop_name']){
			$where['shop.shop_name']=array('like',$_GET['shop_name'].'%');
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
			'2'=>'充值',
			'3'=>'提现',
			'4'=>'退款'
			);
		$this->assign("type_array",$type_array);

        $pre=C('DB_PREFIX');//表前缀
        $count=M()->table($pre.'shop_consume_record reb')//
         ->join($pre.'admin mem on reb.shop_id=mem.id')//
    	  ->join($pre.'shop_detail shop  on reb.shop_id=shop.shop_id')//
        ->where($where)->count();
        $page=D("Common")->getPage($count);//分页

        $field="reb.*,shop.shop_name,mem.account";
        
    	$list =M()->table($pre.'shop_consume_record reb')
    	  ->join($pre.'admin mem on reb.shop_id=mem.id')//
    	  ->join($pre.'shop_detail shop  on reb.shop_id=shop.shop_id')//
    	 ->where($where)->field($field)//
    	->order('reb.add_time desc')->limit($page['start'].','.$page['pagesize'])->select();
    
		$menulist['list']=$list;
		$menulist['page']=$page['page'];
		$this->assign("list",$menulist);

		$this->display();
	}
  
	//商户充值记录
	public function member_recharge(){
		$where['reb.type']=2;//类型 1会员充值 2商户充值
        if($_GET['account']){
			$where['mem.account']=array('eq',$_GET['account']);
		}
		if($_GET['shop_name']){
			$where['shop.shop_name']=array('like',$_GET['shop_name'].'%');
		}
		if($_GET['status']){
			$where['reb.status']=$_GET['status'];
		}
		
		//0已退款 1 待支付 2已支付 3支付失败
		$type_array=array(
			'1'=>'待支付',
			'2'=>'已支付',
			'3'=>'支付失败'
			);
		$this->assign("type_array",$type_array);

        $pre=C('DB_PREFIX');//表前缀
        $count=M()->table($pre.'member_recharge reb')//
        ->join($pre.'admin mem on reb.shop_id=mem.id')//
    	  ->join($pre.'shop_detail shop  on reb.shop_id=shop.shop_id')//
        ->where($where)->count();
        $page=D("Common")->getPage($count);//分页

        $field="reb.*,shop.shop_name,mem.account";
        
    	$list =M()->table($pre.'member_recharge reb')
    	 ->join($pre.'admin mem on reb.shop_id=mem.id')//
    	  ->join($pre.'shop_detail shop  on reb.shop_id=shop.shop_id')//
    	 ->where($where)->field($field)//
    	->order('reb.add_time desc')->limit($page['start'].','.$page['pagesize'])->select();
	
		$menulist['list']=$list;
		$menulist['page']=$page['page'];

		$this->assign("list",$menulist);
		$this->display();
	}
    ///商户提现帐号管理
    public  function shop_bank_account(){
    	$where['shop_id']=session('afid');
    	$where['is_del']=0;
        $pre=C('DB_PREFIX');//表前缀
        $count=M("bank_account")->where($where)->count();
        $page=D("Common")->getPage($count);//分页

    	$list =M("bank_account")->where($where)->order('is_default desc,add_time desc')//
    	->limit($page['start'].','.$page['pagesize'])->select();
	
		$menulist['list']=$list;
		$menulist['page']=$page['page'];

		$this->assign("list",$menulist);
		$this->display();
    }
    ///商户提现帐号管理
    public  function shop_bank_account_edit(){
    	if($_POST){
			if(empty($_POST['blank_id'])){
                $this->error("请选择开户行");
			}
			$blank=M('blank')->where(array('blank_id'=>$_POST['blank_id']))->find();
			$_POST['account_bank']=$blank['blank_name'];
			if(!empty($_POST["id"])){
				if(empty($_SESSION['topadmin'])){
                 ///////
				}
				$where['shop_id']=session('afid');
				$where['id']=$_POST["id"];
				$res = M("bank_account")->where($where)->save($_POST);
			}else{
				$_POST['add_time']=time();
				$_POST['shop_id']=$_SESSION['afid'];
				$res = M("bank_account")->add($_POST);
			}
			if($res!==false){
				if($_POST['is_default']){
					if($_POST['id']){
                        $notwhere['id']=array('neq',$_POST['id']);
					}else{
						$notwhere['id']=array('neq',$res);
					}
        		    $notwhere['shop_id']=session('afid');
        		    $notwhere['is_del']=0;
	        	    //取消之前的 默认提现帐号
	        	    M("bank_account")->where($notwhere)->save(array('is_default'=>0));
				}
				$this->success("操作成功",U('Admin/Shop/shop_bank_account'));
			}else{
				$this->error("操作失败");
			}
		}else{
			if($_GET["id"]){
				$where['id']=$_GET["id"];
		    	$where['shop_id']=session('afid');
		    	$where['is_del']=0;
				$data = M("bank_account")->where($where)->find();
				$title="提现账户";
			}else{
				$title="提现账户";
			}
			
			$list=M("blank")->where(array('status'=>1,'is_del'=>0))->select();
			$this->assign("data",$data);
			$this->assign("list",$list);
			$this->assign("msgtitle",$title);
			$this->display();
		}
    }
    ///商户提现帐号 设为默认帐号
    public  function shop_bank_account_default(){
        $id=$_GET['id'];
    	if(empty($id)){
    		$this->error("系统错误");
    	}
    	$where['id']=$id;
    	$where['shop_id']=session('afid');
    	$where['is_del']=0;

        $res=M("bank_account")->where($where)->save(array('is_default'=>1));
        if($res!==false){
        	$notwhere['id']=array('neq',$id);
        	$notwhere['shop_id']=session('afid');
        	$notwhere['is_del']=0;
        	//取消之前的 默认提现帐号
        	 M("bank_account")->where($notwhere)->save(array('is_default'=>0));

             $this->success("操作成功");
        }else{
        	 $this->error("操作失败");
        }
       
    }
    ///商户提现帐号 删除
    public  function shop_bank_account_del(){
    	$id=$_GET['id'];
    	if(empty($id)){
    		$this->error("系统错误");
    	}
    	$where['id']=$id;
    	$where['shop_id']=session('afid');
        $res=M("bank_account")->where($where)->save(array('is_del'=>1));
        if($res!==false){	 
             $this->success("操作成功");
        }else{
        	 $this->error("操作失败");
        }
    }
	
}
?>