<?php
/****
** 会员action
** 
**/
class UsedcarAction extends AuthAction{

    //二手车信息列表
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
	//执行审核
	public function do_msg_check(){
		//是否审核通过
		if ($_GET['value'] != null) {
			$id = $_GET["id"];
			$type=$_GET['type'];
			$value=$_GET["value"];
			if ($type=='is_check'){
				$save_date['is_check'] = $value;
			}else{
				$save_date['remark'] = $value;
			}

			$where['id'] = $id;

			$res = M('usde_cars_info')->where($where)->save($save_date);
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