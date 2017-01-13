<?php
/****
** 商品分类
** @author  gqh 2015-10-2
**
**/
class CategoryAction extends AuthAction{
	//分类列表
	public function Index(){
		if($_GET["cat_name"]){
			$where["cat_name"] = array("like","%".$_GET["cat_name"]."%");
		}
		if($_GET["goods_sn"]){
			$where["goods_sn"] = array("like","%".$_GET["goods_sn"]."%");
		}
		if($_GET["keywords"]){
			$where["keywords"] = array("like","%".$_GET["keywords"]."%");
		}
		$order="sort_order desc";
		$list =D('Common')->getAllList('g_category',$where,'*',$order);   //获取分页数据
        $typeList = $list;
        $page=$list['page'];
		$typeList = $this->gettree($typeList,0,0,'parent_id','cat_id');
       // $this->assign('page',$page);
		$this->assign('typeList',$typeList);
		$this->assign('msgtitle','分类管理');
		$this->display();
	}
	//编辑新增商品
	public function category_edit(){

		if ($_POST) {  //POST有值执行添加或者更新
			if(!empty($_GET['id'])){//编辑分类
//                 $re = M('g_category')->field('cat_id')->where(array('parent_id'=>$_GET['id']))->select();
//                 if($re){//判断是不是添加到自己的子分类
//                     $this -> error('上级分类不能是自己的子分类');
//                 }
			 	if($_GET['id']==$_POST['parent_id']){
			 		$this->error('上级分类不能是自己');
			 	}
                $cwhere['cat_id'] = $_GET['id'];
                $re = M('g_category')->where($cwhere)->save($_POST);

			}else{ //增加分类
			 	$re = M('g_category')->add($_POST);
			}
			if ($re !== false) {
				$this ->success('操作成功',U('admin/category/index'));
				die;
			}else{
				$this ->error('操作失败');
			}
		}else{   //POST没有值显示添加或者更新
			if (isset($_GET['id'])) {
				$cwhere['cat_id']=$_GET['id'];
				$data=M('g_category')->where($cwhere)->find();  //读取记录
				$msgtitle ="编辑分类";
				$is_update = 1;
				$this->assign('is_update',$is_update);
			}else{
				//新增分类
				$msgtitle="新增分类";
			}

		}
		//读取分类列表
		if((!empty($_GET['id']))){
			$gwhere['parent_id'] = array('neq', $_GET['id']);
		}
		//$category =D('Common')->getAllList('g_category');
		$category = M('g_category')->field('cat_id,cat_name,parent_id')->where($gwhere)->select();
		$category = $this->gettree($category,0,0,'parent_id','cat_id');
		$this->assign('id',$cwhere['cat_id']);
		$this->assign('data',$data);
		$this->assign('msgtitle',$msgtitle);
		$this->assign('category',$category);
		$this -> display();
	}
	//删除分类
	public function category_del(){
		$cwhere['parent_id'] = $_GET['id'];
		$re = M('g_category')->field('parent_id')->where($cwhere)->count();
		//判断存不存在子分类
		if($re>0){   //不存在执行删除
			$this->error('请删除子分类后在执行删除操作');
		}else{   //不存在执行删除
			$gwhere['cat_id']=$_GET['id'];
			$m = D('g_category');
			$re = $m->where($gwhere)->delete();
			if($re){
				$this->success('操作成功');
			}else{
				$this->error('操作失败');
			}
		}
	}
	//验证货号是否唯一 zm
	public function cheack_goods_sn(){
		$goods_sn=$_GET["goods_sn"];
		$is_update=$_GET["is_update"];
		if(empty($goods_sn)){
			echo 1;die;
		}
		//新增货号
		if (empty($is_update)) {
			$where['goods_sn'] = $goods_sn;
		}else{//修改时没修改货号
			$where['goods_sn'] = $goods_sn;
			$where['cat_id'] = array('neq',$is_update);
		}
		$count= M("g_category")->where($where)->count();
		echo $count;die;
	}
}