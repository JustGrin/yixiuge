<?php
/****
**菜单 用户组
** 
**/
class PaymentAction extends AuthAction{

    //菜单支付方式
	public function index(){
        $menulist=D("Common")->getAllList('payment');
        
        $this->menulist=$menulist;
		$this->display();
		//$this->display();
	}
  
	//编辑支付方式
	public function payment_edit(){
		if($_POST){
			$config_array = explode(',',$_POST["config_name"]);//配置参数
			if(is_array($config_array) && !empty($config_array)) {
				$config_info = array();
				foreach ($config_array as $k) {
					$config_info[$k] = trim($_POST[$k]);
				}
				$payment_config	= serialize($config_info);
			}
			$data['payment_config'] = $payment_config;//支付接口配置信息
			$data['payment_state']=$_POST['payment_state'];
			$res = M("payment")->where("payment_id=".$_POST["payment_id"])->save($data);
			if($res!==false){
				$this->success("操作成功",U("Admin/Payment/index"));
			}else{
				$this->error("操作失败");
			}
		}else{

			$menuwhere["payment_id"] =  array("eq",$_GET["id"]);
			$data = M("payment")->where($menuwhere)->find();
			if ($data['payment_config'] != ''){
				$data['config_array']=unserialize($data['payment_config']);
		    }
			$title="支付设置";
			$this->assign("data",$data);
			$this->assign("msgtitle",$title);
			$this->display();
		}
	}
}
?>