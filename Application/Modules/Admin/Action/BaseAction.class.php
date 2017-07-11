<?php

class BaseAction extends CommonAction {
	public function __construct(){
		parent::__construct();
		if(empty($_SESSION["auid"])){
			if($_GET["_URL_"][2]!="login" && $_GET["_URL_"][2]!="checkLogin" && $_GET["_URL_"][2]!="login_lock"){
				$this->redirect("/Admin/Index/login");
				exit();
			}
		}
	}

    public function check_login(){
		if(empty($_SESSION["auid"])){
			$this->redirect(U("Admin/Index/login"));
		    exit();
		}
    }

}