<?php
/****
**广告
** 
**/
class AdAction extends AuthAction{
    //分类列表
	public function index(){
		$where = array();
		isset($_GET['title']) ? $where['title']=array('like',$_GET['title'].'%') : $_GET['title'] = '';

		if(isset($_GET['add_time'])){
			$add_time=strtotime($_GET['add_time']);
		}else{
			$add_time = 0 ;
			$_GET['add_time'] = '';
		}
		if(isset($_GET['end_time'])){
			$end_time = strtotime($_GET['end_time']);
		}else{
			$end_time = 0;
			$_GET['end_time'] = '';
		}
		if($end_time>0 && $add_time>0){
			$where['add_time']=array('between',array($add_time,$end_time));
		}else{
			if($add_time>0){
				$where['add_time']=array('egt',$add_time);
			}
			if($end_time>0){
				$where['add_time']=array('elt',$end_time);
			}
		}

		if(isset($_GET['status'])){
			if($_GET['status']!='all'){
				$where['status']=$_GET['status'];
			}
		}else{
			$_GET['status']='all';
		}
		$menulist=D("Common")->getPageList('ad_type',$where);

		$this->assign("list",$menulist);
		$this->display();
		//$this->display();
	}
   
	//编辑分类
	public function ad_type_edit(){
		if($_POST){
			if(!empty($_POST["id"])){
				$res = M("ad_type")->where("id=".$_POST["id"])->save($_POST);
			}else{
				$_POST['add_time']=time();
				$res = M("ad_type")->add($_POST);
			}
			if($res!==false){
				$this->success("操作成功",U('Admin/Ad/index'));
			}else{
				$this->error("操作失败");
			}
		}else{
			$id = isset($_GET["id"]) ? $_GET["id"] : 0 ;
			if($id){
				$gwhere["id"] =  array("eq",$id);
				$data = M("ad_type")->where($gwhere)->find();
				$this->assign("data",$data);
				$title="编辑广告位置/类型";
			}else{
				$title="新增广告位置/类型";
			}
			$this->id = $id;

			$this->assign("msgtitle",$title);
			$this->display();
		}
	}

	//删除分类
	public function ad_type_del(){
		$id = $_GET["id"];
		if(empty($id)){
			$this->error("操作失败");
		}
		$res = M("ad")->where(array('type'=>$id))->count();
		if($res){
			$this->error('此广告位置/类型下还有广告，请删除后再试！');
		}
		$where['id']=$id;
		$res = M("ad_type")->where($where)->delete();
		if($res!==false){
			$this->success("操作成功");
		}else{
			$this->error("操作失败");
		}
	}
	
	//后台管理员列表
	public function ad_list(){
		if(empty($_GET["id"])){
			$this->error('此广告位置/类型不存在！');
		}
        $gwhere["id"] =  array("eq",$_GET["id"]);
		$data = M("ad_type")->where($gwhere)->find();
        if(empty($data)){
			$this->error('此广告位置/类型不存在！');
		}
        $where['type']=$_GET['id'];
		$menulist=D("Common")->getPageList('ad',$where,'*','sort desc,add_time desc');

		$this->assign("list",$menulist);
		$this->assign("data",$data);
		$this->display();
	}

	//商品编辑/添加
	public function ad_edit(){
		if($_POST){
			if(empty($_POST["type"])){
			   $this->error("系统错误");
			}			
			if($_POST["id"]){//操作为修改信息
			    $resault = M("ad")->where("id=".$_POST["id"])->save($_POST);
			}else{
				 $_POST["add_time"]=time();
			     $resault = M("ad")->add($_POST);
			}
			if($resault!==false){
				$this->success("操作成功",U('Admin/Ad/ad_list',array('id'=>$_POST["type"])));
			}else{
				$this->error("操作失败");
			}
		}else{
			if($_GET["id"]){
				$where['id']=$_GET["id"];
				$data = M("ad")->where($where)->find();
				$titles="编辑广告信息";
			}else{
				$titles="添加广告信息";
			}
			if($data['type']){
                $twhere=array('id'=>$data["type"]);
			}else{
				$twhere=array('id'=>$_GET["type"]);
			}
			$type=M('ad_type')->where($twhere)->find();
			$data['type']=$type['id'];
			$data['type_name']=$type['title'];
		
			$this->assign("data",$data);
			$this->assign("msgtitle",$titles);
			$this->display();
		}
	}
   
	//删除广告
	public function ad_del(){
		$id=$_GET["id"];
		if(empty($id)){
			$this->error("系统错误");
		}
		$where['id']=$id;
		$data = M("ad")->where($where)->find();
		if(empty($data)){
			$this->error("该信息不存在");
		}
		$resault = M("ad")->where($where)->delete();
		if($resault!==false){
			$this->success("操作成功",U('Admin/Ad/ad_list',array('id'=>$data["type"])));
		}else{
			$this->error("操作失败");
		}
	}

	//首页产品展示
	public function ad_home_list(){
		$where = array();
		isset($_GET['title']) ? $where['title']=array('like','%'. $_GET['title'].'%') : $_GET['title'] = '';
		isset($_GET['type']) ? $where['type']=$_GET['type'] : $_GET['type'] = '' ;
		if(!empty($_GET['status']) && $_GET['status'] != 'all'){
			$where['status']=$_GET['status'];
		}else{
			$_GET['status'] = 'all';
		}
		$menulist=D("Common")->getPageList('home_ad',$where,'*','sort desc,add_time desc');
		$this->assign("list",$menulist);
//		var_dump($menulist);die;
		$this->type_arr=array('1'=>'商品关联','2'=>'类别关联','3'=>'限时商品关联');
		$this->display();
	}

