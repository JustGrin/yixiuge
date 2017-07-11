<?php
/****
**菜单 用户组
** 
**/
class SystemAction extends AuthAction{

    public $icon=array(
    	'ti-settings',
    	'ti-layout-grid2',
    	'ti-pencil-alt',
    	'ti-user',
    	'ti-layers-alt',
    	'ti-package',
    	'ti-folder',
    	'ti-menu-alt',
    	'ti-location-pin',
    	'ti-pie-chart',
    	'fa fa-suitcase',
    	'fa fa-truck',
    	'fa fa-volume-up',
    	'fa fa-tachometer',
    	'fa fa-tasks',
    	'fa fa-shopping-cart',
    	'fa fa-share-alt-square',
    	'fa fa-gears',
    	'fa fa-inbox',
    	'fa fa-street-view',
    	'fa fa-sliders',
    	'fa fa-square',
    	'fa fa-th',
    	'fa fa-empire',
    	'fa fa-chain',
    	'fa fa-pie-chart',
    	'fa fa-line-chart',
    	'fa fa-circle-o-notch',
    	'fa fa-dot-circle-o',
    	'fa fa-laptop',
    	'fa fa-eye',
    	'fa fa-bookmark'
    	);

    //菜单列表
	public function index(){
        if(session('auid')==1){
            $menulist=D("Common")->getAllList('admin_rule',array('is_del'=>0));
            $menulist=$this->gettree($menulist);
        }else{
        	$menulist=$this->menu;
        }
        $this->menulist=$menulist;
		$this->display();
		//$this->display();
	}
    //新增菜单
	public function rule_add(){
		if($_POST){

			if($_POST['name']){
				$_POST['name']=strtolower($_POST['name']);////大写转成小写
			}
			$_POST['create_time']=time();
			if($_POST['id']){
				$res = M("admin_rule")->where("id=".$_POST["id"])->save($_POST);
			}else{
				$res = M("admin_rule")->add($_POST);
			}

			if($res!==false){
				$this->success("操作成功",U("Admin/System/index"));
			}else{
				$this->error("操作失败");
			}
		}else{
			$id = isset($_GET['id']) ? $_GET['id'] : 0 ;
			if($id){
				$menuwhere["id"] =  array("eq",$id);
				$data = M("admin_rule")->where($menuwhere)->find();
				$title="编辑菜单";
				$this->assign("data",$data);
			}else{
				$title="添加菜单";
			}
			$this->id =$id;
			$this->iconlist=$this->icon;
			$mwhere["pid"] = array("eq",0);
			$mwhere["status"] = array("eq",1);
			$list = M("admin_rule")->where($mwhere)->select();
			$this->assign("list",$list);
			$this->assign("msgtitle",$title);
			$this->display('rule_add');
		}
	}
	//编辑菜单
	public function rule_edit(){
		if($_POST){
			if($_POST['name']){
				$_POST['name']=strtolower($_POST['name']);////大写转成小写
			}
			$res = M("admin_rule")->where("id=".$_POST["id"])->save($_POST);
			if($res!==false){
				$this->success("操作成功",U("Admin/System/index"));
			}else{
				$this->error("操作失败");
			}
		}else{
			if($_GET["id"]){
				$menuwhere["id"] =  array("eq",$_GET["id"]);
				$data = M("admin_rule")->where($menuwhere)->find();
				$title="编辑菜单";
				$this->assign("data",$data);
			}
			$this->iconlist=$this->icon;
			$mwhere["pid"] = array("eq",0);
			$mwhere["status"] = array("eq",1);
			$list = M("admin_rule")->where($mwhere)->select();
			$this->assign("list",$list);
			$this->assign("msgtitle",$title);
			$this->display('rule_add');
		}
	}

	//删除菜单
	public function rule_del(){
		$id = $_GET["id"];
		$res = M("admin_rule")->where("id=".$id)->save(array('is_del'=>1));
		if($res!==false){
				$this->success("操作成功");
			}else{
				$this->error("操作失败");
			}
	}
	
	//分组列表
	public function group_list(){
		$auid=$_SESSION['auid'];
		$where =array();
		if($auid!=1){
           $where['auid'] =$auid;
		}
		$list=D("Common")->getAllList('admin_group',$where,'*','auid asc');
		$this->assign("list",$list);
		$this->display();
	}

