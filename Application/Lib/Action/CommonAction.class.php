<?php
// 本类由系统自动生成，仅供测试用途
class CommonAction extends Action {


	public function  __construct(){
	//echo '<h1 style="size: 10px"><a href="'.U('wap/upgrade/shopkeeper_area').'"> 店主专区</a></h1>';
    parent::__construct();
		$result1 ='test1';
		$this->result1 = $result1;
    $this->mapak=C('SET_MAP_AK');
        ////echo GROUP_NAME,MODULE_NAME,ACTION_NAME;
    //当前操作的请求                 分组名称/模块名/方法名
    // $url=GROUP_NAME.'/'.MODULE_NAME.'/'.ACTION_NAME;
    //$url=strtolower($url);//大写转成小写
    $now_group=GROUP_NAME;///分组名称
    $now_group=strtolower($now_group);//大写转成小写
	
	$user_agent = $_SERVER['HTTP_USER_AGENT'];
	$is_weixinbw = 0;
	if(strpos($user_agent, 'MicroMessenger') !== false) {
		$is_weixinbw = 1;
	}
	
    if($now_group=='index'){
      if(is_mobile() && $is_weixinbw){
        redirect(U("Wap/Index/Index"));
      }
      $host=$_SERVER['HTTP_HOST'];
      if($host=='www.fei-mi.com' || $host=='fei-mi.com'){
       // redirect(U("index/Index/Index"));
      }
    }
	if($now_group=='fghome'){
      if(is_mobile() && $is_weixinbw){
        redirect(U("Wap/Index/Index"));
      }
	    $host=$_SERVER['HTTP_HOST'];
      if($host=='www.fei-mi.com' || $host=='fei-mi.com'){
		//  C('DEFAULT_GROUP','Index');
        redirect(__ROOT__.'index.php/index');
      }
    }
    if(!IS_AJAX){
      $this->get_webseting();
    }
	}
	/**
     * 获得网站设置
     */
  public function get_webseting(){
     if(!IS_AJAX){
        $model=M('sys_param');
       $webseting['web_title']=$model->where(array('param_code'=>'web_title'))->getField('param_value'); 
       $webseting['web_copyright']=$model->where(array('param_code'=>'web_copyright'))->getField('param_value'); 
       $this->webseting=$webseting;
     }
  }
	/**
     * 获得树状结构数组
     * $arr必带 $arr['id'] $arr['pid']  
     */
	public function gettree($arr=array(),$pid=0,$lv=0,$field='pid',$f_field='id'){
      $returnarr=array();
      if(!empty($arr)){
      	 foreach ($arr as $key => $value) {
	       	if($pid==$value[$field]){
	       		unset($arr[$key]);
               $value['tree']=$this->gettree($arr,$value[$f_field],$lv++,$field,$f_field);
               $returnarr[]=$value;
	       	}else{
	       	   if($pid==$value[$f_field]){
	       	   	   unset($arr[$key]);
	               $value['tree']=$this->gettree($arr,$value[$f_field],$lv++,$field,$f_field);
	               $returnarr[]=$value;
	       	   }
	       	}
	      }
      }
      return $returnarr;
	}
///图片上传
		public function upload(){
		$path='./Uploads/';
		$years=Date("Y");
		$moth=Date("m");
		$day=Date("d");
		$path=$path."{$years}/";
		if (!is_dir("{$path}")) mkdir("{$path}"); // 如果不存在则创建
		$path=$path."{$moth}/";
		if (!is_dir("{$path}")) mkdir("{$path}"); // 如果不存在则创建
		$path=$path."{$day}/";
		if (!is_dir("{$path}")) mkdir("{$path}"); // 如果不存在则创建
		 import('ORG.Net.UploadFile');
		 $upload = new UploadFile();// 实例化上传类
		 $upload->maxSize  = 3145728 ;// 设置附件上传大小
		 $upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
		 $upload->savePath =  $path;// 设置附件上传目录
		 if(!$upload->upload()) {// 上传错误提示错误信息
			if(IS_AJAX){
				$this->ajaxReturn($data,$upload->getErrorMsg(),0);
				exit();
			}
			$this->error($upload->getErrorMsg());
		 }else{// 上传成功 获取上传文件信息
			$info =  $upload->getUploadFileInfo();
		 }
		 return $info;
	}

	///图片上传
	public function upload_ajax(){
		$path='./Uploads/';
		$years=Date("Y");
		$moth=Date("m");
		$day=Date("d");
		$path=$path."{$years}/";
		if (!is_dir("{$path}")) mkdir("{$path}"); // 如果不存在则创建
		$path=$path."{$moth}/";
		if (!is_dir("{$path}")) mkdir("{$path}"); // 如果不存在则创建
		$path=$path."{$day}/";
		if (!is_dir("{$path}")) mkdir("{$path}"); // 如果不存在则创建
		import('ORG.Net.UploadFile');
		$upload = new UploadFile();// 实例化上传类
		$upload->maxSize  = 3145728 ;// 设置附件上传大小
		$upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
		$upload->savePath =  $path;// 设置附件上传目录
		if(!$upload->upload()) {// 上传错误提示错误信息
			if(IS_AJAX){
				$this->ajaxReturn($data,$upload->getErrorMsg(),0);
				exit();
			}
			$this->error($upload->getErrorMsg());
		}else{// 上传成功 获取上传文件信息
			$info =  $upload->getUploadFileInfo();
		}
		$data['error']='';//成功
		$data['msg']='0000';//成功
		$data['imgurl']=$info[0]['savepath'].$info[0]['savename'];//成功
		$watermark=C('WATERMARK');
		if(file_exists($watermark)){
			//给图添加水印
			import("ORG.Util.Image");
			//给图添加水印, Image::water('原文件名','水印图片地址')
			Image::water($data['imgurl'], $watermark);
		}

		$data['imgurl']=substr($data['imgurl'],1);//去掉点
		echo json_encode($data);
	}
	
