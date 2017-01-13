<?php
/****
**商家服务（团购）
** 
**/
class GroupAction extends AuthAction{

    //分类列表
	public function index(){
        if(session('afid')==1){
            
        }else{
        	 //$menulist=$this->menu;
        }
        $menulist=D("Common")->getAllList('group_class',array('is_del'=>0));
        $menulist=$this->gettree($menulist);
        
        $this->menulist=$menulist;
		$this->display();
		//$this->display();
	}
   
	//编辑菜单
	public function class_edit(){
		if($_POST){
			$auid=session('afid');
		   if(session('topadmin')==0){//商家员
            // $_POST['auid']=$auid;
		   }
			if(!empty($_POST["id"])){
				$res = M("group_class")->where("id=".$_POST["id"])->save($_POST);
			}else{
				$res = M("group_class")->add($_POST);
			}
			if($res!==false){
				$this->success("操作成功",U('Admin/Group/index'));
			}else{
				$this->error("操作失败");
			}
		}else{
			if($_GET["id"]){
				$gwhere["id"] =  array("eq",$_GET["id"]);
				$data = M("group_class")->where($gwhere)->find();
				$menuarr = explode(",",$data["rules"]);
				$title="编辑分类";
				/*if($data['auid']){
					$adm=M('admin')->where(array('id'=>$data['auid']))->find();
					$data['adm_name']=$adm['name'];
					$data['adm_account']=$adm['account'];
				}*/
				$wherelist['id']= array("neq",$_GET["id"]);
			}else{
				$title="新增分类";
				$menuarr = array();
				/*if(session('topadmin')==0){//商家管理员帐号
					$adm=M('admin')->where(array('id'=>session('afid')))->find();
					$data['adm_name']=$adm['name'];
					$data['adm_account']=$adm['account'];
				}*/
			}
			$wherelist['pid']=0;
			$wherelist['status']=1;
			$wherelist['is_del']=0;
            $menulist=M('group_class')->where($wherelist)->select();
			
			$this->assign("data",$data);
			$this->assign("list",$menulist);
			$this->assign("msgtitle",$title);
			$this->display();
		}
	}

	//删除分类
	public function class_del(){
		$id = $_GET["id"];
		if(empty($id)){
			$this->error("操作失败");
		}
		$where['id']=$id;
		$where['fid']=$id;
		$res = M("group_class")->where($where)->save(array('is_del'=>1));
		if($res!==false){
			$this->success("操作成功");
		}else{
			$this->error("操作失败");
		}
	}
	
	//后台管理员列表
	public function goods_list(){
        if(session('afid')>1){
          $where['shop_id']=session('afid');
        }
        if($_GET['goods_name']){
			$where['goods_name']=array('like',$_GET['account'].'%');
		}
		 if($_GET['goods_name']){
			$where['goods_name']=array('like',$_GET['goods_name'].'%');
		}
		if (isset($_GET['is_show_index'])){
		    if ($_GET['is_show_index']!='all') {
		    	   $where['is_show_index']=$_GET['is_show_index'];
		    }
		}else{
			$_GET['is_show_index']='all';
		}

		if($_GET['goods_jingle']){
			$where['goods_jingle']=array('like',$_GET['goods_jingle'].'%');
		}
		if(isset($_GET['goods_state'])){
			if($_GET['goods_state']!='all'){
				$where['goods_state']=$_GET['goods_state'];
			}
		}else{
			$_GET['goods_state']='all';
		}
		if(isset($_GET['goods_status'])){
			if($_GET['goods_status']!='all'){
				$where['goods_status']=$_GET['goods_status'];
			}
		}else{
			$_GET['goods_status']='all';
		}
		$where['is_del']=0;
		if(session('afid')>1){ //权限 判断
           $where['shop_id'] = session('afid');
        }
		$menulist=D("Common")->getPageList('group_goods',$where);

		$this->assign("list",$menulist);
		$this->display();
	}

