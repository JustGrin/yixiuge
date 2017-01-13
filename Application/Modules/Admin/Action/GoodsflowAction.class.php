<?php
/****
**流量统计
** 
**/
class GoodsflowAction extends AuthAction{
	//统计浏览次数
	public function goods_browse(){
		switch ($_GET['status']) {
			case '1':
				$order= "goods_browse desc";
				break;
			case '2':
				$order= "goods_salesvolume desc";
				break;
			case '3':
				$order= "goods_share desc";
				break;
			case '4':
				$order= "goods_collect desc";
				break;
			default:
				$order= "goods_browse desc";
				break;
		}
		if($_GET['goods_name']){
			$where['goods.goods_name']=array('like',$_GET['goods_name'].'%');
		}
		if($_GET['goods_sn']  &&  $_GET['goodsNum']!=0){
			$where['goods.goods_sn'] = array('like', array('%' . $_GET['goodsNum'] . '%', '%' . $_GET['goods_sn'] . '%'), 'AND');
            //$where['_sting'] = " goods_sn like '%".$_GET['goodsNum']."%' AND goods_sn like '%".$_GET['goods_sn']."%'";
		}else{
            if($_GET['goods_sn']){
                $where['goods.goods_sn'] = array('like', '%' . $_GET['goods_sn'] . '%');
            }
             if($_GET['goodsNum']){
                $where['goods.goods_sn'] = array('like', '%' . $_GET['goodsNum'] . '%');
            }
            
        }
		if($_GET['cat_id']){
			$where['goods.cat_id']=array('eq',$_GET['cat_id']);
		}
		if($_GET['brand_id']){
			$where['goods.brand_id']=array('eq',$_GET['brand_id']);
		}
		if($_GET['goods_type']){
			$where['goods.goods_type']=array('eq',$_GET['goods_type']);
		}
		if(isset($_GET['is_on_sale'])){
			if($_GET['is_on_sale']!='all'){
				$where['goods.is_on_sale']=$_GET['is_on_sale'];
			}
		}else{
			$_GET['is_on_sale']='all';
		}
        if(isset($_GET['is_auditing'])){
            if($_GET['is_auditing']!='all'){
                $where['goods.is_auditing']=$_GET['is_auditing'];
            }
        }else{
            $_GET['is_auditing']='all';
        }
	    $pre=C('DB_PREFIX');//表前缀
	    $where['goods.is_delete']=0;
		$count=M()->table($pre."g_goods goods")->where($where)->count();
		$page=D("Common")->getPage($count);
		$data=M()->table($pre."g_goods goods")
				->join($pre."g_goods_type type on goods.cat_id=type.cat_id")
				->where($where)
				->limit($page['start'].",".$page['pagesize'])
			    ->order($order)->select();
	    $list["list"]=$data;
	    $list["page"]=$page["page"];
		$this->assign("list",$list);
		 $goodsNumList=M('g_category')->where(array('parent_id'=>0))->field('goods_sn')->select();
		$this->assign('goodsNumList',$goodsNumList);

		$this->display();
	}
}