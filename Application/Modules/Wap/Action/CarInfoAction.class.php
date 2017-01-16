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

        $this->display();
    }
    public function result_list(){
        $where = array();
        $msg = '' ;
        isset($_REQUEST['brand']) && $_REQUEST['brand'] ? $where['brand'] = array('like','%'.trim($_REQUEST['brand']).'%') : false;
        isset($_REQUEST['series']) && $_REQUEST['series'] ? $where['series'] = array('like','%'.trim($_REQUEST['series']).'%') : false;
        isset($_REQUEST['version']) && $_REQUEST['version'] ? $where['version'] = array('like','%'.trim($_REQUEST['version']).'%') : false;
        if (empty($where)){
            $msg = '请至少输入一个信息';
            $list = '';
        }else{
            $list = M('cars')->where($where)->select();
            if (empty($list)){
                $msg = '抱歉,没有找到结果';
            }
        }

        $this->msg = $msg;
        $this->list = $list;
        $this->display();
    }
    public function car_detail(){
        $car_id =0;
        isset($_GET['id']) && $_GET['id'] ? $car_id =$_GET['id'] : $this->redirect('carinfo/index');
        $car_detail = M('cars')->where("id = {$car_id}")->find();
        $this->data =$car_detail;
        $this->display();
    }


}