<?php
/****
 ** 活动列表
 **
 **/
class ActivityAction extends AuthAction
{
	/**
	 * @return config
	 */
	public function index()
	{
		$where['is_del'] = 0;
		$activity_list= M('activity')->where($where)->select();
		$this->list = $activity_list;
		$this->display();
	}

	public function  activity_edit(){
		if($_POST){
			if(!empty($_POST["id"])){
				$_POST['start_time'] = strtotime($_POST['start_time']);
				$_POST['end_time'] = strtotime($_POST['end_time']);
				$res = M("activity")->where("id=".$_POST["id"])->save($_POST);
			}else{
				$res = M("activity")->add($_POST);
			}
			if($res!==false){
				$this->success("操作成功",U('Admin/Activity/index'));
			}else{
				$this->error("操作失败");
			}
		}else{
			if($_GET["id"]){
				$gwhere["id"] =  array("eq",$_GET["id"]);
				$data = M("activity")->where($gwhere)->find();
				$title="编辑活动";
			}else{
				$title="新增活动";
			}
			$this->assign("data",$data);
			$this->assign("msgtitle",$title);
			$this->display();
		}
	}
	public function  activity_del(){
		if($_GET["id"]){
			$save['is_del'] = 1;
			$res = M("activity")->where("id=".$_GET["id"])->save($save);
			if($res!==false){
				$this->success("操作成功",U('Admin/Activity/index'));
			}
		}else{
			$this->error("操作失败");
		}
	}
	
}