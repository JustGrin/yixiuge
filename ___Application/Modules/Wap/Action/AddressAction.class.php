<?php
// 用户控制器
class AddressAction extends UserAction {
	//魔术方法
public function __construct(){
		parent::__construct();
		
	}
	///收获地址列表
    public function index(){
		$this->list=M('g_user_address')->where(array('user_id'=>$this->uid))->order('is_default desc')->select();
        
		$this->city_group = get_city_group();
		
    	$this->webtitle="FG峰购";
		$this->display();
    }

    ///收获地址列表
    public function address_add(){
        $address_id=$_GET['id'];
        if($address_id){
        	$address=M('g_user_address')->where(array('address_id'=>$address_id,'user_id'=>$this->uid))->find();
        	$this->data=$address;
        }
		
        $citydata=array('province'=>1,'provinceid'=>$address['province'],'cityid'=>$address['city']);
        $citylist=$this->get_city_return($citydata);
        $this->citylist=$citylist;

    	$this->webtitle="FG峰购";
		$this->display();
    }
    ///收获地址列表
    public function set_address(){
        $data['status']=0;
        $address_id=$_POST['address_id'];
        $consignee=$_POST['consignee'];
        $email=$_POST['email'];
        $province=$_POST['province'];
        $city=$_POST['city'];
        $area=$_POST['area'];
        $address=$_POST['address'];
        $zipcode=$_POST['zipcode'];
        $tel=$_POST['tel'];
        $mobile=$_POST['mobile'];
        $sign_building=$_POST['sign_building'];
        $best_time=$_POST['best_time'];

        if(empty($consignee)){
          $data['error']="请填写收货人的名字";
          echo json_encode($data);die;
        }
		
		$area_info = $_POST['area_info'];
		if($area_info){
			list($province, $city, $area) = explode(',', $area_info);
		}
		
		if(empty($province) || empty($city) || empty($area)){
		  $data['error']="请选择完整的省市区";
		  echo json_encode($data);die;
		}
		
        if(empty($address)){
          $data['error']="请填写详细地址";
          echo json_encode($data);die;
        }
        if(empty($tel) && empty($mobile)){
          $data['error']="收货人的电话或手机";
          echo json_encode($data);die;
        }
        if($_POST['is_default']){
            $save_data['is_default']=1;
            $addressId= M('g_user_address')->field('address_id')->where(array('user_id'=>$this->uid,'is_default'=>$save_data['is_default']))->find();
            $default['is_default']=0;
            M('g_user_address')->where(array('address_id'=>$addressId['address_id']))->save($default);
        }else{
            $save_data['is_default']=0;
        }
		M('g_user_address')->where(array('user_id'=>$this->uid))->save(array('is_default'=>0));//所有地址都去除默认
		$province_name=M('city_province')->where(array('provinceid'=>$province))->getField('province');
        $city_name=M('city_city')->where(array('cityid'=>$city))->getField('city');
        $area_name=M('city_area')->where(array('areaid'=>$area))->getField('area');
        $address_name=$province_name.$city_name.$area_name;
        $save_data['address_name']=$address_name;
        $save_data['consignee']=$consignee;
        $save_data['email']=$email;
        $save_data['province']=$province;
        $save_data['city']=$city;
        $save_data['area']=$area;
        $save_data['address']=$address;
        $save_data['zipcode']=$zipcode;
        $save_data['tel']=$tel;
        $save_data['mobile']=$mobile;
		$save_data['is_default']=1;//新加地址变成默认地址
        $save_data['sign_building'] = empty($sign_building) ? '' : $sign_building;
        $save_data['best_time']=empty($best_time) ? '' : $best_time;
        $data['save_data']=$save_data;
        if($address_id){//修改
            $res=M('g_user_address')->where(array('user_id'=>$this->uid,'address_id'=>$address_id))->save($save_data);
        }else{
			$address_num = M('g_user_address')->where(array('user_id'=>$this->uid))->count();
			if($address_num >= 6){
				$data['error'] = $this->uid."收货地址最多6个".$address_num;
				echo json_encode($data);die;
			}
        	$save_data['user_id']=$this->uid;
        	$res=M('g_user_address')->add($save_data);
			$address_id = M('g_user_address')->getLastInsID();
        }
		
		$data['save_data']['address_id'] = $address_id;
		
		if($res!==false){
          $data['status']=1;
          $url=U('Wap/Address/index');
          if($_REQUEST['type']=="cart"){
                $url=U('Wap/Cart/order_add').'?cart_id='.$_REQUEST['cart_id'].'&discount='.$_REQUEST['discount'];
          }elseif($_REQUEST['type']=="ucart"){
              $url=$_REQUEST['uri'];
              $url=U('Wap/Cart/upgrade_order_add').'?'.str_replace('||','&',$url);
          }elseif($_REQUEST['type']=="start"){//-----------------
              $url=$_REQUEST['uri'];
              $url=U('Wap/start/gift_address').'?order_id='.$_REQUEST['order_id'];
          }elseif ($_REQUEST['type']=='draw') {
             $url=U('Wap/draw/draw_address');
          }elseif ($_REQUEST['type']=='set_draw') {
             $url=U('Wap/draw/set_draw_address').'?draw_log_id='.$_REQUEST['draw_log_id'];;
          }
           $data['return_url']=$url;
        }else{
          $data['error']="操作失败";
        }
        echo json_encode($data);die;
    }
	
	//设置默认地址
	public function set_default_address(){
		$id = $_REQUEST['address_id'];
		$data['stauts'] = 0;
		if(!$id){
			$data['error']="该地址不存在";
			echo json_encode($data);die;
		}
		M('g_user_address')->where(array('user_id'=>$this->uid))->save(array('is_default'=>0));
		$res=M('g_user_address')->where(array('user_id'=>$this->uid, 'address_id'=>$id))->save(array('is_default'=>1));
		if($res!==false){
			$data['stauts'] = 1;
			echo json_encode($data);die;
		}else{
			$data['error']="操作失败";
			echo json_encode($data);die;
		}
	}

    ///收获地址删除
    public function del_address(){
    	$data['status']=0;
        $address_id=$_GET['address_id'];
        if(empty($address_id)){
          $data['msg']="该地址不存在";
          echo json_encode($data);die;
        }
        $count=M('g_user_address')->where(array('user_id'=>$this->uid,'address_id'=>$address_id))->count();
		if(empty($count)){
          $data['msg']="该地址不存在";
          echo json_encode($data);die;
        }
        $res=M('g_user_address')->where(array('user_id'=>$this->uid,'address_id'=>$address_id))->delete();
		if($res!==false){
			$data['msg']="删除成功！";
          $data['status']=1;
        }else{
          $data['msg']="删除失败";
        }
        echo json_encode($data);die;
    }
   

}