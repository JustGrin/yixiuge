<?php 
ini_set('date.timezone','Asia/Shanghai');
//error_reporting(E_ERROR);
//require_once "lib/WxPay.Api.php";
//require_once "WxPay.JsApiPay.php";
//require_once 'log.php';
require_once "lib_new/WxPay.Api.php";
require_once "unit/WxPay.JsApiPay.php";
require_once 'unit/log.php';
function post_go($url,$data='')
{
    $post='';
    $row = parse_url($url);
    $host = $row['host'];
    $port = isset($row['port']) && $row['port'] ? $row['port'] : 80;
    $file = $row['path'];
    while (list($k,$v) = each($data))
    {
        $post .= rawurlencode($k)."=".rawurlencode($v)."&"; //转URL标准码
    }
    $post = substr( $post , 0 , -1 );
    $len = strlen($post);
    $fp = @fsockopen( $host ,$port, $errno, $errstr, 10);
    if (!$fp) {
        return "$errstr ($errno)\n";
    } else {
        $receive = '';
        $out = "POST $file HTTP/1.0\r\n";
        $out .= "Host: $host\r\n";
        $out .= "Content-type: application/x-www-form-urlencoded\r\n";
        $out .= "Connection: Close\r\n";
        $out .= "Content-Length: $len\r\n\r\n";
        $out .= $post;
        fwrite($fp, $out);
        while (!feof($fp)) {
            $receive .= fgets($fp, 128);
        }
        fclose($fp);
        $receive = explode("\r\n\r\n",$receive);
        unset($receive[0]);
        return implode("",$receive);
    }
}
$openid=$_GET['openid'];
$pay_sn=$_GET['pay_sn'];
$data['pay_sn']=$pay_sn;
$url="http://" . $_SERVER['HTTP_HOST'] . '/index.php/wap/Goodsorderquery/get_weixinpay_msg';
$re=post_go($url,$data);
$left_1 = substr($re, 0, 5);
if($left_1 != '{'){
	$re = substr($re, 4);
}
// $left_frist = strpos($re, '{');
// $re = substr($re, $left_first);

$re=json_decode($re,true);
if(empty($re['status'])){
    $return_data['status']=0;
    $return_data['error']=$re['error'];
    echo json_encode($return_data);exit();
}
$data=$re['data'];

//初始化日志
$logHandler= new CLogFileHandler("./logs/".date('Y-m-d').'.log');
$log = Log::Init($logHandler, 15);

$order_pay_info['pay_sn']=$data['pay_sn'];
$order_pay_info['pay_amount']=$data['pay_amount'];
$order_pay_info['subject']=$data['subject'];
$order_pay_info['order_pay_sn']=$_GET['order_pay_sn'];

 $user_agent = $_SERVER['HTTP_USER_AGENT'];
if (strpos($user_agent, 'MicroMessenger') === false) {
    // $return_data['status']=0;
    // $return_data['error']='请在微信上打开支付';
    // echo json_encode($return_data);exit();
}

//①、获取用户openid
$tools = new JsApiPay();
$openId=$_GET['openid'];
if(empty($openId)){
    $return_data['error']='支付错误，请刷新页面重试！';
    echo json_encode($return_data);exit();
}

$order_pay_info['subject']='商品购买_'.$order_pay_info['pay_sn'];
//②、统一下单
$input = new WxPayUnifiedOrder();
$input->SetBody($order_pay_info['subject']);
$input->SetAttach($order_pay_info['subject']);
$input->SetOut_trade_no($order_pay_info['pay_sn']);

$pay_amount=$order_pay_info['pay_amount']*100;//分

$return_data['pay_amount']=$order_pay_info['pay_amount'];

$input->SetTotal_fee($pay_amount);

$input->SetTime_start(date("YmdHis"));
$input->SetTime_expire(date("YmdHis", time() + 7200));
$input->SetGoods_tag($order_pay_info['subject']);

$url_l="http://".$_SERVER['HTTP_HOST'].'/index.php/wap/returnmallpayment/index';
$input->SetNotify_url($url_l);
$input->SetTrade_type("JSAPI");
$input->SetOpenid($openId);

//检测必填参数
/*
if(!$input->IsOut_trade_noSet()) {
	$return_data['error']='缺少统一支付接口必填参数out_trade_no！';
    echo json_encode($return_data);exit();
}else if(!$input->IsBodySet()){
	$return_data['error']='缺少统一支付接口必填参数body！';
    echo json_encode($return_data);exit();
}else if(!$input->IsTotal_feeSet()) {
	$return_data['error']='缺少统一支付接口必填参数total_fee！';
    echo json_encode($return_data);exit();
}else if(!$input->IsTrade_typeSet()) {
	 $return_data['error']='缺少统一支付接口必填参数trade_type！';
    echo json_encode($return_data);exit();
}
*/

//关联参数
/*
if($input->GetTrade_type() == "JSAPI" && !$input->IsOpenidSet()){
	throw new WxPayException();
	 $return_data['error']="统一支付接口中，缺少必填参数openid！trade_type为JSAPI时，openid为必填参数！";
    echo json_encode($return_data);die;
}
if($input->GetTrade_type() == "NATIVE" && !$input->IsProduct_idSet()){
	 $return_data['error']='统一支付接口中，缺少必填参数product_id！trade_type为JSAPI时，product_id为必填参数！';
    echo json_encode($return_data);die;
}
*/

$order = WxPayApi::unifiedOrder($input);
if(empty($order)){
    $return_data['error']='支付错误，请刷新页面重试.';
    //print_r($return_data);
    echo json_encode($return_data);die;
}
if($order['return_code']=='FAIL'){
    $return_data['error']=$order['return_msg'];
	//print_r($return_data);
    echo json_encode($return_data);die;
}
if($order['return_msg']!='OK'){
    $return_data['error']=$order['return_msg'];
    //print_r($return_data);
    echo json_encode($return_data);die;
}
if(!array_key_exists("appid", $order)
	|| !array_key_exists("prepay_id", $order)
	|| $order['prepay_id'] == "")
	{
		$return_data['error']='参数错误，请刷新页面重试';
		//print_r($return_data);
        echo json_encode($return_data);die;
	}

$jsApiParameters = $tools->GetJsApiParameters($order);
if($jsApiParameters){
	$return_data['status']=1;
    $return_data['payvalue']=json_decode($jsApiParameters,true);
    echo json_encode($return_data);die;
}else{
	
	$return_data['error']='支付错误，拉起支付失败';
	//print_r($return_data);
    echo json_encode($return_data);die;
}
if(isset($_GET['farm_id']) && $_GET['farm_id'] > 0){
	$return_url="http://".$_SERVER['HTTP_HOST'].'/wap/farm/farm_agreement/farm_id/'.$_GET['farm_id'];
}else{
	$return_url="http://".$_SERVER['HTTP_HOST'].'/index.php/wap/goodsorder/order_pay/pay_sn/'.$order_pay_info['order_pay_sn'];
}

//获取共享收货地址js函数参数
//$editAddress = $tools->GetEditAddressParameters();

//③、在支持成功回调通知中处理成功之后的事宜，见 notify.php
/**
 * 注意：
 * 1、当你的回调地址不可访问的时候，回调通知会失败，可以通过查询订单来确认支付是否成功
 * 2、jsapi支付时需要填入用户openid，WxPay.JsApiPay.php中有获取openid流程 （文档可以参考微信公众平台“网页授权接口”，
 * 参考http://mp.weixin.qq.com/wiki/17/c0f37d5704f0b64713d5d2c37b468d75.html）
 */
?>
