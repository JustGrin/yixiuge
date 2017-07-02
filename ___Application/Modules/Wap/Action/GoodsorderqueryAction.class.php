<?php

// 上次订单
class GoodsorderqueryAction extends CommonAction
{
    //魔术方法
    public function __construct()
    {
        parent::__construct();
    }

    ///获取微信支付的订单信息
    public function get_weixinpay_msg(){
        $pay_sn = $_REQUEST['pay_sn'];
        //购买开关
        $buy_switch = M('sys_param')->where(array('param_code' => 'buy_switch'))->getField('param_value');
        if (empty($buy_switch)) {
            $data['status'] = 0;
            $data['error'] = '管理员已关闭交易';
            echo json_encode($data);
            die;
        }
        $payment_code = 'wxpay';
        $model_payment = D('Mallpayment');
        $result = $model_payment->productBuy($pay_sn, $payment_code);
        if (!empty($result['error'])) {
            $data['status'] = 0;
            $data['error'] = $result['error'];
            echo json_encode($data);
            die;
        }
        $order_pay_info = $result['order_pay_info'];
        $payment_info = $result['payment_info'];
        $data['status'] = 1;
        $data['data'] = $order_pay_info;
        echo json_encode($data);
        die;
    }

}