	//托截取上传
	public function crop_submit(){
         $_REQUEST['x']=round($_REQUEST['x']);  
		 $_REQUEST['y']=round($_REQUEST['y']);
		 $_REQUEST['w']=round($_REQUEST['w']);
		 $_REQUEST['h']=round($_REQUEST['h']);
		 $targ_w = round($_REQUEST['w']);
		 $targ_h = round($_REQUEST['h']);
		 $jpeg_quality = 90;
		 $pic_name = $_REQUEST['pic_name'];
		 $src = '.'.$pic_name;
	     $pic_type=explode('.', $pic_name);
	     $type=$pic_type[1];

	     $createFun = 'ImageCreateFrom' . ($type == 'jpg' ? 'jpeg' : $type);
	     if(!function_exists($createFun)) {
	       	 $data['status']=0;
	       	 $data['error']='缩略图创建失败';
	       	 echo json_encode($data);die;
	       }
	        $img_r = $createFun($src);
		  // $img_r = imagecreatefromjpeg($src);
		 $dst_r = imagecreatetruecolor( $targ_w, $targ_h );
		  // imagecopy( $target, $source, 0, 0, $x, $y, $w, $h);
          if (function_exists("ImageCopyResampled"))
              imagecopyresampled($dst_r,$img_r,0,0,$_REQUEST['x'],$_REQUEST['y'],$targ_w,$targ_h,$_REQUEST['w'],$_REQUEST['h']);
          else
              imagecopyresized($dst_r,$img_r,0,0,$_REQUEST['x'],$_REQUEST['y'],$targ_w,$targ_h,$_REQUEST['w'],$_REQUEST['h']);
            
		 //imagecopyresampled($dst_r,$img_r,0,0,$_REQUEST['x'],$_REQUEST['y'],$targ_w,$targ_h,$_REQUEST['w'],$_REQUEST['h']);
         //header("content-type: image/jpg");
		 //var_dump($dst_r);die;
         $pic_name='.'.$pic_type[0].rand(1000,9999).'.'.$pic_type[1];
		 //生成图片
	     $re=imagejpeg($dst_r,$pic_name,$jpeg_quality);
        imagedestroy($dst_r);
		imagedestroy($img_r);
	     $data['status']=0;
	     if($re){
	     	if(file_exists($src)){
	     		unlink($src);//删除原文件
	     	}
	     	$data['status']=1;
	     	$data['file']=substr($pic_name,1);
	     }else{
	     	$data['error']='缩略图创建失败';
	     }
	     echo json_encode($data);

	}
  // base64 转图片
  public function upload_file_base64(){
    //生成图片 
    $path='./Uploads/';
    $years=Date("Y");
    $moth=Date("m");
    $day=Date("d");
    $path=$path."{$years}/";
    if (!is_dir("{$path}")) mkdir("{$path}"); // 如果不存在则创建
    $path=$path."{$moth}/";
    if (!is_dir("{$path}")) mkdir("{$path}"); // 如果不存在则创建
    $path=$path."{$day}/";
    if (!is_dir("{$path}")) mkdir("{$path}"); // 如果不存在则创建
  
    $filename=uniqid().mt_rand(1000,9999).".jpg";///要生成的图片名字 

    $base64_image_content=$_POST['image'];
    $imgdata=explode(',', $base64_image_content);
    if($imgdata){
       $file=$imgdata[1];
	   unset($imgdata);
        $filePath = $path.$filename;
        $file=base64_decode($file); 
        $res=file_put_contents($filePath,$file);
		unset($file);
		
      if($res){
         echo substr($filePath,1);die;
       }else{
         echo 0;die;
       }
    }else{
       echo 0;die;
    }
  }
   public function get_access_token_ts(){
        M('sys_param')->where(array('param_code'=>'buy_switch'))->save(array('param_value'=>0));
    }
	//检测验证码
	public function checkVerify(){
		if($_SESSION['verify'] != md5($_REQUEST['verify_code'])) {
			echo 0;
		}else{
			echo 1;
		}
	}
	//检测验证码
	public function checkVerify_return(){
		if($_SESSION['verify'] != md5($_REQUEST['verify_code'])) {
			return false;
		}else{
			return true;
		}
	}
	//生成验证码
	public function verify(){
		import('ORG.Util.Image');
		Image::buildImageVerify();
	}
   //获取缩略图
   public function get_thumbs(){
        $imgurl=$_GET['img'];
         $width=$_GET['w'];
        $height=$_GET['h'];
        if($_GET['auto']){
          $width=$_GET['auto'];
          $height=$_GET['auto'];
        }
        thumbs_auto($imgurl,$width,$height, 'base64');
   }
    //短信验证码验证 注册
   public function check_send_reg(){
   	$data['status']=0;
   	    $send=$_SESSION['send'];
   	    if(empty($send)){
           $data['msg']="短信验证码错误";
           echo 0;die;
   	    }
   	    $time=time();
   	    $s_time=$time-$send['add_time'];
   	    if($s_time>60*10){//10分钟
          $data['msg']="短信验证码已过期请重新获取";
          echo 0;die;
   	    }
		$mobile=$_REQUEST['mobile'];
        if(empty($mobile) || $mobile!=$send['mobile']){
        	$data['msg']="手机号码不匹配";
        	 echo 0;die;
        }
        $code=$_REQUEST['mobile_code'];
        if(empty($code) || $code!=$send['code']) {
			$data['msg']="验证码错误";
        	 echo 0;die;
		}
		//unset($_SESSION['send']);
		//发送成功
        $data['status']=1;
        $data['msg']="短信验证码验证通过";
        $data['code']=$code;

         echo 1;die;
    }
      //绑定微信短信发送
   public function binding_send_sms(){

        $data['status']=0;
         $user_agent = $_SERVER['HTTP_USER_AGENT'];
        if (strpos($user_agent, 'MicroMessenger') === false) {
          $data['msg']="请在微信内打开";
         // echo json_encode($data);die;///--------------------------
        }
        $send=$_SESSION['send'];
        $time=time();
        $s_time=$time-$send['add_time'];
        if($s_time<60){
          $data['msg']="短信发送太频繁，请前后间隔60秒";
          echo json_encode($data);die;
        }
       $mobile=$_REQUEST['mobile'];
        if(empty($mobile) || check_phone($mobile)===false){
          $data['msg']="手机号码错误";
          echo json_encode($data);die;
        }
    
      $code=rand(100000,999999);
      $mseeage="您的短信验证码为：{$code}。";
       //$res=//发送短信
       $send=$this->sendSMS($mobile,$mseeage);
       $send_status=0;
       if($send['status']==1){
          $send_status=1;
          //发送成功
          $data['status']=1;
          $data['msg']="短信发送成功";
          //$data['code']=$code;
           //存入session
          $s_data['mobile']=$mobile;
          $s_data['add_time']=$time;
          $s_data['code']=$code;
          $_SESSION['send']=$s_data;
       }


        //存入数据库
       /* $send_data=$s_data;
        $send_data['message']=$message;
        $send_data['status']=1;
        M("send_log")->add($send_data);*/

        echo json_encode($data);die;
    }
   //短信验证码验证 注册
   public function check_send_return(){
   	$data['status']=0;
   	    $send=$_SESSION['send'];
   	    if(empty($send)){
           $data['error']="短信验证码错误";
           return $data;
   	    }
   	    $time=time();
   	    $s_time=$time-$send['add_time'];
   	    if($s_time>60*10){//10分钟
          $data['error']="短信验证码已过期请重新获取";
          return $data;
   	    }
		$mobile=$_REQUEST['mobile'];
        if(empty($mobile) || $mobile!=$send['mobile']){
        	$data['error']="手机号码不匹配";
        	return $data;
        }
        $mobile_code=$_REQUEST['mobile_code'];
        if(empty($mobile_code) || $mobile_code!=$send['code']) {
			$data['error']="验证码错误";
        	return $data;
		}
		//unset($_SESSION['send']);
		//发送成功
        $data['status']=1;
        return $data;
    }
   //短信验证码验证
   public function check_send_ajax(){
   	$data['status']=0;
   	    $send=$_SESSION['send'];
   	    if(empty($send)){
           $data['msg']="短信验证码错误";
           echo json_encode($data);die;
   	    }
   	    $time=time();
   	    $s_time=$time-$send['add_time'];
   	    if($s_time>60*10){//10分钟
          $data['msg']="短信验证码已过期请重新获取";
          echo json_encode($data);die;
   	    }
		$mobile=$_REQUEST['mobile'];
        if(empty($mobile) || $mobile!=$send['mobile']){
        	$data['msg']="手机号码不匹配";
        	echo json_encode($data);die;
        }
        $code=$_REQUEST['mobile_code'];
        if(empty($code) || $code!=$send['code']) {
			$data['msg']="验证码错误";
        	echo json_encode($data);die;
		}
		//unset($_SESSION['send']);
		//发送成功
        $data['status']=1;
        $data['msg']="短信验证码验证通过";
        $data['code']=$code;

        echo json_encode($data);die;
    }
    //短信验证码验证
   public function check_send(){
   	$data['status']=0;
   	    $send=$_SESSION['send'];
   	    if(empty($send)){
           $data['msg']="短信验证码错误";
           return $data;
   	    }
   	    $time=time();
   	    $s_time=$time-$send['add_time'];
   	    if($s_time>60*10){//10分钟
          $data['msg']="短信验证码已过期请重新获取";
          return $data;
   	    }
		$mobile=$_REQUEST['mobile'];
        if(empty($mobile) || $mobile!=$send['mobile']){
        	$data['msg']="手机号码不匹配";
        	return $data;
        }
        $code=$_REQUEST['mobile_code'];
        if(empty($code) || $code!=$send['code']) {
			$data['msg']="验证码错误";
        	return $data;
		}
		//unset($_SESSION['send']);
		//发送成功
        $data['status']=1;
        $data['msg']="短信验证码验证通过";
        $data['code']=$code;

        return $data;
    }
   //短信发送
   public function send_sms(){
   	    $data['status']=0;
   	    $send=$_SESSION['send'];
   	    $time=time();
   	    $s_time=$time-$send['add_time'];
   	    if($s_time<60){
          $data['msg']="短信发送太频繁，请前后间隔60秒";
          echo json_encode($data);die;
   	    }
		$mobile=$_REQUEST['mobile'];
		$is_login=$_REQUEST['is_login'];
		
		if($is_login && $is_login == 'login_sms'){
			$count=M('member')->where(array('mobile'=>$mobile))->count();
			if($count <= 0){
				$data['msg']="您的手机未绑定";
				echo json_encode($data);die;
			}
		}
		
        if(empty($mobile) || check_phone($mobile)===false){
        	$data['msg']="手机号码错误";
        	echo json_encode($data);die;
        }
/*        if($_SESSION['verify'] != md5($_REQUEST['verify_code'])) {
			$data['msg']="验证码错误";
        	echo json_encode($data);die;
		}*/
		$code=rand(100000,999999);
	    $mseeage="您的短信验证码为：{$code}。";
       //$res=//发送短信
       $send=$this->sendSMS($mobile,$mseeage);
       $send_status=0;
       if($send['status']==1){
          $send_status=1;
          //发送成功
          $data['status']=1;
          $data['msg']="短信发送成功";
          //$data['code']=$code;
           //存入session
          $s_data['mobile']=$mobile;
          $s_data['add_time']=$time;
          $s_data['code']=$code;
          $_SESSION['send']=$s_data;
       }


        //存入数据库
       /* $send_data=$s_data;
        $send_data['message']=$message;
        $send_data['status']=1;
        M("send_log")->add($send_data);*/

        echo json_encode($data);die;
    }
    //短信发送
   public function send_sms_findpwd(){
        $data['status']=0;
        $send=$_SESSION['send'];
        $time=time();
        $s_time=$time-$send['add_time'];
        if($s_time<60){
          $data['msg']="短信发送太频繁，请前后间隔60秒";
          echo json_encode($data);die;
        }
        $mobile=$_REQUEST['mobile'];
        if(empty($mobile) || check_phone($mobile)===false){
          $data['msg']="手机号码错误";
          echo json_encode($data);die;
        }
        /*if($_SESSION['verify'] != md5($_REQUEST['verify_code'])) {
          $data['msg']="验证码错误";
              echo json_encode($data);die;
        }*/
         $count=M('member')->where(array('mobile'=>$mobile))->count();
        if(empty($count)){
          $data['msg']="手机号未注册";
          echo json_encode($data);die;
        }
      $code=rand(100000,999999);
      $mseeage="您的短信验证码为：{$code}。";
       //$res=//发送短信
       $send=$this->sendSMS($mobile,$mseeage);
       $send_status=0;
       if($send['status']==1){
          $send_status=1;
          //发送成功
          $data['status']=1;
          $data['msg']="短信发送成功";
          //$data['code']=$code;
           //存入session
          $s_data['mobile']=$mobile;
          $s_data['add_time']=$time;
          $s_data['code']=$code;
          $_SESSION['send']=$s_data;
       }else{
         $data['msg']=$send['error'];
       }

        //存入数据库
       /* $send_data=$s_data;
        $send_data['message']=$message;
        $send_data['status']=$send_status;
        M("send_log")->add($send_data);*/

        echo json_encode($data);die;
    }
      //短信发送
   public function send_sms_fastreg(){
        $data['status']=0;
        $send=$_SESSION['send'];
        $time=time();
        $s_time=$time-$send['add_time'];
        if($s_time<60){
          $data['msg']="短信发送太频繁，请前后间隔60秒";
          echo json_encode($data);die;
        }
        $mobile=$_REQUEST['mobile'];
        if(empty($mobile) || check_phone($mobile)===false){
          $data['msg']="手机号码错误";
          echo json_encode($data);die;
        }
        $count=M('member')->where(array('mobile'=>$mobile))->count();
        if($count>0){
          $data['msg']="手机号已注册";
          echo json_encode($data);die;
        }
        /*if($_SESSION['verify'] != md5($_REQUEST['verify_code'])) {
          $data['msg']="验证码错误";
              echo json_encode($data);die;
        }*/
      $code=rand(100000,999999);
      $mseeage="您的短信验证码为：{$code}。";
       //$res=//发送短信
       $send=$this->sendSMS($mobile,$mseeage);
       $send_status=0;
       if($send['status']==1){
          $send_status=1;
          //发送成功
          $data['status']=1;
          $data['msg']="短信发送成功";
          //$data['code']=$code;
           //存入session
          $s_data['mobile']=$mobile;
          $s_data['add_time']=$time;
          $s_data['code']=$code;
          $_SESSION['send']=$s_data;
       }
        //存入数据库
       /* $send_data=$s_data;
        $send_data['message']=$message;
        $send_data['status']=1;
        M("send_log")->add($send_data);*/

        echo json_encode($data);die;
    }
    //获取城市 信息
    public function get_city(){
    	$province=$_REQUEST['province'];//获取省份信息
    	$provinceid=$_REQUEST['provinceid'];//获取城市信息
    	$cityid=$_REQUEST['cityid'];//获取区县 信息
       //$citylist=array("province"=>1,"provinceid"=>0,"cityid"=>0
        $city=array('province'=>$province,'provinceid'=>$provinceid,'cityid'=>$cityid);
        $citylist=getcitylist($city);
        $return="";
        foreach ($citylist as $key => $value) {
        	$return=$citylist[$key];
        }
        unset($citylist);
        echo  json_encode($return);
    }
    //获取城市 信息
    public function get_city_return($data=array('province'=>1,'provinceid'=>0,'cityid'=>0)){
       //$citylist=array("province"=>1,"provinceid"=>0,"cityid"=>0
        $city=array();
        $citylist=getcitylist($data);
        return $citylist;
    }
    ///记录用户消费日志 消费记录
    /*$log_data['type']=3;//消费类型 1订单消费 2充值 3提现   4退款 5 收益 6认证消费
     *       $log_data['member_id']=$this->uid;
      *      $log_data['money']=$money;
      *      $log_data['status']=2;//状态 1收入 2支出
      *      $log_data['des']="申请提现扣除金额￥".$money;//消费介绍
      *     $log_data['add_time']=time();//消费时间
      */
    public function set_member_consume_record($data=array()){
       if(empty($data)){
        return false;
       }
        return M('member_consume_record')->add($data);
    }
     ///记录商户消费日志 消费记录  type 消费类型 1订单消费 2充值 3提现   4退款 5 收益 6认证消费
   /*   $log_data['type']=3;//消费类型 1订单消费 2充值 3提现    4退款 
     *         $log_data['shop_id']=session('afid');
     *         $log_data['money']=$money;
     *         $log_data['status']=2;//状态 1收入 2支出
     *         $log_data['des']="申请提现扣除金额￥".$money;//消费介绍
     *         $log_data['add_time']=time();//消费时间
     */
    public function set_shop_consume_record($data=array()){
       if(empty($data)){
        return false;
       }
       return M('shop_consume_record')->add($data);
    }
         ///记录用户积分日志 消费记录  type 消费类型 1获得 2消费
   /*   $log_data['type']=3;//消费类型 1获得 2消费
     *         $log_data['member_id']=$member_id;
     *         $log_data['integral']=$integral;
     *         $log_data['status']=2;//状态 1收入 2支出
     *         $log_data['des']="申请提现扣除金额￥".$money;//消费介绍
     *         $log_data['add_time']=time();//消费时间
     */
    public function set_member_integral($data=array()){
       if(empty($data)){
        return false;
       }
       return M('member_integral')->add($data);
    }
   ///加减余额 记录日志 $status 1 收入 2支持  money
    /*   $log_data['type']=3;//消费类型 1订单消费 2充值 3提现    4退款
      *         $log_data['shop_id']=session('afid');
      *         $log_data['money']=$money;
      *         $log_data['status']=2;//状态 1收入 2支出
      *         $log_data['des']="申请提现扣除金额￥".$money;//消费介绍
      *         $log_data['add_time']=time();//消费时间
      *         $money_give  系统赠送金额  $money 总金额 包括系统赠送金额
      */
    public function set_member_balance($member_id=0,$status=0,$all_money=0,$log=array(),$money_give=0){
        if(empty($member_id) || !in_array($status, array(1,2))|| empty($all_money) || $all_money<0 || empty($log)){
            return false;
        }
        $consume_record_model=M('member_consume_record');
        $member_detail_model=M('member_detail');
        $field="member_id,points,balance,balance_give";
        $where['member_id']=$member_id;
        $memberinfo=$member_detail_model->Where($where)->field($field)->find();
        if(empty($memberinfo)){
            return false;
        }
        $money=$all_money-$money_give;///实际金额等于 总金额减去系统赠送金额
        if($status==1){//收入
            $m_s_data['balance']=$memberinfo['balance']+$money;
            $m_s_data['balance_give']=$memberinfo['balance_give']+$money_give;
        }else{
            $m_s_data['balance']=$memberinfo['balance']-$money;
            $m_s_data['balance_give']=$memberinfo['balance_give']-$money_give;
        }
        $log['status']=$status;
        $log['add_time']=time();
        $log['member_id']=$member_id;
        $log['money']=$all_money;///总金额
        $member_detail_model->startTrans();//开起事务
        $consume_record_model->startTrans();//开起事务

        $m_res=$member_detail_model->where($where)->save($m_s_data);
        $c_res=$consume_record_model->add($log);
        if($c_res!==false && $m_res!==false){
            $consume_record_model->commit();//提交事务
            $member_detail_model->commit();//提交事务
            if ($status==1) {
              $send_data['money'] = $money;
              $this->weixin_send($member_id, $send_data, 4);
            }
            return true;
        }else{
            $member_detail_model->rollback();//回滚事务
            $consume_record_model->rollback();//回滚事务

            return false;
        }
    }
 ///加减积分 记录日志 $status 1 收入 2支持  integral 积分
   /*   $log_data['type']=3;//消费类型 1订单消费 2充值 3提现    4退款 
     *         $log_data['shop_id']=session('afid');
     *         $log_data['money']=$money;
     *         $log_data['status']=2;//状态 1收入 2支出
     *         $log_data['des']="申请提现扣除金额￥".$money;//消费介绍
     *         $log_data['add_time']=time();//消费时间
     */
    public function set_member_points($member_id=0,$status=0,$integral=0,$log=array()){
       if(empty($member_id) || !in_array($status, array(1,2,15))|| empty($integral) || $integral<0 || empty($log)){
         return false;
        }

        $member_integral=M('member_integral');
        $member_detail_model=M('member_detail');
        $consume_record_model=M('member_consume_record');
        $field="member_id,points,balance";
        $where['member_id']=$member_id;
        $memberinfo=$member_detail_model->Where($where)->field($field)->find();
        if(empty($memberinfo)){
          return false;
        }
        if($status==1){//收入
            $m_s_data['points']=$memberinfo['points']+$integral;
        }else{
            $m_s_data['points']=$memberinfo['points']-$integral;
        }
        $log['status']=$status;
        $log['type']=$log['type']?$log['type']:$status;
        $log['add_time']=time();
        $log['member_id']=$member_id;
        $log['integral']=$integral;
        $member_detail_model->startTrans();//开起事务
        $member_integral->startTrans();//开起事务
        $consume_record_model->startTrans();//开起事务

        $m_res=$member_detail_model->where($where)->save($m_s_data);
        $c_res=$member_integral->add($log);
        $r_res=$consume_record_model->add($log);

        if($c_res!==false && $m_res!==false ){
            $consume_record_model->commit();//提交事务
            $member_integral->commit();//提交事务
            $member_detail_model->commit();//提交事务
            return true;
        }else{
            $member_detail_model->rollback();//回滚事务
            $member_integral->rollback();//回滚事务
            $consume_record_model->rollback();//回滚事务
            return false;
        }
    }