	//首页产品展示编辑/添加
	public function ad_home_edit(){
		if($_POST){     //为执行操作
			$_POST['type'] = $_POST['sel_type'];
			unset($_POST['sel_type']);
			$_POST['start_time'] = strtotime($_POST['start_time']);
			$_POST['end_time'] = strtotime($_POST['end_time']);
			if($_POST['type']==3){
			  $g_goods=M('g_goods')->where(array('goods_id'=>$_POST['value']))->find();
			  if($g_goods){
			  	if($g_goods['is_promote']){
			  		 $_POST['start_time'] = $g_goods['promote_start_date'];
				     $_POST['end_time'] = $g_goods['promote_end_date'];
			  	}else{
                     $this->error("该商品不是限时商品");
			  	}
			  }else{ 
                 $this->error("该商品不存在");
			  }
             
			}
			if($_POST["id"]){//操作为修改信息
//				var_dump($_POST);die;
			    $resault = M("home_ad")->where("id=".$_POST["id"])->save($_POST);
			}else{
				$_POST['add_time']=time();
//				var_dump($_POST);die;
			     $resault = M("home_ad")->add($_POST);
			}
			if($resault!==false){
				$this->success("操作成功",U('Admin/Ad/ad_home_list'));die;
			}else{
				$this->error("操作失败");
			}
		}else{   //显示当前页面
			$id = isset($_GET['id']) ? $_GET['id'] : 0;
			if($id){
				$where['id']=$id;
				$data = M("home_ad")->where($where)->find();

				if($data['type']==2){ //为关联类别的时候
					$category=M('g_category')->field('cat_name')->where(array('cat_id'=>$data['value']))->find();
					$data['title_t']=$category['cat_name'];
				}
				if($data['type']==3 || $data['type'] ==1){ //为关联限时商品或者销售商品的时候
					$arr = explode(',', $data['value']);
					foreach($arr as $v){
						$goods = M('g_goods')->field('goods_name')->where(array('goods_id' =>$v))->find();
						$data['title_t'][]=$goods['goods_name'];
					}
//					var_dump($data);die;
				}
				$titles="编辑首页商品展示";
			}else{
				$titles="添加首页商品展示";
			}
			if(empty($data['start_time'])){
				$data['start_time']=time();
			}
			if(empty($data['end_time'])){
				$data['end_time']=strtotime('+1 month');
			}
			$this->id = $id;
			$this->assign("data",$data);
			$this->assign("msgtitle",$titles);
			$this->display();
		}
	}
   
	//首页产品展示删除
	public function ad_home_del(){
		$id=$_GET["id"];
		if(empty($id)){
			$this->error("系统错误");
		}
		$where['id']=$id;
		$data = M("home_ad")->where($where)->find();
		if(empty($data)){
			$this->error("该信息不存在");
		}
		$resault = M("home_ad")->where($where)->delete();
		if($resault!==false){
			$this->success("操作成功");
		}else{
			$this->error("操作失败");
		}
	}
    //新增展示内容弹窗 gqh
	public function popup_type(){
		if($_GET['type']==1){
			if($_GET['value']){
				$value=explode(',',$_GET['value']);
				$this->value=$value;
			}
			if ($_GET['goods_name']) {
				$where['goods_name'] = array('like','%'.$_GET['goods_name'] . '%');
			}
			if ($_GET['goods_sn']) {
				$where['goods_sn'] = array('like', '%' . $_GET['goods_sn'] . '%');
			}
			$field = 'goods_id,goods_name,goods_img,goods_sn,shop_price';
			$where['is_delete'] = 0;
			$where['is_on_sale'] = 1;
			$where['is_auditing'] = 1;
//		echo M()->_sql();die;
			$p = $_REQUEST['p'];
			$pagesize = 10;
			$p = !empty($p) ? $p : 1;
			$start = ($p - 1) * $pagesize;
			$list = M('g_goods')->field($field)->where($where)->order('goods_id desc')->limit($start, $pagesize)->select();
			$this->count=M('g_goods')->where($where)->count();
			if (IS_AJAX) {
				echo json_encode($list);
				die;
			}
			$this->assign("list", $list);
			$this->display();
		}
		if($_GET['type']==2){
			if ($_GET["cat_name"]) {
				$where["cat_name"] = array("like", $_GET["cat_name"] . "%");
			}
			if ($_GET["goods_sn"]) {
				$where["goods_sn"] = array("like", $_GET["goods_sn"] . "%");
			}
			if ($_GET["keywords"]) {
				$where["keywords"] = array("like", $_GET["keywords"] . "%");
			}
			$order = "sort_order desc";
			$list = D('Common')->getAllList('g_category', $where, '*', $order);   //获取分页数据
			$data = $list;
			$data = $this->gettree($data, 0, 0, 'parent_id', 'cat_id');
		}
		if($_GET['type']==3){
			$time=time();
			$field = 'promote_price,is_on_sale,is_auditing,is_promote,goods_id,goods_name,promote_img,promote_start_date,promote_end_date';
			$where['is_on_sale'] = 1;  //是否开放销售,
			$where['is_promote'] = 1;
			$where['is_auditing'] = 1;
			$where['is_delete'] = 0;
			$where['promote_end_date']=array('egt',$time);
			$order['sort_order'] = 'desc';
			$list=D('Common')->getPageList('g_goods',$where,$field,$order,20);
			$data=$list['list'];
			$page=$list['page'];
			foreach ($data as $k => $v) {    //获得活动状态
				$data[$k] = D('Goods')->getGoodsPrice($v);
				if ($data[$k]['promote_status'] == -1) {
				}
			}
		}
		$this->data=$data;
		$this->assign('page',$page);
		$this->type=$_GET['type'];
		$this->display();
	}
}
?>