	//编辑分组
	public function group_edit(){
		if($_POST){
			$auid=session('afid');
			$_POST["rules"] = implode(",",$_POST["menuarr"]);
		   if(session('topadmin')==0){//商家员
             $_POST['auid']=$auid;
		   }
			if(!empty($_POST["id"])){
				$res = M("admin_group")->where("id=".$_POST["id"])->save($_POST);
			}else{
				$res = M("admin_group")->add($_POST);
			}
			if($res!==false){
				$this->success("操作成功",U('Admin/System/group_list'));
			}else{
				$this->error("操作失败");
			}
		}else{
			$data = array();
			$data['id'] = 0;
			if(isset($_GET["id"])){
				$gwhere["id"] =  array("eq",$_GET["id"]);
				$data = M("admin_group")->where($gwhere)->find();
				$menuarr = explode(",",$data["rules"]);
				$title="编辑分组";
				if($data['auid']){
					$adm=M('admin')->where(array('id'=>$data['auid']))->find();
					$data['adm_name']=$adm['name'];
					$data['adm_account']=$adm['account'];
				}
			}else{
				$title="新增分组";
				$menuarr = array();
				if(session('topadmin')==0){//商家管理员帐号
					$adm=M('admin')->where(array('id'=>session('afid')))->find();
					$data['adm_name']=$adm['name'];
					$data['adm_account']=$adm['account'];
				}
			}
            $menulist=$this->getallmenu();
			
			$this->assign("data",$data);
			$this->assign("menulist",$menulist);
			$this->assign("msgtitle",$title);
			$this->assign("menuarr",$menuarr);
			$this->display();
		}
	}


	//删除分组
	public function group_del(){
		$id = $_GET["id"];

		$where['id']=$id;
		if(session('topadmin')==0){
			$where['auid']=session('afid');
		}
		$res = M("admin_group")->where($where)->find();
		if(empty($res)){
			$this->error("该分组不存在");
		}
		if($res['auid']==0){
			$this->error("系统分组不能删除");
		}
		$res = M("admin_group")->where($where)->delete();
		if($res){
			$this->success("分组删除成功");
		}else{
			$this->error("删除失败");
		}
	}
	
	//后台管理员列表
	public function manager_list(){
		$where =array();
        if(session('afid')>1){
          $where['fid']=session('afid');
        }
        if(isset($_GET['account'])){
			$where['account']=array('like',$_GET['account'].'%');
		}else{
			$_GET['account'] ='';
		}
		if(isset($_GET['name'])){
			$where['name']=array('like',$_GET['name'].'%');
		}else{
			$_GET['name'] ='';
		}
		if(isset($_GET['mobile'])){
			$where['mobile']=array('like',$_GET['mobile'].'%');
		}else{
			$_GET['mobile'] = '';
		}
		if(isset($_GET['type'])){
			if($_GET['type']!='all'){
				$where['type']=$_GET['type'];
			}
		}else{
			$_GET['type']='all';
		}
		if(isset($_GET['status'])){
			if($_GET['status']!='all'){
				$where['status']=$_GET['status'];
			}
		}else{
			$_GET['status']='all';
		}
		$menulist=D("Common")->getPageList('admin',$where);
		$adm_list=$menulist['list'];
		$shop_detail=M('shop_detail');
		foreach ($adm_list as $key => $value) {
			//管理员 商家 
			if($value['type']==1 || $value['fid']!=$value['id'] ){
              $adm_list[$key]['shop_count']=1;
			}else{
              $adm_list[$key]['shop_count']=$shop_detail->where(array('shop_id'=>$value['fid']))->count();
			}
		}
		$menulist['list']=$adm_list;
		$this->assign("list",$menulist);
		$this->display();
	}