    ///加减vip积分 记录日志 $status //状态 1获得 2消费（减少）
    //$money 【获得】消费金额 【减少 】1.降级 减少的积分
    /*   $log_data['type']=1;//【获得】1 推荐vip升级 2 订单消费 【减少】1 降级 2 订单退货
      *         $log_data['$integral']=$integral;
      *         $log_data['status']=2;//状态 1收入 2支出
      *         $log_data['des']="推荐人升级vip获得".$integral;//消费介绍
      *         $log_data['add_time']=time();//消费时间
      */
    public function set_member_vip_integral($member_id=0,$status=0,$money=0,$log=array()){
        return   D('Membervip')->set_member_vip_integral($member_id,$status,$money,$log);
    }
//定时发送
/*
$time = '2010-05-27 12:11';
$res = sendSMS($uid,$pwd,$mobile,$content,$time);
echo $res;
*/
function sendSMS($mobile,$content,$time='',$mid='')
{
    return 112233;///本网站目前没有设置短信发送功能
  $sendUrl = 'http://v.juhe.cn/sms/send'; //短信接口的URL
 
$smsConf = array(
    'key'   => 'bc250fc27b164c08afcf00504d575320', //您申请的APPKEY
    'mobile'    => $mobile, //接受短信的用户手机号码
    'tpl_id'    => '17956', //您申请的短信模板ID，根据实际情况修改
    'tpl_value' =>'#code#='.$content.'&#company#='.$content //您设置的模板变量，根据实际情况修改
);
$smsConf['tpl_value']=urlencode($smsConf['tpl_value']);
  $s_data['mobile']=$mobile;
    $s_data['add_time']=time();
    $send_data=$s_data;
    $send_data['message']=$content;
$content = $this->juhecurl($sendUrl,$smsConf,1); //请求发送短信

if( $content)
  {
	 $result = json_decode($content,true);
    $error_code = $result['error_code'];
    if($error_code == 0){
        //状态为0，说明短信发送成功
        //echo "短信发送成功,短信ID：".$result['result']['sid'];
		$return_data['status']=1;
		$send_data['status']=1;
		M("send_log")->add($send_data);
    }else{
        //状态非0，说明失败
		$return_data['error']="短信发送失败(".$error_code.")：".$result['reason'];
		$return_data['status']=0;
       // echo "短信发送失败(".$error_code.")：".$result['reason'];
    }
    return $return_data;
  }
  else 
  {
    //return "发送失败! 状态：".$re;
    $return_data['error']="短信发送失败，请稍后再试";
     $send_data['status']=0;
     //M("send_log")->add($send_data);
    return $return_data;
  }
} 
/**
 * 请求接口返回内容
 * @param  string $url [请求的URL地址]
 * @param  string $params [请求的参数]
 * @param  int $ipost [是否采用POST形式]
 * @return  string
 */
function juhecurl($url,$params=false,$ispost=0){
    $httpInfo = array();
    $ch = curl_init();
    curl_setopt( $ch, CURLOPT_HTTP_VERSION , CURL_HTTP_VERSION_1_1 );
    curl_setopt( $ch, CURLOPT_USERAGENT , 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.22 (KHTML, like Gecko) Chrome/25.0.1364.172 Safari/537.22' );
    curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT , 30 );
    curl_setopt( $ch, CURLOPT_TIMEOUT , 30);
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER , true );
    if( $ispost )
    {
        curl_setopt( $ch , CURLOPT_POST , true );
        curl_setopt( $ch , CURLOPT_POSTFIELDS , $params );
        curl_setopt( $ch , CURLOPT_URL , $url );
    }
    else
    {
        if($params){
            curl_setopt( $ch , CURLOPT_URL , $url.'?'.$params );
        }else{
            curl_setopt( $ch , CURLOPT_URL , $url);
        }
    }
    $response = curl_exec( $ch );
    if ($response === FALSE) {
        //echo "cURL Error: " . curl_error($ch);
        return false;
    }
    $httpCode = curl_getinfo( $ch , CURLINFO_HTTP_CODE );
    $httpInfo = array_merge( $httpInfo , curl_getinfo( $ch ) );
    curl_close( $ch );
    return $response;
}   
public function postSMS($url,$data='')
{
  $post='';
  $row = parse_url($url);
  $host = $row['host'];
  $port = $row['port'] ? $row['port']:80;
  $file = $row['path'];
  while (list($k,$v) = each($data)) 
  {
    $post .= rawurlencode($k)."=".rawurlencode($v)."&"; //转URL标准码
  }
  $post = substr( $post , 0 , -1 );
  $len = strlen($post);
  $fp = @fsockopen( $host ,$port, $errno, $errstr, 10);
  if (!$fp) {
    return "$errstr ($errno)\n";
  } else {
    $receive = '';
    $out = "POST $file HTTP/1.0\r\n";
    $out .= "Host: $host\r\n";
    $out .= "Content-type: application/x-www-form-urlencoded\r\n";
    $out .= "Connection: Close\r\n";
    $out .= "Content-Length: $len\r\n\r\n";
    $out .= $post;    
    fwrite($fp, $out);
    while (!feof($fp)) {
      $receive .= fgets($fp, 128);
    }
    fclose($fp);
    $receive = explode("\r\n\r\n",$receive);
    unset($receive[0]);
    return implode("",$receive);
  }
}
//给用户微信发送消息
/*
  $touser 接收者的member_id
  $send_data 发送的信息数据
  $type 发送信息类型 {
    1、店主收到交易成功消息 (1)$send_data['goods_msg']商品名称 (2)$send_data['member_id']买方的member_id
    2、订单状态更新 (1)$send_data['order_sn']订单编号
    3、新到访客 (1)$send_data['customer_id']访客的member_id
    4、余额增加 (1)$send_data['money']余额增加量
  }
*/
function weixin_send($touser, $send_data, $type)
{
    $touser = M("member")->where(array("id"=>$touser))->getField("openid");
    if (empty($touser)) {
      return false;
    }
    //获得token
    $access_token = $this->get_access_token();
    if ($type == 1) {
        $template_id = "Gvn5HGoP6yRUAvTB-kVOgWvh4bc4F_3j6rx7V_Mb6y4";
        //1.$send_data['goods_msg']商品名称 2.$send_data['member_id']买方用户的member_id
        if (empty($send_data['goods_msg']) || empty($send_data['member_id'])) {
          return false;
        }
        //获取买家微信昵称
        $send_data['customer'] = M("member")->where(array("id"=>$send_data['member_id']))->getField("openid");
        $url =  "https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$access_token."&openid=".$send_data['customer']."&lang=zh_CN"; 
        $data = file_get_contents($url);
        $data = json_decode($data);
        $send_data['customer'] = $data->nickname;
        if (empty($send_data['customer'])) {
          return false;
        }
        $end_url = "http://".$_SERVER['HTTP_HOST']."/wap/member/store_order";
        $data = array(
          'first'=>array('value'=>"wow~好消息！您店铺有交易成功的商品订单！",'color'=>"#743A3A"),
          'keyword1'=>array('value'=>$send_data['goods_msg'],'color'=>'#000033'),
          'keyword2'=>array('value'=>$send_data['customer'],'color'=>'#000033'),
          'remark'=>array('value'=>'待买家确认收货后，店铺奖金和积分随即到帐。','color'=>'#222222')
        );
    }elseif ($type == 2) {
        $template_id = "AbSQY4ZLkHZk8WCxsOJrgpE_-OeR1yjI-AgdTwQcnI8";
        //1.$send_data['order_sn']订单编号
        if (empty($send_data['order_sn'])) {
          return false;
        }
        //获取买家微信名称
        $url =  "https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$access_token."&openid=".$touser."&lang=zh_CN"; 
        $data = file_get_contents($url);
        $data = json_decode($data);
        $send_data['customer'] = $data->nickname;
        if (empty($send_data['customer'])) {
          return false;
        }
        //根据订单id获取快递单号及快递最新动态
        $order_info = M("g_order_info")->where(array("order_sn"=>$send_data['order_sn']))->find();
        $send_data['invoice_no'] = $order_info['invoice_no'];
        $express_query = $this->express_query($order_info['order_id'],'','','json');
        //有物流信息
        if ($express_query['status'] == 1) {
          $send_data['remark'] = "快递单号：".$send_data['invoice_no']."\n物流信息：".$order_info['express_name']."\n点击“详情”查看完整物流信息";
        }else{
          if(empty($order_info['invoice_no']) || empty($order_info['express_code'])){
            $send_data['remark'] = "快递单号：".$send_data['invoice_no']."\n物流信息：".$order_info['express_name']."\n暂无物流详细信息";
          }else{
            $send_data['remark'] = "暂无物流信息";
          }
        }
        $end_url = "http://".$_SERVER['HTTP_HOST']."/wap/goodsorder/order_detail/id/".$order_info['order_id'];
        //判断订单状态
        // 支付状态；0，未付款；1，付款中 ；2，已付款
        $pay_status = array('0' => '未付款', '1' => '已支付', '2' => '已付款', '100'=>'等待买家付款', '101'=>'已支付，对账中', '102'=>'支付已完成');
        //商品配送情况，0，未发货； 1，已发货；2，已收货；3，备货中
        $shipping_status = array('0' => '未发货', '1' => '已发货', '2' => '已完成', '3' => '备货中');
        //订单状态。0，未确认；1，已确 认；2，已取消；3，无效；4，退货；
        $order_status = array('0' => '未确认', '1' => '已确认', '2' => '已取消', '3' => '无效', '4' => '退货');
        if ($order_info['pay_status'] == 2) {
          if ($order_info['offline'] == 1) {
            $send_data['order_status'] = "货到付款";
          }elseif ($order_info['offline'] != 1 && $order_info['shipping_status'] != 0) {
            $send_data['order_status'] = $shipping_status[$order_info['shipping_status']];
          }else{
            $send_data['order_status'] = $pay_status[$order_info['pay_status']];
          }
        }elseif ($order_info['order_status'] == 2) {
          $send_data['order_status'] = $order_status[$order_info['order_status']];
        }else{
          $send_data['order_status'] = $pay_status[$order_info['pay_status']];
        }
        $data = array(
          'first'=>array('value'=>"尊敬的".$send_data['customer']."：",'color'=>"#743A3A"),
          'OrderSn'=>array('value'=>$send_data['order_sn'],'color'=>'#000033'),
          'OrderStatus'=>array('value'=>$send_data['order_status'],'color'=>'#000033'),
          'remark'=>array('value'=>$send_data['remark'],'color'=>'#222222')
        );
    }elseif ($type == 3) {
        $template_id = "jqhODPWwvBlO8MoWlDGrUIZDCLibd_C9EUGV2R8aXCE";
        //1.$send_data['customer_id']访客的member_id
        if (empty($send_data['customer_id'])) {
            return false;
        }
        //获取初次访客微信昵称
        $member_data = M("member")->where(array("id"=>$send_data['customer_id']))->find();
        $url =  "https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$access_token."&openid=".$member_data['openid']."&lang=zh_CN"; 
        $data = file_get_contents($url);
        $data = json_decode($data);
        $send_data['customer'] = $data->nickname;
        if (empty($send_data['customer'])) {
            return false; 
        }
        $send_data['time'] = date("Y-m-d H:i", time());
        $data = array(
          'first'=>array('value'=>"您有新的访客到访",'color'=>"#743A3A"),
          'keyword1'=>array('value'=>$send_data['customer'],'color'=>'#000033'),
          'keyword2'=>array('value'=>$member_data['mobile'],'color'=>'#000033'),
          'keyword3'=>array('value'=>$send_data['time'],'color'=>'#000033'),
          'remark'=>array('value'=>'感谢您的使用','color'=>'#222222')
        );
    }elseif ($type == 4) {
        $template_id = "DGqQGid09pwOIFwwRZC6h-wKxjiBV9Myr1fOJ7rkicM";
        //1.$send_data['money']余额增加量
        if (empty($send_data['money'])) {
            return false;
        }
        $send_data['time'] = date("Y-m-d H:i", time());
        $data = array(
          'first'=>array('value'=>"牛！".$send_data['money']."元进入了钱包，网友太给力",'color'=>"#743A3A"),
          'keyword1'=>array('value'=>$send_data['money']."元",'color'=>'#000033'),
          'keyword2'=>array('value'=>$send_data['time'],'color'=>'#000033'),
          'remark'=>array('value'=>'将自己的店铺分享给他人，网友越多，赚钱越多！','color'=>'#222222')
        );
    }

    //判断发送消息里是否有详情页面
    if (empty($end_url)) {
        $template = array(
          "template_id" => $template_id,
          "touser" => $touser,
          "data" => $data
        );
    }else{
        $template = array(
          "url" => $end_url, 
          "template_id" => $template_id,
          "touser" => $touser,
          "data" => $data
        );
    }
    $json_template = json_encode($template);
    $url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=" . $access_token;
    $dataRes = $this->request_post($url, urldecode($json_template));
    if ($dataRes['errcode'] == 0) {
        return true;
    } else {
        return false;
    }
}
public function get_access_token(){
  $access_token=S('access_token');
  $test = "https://api.weixin.qq.com/cgi-bin/getcallbackip?access_token=".$access_token;
  $result = json_decode(file_get_contents($test));
  if(empty($access_token) || $result->errcode == 40001){
    $appid=C('APPID');
    $appsecret=C('APPSECRET');
    $url="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$appid}&secret={$appsecret}";
    //$str=file_get_contents($url);
    $str=$this->curl_https($url);
    $str=json_decode($str);

    if(empty($str->errcode)){
      $access_token=$str->access_token;//获取到的凭证
      $expires_in=$str->expires_in;//凭证有效时间，单位：秒
      $expires_in=$expires_in-2400;
      if($expires_in<=0){
        $expires_in=0;
      }
      S('access_token',$access_token,$expires_in);
    }
  }
  return $access_token;
}
public function curl_https($url=''){
  if (empty($url)) {
    return false;
  }
  $curl = curl_init();

  curl_setopt($curl, CURLOPT_URL, $url);
  curl_setopt($curl, CURLOPT_HEADER, 1);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);//这个是重点。
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); // https请求 不验证证书和hosts
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
  $data = curl_exec($curl);
  curl_close($curl);
   $receive = explode("\r\n\r\n",$data);
  unset($receive[0]);
  return implode("",$receive);
}
//发送POST请求
function request_post($url = '', $param = '')
{
    if (empty($url) || empty($param)) {
        return false;
    }
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    $data = curl_exec($ch);
    return $data;
}
//定时发送
/*
* 订单生成 发送短信
*/
function order_sendSMS($order_sn=null,$money=null,$pay_type="余额支付")
{
 if(empty($order_sn) || empty($money)){
    return false;
 }
 
  $content="FG峰购产生一条新订单，订单号：".$order_sn."，金额￥".$money."，时间".date("Y-m-d H:i").$pay_type;
  $mobile=C('SENDMOBILE');
  if($mobile){
      $re= $this->sendSMS($mobile,$content);      //POST方式提交
      $return_data['status']=0;
      if( $re['status'] == '1' )
      {
        $datas['mobile']=$mobile;
        $datas['content']=$content;
        $datas=json_encode($datas);
        $datas.=json_encode($re);
        Log::write("短信发送成功".$datas);
      }
      else 
      {
        $datas=json_encode($re);
        Log::write("短信发送失败:".$datas);
      }
  }
 
} 
/*
* 提现申请通知
*/
function apply_sendSMS($mobile=null)
{
  $mobile=$mobile?$mobile:$_GET['mobile'];
 if(empty($mobile)){
    return false;
 }
  $content="你的提现已办理，请注意查收，谢谢！客服电话023-86716101。";
  $data['mobile']=$mobile;
  $data['content']=$content;
  if($mobile){
      $re= $this->sendSMS($mobile,$content);      //POST方式提交
      $return_data['status']=0;
      if( $re['status'] == '1' )
      {
         $datas=json_encode($data);
         echo $datas;
          Log::write("短信发送成功:".$datas);
      }
      else 
      {
         $datas=json_encode($data);
         $datas.=json_encode($re);
         echo $datas;
         Log::write("短信发送失败:".$datas);
      }
  }
 
} 

   /**
     * 取得二维码图像信息
     * @static
     * @access public
     * @param string $data 二维码内容
     * @param string $water_url  水印图片
     * @param string $level  'L','M','Q','H'
     * @param string $size   大小  1到10
     * @return mixed
     */
