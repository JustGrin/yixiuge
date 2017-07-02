<?php
//
class  CommonModel extends Model {
     /**
      * 获取所有数据
      * @param tableName   数据库表名 不加前缀
      * @param where       查询条件  字符串或数组
      * @return boolean    数据列表  失败返回false
     */
	public function getAllList($tableName='',$where= array(),$field='*',$order=''){
      if(empty($tableName)){
        return false;
      }
      return M($tableName)->where($where)->field($field)->order($order)->select();
	}
    /**
      * 获取分页数据 传当前页码 一般用于ajax
       * @param tableName   数据库表名 不加前缀
      * @param where string|array  查询条件 支持字符串和数组
      * @param order  字符串       排序规则
      * @param p                 当前页码（从1开始）
       * @param field        需要查询的字段
      * @param pagesize            页面大小（每页条数）
      * @param     
      * @return boolean           数据列表 失败返回false
     */
	public function getnNoPageList($tableName='',$where= array(),$order='',$p=1,$field='*',$pagesize=20){
     if(empty($tableName)){
        return false;
      }
	   $p=$p>=1?$p:1;
	   $start=($p-1)*10;	
     return M($tableName)->where($where)->field($field)->limit($start,$pagesize)->order($order)->select();
	}
    
    /**
      * 获取分页数据 不传当前页码  （tp自带分页）
      * @param tableName   数据库表名 不加前缀
      * @param where string|array  查询条件 支持字符串和数组
      * @param field  字符串       要查询的字段
      * @param order  字符串       排序规则
      * @param pagesize            页面大小（每页条数）
      * @param 
      * @return boolean           $data['list']数据列表 $data['page']分页参数  失败返回false
     */
	public function getPageList($tableName='',$where= array(),$field='*',$order='',$pagesize=20){
    if(empty($tableName)){
        return false;
      }
	  $model=M($tableName);// 实例化Data数据对象
		// 进行分页数据查询
		import('ORG.Util.Page');// 导入分页类
		$count      = $model->where($where)->count();// 查询满足要求的总记录数 $map表示查询条件
    if($count===false){
        return false;
    }
		$Page       = new Page($count,$pagesize);// 实例化分页类 传入总记录数 
		$show       = $Page->show();// 分页显示输出
		// 进行分页数据查询
		$list = $model->where($where)->field($field)->order($order)->limit($Page->firstRow.','.$Page->listRows)->select();
    if($list===false){
        return false;
    }
	  $data['list']=$list;
	  $data['page']=$show;
    return $data;
	}

  /**
      * 获取分页数据 不传当前页码  （tp自带分页）
      * @param count   int       总条数
      * @param pagesize   int    页面大小（每页条数）
      * @param 
      * @return boolean          返回数据分页
     */
  public function getPage($count=0,$pagesize=20){
    // 进行分页数据查询
    import('ORG.Util.Page');// 导入分页类
    $Page       = new Page($count,$pagesize);// 实例化分页类 传入总记录数 
    $show       = $Page->show();// 分页显示输出
    $data['start']=$Page->firstRow;
    $data['pagesize']=$Page->listRows;
    $data['page']=$show;
    return $data;
  }
	
}