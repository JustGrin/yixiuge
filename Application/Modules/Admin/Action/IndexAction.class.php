<?php
// 后台 首页
class IndexAction extends AuthAction {

	public function index(){
         //echo U("Admin/Index/index");die;
		if($_SESSION["auid"]){
			//$pre = C("DB_PREFIX");//获取表前缀
			
			$this->display();
		}else{
			$this->redirect("login");
		}
    }

    public function login_lock(){
         //echo U("Admin/Index/index");die;
    	unset($_SESSION);
		$this->display();
    }

	public function login(){
		//echo GROUP_NAME,MODULE_NAME,ACTION_NAME;
		$this->display();		
	}

	public function checkLogin(){
		$username = $_POST["username"];
		$pass = md5($_POST["userpwd"]);
		//$verify = $_POST["verify_code"];
		//if($_SESSION['verify'] == md5($_POST['verify_code'])) {
			$where["account"] = array("eq",$username);
			$result = M("admin")->where($where)->find();
			//echo M("admin")->getLastSql();die;
			if($result){
				if($result["password"] == $pass){
					if($result["status"] == 1){
					session("auid",$result["id"]);
					session("afid",$result["fid"]);
					session("account",$result["account"]);
					session("name",$result["name"]);
					if($result["fid"]==$result["id"] && $result["id"]==1){
						session("topadmin",1);//超级 管理员
					}elseif($result["fid"]==1){
						session("topadmin",2);//普通管理员
					}else{
						session("topadmin",0);//商家
					}
					$data["status"] = 1;
					$data["info"] = "登录中";
					}else{
						$data["status"] = 0;
						$data["info"] = "帐号已被锁定";
					}
				}else{
					$data["status"] = 0;
					$data["info"] = "用户名或密码错误";
				}
			}else{
				$data["status"] = 0;
				$data["info"] = "用户名不存在";
			}
		/*
		}else{
			$data["status"] = 0;
			$data["info"] = "验证码错误";
		}
		*/
		if($data['status']==1){
			$url=U("Admin/Index/index");
			if(!empty($_POST['jump_url'])){
             $url=$_POST['jump_url'];
			}
           $this->success("登录成功",$url);
		}else{
          $this->error($data["info"]);
		}
		//$data["info"] = urlencode($data["info"]);
		//echo urldecode(json_encode($data));
	}

	
	public function loginout(){
		unset($_SESSION);
		$this->redirect("login");
	}

	

	public function main(){
		$this->display();
	}

	//我信息编辑 密码修改
	public function edit_pwd(){
		if($_POST){
			if(!empty($_POST["newapass"])){
			    $_POST["password"] = md5($_POST["newapass"]);
			}
			if($_POST["check_pwd"]){
				if(empty($_POST["oldpass"])){
			       $this->error("请输入原密码");
			     }
			     if(empty($_POST["newapass"])){
			       $this->error("请输入新密码");
			     }
			     if($_POST["newapass"]!=$_POST["newapass2"]){
			       $this->error("两次输入新密码不一致");
			     }
				$res = M("admin")->where("id=".$_SESSION["auid"])->find();
				if($res['password']!=md5($_POST["oldpass"])){
                   $this->error("原密码错误");
				}
			}
			
			$res = M("admin")->where("id=".$_SESSION["auid"])->save($_POST);
			if($res!==false){
				$this->success("操作成功");
			}else{
				$this->error("操作失败");
			}
		}else{
			$adminmsg = M("admin")->where("id=".$_SESSION["auid"])->find();
			$titles="我的信息";
			
			//$grouplist = M("admin_group")->where($where)->select();
			
			$this->assign("data",$adminmsg);
			$this->assign("msgtitle",$titles);
			$this->assign("grouplist",$grouplist);
			$this->display();
		}
	}

	public function setmap(){
		
		$this->display();
	}

}