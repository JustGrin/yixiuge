<?php
// 前台控制器，继承公共目录下的HomeAction，方便公共数据调用
class PartnerAction extends CommonAction {
	//魔术方法
	public function __construct(){
		parent::__construct();

	}
	public function index(){
		$this->display();
	}
	public function basedetail(){
		$this->display();
	}

}