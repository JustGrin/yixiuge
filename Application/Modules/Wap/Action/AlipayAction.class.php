<?php
/**
 * 支付
 */
define('DS','/');
define('BASE_PATH',__ROOT__);

class AlipayAction extends UserAction {

	public function __construct() {
		parent::__construct();
	}

    /**
     * 支付
     */
    public function index() {
        $pay_sn = $_GET['pay_sn'];
      $payment_code = 'alipay';

      $model_payment = D('Mallpayment');
      $uid=0;
    $result = $model_payment->productBuy($pay_sn, $payment_code, $this->uid);
      if(!empty($result['error'])) {
          $this->error($result['error']);
      }
    //  var_dump($result);die;
        //第三方API支付
      $this->_api_pay($result['order_pay_info'], $result['payment_info']);
    }

	/**
	 * 第三方在线支付接口
	 *
	 */
	private function _api_pay($order_pay_info, $payment_info) {
    	$inc_file = './'.BASE_PATH.DS.'api'.DS.'payment'.DS.$payment_info['payment_code'].DS.$payment_info['payment_code'].'.php';
    	if(!file_exists($inc_file)){
            $this->error('支付接口不存在');
    	}
    	require_once($inc_file);
        $param = array();
        //$param = unserialize($payment_info['payment_config']);
        $param['order_sn'] =$order_pay_info['pay_sn'];
        $param['pay_amount'] =$order_pay_info['pay_amount'];
        $param['subject'] =$order_pay_info['subject'];
        $param['notify_url'] = "http://".$_SERVER['HTTP_HOST'].U('wap/Alipayreturn/notify_url');
        //服务器异步通知页面路径
        //需http://格式的完整路径，不能加?id=123这类自定义参数

        $param['return_url'] = "http://".$_SERVER['HTTP_HOST'].U('wap/Alipayreturn/return_url');
        //页面跳转同步通知页面路径
        //商品展示地址
        $param['show_url'] = 'http://'.$_SERVER['HTTP_HOST'].'/index.php';//"http://".$_SERVER['HTTP_HOST'].'/index.php';//U('wap/goods/order_detail',array('pay_sn'=>$pay_sn));
        //必填，需以http://开头的完整路径，例如：http://www.商户网址.com/myorder.html
        
        $payment_api = new $payment_info['payment_code']($param);
        $return = $payment_api->submit();
        echo $return;
    	exit;
	}
}