function get_qrcode($data=null,$water_url,$level='H',$size='10')
{
      import('ORG.Util.Phpqrcode');
       if(empty($water_url))
        $water_url='./Public/wap/img/codelogo_m.png';

      if($size<6)
         $water_url='./Public/wap/img/codelogo_m.png';

      $imgurl=Phpqrcode::set_code($data,$water_url,$level,$size);
      return  $imgurl;
} 

	/**
	 * 取得二维码图像信息
	 * @static
	 * @access public
	 * @param string $data 二维码内容
	 * @param string $water_url  水印图片
	 * @param string $level  'L','M','Q','H'
	 * @param string $size   大小  1到10
	 * @return mixed
	 */
	function get_qrcode_api($data=null)
	{     header('Content-type: image/jpg');
	//$host="http://".$_SERVER["HTTP_HOST"];
	  $data=$data?$data:$_GET['data'];
	  $imgurl=$this->get_qrcode($data);
	  if(!empty($imgurl)){
		 $imgurl='.'.$imgurl;
	  }
	if (!file_exists($imgurl)){//文件不存在
		$return_url ='./Uploads/default'.$filename;
		if (!file_exists($return_url)){//文件不存在
			$return_url ='./Uploads/codelogo.png';
	  }
	}else{
		$return_url=$imgurl;
	}
	//var_dump($return_url);die;
	$return_url = file_get_contents($return_url);  //读取文件
	echo  $return_url;
	}

    /**
     * @return mixed
     */


    //通过二维码推荐时获得推荐者会员卡号
    /*public function getRecommendCode()
    {
        if ($_GET['member_card']){
        $recommend_code=$_GET['member_card'];
        $_SESSION['member']['recommend_code']=$recommend_code;
      }
    }*/


    //自定义成员属性
	
	/*
	 * 查询物流信息
	 * @param int $order_id 订单ID 有订单ID的都使用订单ID查询
	 * @param string $express_no 运单号
	 * @param string $express_code 快递公司代码
	 * @param string $return_type 返回数据格式  html：HTML格式 json：JSON格式
	 * @return array $data status:0失败 1成功  msg:查询提示信息  result：查询结果
	 */
	function express_query($order_id=0, $express_no='', $express_code='', $return_type='html'){
		$time = time();
		$data = array();
		$data['status'] = 0;
		$data['result'] = '';
		if($order_id){
			if(empty($express_no) || empty($express_code)){
				$order_info = M('g_order_info')->where(array('order_id'=>$order_id))->find();
				if($order_info){
					$express_no = $order_info['invoice_no'];
					$express_code = $order_info['express_code'];
				}
			}
		}
		if(!($express_no && $express_code)){
			$data['msg'] = '运单信息不完整，查询失败！';
			return $data;
		}

		//查询数据库缓存
		$cache = M('express_query_cache')->where(array('express_no'=>$express_no))->find();
		if($cache && isset($cache['state']) && $cache['state'] == 3){
			$result_array = json_decode($cache['info'], true);
		}elseif($cache && isset($cache['u_time']) && (($cache['u_time'] + 7200) > $time)){
			$result_array = json_decode($cache['info'], true);
		}else{
			//快递鸟 查询
			$requestData = "{'OrderCode':'','ShipperCode':'".$express_code."','LogisticCode':'".$express_no."'}";
			$datas = array(
				'EBusinessID' => C('EXPRESS.EBusinessID'),
				'RequestType' => '1002',
				'RequestData' => urlencode($requestData) ,
				'DataType' => '2',
			);
			
			$datas['DataSign'] = urlencode(base64_encode(md5($requestData.C('EXPRESS.AppKey'))));
			$request_result = $this->sendPost(C('EXPRESS.ReqURL'), $datas);
			if(!$request_result){
				$data['msg'] = '查询失败！';
				return $data;
			}
			$result_array = json_decode($request_result, true);
			// if(!$result_array['Success']){
				// $data['msg'] = $result_array['Reason'];
				// return $data;
			// }
			
			if($cache){
				// 更新数据库
				$cache_data = array(
					'info'			=> $request_result,
					'state'			=> $result_array['State'],
					'u_time'		=> $time
				);
				M('express_query_cache')->where(array('id'=>$cache['id']))->save($cache_data);
			}else{
				// 存入数据库
				$cache_data = array(
					'order_id'		=> $order_id ? $order_id : 0,
					'express_no'	=> $express_no,
					'express_code'	=> $express_code,
					'info'			=> $request_result,
					'state'			=> $result_array['State'],
					'c_time'		=> $time,
					'u_time'		=> $time
				);
				M('express_query_cache')->add($cache_data);
			}
		}
				
		//更新订单物流状态
		if($order_id > 0 && isset($result_array['State']) && $result_array['State'] > 0){
			$sign_for_array = array(3=>1, 2=>2, 4=>3); //已签收, 在途中, 问题件
			M('g_order_info')->where(array('order_id'=>$order_id))->save(array('is_sign_for'=>$sign_for_array[$result_array['State']]));
		}
		
		if($result_array['Traces']){
			if($return_type == 'html'){
				$result = '<div id="express_info" class="express newbox2">';
				foreach($result_array['Traces'] as $k=>$v){
					$result .= '<div class="list"><i class="iconfont road">&#xe659</i><i class="iconfont end">&#xe68c</i><span class="info">'.$v['AcceptStation'].'&nbsp;&nbsp;'.$v['Remark'].'</span><span class="time num"><i class="iconfont">&#xe65d</i>'.$v['AcceptTime'].'</span></div>';
				}
				$result .= '</div>';
			}else if($return_type == 'json'){
				$result = array();
				foreach($result_array['Traces'] as $k=>$v){
					$result[]['time'] = $v['AcceptTime'];
					$result[]['info'] = $v['AcceptStation'].'&nbsp;&nbsp;'.$v['Remark'];
				}
				$result = json_encode($result);
			}
		}else{
			$data['msg'] = '暂无物流信息！';
			$data['msg_test'] = $result_array['Reason'];
			return $data;
		}
		unset($result_array);
		
		$data['status'] = 1;
		$data['result'] = $result;
		return $data;
	}
	 
	/**
	 *  post提交数据 
	 * @param  string $url 请求Url
	 * @param  array $datas 提交的数据 
	 * @return url响应返回的html
	 */
	private function sendPost($url, $datas) {
		$temps = array();
		foreach ($datas as $key => $value) {
			$temps[] = sprintf('%s=%s', $key, $value);		
		}	
		$post_data = implode('&', $temps);
		$url_info = parse_url($url);
		if($url_info['port']==''){
			$url_info['port']=80;
		}
		$httpheader = "POST " . $url_info['path'] . " HTTP/1.0\r\n";
		$httpheader.= "Host:" . $url_info['host'] . "\r\n";
		$httpheader.= "Content-Type:application/x-www-form-urlencoded\r\n";
		$httpheader.= "Content-Length:" . strlen($post_data) . "\r\n";
		$httpheader.= "Connection:close\r\n\r\n";
		$httpheader.= $post_data;
		$fd = fsockopen($url_info['host'], $url_info['port']);
		fwrite($fd, $httpheader);
		$gets = "";
		$headerFlag = true;
		while (!feof($fd)) {
			if (($header = @fgets($fd)) && ($header == "\r\n" || $header == "\n")) {
				break;
			}
		}
		while (!feof($fd)) {
			$gets.= fread($fd, 128);
		}
		fclose($fd);

		return $gets;
	}
	//获取订单配送状态的列表
	public function status_before_deliver_list($order_info){
		$status_remark_arr=array();
		if($order_info['pay_status'] == 2){//已付款
			if($order_info['order_status'] == 1){//点单已确认
				$confirm_time = $order_info['confirm_time'];//订单确认时间
				//假期
				$rest = array('0501','0502','0503','0504','1001','1002','1003','1004','1005','1006','1007'  );
				$closing_time=mktime(18,0,0,date('m',$confirm_time),date('d',$confirm_time),date('Y',$confirm_time));//确认点单当天下班时间
				$time_ch_arr =array(0,2,8,10);
				$status_remarks = array('您的订单已确认，信息接收中','信息已接收，备货中','您的产品正在进行打包~','正在等待快递揽件','您的产品已发货，请耐心等待包裹的到来');
				if($confirm_time > $closing_time){//如果确认时间 大于下班时间 则之后的时间假设为 下班前半小时
					$confirm_time = $closing_time - 3600*0.5;
				}
				$time_ch1 = $closing_time - $confirm_time;//从确认订单到下班的——时间
				foreach ($time_ch_arr as $k =>$v){
					if($confirm_time+$v*3600 < $closing_time || $k == 0){
						$status_time = $confirm_time+$v*3600 ;
					}else{
						$starting_time = mktime(9,30,0,date('m',$confirm_time),date('d',$confirm_time)+1,date('Y',$confirm_time));//第二天上班班时间
						$status_time = $starting_time +($v*3600-$time_ch1);//如果第二为工作日，状态的时间
						$_this_date = date('md',$starting_time);
						$_this_week = date('w',$starting_time);
						while (in_array($_this_date,$rest) || in_array($_this_week,array('0','6'))){
							$status_time += 24*3600;
							$_this_date = date('md',$status_time);
							$_this_week = date('w',$status_time);
						}
					}
					if(time() > $status_time){
						$status_remark_arr[$k] = array($status_remarks[$k],$status_time);
					}
				}
				if(in_array($order_info['shipping_status'],array(1,2))){
					$status_remark_arr[4] = array($status_remarks[4],$order_info['shipping_time']);
				}
			}
			if($order_info['to_buyer']){
				$status_remark_arr[5] = array($order_info['to_buyer']);
			}
		}
		return $status_remark_arr;
	}


}


