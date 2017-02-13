<?php
/****
** 会员action
** 
**/
class UsedcarAction extends AuthAction{

    //会员列表
	public function index(){
		set_time_limit(0);
//        if($_GET['member_card']){
//			$where['mem.member_card']=array('like',$_GET['member_card'].'%');
//		}
		$where = array();
		isset($_GET['member_name']) ? $where['mem.member_name'] = array('like', '%' . $_GET['member_name'] . '%') : $_GET['member_name'] = '';
		if(isset($_GET['mobile'])){
			$s_where['mem.mobile'] = array('like', '%' . $_GET['mobile'] . '%');
			$s_where['mem.member_card'] = array('like', '%' . $_GET['mobile'] . '%');
			$s_where['_logic'] = 'or';
			$where['_complex'] = $s_where;
		}else{
			$_GET['mobile'] = '';
		}

		if(isset($_GET['is_check'] )&& $_GET['is_check'] !='all'){
			$where['is_check'] = $_GET['is_check'];
		}else{
			$_GET['is_check'] = 'all';
		}
		if (isset($_GET['start_time'])){
			$start_time = strtotime($_GET['start_time']) ;
		}else{
			$start_time = 0;
			$_GET['start_time'] = '';
		}

		if (isset($_GET['end_time'])){
			$end_time = strtotime($_GET['end_time']) ;
		}else{
			$end_time = -1;
			$_GET['end_time'] = '';
		}

		if($end_time>0 && $start_time>0){
			$where['mem.change_time']=array('between',array($start_time,$end_time));
		}else{
			if($start_time>0){
				$where['mem.change_time']=array('egt',$start_time);
			}
			if($end_time>0){
				$where['mem.change_time']=array('elt',$end_time);
			}
		}
		$order=" car.is_check ASC,car.change_time ASC";

		$field="mem.id,mem.member_name,mem.member_card,mem.mobile";
		$field.=",car.*";
		$pre=C("DB_PREFIX");//获取表前缀
		import('ORG.Util.Page');// 导入数据页分类
		$model=M();
		if(empty($where)){
			$count = $model->table($pre.'usde_cars_info car')
					->count();
		}else{
			$count = $model->table($pre.'usde_cars_info car')
					//->field($field)
					->where($where)
					->join($pre.'member mem on car.member_id=mem.id')
					->count();
		}

			
		$page = new page($count,20);
		$show = $page->show();   //输出分页
		$list = $model->table($pre.'usde_cars_info car')
			->field($field)
			->where($where)
			->join($pre.'member mem on car.member_id=mem.id')
			->order($order)
			->limit($page->firstRow.','.$page->listRows)
			->select();

		$menulist['list']=$list;
			$menulist['page']=$show;
		$this->assign("list",$menulist);
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
}
?>