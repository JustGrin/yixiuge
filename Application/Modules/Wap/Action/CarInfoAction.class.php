<?php

// 购物车
class CarinfoAction extends BaseAction
{
    //魔术方法
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
		$where =array();
		$where['version_id'] = array('neq',0);
		$brand_list = M('cars')->field(' brand_id,brand ')->where($where)->group('brand_id')->select();
		//$series_list = M('cars')->field(' series_id,series ')->where($where)->group('series_id')->select();
		//$version_list = M('cars')->field(' version_id,version ')->where($where)->group('version_id')->select();

		$this->brand_list = $brand_list;
		//$this->series_list = $series_list;
		//$this->version_list = $version_list;
        $this->display();
    }
	// 索引类型 $b - brand   $v - version  $s - series
	public function get_search_list(){
		$where = array();
		$where['version_id'] = array('neq',0);
		$data = array();
		$data['status'] = 0;
		if(isset($_REQUEST['type']) && $_REQUEST['type'] == 'brand'){

			$where['brand_id'] = $_REQUEST['id'];
			$series_list = M('cars')->field(' series_id,series ')->where($where)->group('series_id')->select();
			$series_options= '<option value="null">请选择车系</option>';
			foreach ($series_list as $k => $v){
				$series_options .='<option value="'.$v['series_id'].'">'.$v['series'].'</option>';
			}

			$data['status'] = 1;
			$data['type'] = 'series';
			$data['list'] = $series_options;
			echo json_encode($data);die();
		}elseif(isset($_REQUEST['type']) && $_REQUEST['type'] == 'series'){
			$where['series_id'] = $_REQUEST['id'];
			$version_list = M('cars')->field(' version_id,version ')->where($where)->group('version_id')->select();
			$version_options= '<option value="null">请选择车型</option>';
			foreach ($version_list as $k => $v){
				$version_options .='<option value="'.$v['version_id'].'">'.$v['version'].'</option>';
			}

			$data['status'] = 1;
			$data['type'] = 'version';
			$data['list'] = $version_options;
			echo json_encode($data);die();
		}
		$data['msg'] = '请先选择品牌';
		echo json_encode($data);die();
	}

    public function car_detail(){
        $car_id =0;
        isset($_REQUEST['version_id']) && $_REQUEST['version_id'] ? $version_id =$_REQUEST['version_id'] : $this->redirect('carinfo/index');
        $car_detail = M('cars')->where("version_id = {$version_id}")->find();
        $this->data =$car_detail;
        $this->display();
    }


}