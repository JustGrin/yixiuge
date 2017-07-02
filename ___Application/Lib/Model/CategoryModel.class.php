<?php
/**
 * 商品分类
 *
 * 
 *
 */
class CategoryModel extends Model {


    public function getCategoryInfo($condition = array()) {
        return M('g_category')->where($condition)->find();
    }

     /**
     * 商品分类列表
     * @param unknown $condition
     * @param string $pagesize
     * @param string $field
     * @param string $order
     * @param string $limit
     * @return Ambigous <multitype:boolean Ambigous <string, mixed> , unknown>
     */
    public function getCategoryList($condition, $pagesize = '', $field = '*', $order = 'sort_order asc', $limit = ''){
        $list = M('g_category')->field($field)->where($condition)->order($order)->limit($limit)->select();
        if (empty($list)) return array();
        return $list;
    }

    /**
     * 所有商品分类
     * @param unknown $condition
     * @param string $field
     * @param string $order
     * @return Ambigous <multitype:boolean Ambigous <string, mixed> , unknown>
     */
    public function getCategoryAll($condition,$field = '*', $order = 'sort_order asc'){
        $list = M('g_category')->field($field)->where($condition)->order($order)->select();
        if (empty($list)) return array();
        return $list;
    }
       /**
     * 添加商品分类
     *
     * @param unknown_type $data
     */
    public function addCategory($data) {
        return M('g_category')->add($data);
    }   
    /**
	 * 更改商品分类
	 *
	 * @param unknown_type $data
	 * @param unknown_type $condition
	 */
	public function editCategory($data,$condition) {
		return M('g_category')->where($condition)->save($data);
	}

}
