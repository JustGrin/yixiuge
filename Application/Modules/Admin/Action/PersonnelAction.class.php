<?php
/****
** 用户  人事管理
** 
**/
class PersonnelAction extends AuthAction{

    //部门列表
	public function index(){
        $menulist=D("Common")->getAllList('p_personnel');
         $menulist=$this->gettree($menulist);
        $this->menulist=$menulist;
		$this->display();
		//$this->display();
	}
	//编辑部门
	public function personnel_edit(){
		if($_POST){
			if(empty($_POST['p_name'])){
				$this->error("请填写部门名称");
			}
			if($_POST['id']){
                $res = M("p_personnel")->where("id=".$_POST["id"])->save($_POST);
			}else{
                $res = M("p_personnel")->add($_POST);
			}
			if($res!==false){
				$this->success("操作成功",U("Admin/Personnel/index"));
			}else{
				$this->error("操作失败");
			}
		}else{
			if($_GET["id"]){
				$menuwhere["id"] =  array("eq",$_GET["id"]);
				$data = M("p_personnel")->where($menuwhere)->find();
				$title="编辑部门";
				$where['pid']=array('eq',0);
				$where['id']=array('neq',$_GET["id"]);
			}else{
				$title="新增部门";
				$where['pid']=array('eq',0);
			}
			
			$where['status']=1;
			$list= M("p_personnel")->where($where)->select();
			
			$this->assign("data",$data);
			$this->assign("list",$list);
			$this->assign("msgtitle",$title);
			$this->display();
		}
	}

	//删除部门
	public function personnel_del(){
		$id = $_GET["id"];
		$res = M("p_members")->where("pid=".$_GET["id"])->count();
		if($res){
			$this->error("该部门有下级部门请删除下级部门后删除");
		}
		$res = M("p_personnel")->where("id=".$id)->delete();
		if($res!==false){
				$this->success("操作成功");
		}else{
				$this->error("操作失败");
		}
	}
	
	//人员列表
	public function members_list(){
        if($_GET['name']){
			$where['mem.name']=array('like',$_GET['name'].'%');
		}
		if($_GET['p_id']){
			$where['_string']='mem.p_id='.$_GET['p_id'].' or mem.top_p_id='.$_GET['p_id'];
		}
		if(isset($_GET['status'])){
			if($_GET['status']!='all'){
				$where['mem.status']=$_GET['status'];
			}
		}else{
			$_GET['status']='all';
		}
		$plist=D("Common")->getAllList('p_personnel',array('status'=>1));
		$plist=$this->gettree($plist);
		$this->assign("p_list",$plist);

        $pre=C('DB_PREFIX');//表前缀
        $count=M()->table($pre.'p_members mem')->where($where)->count();
        $page=D("Common")->getPage($count);//分页

        $field="mem.*,adm.account,adm.name as admin_name,per.p_name";

    	$list =M()->table($pre.'p_members mem')->where($where)->field($field)//
    	->join($pre.'admin adm on adm.id=mem.auid')//
    	->join($pre.'p_personnel per on per.id=mem.p_id')//
    	->order($order)->limit($page['start'].','.$page['pagesize'])->select();
	
		$menulist['list']=$list;
		$menulist['page']=$page['page'];
		$this->assign("list",$menulist);
		$this->display();
	}

	//人员信息编辑/添加
	public function members_edit(){
		if($_POST){

			if(empty($_POST["name"])){
					$this->error("请填写员工姓名");
			}
			if($_POST['p_id']){
				$_POST['top_p_id']=M('p_personnel')->where(array('id'=>$_POST['p_id']))->getField('pid');
			}
			if($_POST["id"]){//操作为修改信息
				$res = M("p_members")->where("id=".$_POST["id"])->save($_POST);
			}else{
				 $_POST['add_time']=time();
				$res = M("p_members")->add($_POST);
			}
			if($res!==false){
					$this->success("操作成功",U('Admin/Personnel/members_list'));
				}else{
					$this->error("操作失败");
				}
		}else{
			if($_GET["id"]){
				$adminmsg = M("p_members")->where("id=".$_GET["id"])->find();
				$titles="编辑员工信息";
			}else{
				$titles="添加员工信息";
			}
			$where['status']=1;
			$list = M("p_personnel")->where($where)->select();
			 $list=$this->gettree($list);

			 $adminlist = M("admin")->where(array('fid'=>1,'type'=>1,'status'=>1))->select();
		
			
			$this->assign("data",$adminmsg);
			$this->assign("msgtitle",$titles);
			$this->assign("list",$list);
			$this->assign("adminlist",$adminlist);
			$this->display();
		}
	}
  
	//人员信息 删除
	public function members_del(){
		$id=$_GET["id"];
		$resault = M("p_members")->where("id=".$_GET["id"])->delete();
		if($resault){
			$this->success("操作成功");
		}else{
			$this->error("操作失败");
		}
	}
    
    //商家合约
   public  function shop_contract(){
        if($_GET['param_code']){
			$where['param_code']=array('like',$_GET['param_code'].'%');
		}
		if($_GET['param_name']){
			$where['param_name']=array('like',$_GET['param_name'].'%');
		}
		$where['is_del']=0;
		$menulist=D("Common")->getPageList('sys_param',$where);
		$this->assign("list",$menulist);
		$this->display();
   }
   //商家合约 查看
   public  function shop_contract_detail(){
   	    $gwhere["id"] =  array("eq",$_GET["id"]);
	    $data = M("p_shop_contract")->where($gwhere)->find();
		$this->assign("data",$data);
		$this->display();
   }
   //商家合约 
   public  function shop_contract_edit(){
       if($_POST){
		    if(empty($_POST['shopname'])){
	          $this->error("请输入商家名称");
			}
			if(!empty($_POST["id"])){
				$res = M("p_shop_contract")->where("id=".$_POST["id"])->save($_POST);
			}else{
				$_POST["add_time"]=time();
				$res = M("p_shop_contract")->add($_POST);
			}
			if($res!==false){
				$this->success("操作成功",U('Admin/Personnel/shop_contract'));
			}else{
				$this->error("操作失败");
			}
		}else{
			if($_GET["id"]){
				$gwhere["id"] =  array("eq",$_GET["id"]);
				$data = M("p_shop_contract")->where($gwhere)->find();
				$title="编辑商家合约";
			}else{
				$title="新增商家合约";
			}
			$this->assign("msgtitle",$title);
			$this->assign("data",$data);
			$this->display();
		}
   }

	//删除商家合约  
	public function shop_contract_del(){
		$id = $_GET["id"];
		$where['id']=$id;
		$res = M("p_shop_contract")->where($where)->save(array("is_del",1));
		if($res){
			$this->success("删除成功");
		}else{
			$this->error("删除失败");
		}
	}


	
}
?>