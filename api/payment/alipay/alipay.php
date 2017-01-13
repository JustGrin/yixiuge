<?php
/* *
 * 功能：手机网站支付接口接入页
 * 版本：3.3
 * 修改日期：2012-07-23
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 * 该代码仅供学习和研究支付宝接口使用，只是提供一个参考。

 *************************注意*************************
 * 如果您在接口集成过程中遇到问题，可以按照下面的途径来解决
 * 1、商户服务中心（https://b.alipay.com/support/helperApply.htm?action=consultationApply），提交申请集成协助，我们会有专业的技术工程师主动联系您协助解决
 * 2、商户帮助中心（http://help.alipay.com/support/232511-16307/0-16307.htm?sh=Y&info_type=9）
 * 3、支付宝论坛（http://club.alipay.com/read-htm-tid-8681712.html）
 * 如果不想使用扩展功能请把扩展功能参数赋空值。
 */


require_once("lib/alipay_submit.class.php");

class Alipay {
	/**
	 * 支付接口配置信息
	 *
	 * @var array
	 */
	private $payment;
	public function __construct($payment_info=array()){

		if(!empty($payment_info)){
			$this->payment	= $payment_info;
		}
	}
/**************************请求参数**************************/
function  submit(){
	//header("Content-type:text/html;charset=utf-8");
	//$alipay_config=$this->alipay_config;
	require_once("alipay.config.php");

	//支付类型
	$payment_type = "1";
	//必填，不能修改
	//服务器异步通知页面路径
	$notify_url = $this->payment['notify_url'];//"http://".$_SERVER['HTTP_HOST']."/api/payment/alipay/notify_url.php";
	//需http://格式的完整路径，不能加?id=123这类自定义参数

	//页面跳转同步通知页面路径
	$return_url =  $this->payment['return_url'];//"http://".$_SERVER['HTTP_HOST']."/api/payment/alipay/return_url.php";
	//需http://格式的完整路径，不能加?id=123这类自定义参数，不能写成http://localhost/
	$order_pay_info['pay_sn']= $this->payment['order_sn'];
	$order_pay_info['pay_amount']= $this->payment['pay_amount'];
	$order_pay_info['subject']= $this->payment['subject'];
   //商户订单号
	$out_trade_no =  $this->payment['order_sn'];//$_POST['pay_sn'];
	//商户网站订单系统中唯一订单号，必填

	//订单名称
	$subject =  $this->payment['subject'];//$_POST['WIDsubject'];
	//必填

	//付款金额
	$total_fee =  $this->payment['pay_amount'];//$_POST['WIDtotal_fee'];
	//必填

	//商品展示地址
	$show_url = $this->payment['show_url']; //$_POST['WIDshow_url'];
	//必填，需以http://开头的完整路径，例如：http://www.商户网址.com/myorder.html
	
	//订单描述
	$body = ""; //$this->payment['subject'];//$_POST['WIDbody'];
	//选填

	//超时时间
	$it_b_pay = '';//'90m';//$_POST['WIDit_b_pay'];
	//选填

	//钱包token
	$extern_token = '';//$_POST['WIDextern_token'];
	//选填

    $seller_id=$partner=$alipay_config['partner'];//'2088121112244264';
	/************************************************************/

//构造要请求的参数数组，无需改动
	$parameter = array(
		"service" => "alipay.wap.create.direct.pay.by.user",
		"partner" => trim($partner),
		"seller_id" => trim($seller_id),
		"payment_type"	=> $payment_type,
		"notify_url"	=> $notify_url,
		"return_url"	=> $return_url,
		"out_trade_no"	=> $out_trade_no,
		"subject"	=> $subject,
		"total_fee"	=> $total_fee,
		"show_url"	=> $show_url,
		"body"	=> $body,
		"it_b_pay"	=> $it_b_pay,
		"extern_token"	=> $extern_token,
		"_input_charset"	=> trim(strtolower($alipay_config['input_charset']))
	);
/*var_dump($parameter);
var_dump($alipay_config);*/
//die;
//建立请求
	$alipaySubmit = new AlipaySubmit($alipay_config);
	$html_text = $alipaySubmit->buildRequestForm($parameter,"get", "确认");
/*	$html_text='<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta content="telephone=no" name="format-detection"/>
	<meta name="viewport"
		  content="width=device-width,initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0,user-scalable = no"/>
	<title>支付宝支付</title>
</head>'.$html_text.'</body>
</html>';*/
	//var_dump($html_text);die;
	return $html_text;

}

}
?>