	//管理员信息编辑/添加
	public function manager_edit(){
		if($_POST){
			if(!empty($_POST["newapass"])){
					$_POST["password"] = md5($_POST["newapass"]);
				}
			if($_POST["id"]){//操作为修改信息
				if($_POST['id']){
					$res = M("admin")->where("id=".$_POST["id"])->save($_POST);
				}
				if($res!==false){
					if($_POST['group']){
				    	$count=M('admin_group_access')->where("auid=".$_POST['id'])->count();
				    	if($count){
				    		$add_access['group_id']=$_POST['group'];
				    		M('admin_group_access')->where("auid=".$_POST['id'])->save($add_access);
				    	}else{
				    		$add_access['auid']=$_POST['id'];
				    		$add_access['group_id']=$_POST['group'];
				    		M('admin_group_access')->add($add_access);
				    	}
				    }
					$this->success("操作成功",U('Admin/System/manager_list'));
				}else{
					$this->error("操作失败");
				}
			}else{
				$res = M("admin")->where("account='".$_POST["account"]."'")->find();
				if($res){
					$this->error("帐号已经存在");
				}else{
				   if(session('afid')>1){/// 商家新增商家管理员
				   	 $_POST['fid']=session('afid');
				   }else{
				   	 $_POST['fid']=session('afid');
				   }
				   $_POST['create_time']=time();
					$resault = M("admin")->add($_POST);
					if($resault){
							//type 0 商家  type 1 管理员 
							if(session('afid')<=1 && $_POST['type']==0){/// 系统管理员 新增时设置fid
						       $data['fid']=$resault;	
							   $res = M("admin")->where("id=".$resault)->save($data);
						    }
						    if($_POST['group']){
						    	$count=M('admin_group_access')->where("auid=".$resault)->count();
						    	if($count){
						    		$add_access['group_id']=$_POST['group'];
						    		M('admin_group_access')->where("auid=".$resault)->save($add_access);
						    	}else{
						    		$add_access['auid']=$resault;
						    		$add_access['group_id']=$_POST['group'];
						    		M('admin_group_access')->add($add_access);
						    	}
						    }
						$this->success("添加成功",U('Admin/System/manager_list'));
					}else{
						$this->error("操作失败");
					}
				}
			}
		}else{
			if($_GET["id"]){
				$adminmsg = M("admin")->where("id=".$_GET["id"])->find();
				$this->group_id=M('admin_group_access')->where("auid=".$_GET["id"])->getField('group_id');
				$titles="编辑后台帐号信息";
			}else{
				$titles="添加后台帐号信息";
			}
			$where=array('status'=>1);
            if(session('afid')>1){ //权限 判断
               $where['auid'] = session('afid');
            }else{
               $where['auid'] =0;
            }

			$grouplist = M("admin_group")->where($where)->select();
			
			$this->assign("data",$adminmsg);
			$this->assign("msgtitle",$titles);
			$this->assign("grouplist",$grouplist);
			$this->display();
		}
	}
    //验证帐号是否存在
	public function cheack_admin(){
        $account=$_GET['account'];
        if(empty($account)){
        	echo 1;die;
        }
        $count= M("admin")->where(array('account'=>$account))->count();
        echo $count;die;

	}
	//冻结管理员信息
	public function manage_del(){
		$id=$_GET["id"];
		$res = M("admin")->where("id=".$_GET["id"])->find();
		if(empty($res)){
          $this->error("该用户信息不存在");
		}
		if($res['id']==1){
          $this->error("权限不足");
		}
		if($res['id']==session('auid')){
          $this->error("不能冻结自己的帐号");
		}
		if($res['id']==session('afid')){
          $this->error("权限不足");
		}
		if($res['status']){
			$data['status']=0;
		}else{
			$data['status']=1;
		}
		$resault = M("admin")->where("id=".$_GET["id"])->save($data);
		if($resault){
			$this->success("操作成功");
		}else{
			$this->error("操作失败");
		}
	}
    //自设数据字典添加 
   public  function sys_param_add(){
   	   if(IS_AJAX){
	   	   	 $account=$_GET['param_code'];
	        if(empty($account)){
	        	echo 1;die;
	        }
	        $count= M("sys_param")->where(array('param_code'=>$account))->count();
	        echo $count;die;
   	   }
       if($_POST){
	       	if(empty($_POST["id"])){//新增时判断
			    if(empty($_POST['param_code'])){
		          $this->error("请输入字典参数code");
				}
				if(empty($_POST['param_name'])){
		          $this->error("请输入字典参数名称");
				}
			}
			if($_POST['type']==2){
	          $_POST['param_value']=$_POST['type_2'];
			}else{
			  $_POST['param_value']=$_POST['type_1'];
			}
			unset($_POST['type_1']);
			unset($_POST['type_2']);
			if(!isset($_POST['param_value'])){
	          $this->error("请输入字典参数值");
			}
			
			if(!empty($_POST["id"])){
				$where['id']=array('neq',$_POST['id']);
				$where['param_code']=$_POST['param_code'];
				$res = M("sys_param")->where($where)->count();
                if($res){
                	 $this->error("字典参数code不能重复");
                }
				$res = M("sys_param")->where("id=".$_POST["id"])->save($_POST);
			}else{
				$where['param_code']=$_POST['param_code'];
				$res = M("sys_param")->where($where)->count();
                if($res){
                	 $this->error("该字典参数code已设置");
                }
				$res = M("sys_param")->add($_POST);
			}
			if($res!==false){
				$this->success("操作成功");
			}else{
				$this->error("操作失败");
			}
		}else{
		   $id = isset($_GET['id']) ? $_GET['id']: 0;
			if($id){
				$gwhere["id"] =  array("eq",$_GET["id"]);
				$data = M("sys_param")->where($gwhere)->find();
				$this->assign("data",$data);
				$title="编辑数据字典";
			}else{
				$title="新增数据字典";
			}
		   $this->id = $id;
			$this->assign("msgtitle",$title);
			$this->display();
		}
   }
    //自设数据字典 
   public  function sys_param(){
       $where = array();
	   isset($_GET['param_code']) ? $where['param_code']=array('like',$_GET['param_code'].'%') : $_GET['param_code'] = '';
	   isset($_GET['param_name']) ? $where['param_name']=array('like',$_GET['param_name'].'%') : $_GET['param_name'] = '' ;
		$where['is_key']=0;
		$where['is_delete']=0;
		$menulist=D("Common")->getPageList('sys_param',$where);
		$this->assign("list",$menulist);
		$this->display();
   }