	//商品编辑/添加
	public function goods_edit(){
		if($_POST){
			if(empty($_POST["goods_name"])){
			   $this->error("请填写服务名称");
			}
			if($_POST['goods_class']){
				$_POST['goods_top_class']=M('group_class')->where(array('id'=>$_POST['goods_class']))->getField('pid');
			}
			if(!empty($_POST['goods_image_url'])){
				$_POST['goods_image_url']=implode(',', $_POST['goods_image_url']);
			}
			if($_POST["id"]){//操作为修改信息
			    $resault = M("group_goods")->where("id=".$_POST["id"])->save($_POST);
			}else{
				 $_POST['shop_id']=session('afid');
			     $resault = M("group_goods")->add($_POST);
			}
			if($resault!==false){
				if(empty($_SESSION["topadmin"]) && $_POST["id"] && empty($resault)){
					 $resault = M("group_goods")->where("id=".$_POST["id"])->save(array('goods_status'=>0));
				}
				$this->success("操作成功",U('Admin/Group/goods_list'));
			}else{
				$this->error("操作失败");
			}
		}else{
			if($_GET["id"]){
				$where['id']=$_GET["id"];
				if(session('afid')>1){ //权限 判断
		           $where['shop_id'] = session('afid');
		        }
				$adminmsg = M("group_goods")->where($where)->find();
				if($adminmsg['goods_image_url']){
					$adminmsg['goods_image_url']=explode(',', $adminmsg['goods_image_url']);
				}
				$titles="编辑服务信息";
			}else{
				$titles="添加服务信息";
			}
			$where=array('status'=>1);
            if(session('afid')>1){ //权限 判断
               $where['shop_id'] = session('afid');
            }else{
               $where['auid'] =0;
            }

			$menulist=D("Common")->getAllList('group_class',array('status'=>1,'is_del'=>0));
            $menulist=$this->gettree($menulist);
		
			$this->assign("data",$adminmsg);
			$this->assign("msgtitle",$titles);
			$this->assign("list",$menulist);
			$this->display();
		}
	}
    //商品上下架
	public function goods_state(){
		$id=$_GET["id"];
		if(empty($id)){
			$this->error("系统错误");
		}
		$where['id']=$id;
		if(session('afid')>1){ //权限 判断
           $where['shop_id'] = session('afid');
        }
		$res = M("group_goods")->where($where)->find();
		if(empty($res)){
			$this->error("该服务不存在");
		}
		if($res['goods_state']){
			$data['goods_state']=0;
		}else{
			$data['goods_state']=1;
		}
		$resault = M("group_goods")->where($where)->save($data);
		if($resault!==false){
			$this->success("操作成功");
		}else{
			$this->error("操作失败");
		}
	}
	//商品审核
	public function goods_status(){
		if (IS_POST) {
			$id=$_POST["id"];
			if(empty($id)){
				$this->error("系统错误");
			}
			//goods_status
			$where['id']=$id;
			if(session('afid')>1){ //权限 判断
	           $where['shop_id'] = session('afid');
	        }
			$res = M("group_goods")->where($where)->find();
			if(empty($res)){
				$this->error("该服务不存在");
			}
			$data['goods_status']=$_POST['goods_status'];
			$data['goods_status_des']=$_POST['goods_status_des'];
			
			$resault = M("group_goods")->where($where)->save($data);
			if($resault!==false){
				$this->success("操作成功");
			}else{
				$this->error("操作失败");
			}
			die;
		}
		$id=$_GET["id"];
		if(empty($id)){
			$this->error("系统错误");
		}
		//goods_status
		$where['id']=$id;
		if(session('afid')>1){ //权限 判断
           $where['shop_id'] = session('afid');
        }
		$res = M("group_goods")->where($where)->find();
		if(empty($res)){
			$this->error("该服务不存在");
		}
		if($res['goods_image_url']){
			$res['goods_image_url']=explode(',', $res['goods_image_url']);
		}
		$class_name="";
		if($res['goods_top_class']){
			$class_name=M('group_class')->where(array('id'=>$res['goods_top_class']))->getField('class_name');
			if($class_name){
				$class_name.=">";
			}
		}
		if($res['goods_class']){
			$class_name.=M('group_class')->where(array('id'=>$res['goods_class']))->getField('class_name');
		}
		$res['class_name']=$class_name;
		$this->msgtitle="服务审核";
		$this->data=$res;
		$this->display();
	}
	//商户首页推荐
	public function goods_show_index(){
		$id = $_GET["id"];
		$res = M("group_goods")->where("id=".$_GET["id"])->find();
		if(empty($res)){
			$this->error("该服务不存在");
		}
		if($res['is_show_index']){
			$data['is_show_index']=0;
		}else{
			$data['is_show_index']=1;
		}
		$res = M("group_goods")->where("id=".$id)->save($data);
		if($res!==false){
				$this->success("操作成功");
		}else{
				$this->error("操作失败");
		}
	}
	//删除商品
	public function goods_del(){
		$id=$_GET["id"];
		if(empty($id)){
			$this->error("系统错误");
		}
		$where['id']=$id;
		if(session('afid')>1){ //权限 判断
           $where['shop_id'] = session('afid');
        }
		$res = M("group_goods")->where($where)->find();
		if(empty($res)){
			$this->error("该服务不存在");
		}
		$data['is_del']=1;
		$resault = M("group_goods")->where($where)->save($data);
		if($resault!==false){
			$this->success("操作成功");
		}else{
			$this->error("操作失败");
		}
	}
    	
}
?>