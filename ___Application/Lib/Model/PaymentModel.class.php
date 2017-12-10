<?php
/**
 * 支付方式
 *
 * 
 *
 */
class PaymentModel extends Model {
    /**
     * 开启状态标识
     * @var unknown
     */
    const STATE_OPEN = '1';
    
	/**
	 * 读取单行信息
	 *
	 * @param
	 * @return array 数组格式的返回结果
	 */
	public function getPaymentInfo($condition = array()) {
		return M('payment')->where($condition)->find();
	}

	/**
	 * 读开启中的取单行信息
	 *
	 * @param
	 * @return array 数组格式的返回结果
	 */
	public function getPaymentOpenInfo($condition = array()) {
	    $condition['payment_state'] = self::STATE_OPEN;
	    return M('payment')->where($condition)->find();
	}
	
	/**
	 * 读取多行
	 *
	 * @param 
	 * @return array 数组格式的返回结果
	 */
	public function getPaymentList($condition = array()){
        return M('payment')->where($condition)->select();
	}
	
	/**
	 * 读取开启中的支付方式
	 *
	 * @param
	 * @return array 数组格式的返回结果
	 */
	public function getPaymentOpenList($condition = array()){
	    $condition['payment_state'] = self::STATE_OPEN;
	    return M('payment')->where($condition)->key('payment_code')->select();
	}
	
	/**
	 * 更新信息
	 *
	 * @param array $param 更新数据
	 * @return bool 布尔类型的返回结果
	 */
	public function editPayment($data, $condition){
		return M('payment')->where($condition)->save($data);
	}

	/**
	 * 读取支付方式信息by Condition
	 *
	 * @param
	 * @return array 数组格式的返回结果
	 */
	public function getRowByCondition($conditionfield,$conditionvalue){
	    $param	= array();
	    $param['table']	= 'payment';
	    $param['field']	= $conditionfield;
	    $param['value']	= $conditionvalue;
	    $result	= Db::getRow($param);
	    return $result;
	}

    /**
     * 购买商品
     */
    public function productBuy($pay_sn, $payment_code, $member_id) {
        $condition = array();
        $condition['payment_code'] = $payment_code;
        $payment_info = $this->getPaymentOpenInfo($condition);
        if(!$payment_info) {
            return array('error' => '系统不支持选定的支付方式');
        }

        //验证订单信息
	    $model_order = D('Order');
	    $order_pay_info = $model_order->getOrderPayInfo(array('pay_sn'=>$pay_sn,'buyer_id'=>$member_id));
	    if(empty($order_pay_info)){
            return array('error' => '该订单不存在');
	    }
	    $order_pay_info['subject'] = '商品购买_'.$order_pay_info['pay_sn'];
	    $order_pay_info['order_type'] = 'product_buy';

	    //重新计算在线支付且处于待支付状态的订单总额
        $condition = array();
        $condition['pay_sn'] = $pay_sn;
        $condition['order_state'] = '10';
        $order_list = $model_order->getOrderList($condition,'','order_id,order_sn,pay_sn,order_amount,pd_amount');
        if (empty($order_list)) {
            return array('error' => '该订单不存在');
        }

        //计算本次需要在线支付的订单总金额
        $pay_amount = 0;
        foreach ($order_list as $order_info) {
                $pay_amount += PriceFormat(floatval($order_info['order_amount']) - floatval($order_info['pd_amount']));
        }

        //如果为空，说明已经都支付过了或已经取消或者是价格为0的商品订单，全部返回
        if (empty($pay_amount)) {
            return array('error' => '订单金额为0，不需要支付');
        }
        $order_pay_info['pay_amount'] = $pay_amount;

        return(array('order_pay_info' => $order_pay_info, 'payment_info' => $payment_info));

    }

    /**
     * 购买订单支付成功后修改订单状态
     */
    public function updateProductBuy($out_trade_no, $payment_code, $order_list, $trade_no , $pay_arr = array()) {
	    try {
	        $model_order = D('Order');
	        $model_order->startTrans();

	        $data = array();
	        $data['api_pay_state'] = '1';
			$data['trade_no'] = $trade_no;
			if(!empty($pay_arr)){
				$data['total_money']=$pay_arr['total_money'];
				$data['end_time'] =$pay_arr['end_time'];
			}
	        $update = $model_order->editOrderPay($data,array('pay_sn'=>$out_trade_no));
	        if (!$update) {
	            throw new Exception('更新订单状态失败');
	        }

	        $data = array();
	        $data['order_state']	= '20';
	        $data['payment_time']	= time();
	        $data['payment_code']   = $payment_code;
	        //$data['exchange_code']=date("YmdHis").$trade_no.rand(1000,9999);
	        $update = $model_order->editOrder($data,array('pay_sn'=>$out_trade_no,'order_state'=>'10'));
	        if (!$update) {
	            throw new Exception('更新订单状态失败');
	        }

            foreach($order_list as $order_info) {
                //如果有预存款支付的，彻底扣除冻结的预存款
                /*$pd_amount = floatval($order_info['pd_amount']);
                if ($pd_amount > 0) {
                    $data_pd = array();
                    $data_pd['member_id'] = $order_info['buyer_id'];
                    $data_pd['member_name'] = $order_info['buyer_name'];
                    $data_pd['amount'] = $order_info['pd_amount'];
                    $data_pd['order_sn'] = $order_info['order_sn'];
                    $model_pd->changePd('order_comb_pay',$data_pd);
                }*/
                //更新兑换码
                 $data=array();
                 $data['exchange_code']=date("ymdHis").$order_info['buyer_id'].rand(100,999);
		        $update = $model_order->editOrder($data,array('order_id'=>$order_info['order_id']));
		        if (!$update) {
		            throw new Exception('更新订单兑换码失败');
		        }
                //记录订单日志
                $data = array();
                $data['order_id'] = $order_info['order_id'];
                $data['log_role'] = 'buyer';
                $data['log_msg'] ='订单支付( 支付平台交易号 : '.$trade_no.' )'; //L('order_log_pay').'( 支付平台交易号 : '.$trade_no.' ) ';
                $data['log_orderstate'] = '20';
                $insert = $model_order->addOrderLog($data);
                if (!$insert) {
                    throw new Exception('记录订单日志出现错误');
                }
            }
	        $model_order->commit();
            return array('success' => true);
	    } catch (Exception $e) {
	        $model_order->rollback();
            return array('error' => $e->getMessage());
	    }

    }
}