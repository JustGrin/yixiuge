<?php

// 购物车
class CarinfoAction extends BaseAction
{
	//魔术方法
	public function __construct(){
		parent::__construct();

		if(!IS_AJAX){
			$this->now_menu='';
		}
	}

	//产品列表
	public function index(){
		$where = array();

		$_REQUEST['type'] = isset($_REQUEST['type']) ? $_REQUEST['type'] : 1;
		$_REQUEST['type'] == 1 ? $where['is_check'] = 1 : $where['cars.member_id'] = $this->uid;
		$order = "change_time DESC";
		$join = "db_member as mem on mem.id = cars.member_id ";
		$field = ' cars.* ,mem.member_name,mem.member_logo';
		$carinfo_list = M('usde_cars_info')->alias('cars')->field($field)->join($join)->where($where)->order($order)->select();
		foreach ($carinfo_list as $k => $v){
			$imgs = explode(',',$v['imgs_a']);
			$carinfo_list[$k]['first_img'] =$imgs[0];
		}
		$this->type =  $_REQUEST['type'] ;
		$this->status_names = array('未审核','通过','审核未通过');
		$this->is_ajax = IS_AJAX;
		$this->list = $carinfo_list ;
		$this->display();
	}

	//用户发布商品
	public function add_msg()
	{
		$_GET['id'] = isset($_GET['id']) ? $_GET['id'] : 0;
		$member_id = $this->uid;
		if($_POST){
			$return_data['status'] = 0;
			$data_save =$_POST;
			$data_save['is_check'] = 0;
			$data_save['member_id'] =$member_id;
			$time = time();
			$data_save['change_time'] = $time;
			$data_save['imgs_a'] = implode(',',$_POST['msg_img']);
			$model =M('usde_cars_info');
			if($_POST['id']){
				$res =$model->where('id='.$_POST['id'])->save($data_save);
			}else{
				$data_save['add_time'] = $time;
				unset($data_save['id'] );
				$a = 1;
				$res =$model->add($data_save);
			}
			if($res !== false){
				$return_data['status'] = 1;
				echo json_encode($return_data);die;
			}else{
				$return_data['error'] = '编辑失败'.$a;
				echo json_encode($return_data);die;
			}
		}else{
			if (!isset($_GET['id'] )){
				$_GET['id'] = 0;
			}
			if($_GET['id']){
				$where = array();
				$where['id'] = $_GET['id'];
				$where['member_id'] = $member_id;
				$data=M('usde_cars_info')->where($where)->find();
				$data['imgs_a'] = explode(',',$data['imgs_a']);

				$this->data = $data;
			}
		}
		$this->display();
	}
	//二手车信息详情
	public function msg_detail(){
		isset($_GET['id']) ? $id = $_GET['id'] : $this->redirect('wap/carinfo/index');
		$data = M('usde_cars_info')->where('id='.$id)->find();
		$data['imgs_a'] = explode(',',$data['imgs_a']);
		$this->data = $data;
		$this->display();
	}

}