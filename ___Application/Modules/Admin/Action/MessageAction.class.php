<?php
/****
** 留言
** 
**/
class MessageAction extends AuthAction{

    //会员列表
	public function index(){
        if($_GET['title']){
			$where['title']=array('like',$_GET['title'].'%');
		}
		if($_GET['phone']){
			$where['phone']=array('like',$_GET['phone'].'%');
		}
		
		if(isset($_GET['status'])){
			if($_GET['status']!='all'){
				$where['status']=$_GET['status'];
			}
		}else{
			$_GET['status']='all';
		}
		
		if($_GET['start_time']){
			$start_time=strtotime($_GET['start_time']);
		}
		if($_GET['end_time']){
			$end_time=strtotime($_GET['end_time']);
		}
		if($end_time>0 && $start_time>0){
			$where['add_time']=array('between',array($start_time,$end_time));
		}else{
			if($start_time>0){
				$where['add_time']=array('egt',$start_time);
			}
			if($end_time>0){
				$where['add_time']=array('elt',$end_time);
			}
		}
		if(isset($_GET['order'])){
			$order=$_GET['order']." desc";
		}
		$menulist=D("Common")->getPageList('message',$where);
		$this->assign("list",$menulist);
//订单状态：0(已取消)10(默认):未付款;20:已付款;30:已发货;40:已收货; 
		$status=array('0'=>'待处理','1'=>'已处理');
		$this->status=$status;
		$this->display();
		//$this->display();
	}
	//信息 详情
	public function message_detail(){
		if($_POST){
			if(empty($_POST['id'])){
                $this->error("系统错误");
			}
			if(empty($_POST['remark'])){
                $this->error("请输入处理意见");
			}
			$_POST["status"]=1;
			$res = M("message")->where("id=".$_POST["id"])->save($_POST);
			if($res!==false){
				$this->success("操作成功",U("Admin/Message/index"));
			}else{
				$this->error("操作失败");
			}
		}else{
			$menuwhere["id"] =  array("eq",$_GET["id"]);
			$data = M("message")->where($menuwhere)->find();
			$status=array('0'=>'待处理','1'=>'已处理');
			$this->status=$status;
			$title="留言信息";
			$this->assign("data",$data);
			
			$this->assign("msgtitle",$title);
			$this->display();
		}
	}

	
}
?>