   //自设数据字典 
   public  function sys_param_edit(){
   	   if(IS_AJAX){
	   	   	 $account=$_GET['param_code'];
	        if(empty($account)){
	        	echo 1;die;
	        }
	        $count= M("sys_param")->where(array('param_code'=>$account))->count();
	        echo $count;die;
   	   }
       if($_POST){
	       	if(empty($_POST["id"])){//新增时判断
			    if(empty($_POST['param_code'])){
		          $this->error("请输入字典参数code");
				}
				if(empty($_POST['param_name'])){
		          $this->error("请输入字典参数名称");
				}
			}
			if($_POST['type']==2){
	          $_POST['param_value']=$_POST['type_2'];
			}else{
			  $_POST['param_value']=$_POST['type_1'];
			}
			unset($_POST['type_1']);
			unset($_POST['type_2']);
			if(!isset($_POST['param_value'])){
	          $this->error("请输入字典参数值");
			}
			
			if(!empty($_POST["id"])){
				$where['id']=array('neq',$_POST['id']);
				$where['param_code']=$_POST['param_code'];
				$res = M("sys_param")->where($where)->count();
                if($res){
                	 $this->error("字典参数code不能重复");
                }
				$res = M("sys_param")->where("id=".$_POST["id"])->save($_POST);
			}else{
				$where['param_code']=$_POST['param_code'];
				$res = M("sys_param")->where($where)->count();
                if($res){
                	 $this->error("该字典参数code已设置");
                }
				$res = M("sys_param")->add($_POST);
			}
			if($res!==false){
				$this->success("操作成功",U('Admin/System/sys_param'));
			}else{
				$this->error("操作失败");
			}
		}else{
			if($_GET["id"]){
				$gwhere["id"] =  array("eq",$_GET["id"]);
				$data = M("sys_param")->where($gwhere)->find();
				$title="网站常规设置编辑";
			}else{
				$title="网站常规设置编辑";
			}
			$this->assign("msgtitle",$title);
			$this->assign("data",$data);
			$this->display();
		}
   }
   //删除数据字典 
	public function sys_param_del(){
		$id = $_GET["id"];
		$where['id']=$id;
		$where['is_key']=0;
		$res = M("sys_param")->where($where)->save(array('is_delete'=>1));
		if($res){
			$this->success("删除成功");
		}else{
			$this->error("删除失败");
		}
	}
       //自设数据字典 关键设置
   public  function sys_param_key(){
	   $where =array();
	   isset($_GET['param_code']) ? $where['param_code']=array('like',$_GET['param_code'].'%') : $_GET['param_code'] = '' ;
	   isset($_GET['param_name']) ? $where['param_name']=array('like',$_GET['param_name'].'%') : $_GET['param_name'] = '';
		$where['is_key']=1;
		$where['is_delete']=0;
		$menulist=D("Common")->getPageList('sys_param',$where);
		$this->assign("list",$menulist);
		$this->display();
   }
    //自设数据字典 
   public  function sys_param_key_edit(){
   	   if(IS_AJAX){
	   	   	 $account=$_GET['param_code'];
	        if(empty($account)){
	        	echo 1;die;
	        }
	        $count= M("sys_param")->where(array('param_code'=>$account))->count();
	        echo $count;die;
   	   }
       if($_POST){
	       	if(empty($_POST["id"])){//新增时判断
			    if(empty($_POST['param_code'])){
		          $this->error("请输入字典参数code");
				}
				if(empty($_POST['param_name'])){
		          $this->error("请输入字典参数名称");
				}
			}
			if($_POST['type']==2){
	          $_POST['param_value']=$_POST['type_2'];
			}else{
			  $_POST['param_value']=$_POST['type_1'];
			}
			unset($_POST['type_1']);
			unset($_POST['type_2']);
			if(!isset($_POST['param_value'])){
	          $this->error("请输入字典参数值");
			}
			
			if(!empty($_POST["id"])){
				$where['id']=array('neq',$_POST['id']);
				$where['param_code']=$_POST['param_code'];
				$res = M("sys_param")->where($where)->count();
                if($res){
                	 $this->error("字典参数code不能重复");
                }
                $gwhere["id"] =  array("eq",$_POST["id"]);
				$gwhere['is_key']=1;
		        $gwhere['is_delete']=0;
				$res = M("sys_param")->where($gwhere)->save($_POST);
			}else{
				$where['param_code']=$_POST['param_code'];
				$res = M("sys_param")->where($where)->count();
                if($res){
                	 $this->error("该字典参数code已设置");
                }
				$res = M("sys_param")->add($_POST);
			}
			if($res!==false){
				$this->success("操作成功",U('Admin/System/sys_param_key'));
			}else{
				$this->error("操作失败");
			}
		}else{
			if($_GET["id"]){
				$gwhere["id"] =  array("eq",$_GET["id"]);
				$gwhere['is_key']=1;
		        $gwhere['is_delete']=0;
				$data = M("sys_param")->where($gwhere)->find();
				$title="网站关键设置编辑";
			}else{
				$title="网站关键设置编辑";
			}
			$this->assign("msgtitle",$title);
			$this->assign("data",$data);
			$this->display();
		}
   }

