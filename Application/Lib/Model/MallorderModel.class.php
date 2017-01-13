<?php
/**
 * 支付方式
 *
 * 
 *
 */
class MallorderModel extends Model {


    public function getOrderPayInfo($condition = array()) {
        //return M('order_pay')->where($condition)->find();
        return M('g_order_pay')->where($condition)->find();
    }

     /**
     * 取得订单列表
     * @param unknown $condition
     * @param string $pagesize
     * @param string $field
     * @param string $order
     * @param string $limit
     * @param unknown $extend 追加返回那些表的信息,如array('order_common','order_goods','store')
     * @return Ambigous <multitype:boolean Ambigous <string, mixed> , unknown>
     */
    public function getOrderList($condition, $pagesize = '', $field = '*', $order = 'order_id desc', $limit = '', $extend = array()){
        $list = M('g_order_info')->field($field)->where($condition)->page($pagesize)->order($order)->limit($limit)->select();
        if (empty($list)) return array();
        $order_list = array();
        foreach ($list as $order) {
        	//$order['payment_name'] = orderPaymentName($order['payment_code']);
        	if (!empty($extend)) $order_list[$order['order_id']] = $order;
        }

        if (empty($order_list)) $order_list = $list;

        //追加返回店铺信息
        /*if (in_array('store',$extend)) {
            $store_id_array = array();
            foreach ($order_list as $value) {
            	if (!in_array($value['store_id'],$store_id_array)) $store_id_array[] = $value['store_id'];
            }
            $store_list = Model('store')->getStoreList(array('store_id'=>array('in',$store_id_array)));
            $store_new_list = array();
            foreach ($store_list as $store) {
            	$store_new_list[$store['store_id']] = $store;
            }
            foreach ($order_list as $order_id => $order) {
                $order_list[$order_id]['extend_store'] = $store_new_list[$order['store_id']];
            }
        }*/

        //追加返回买家信息
        /*if (in_array('member',$extend)) {
            $member_id_array = array();
            foreach ($order_list as $value) {
            	if (!in_array($value['buyer_id'],$member_id_array)) $member_id_array[] = $value['buyer_id'];
            }
            $member_list = Model()->table('member')->where(array('member_id'=>array('in',$member_id_array)))->limit($pagesize)->key('member_id')->select();
            foreach ($order_list as $order_id => $order) {
                $order_list[$order_id]['extend_member'] = $member_list[$order['buyer_id']];
            }
        }
*/
        //追加返回商品信息
        if (in_array('order_goods',$extend)) {
            //取商品列表
            $order_goods_list = $this->getOrderGoodsList(array('order_id'=>array('in',array_keys($order_list))));
            foreach ($order_goods_list as $value) {
            	$order_list[$value['order_id']]['extend_order_goods'][] = $value;
            }
        }
        return $order_list;
    }


    /**
     * 取得订单商品表列表
     * @param unknown $condition
     * @param string $fields
     * @param string $limit
     * @param string $page
     * @param string $order
     * @param string $group
     * @param string $key
     */
    public function getOrderGoodsList($condition = array(), $fields = '*', $limit = null, $order = 'rec_id desc') {
        return M('g_order_info')->field($fields)->where($condition)->limit($limit)->order($order)->select();
    }

    	/**
	 * 更改订单信息
	 *
	 * @param unknown_type $data
	 * @param unknown_type $condition
	 */
	public function editOrder($data,$condition) {
        $res=M('g_order_info')->where($condition)->save($data);
		return $res;
	}

	 /**
	 * 添加订单日志
	 */
	public function addOrderLog($data) {
	    $data['log_role'] = str_replace(array('buyer','seller','system'),array('买家','商家','系统'), $data['log_role']);
	    $data['log_time'] = time();
	    return M('g_order_log')->add($data);
	}
    /**
     * 更改订单支付信息
     *
     * @param unknown_type $data
     * @param unknown_type $condition
     */
    public function editOrderPay($data,$condition) {
        return M('g_order_pay')->where($condition)->save($data);
    }

}
