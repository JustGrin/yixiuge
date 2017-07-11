<?php
/**
 * 支付
 */


class PaymentAction extends UserAction {

	public function __construct() {
		parent::__construct();
	}

    /**
     * 支付
     */
    public function index() {
        $pay_sn = $_GET['pay_sn'];
		
        $payment_code = 'wxpay';
      /*  $url=U('Wap/Payment/index',array('pay_sn'=>$pay_sn));
        echo "http://".$_SERVER['HTTP_HOST'].$url;*/
        //http://www.miyou.com/index.php/Wap/Payment/index?pay_sn=380496352818900001
       //die;
        $model_payment = D('Payment');
        $result = $model_payment->productBuy($pay_sn, $payment_code, $this->uid);
   
        if(!empty($result['error'])) {
            $this->error($result['error']);
        }

         if($payment_code=='wxpay'){
            //第三方API支付
            $this->_w_api_pay($result['order_pay_info'], $result['payment_info']);
        }else{
             //第三方API支付
            $this->_api_pay($result['order_pay_info'], $result['payment_info']);
        }
    }

	/**
	 * 第三方在线支付接口
	 *
	 */
	private function _api_pay($order_pay_info, $payment_info) {
    	$inc_file = BASE_PATH.DS.'api'.DS.'payment'.DS.$payment_info['payment_code'].DS.$payment_info['payment_code'].'.php';
    	if(!file_exists($inc_file)){
            dump('支付接口不存在');
    	}
    	require_once($inc_file);
        $param = array();
    	$param = unserialize($payment_info['payment_config']);
        $param['order_sn'] = $order_pay_info['pay_sn'];
        $param['order_amount'] = $order_pay_info['pay_amount'];
        $param['sign_type'] = 'MD5';
    	$payment_api = new $payment_info['payment_code']($param);
        $return = $payment_api->submit();
        echo $return;
    	exit;
	}
     /**
     * 第三方在线支付接口  微信支付
     *
     */
    private function _w_api_pay($order_pay_info, $payment_info) {
 /**
 * JS_API支付demo
 * ====================================================
 * 在微信浏览器里面打开H5网页中执行JS调起支付。接口输入输出数据格式为JSON。
 * 成功调起支付需要三个步骤：
 * 步骤1：网页授权获取用户openid
 * 步骤2：使用统一支付接口，获取prepay_id
 * 步骤3：使用jsapi调起支付
*/
    include_once("./api/payment/wpay/WxPayPubHelper/WxPayPubHelper.php");
   
    //使用jsapi接口
    $jsApi = new JsApi_pub();

    //=========步骤1：网页授权获取用户openid============
    //通过code获得openid
    if (!isset($_GET['code']))
    {
        //触发微信返回code码
        $url=U('Wap/Payment/index',array('pay_sn'=>$order_pay_info['pay_sn']));
        $url= "http://".$_SERVER['HTTP_HOST'].$url;
        $url = $jsApi->createOauthUrlForCode($url);
        Header("Location: $url"); 
    }else
    {
        //获取code码，以获取openid
        $code = $_GET['code'];
        $jsApi->setCode($code);
        $openid = $jsApi->getOpenId();
    }

    //=========步骤2：使用统一支付接口，获取prepay_id============
    //使用统一支付接口
    $unifiedOrder = new UnifiedOrder_pub();
    
    //设置统一支付接口参数
    //设置必填参数
    //appid已填,商户无需重复填写
    //mch_id已填,商户无需重复填写
    //noncestr已填,商户无需重复填写
    //spbill_create_ip已填,商户无需重复填写
    //sign已填,商户无需重复填写
    $unifiedOrder->setParameter("openid","$openid");//商品描述
    $unifiedOrder->setParameter("body",$order_pay_info['subject']);//商品描述
    //自定义订单号，此处仅作举例
    $timeStamp = time();
    //$out_trade_no = WxPayConf_pub::APPID."$timeStamp";
    $pay_amount=100*$order_pay_info['pay_amount'];///分
    $unifiedOrder->setParameter("out_trade_no",$order_pay_info['pay_sn']);//商户订单号 
    $unifiedOrder->setParameter("total_fee",$pay_amount);//总金额  分
    //$unifiedOrder->setParameter("notify_url",WxPayConf_pub::NOTIFY_URL);//通知地址 
    $url=U('Wap/Returnpayment/index');
    $url="http://".$_SERVER['HTTP_HOST'].$url;
    $unifiedOrder->setParameter("notify_url",$url);//通知地址 
    $unifiedOrder->setParameter("trade_type","JSAPI");//交易类型
    //非必填参数，商户可根据实际情况选填
    //$unifiedOrder->setParameter("sub_mch_id","XXXX");//子商户号  
    //$unifiedOrder->setParameter("device_info","XXXX");//设备号 
    //$unifiedOrder->setParameter("attach","XXXX");//附加数据 
    //$unifiedOrder->setParameter("time_start","XXXX");//交易起始时间
    //$unifiedOrder->setParameter("time_expire","XXXX");//交易结束时间 
    //$unifiedOrder->setParameter("goods_tag","XXXX");//商品标记 
    //$unifiedOrder->setParameter("openid","XXXX");//用户标识
    //$unifiedOrder->setParameter("product_id","XXXX");//商品ID

    $prepay_id = $unifiedOrder->getPrepayId();
	//echo $prepay_id;die;
    //=========步骤3：使用jsapi调起支付============
    $jsApi->setPrepayId($prepay_id);

    $jsApiParameters = $jsApi->getParameters();  
$sub=$this->submit_w($jsApiParameters,$order_pay_info);
    echo $sub;

//②、统一下单
/*$input = new WxPayUnifiedOrder();
$input->SetBody($order_pay_info['subject']);
$input->SetAttach($order_pay_info['subject']);
//WxPayConfig::MCHID.date("YmdHis")
$input->SetOut_trade_no($order_pay_info['pay_sn']);
$input->SetTotal_fee($order_pay_info['pay_amount']);
$input->SetTime_start(date("YmdHis"));
$input->SetTime_expire(date("YmdHis", time() + 600));
$input->SetGoods_tag("test");
$input->SetNotify_url("http://".$_SERVER['HTTP_HOST']."/api/payment/wxpay/notify.php");
$input->SetTrade_type("JSAPI");
$input->SetOpenid($openId);
$order = WxPayApi::unifiedOrder($input);
echo '<font color="#f00"><b>统一下单支付单信息</b></font><br/>';
//printf_info($order);
$this->jsApiParameters = $tools->GetJsApiParameters($order);

//获取共享收货地址js函数参数
$this->editAddress = $tools->GetEditAddressParameters();
$sub=$this->submit_w($this->jsApiParameters,$this->editAddress);
    echo $sub;*/
        exit;
    }

public function submit_w($jsApiParameters,$order_pay_info){
    $html='<!DOCTYPE html><html>
<head>
      <meta charset="UTF-8">
	 <meta name="format-detection" content="telephone=no">
    <meta name="viewport" content="minimal-ui,width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <title>微信安全支付</title>

    <script type="text/javascript">
        //调用微信JS api 支付
        function jsApiCall()
        {
            WeixinJSBridge.invoke(
                "getBrandWCPayRequest",
                '.$jsApiParameters.',
                function(res){
                    WeixinJSBridge.log(res.err_msg);
                    alert(res.err_code+res.err_desc+res.err_msg);
                }
            );
        }

        function callpay()
        {
			
            if (typeof WeixinJSBridge == "undefined"){
				
                if( document.addEventListener ){
                    document.addEventListener("WeixinJSBridgeReady", jsApiCall, false);
                }else if (document.attachEvent){
                    document.attachEvent("WeixinJSBridgeReady", jsApiCall); 
                    document.attachEvent("onWeixinJSBridgeReady", jsApiCall);
                }
            }else{
                jsApiCall();
            }
        }
    </script>
</head>
 <style type="text/css">
        .main{
            width: 100%;
            float: left;
        }
        .main .content{
            margin: 0 auto;
            min-width: 300px;
            max-width: 640px;
        }
        .main .content .information{
            float: left;
        }
        .main .content .information p{
            margin:20px;
        }
        .main .buy input{
            margin-left:20px;
            background-color: red;
            width:90%;
            height:40px;
            color: #FFFFFF;
            font-weight: bold;
            -webkit-border-radius: 5px;
            -moz-border-radius: 5px;
            border-radius: 5px;
        }

        </style>
<body>
    </br></br>
	<div class="main">
            <div class="content">
                <h3 style="text-align: center;margin: 20px;">'.$order_pay_info['subject'].'</h3>
                <div  class="information"> <p>订单编号：'.$order_pay_info['pay_sn'].'</p>
                <p>支付金额：￥'.$order_pay_info['pay_amount'].'元 </p></div>
				<p>'.$jsApiParameters.'</p>
            </div>
            <div class="buy" onclick="callpay()"> <p><input type="submit" name="id" value="确认支付"></p></div>
        </div>
</body>
</html>';
 return  $html;
}
    /**
     * 第三方在线支付接口  微信支付
     *
     */
    private function _wx_api_pay($order_pay_info, $payment_info) {
       /* $inc_file = './api/payment/'.$payment_info['payment_code'].'/'.$payment_info['payment_code'].'.php';
        if(!file_exists($inc_file)){
            dump('支付接口不存在');
        }
       
        require_once($inc_file);
        $param = array();
        $param = unserialize($payment_info['payment_config']);
        $param['order_sn'] = $order_pay_info['pay_sn'];
        $param['order_amount'] = $order_pay_info['pay_amount'];
        $param['sign_type'] = 'MD5';
        $payment_api = new $payment_info['payment_code']($param);
        $return = $payment_api->submit();

        echo $return;*/
/*           var_dump($_SERVER); 
   var_dump($order_pay_info);     
 $param = array();
 $param = unserialize($payment_info['payment_config']);die;*/
//echo './api/payment/'.$payment_info['payment_code']."/lib/WxPay.Api.php";die;
require_once  './api/payment/wxpay/lib/WxPay.Api.php';
require_once './api/payment/wxpay/WxPay.JsApiPay.php';
//require_once  './api/payment/'.$payment_info['payment_code'].'/log.php';

//初始化日志
//$logHandler= new CLogFileHandler("./api/payment/wxpay/lib/logs/".date('Y-m-d').'.log');
//$log = Log::Init($logHandler, 15);

  $param['order_sn'] = $order_pay_info['pay_sn'];
 $param['order_amount'] = $order_pay_info['pay_amount'];
$param['sign_type'] = 'MD5';
//①、获取用户openid
$tools = new JsApiPay();
$openId = $tools->GetOpenid();

//②、统一下单
$input = new WxPayUnifiedOrder();
$input->SetBody($order_pay_info['subject']);
$input->SetAttach($order_pay_info['subject']);
//WxPayConfig::MCHID.date("YmdHis")
$input->SetOut_trade_no($order_pay_info['pay_sn']);
$input->SetTotal_fee($order_pay_info['pay_amount']);
$input->SetTime_start(date("YmdHis"));
$input->SetTime_expire(date("YmdHis", time() + 600));
$input->SetGoods_tag("test");
$input->SetNotify_url("http://".$_SERVER['HTTP_HOST']."/api/payment/wxpay/notify.php");
$input->SetTrade_type("JSAPI");
$input->SetOpenid($openId);
$order = WxPayApi::unifiedOrder($input);
echo '<font color="#f00"><b>统一下单支付单信息</b></font><br/>';
//printf_info($order);
$this->jsApiParameters = $tools->GetJsApiParameters($order);

//获取共享收货地址js函数参数
$this->editAddress = $tools->GetEditAddressParameters();
$sub=$this->submit($this->jsApiParameters,$this->editAddress,$order_pay_info);
    echo $sub;
        exit;
    }

public function submit($jsApiParameters,$editAddress,$order_pay_info){
    $html='<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/> 
    <title>微信支付样例-支付</title>
    <script type="text/javascript">
    //调用微信JS api 支付
    function jsApiCall()
    {
        WeixinJSBridge.invoke(
            "getBrandWCPayRequest",
            '.$jsApiParameters.',
            function(res){
                WeixinJSBridge.log(res.err_msg);
                alert(res.err_code+res.err_desc+res.err_msg);
            }
        );
    }

    function callpay()
    {
        if (typeof WeixinJSBridge == "undefined"){
            if( document.addEventListener ){
                document.addEventListener("WeixinJSBridgeReady", jsApiCall, false);
            }else if (document.attachEvent){
                document.attachEvent("WeixinJSBridgeReady", jsApiCall); 
                document.attachEvent("onWeixinJSBridgeReady", jsApiCall);
            }
        }else{
            jsApiCall();
        }
    }
    </script>
    <script type="text/javascript">
    //获取共享地址
    function editAddress()
    {
        WeixinJSBridge.invoke(
            "editAddress",
            '.$editAddress.',
            function(res){
                var value1 = res.proviceFirstStageName;
                var value2 = res.addressCitySecondStageName;
                var value3 = res.addressCountiesThirdStageName;
                var value4 = res.addressDetailInfo;
                var tel = res.telNumber;
                
                alert(value1 + value2 + value3 + value4 + ":" + tel);
            }
        );
    }
    
    window.onload = function(){
        if (typeof WeixinJSBridge == "undefined"){
            if( document.addEventListener ){
                document.addEventListener("WeixinJSBridgeReady", editAddress, false);
            }else if (document.attachEvent){
                document.attachEvent("WeixinJSBridgeReady", editAddress); 
                document.attachEvent("onWeixinJSBridgeReady", editAddress);
            }
        }else{
            editAddress();
        }
    };
    
    </script>
</head>
<style type="text/css">
        .main{
            width: 100%;
            float: left;
        }
        .main .content{
            margin: 0 auto;
            min-width: 300px;
            max-width: 640px;
        }
        .main .content .information{
            float: left;
        }
        .main .content .information p{
            margin:20px;
        }
        .main .buy input{
            margin-left:20px;
            background-color: red;
            width:90%;
            height:40px;
            color: #FFFFFF;
            font-weight: bold;
            -webkit-border-radius: 5px;
            -moz-border-radius: 5px;
            border-radius: 5px;
        }

        </style>
<body>
     </br></br>
	<div class="main">
            <div class="content">
                <h3 style="text-align: center;margin: 20px;">'.$order_pay_info['subject'].'</h3>
                <div  class="information"> <p>订单编号：'.$order_pay_info['pay_sn'].'</p>
                <p>支付金额：￥'.$order_pay_info['pay_amount'].'元 </p></div>
				<p>'.$jsApiParameters.'</p>
            </div>
            <div class="buy" onclick="callpay()"> <p><input type="submit" name="id" value="确认支付"></p></div>
        </div>
</body>
</html> ';
 return  $html;
}


}
