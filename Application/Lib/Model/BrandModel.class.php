<?php
/**
 * 品牌
 *
 * 
 *
 */
class BrandModel extends Model {

    //获取品牌信息
    public function getBrandInfo($condition = array()) {
        return M('g_brand')->where($condition)->find();
    }

     /**
     * 品牌列表
     * @param unknown $condition
     * @param string $pagesize
     * @param string $field
     * @param string $order
     * @param string $limit
     * @return Ambigous <multitype:boolean Ambigous <string, mixed> , unknown>
     */
    public function getBrandList($condition, $pagesize = '', $field = '*', $order = 'sort_order asc', $limit = ''){
        $list = M('g_brand')->field($field)->where($condition)->order($order)->limit($limit)->select();
        if (empty($list)) return array();
       
        return $list;
    }

    /**
     * 所有品牌
     * @param unknown $condition
     * @param string $field
     * @param string $order
     * @return Ambigous <multitype:boolean Ambigous <string, mixed> , unknown>
     */
    public function getBrandAll($condition,$field = '*', $order = 'sort_order asc'){
        $list = M('g_brand')->field($field)->where($condition)->order($order)->select();
        if (empty($list)) return array();
        return $list;
    }
       /**
     * 添加品牌信息
     *
     * @param unknown_type $data
     */
    public function addBrand($data) {
        return M('g_brand')->add($data);
    }   
    /**
	 * 更改品牌信息
	 *
	 * @param unknown_type $data
	 * @param unknown_type $condition
	 */
	public function editBrand($data,$condition) {
		return M('g_brand')->where($condition)->save($data);
	}

}
