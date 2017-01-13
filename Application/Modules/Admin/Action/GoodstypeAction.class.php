<?php
/****
** 类型表
**属性表
** @author  gqh 2015-10-3
** 
**/
class GoodstypeAction extends AuthAction{
	//显示类型列表
	public function index(){
		if($_GET["cat_name"]){
			$where['cat_name']=array('like',$_GET["cat_name"].'%');
		}
		$typeList = D('Common')->getPageList('g_goods_type',$where,$field,'cat_id desc');
		if($typeList !== null)
			$page=$typeList['page'];
			$typeList=$typeList['list'];
			$this->assign('page',$page);
			$this->assign('typeList',$typeList);
			$this->assign('msgtitle','商品类型管理');
		$this->display();
	}
	//新增编辑类型
	public function type_edit(){
		if ($_POST) {  					//POST有值 ->执行添加或者编辑数据
			if (isset($_GET['id'])) {   //GEI[ID]有值执行更新操作
				$swhere['cat_id'] = $_GET['id'];
				$re = M('g_goods_type') -> where($swhere) -> save($_POST);
			}else{       				//GET[ID]无值执行添加操作
				$re = M('g_goods_type')->add($_POST);
			}
			if ($re !== flase) {
				$this->success('操作成功',U('admin/goodstype/index'));die;
			}else{
				$this->error('操作失败');
			}

		}else{      					// POST无值 ->执行显示该页面
			if(isset($_GET['id'])){     //GEI[ID]有值代表从编辑页面进入
				$fwhere['cat_id']=$_GET['id'];
				$type = M('g_goods_type')->where($fwhere)->find();
				$msgtitle = '编辑商品类型';
			}else{						//GEI[ID]无值代表新增页面进入
				$msgtitle = '新增商品类型';
			}
		}
		$this -> assign('type',$type);
		$this -> assign('msgtitle',$msgtitle);
		$this -> display();
	}
	//删除类型
	public function type_del(){
		$dwhere['cat_id'] = $_GET['id'];
		$re = M('g_goods_type')->where($dwhere)->delete();
		if ($re) {
			$this->success('操作成功');
		}else{
			$this->error('操作失败');
		}
	}
	//属性列表
	public function attrList(){
		if($_GET["attr_name"]){
			$cwhere["attr_name"] = array("like",$_GET["attr_name"]."%");
		}
		if($_GET["cat_name"]){
			$cwhere["cat_name"] = array("like",$_GET["cat_name"]."%");
		}
		if($_GET['cat_id']){
			$cwhere['attr.cat_id'] = $_GET['cat_id'];
		}
		$pre=C(DB_PREFIX);//获取表前缀
		import('ORG.Util.Page');// 导入数据页分类
		$count = M()->table($pre . 'g_attribute attr')
			->join($pre.'g_goods_type type on attr.cat_id=type.cat_id')
			->where($cwhere)
			->count(); //获取数据总数
		$page = new page($count,20);
		$show = $page->show();   //输出分页
		//分页数据查询
		$list = M()->table($pre.'g_attribute attr')
			->field('attr.*,type.cat_name')
			->where($cwhere)
			->join($pre.'g_goods_type type on attr.cat_id=type.cat_id')
			->order('attr.cat_id desc')
			->limit($page->firstRow.','.$page->listRows)
			->select();

        $REQUEST_URI=$_SERVER['REQUEST_URI'];
        cookie('now_url_'.MODULE_NAME,$REQUEST_URI);

		//商品类型查询
		$typeList = M('g_goods_type')->field('cat_id,cat_name')->select();
		$this->assign('typeList',$typeList);
		$this->assign('msgtitle','属性列表');
		$this->assign('page',$show);
		$this->assign('attrList',$list);
		$this->display();
	}
	//编辑新增属性 
	public function attrbute_edit(){
		if($_POST){    //POST 执行-->添加更新操作
			if(isset($_GET['id'])){    //有值 -->更新
				$swhere['attr_id'] = $_GET['id'];
				$re = M('g_attribute') -> where($swhere) ->save($_POST);
			}else{			//无值-->增加
				$re = M('g_attribute') -> add($_POST);
			}
			if($re !== flase){
				$now_url=U('admin/goodstype/attrList');
				if(isset($_GET['id'])){
					$now_url=cookie('now_url_'.MODULE_NAME);
				}
				$this->success('操作成功',$now_url);
			}else{
				$this -> error('操作失败');
			}
			die;
		}else{        //POST  执行 -->显示编辑页面
			if(isset($_GET['id'])){    //有值 -->显示编辑
				$fwhere['attr_id'] = $_GET['id'];
				$data= M('g_attribute')->where($fwhere)->find();
				$msgtitle = '编辑属性';

			}else{			//无值-->显示增加
				$msgtitle = '新增属性';
			}
		}
		$typeList = M('g_goods_type')->field('cat_id,cat_name')->select();
		$this ->assign('data',$data);
		$this ->assign('typeList',$typeList);
		$this ->assign('msgtitle',$msgtitle);
		$this ->display();
	}
	//删除属性
	public function attrbute_del(){
		$dwhere['attr_id'] = $_GET['id'];
		$re = M('g_attribute')->where($dwhere)->delete();
		if ($re) {
			$this -> success('操作成功',U('admin/goodstype/attrList'));
		}else{
			$this -> error('操作失败');
		}
	}
}