	//删除数据字典 
	public function sys_param_key_del(){
		$id = $_GET["id"];
		$where['id']=$id;
		$where['is_key']=1;
		$res = M("sys_param")->where($where)->save(array('is_delete'=>1));
		if($res){
			$this->success("删除成功");
		}else{
			$this->error("删除失败");
		}
	}

	  //自设数据字典 
   public  function sys_notice(){
	   $where = array();
	   isset($_GET['title']) ? $where['title']=array('like',$_GET['title'].'%') : $_GET['title'] = '' ;
		isset($_GET['type']) ? $where['type']=array('eq',$_GET['type']) : $_GET['type'] = '' ;
		$menulist=D("Common")->getPageList('sys_notice',$where,$field='*',$order='id desc');
		$this->assign("list",$menulist);
		$this->display();
   }

   //自设数据字典 
   public  function sys_notice_edit(){
       if($_POST){
		    if(empty($_POST['title'])){
	          $this->error("请输入标题");
			}
			if(empty($_POST['content'])){
	          $this->error("请输入内容");
			}	
			if(!empty($_POST["id"])){
				$res = M("sys_notice")->where("id=".$_POST["id"])->save($_POST);
			}else{
				$_POST['add_time']=time();
				$_POST['auid']=session('auid');
				$_POST['auid']=session('account');
				$res = M("sys_notice")->add($_POST);
			}
			if($res!==false){
				$this->success("操作成功",U('Admin/System/sys_notice'));
			}else{
				$this->error("操作失败");
			}
		}else{
		   $id = isset($_GET['id']) ? $_GET['id'] : 0 ;
			if($id){
				$gwhere["id"] =  array("eq",$id);
				$data = M("sys_notice")->where($gwhere)->find();
				$title="编辑公告";
				$this->assign("data",$data);
			}else{
				$title="新增公告";
			}
		   $this->id =$id;
			$this->assign("msgtitle",$title);
			$this->display();
		}
   }

	//删除数据字典 
	public function sys_notice_del(){
		$id = $_GET["id"];
		$where['id']=$id;
		$res = M("sys_notice")->where($where)->delete();
		if($res){
			$this->success("删除成功");
		}else{
			$this->error("删除失败");
		}
	}
	
}
?>