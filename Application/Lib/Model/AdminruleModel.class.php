<?php
//
class  AdminruleModel extends Model {
	
	 protected $tableName= 'admin_rule';

     /**
      * 获取列表
      * @param name string|array  需要验证的规则列表,支持逗号分隔的权限规则或索引数组
      * @param uid  int           认证用户的id
      * @param string mode        执行check的模式
      * @param relation string    如果为 'or' 表示满足任一条规则即通过验证;如果为 'and'则表示需满足所有规则才能通过验证
      * @return boolean           通过验证返回true;失败返回false
     */
	public function getAllList($where= array()){
      return M($this->tableName)->where($where)->select();
	}
    /**
      * 检查权限
      * @param where string|array  查询条件 支持字符串和数组
      * @param order  字符串       排序规则
      * @param p                 当前页码（从1开始）
      * @param pagesize            页面大小（每页条数）
      * @param     
      * @return boolean           数据列表
     */
	public function getnNoPageList($where= array(),$order='',$p=1,$pagesize=10){
	   $p=$p>=1?$p:1;
	   $start=($p-1)*10;	
       return M($this->tableName)->where($where)->limit($start,$pagesize)->order($order)->select();
	}
    
    /**
      * 
      * @param where string|array  查询条件 支持字符串和数组
      * @param order  字符串       排序规则
      * @param pagesize            页面大小（每页条数）
      * @param 
      * @return boolean           $data['list']数据列表 $data['page']分页参数
     */
	public function getPageList($where= array(),$order='',$pagesize=10){
	    $model=M($this->tableName);// 实例化Data数据对象
		// 进行分页数据查询
		import('ORG.Util.Page');// 导入分页类
		$count      = $model->where($where)->count();// 查询满足要求的总记录数 $map表示查询条件
		$Page       = new Page($count,$pagesize);// 实例化分页类 传入总记录数
		$show       = $Page->show();// 分页显示输出
		// 进行分页数据查询
		$list = $model->where($where)->order($order)->limit($Page->firstRow.','.$Page->listRows)->select();
	    $data['list']=$list;
	    $data['page']=$show;
        return $data;
	}
	
}