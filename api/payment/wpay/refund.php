<?php
ini_set('date.timezone','Asia/Shanghai');
error_reporting(E_ERROR);
require_once "lib_new/WxPay.Api.php";
//初始化日志

function printf_info($data)
{
	foreach($data as $key=>$value){
		echo "<font color='#f00;'>$key</font> : $value <br/>";
	}
}
function refund_wx($refund_data){

//$refund_data["out_trade_no"]= "122531270220150304194108";
///$refund_data["total_fee"]= "1";
//$refund_data["refund_fee"] = "1";
	$input = new WxPayRefund();
	if(isset($refund_data["transaction_id"]) && $refund_data["transaction_id"] != "" ){
		$input->SetTransaction_id($refund_data["transaction_id"]);
	}else if(isset($refund_data["out_trade_no"]) && $refund_data["out_trade_no"] != ""){
		$input->SetOut_trade_no($refund_data["out_trade_no"]);
	}else{
		$data['return_msg']='请微信交易号和商户订单号必须有一个';
		return $data;
	}
	$total_fee = $refund_data["total_fee"];
	$refund_fee = $refund_data["refund_fee"];
	$input->SetTotal_fee($total_fee*100);
	$input->SetRefund_fee($refund_fee*100);
	$input->SetOut_refund_no($refund_data['Out_refund_no']);
	$input->SetOp_user_id(WxPayConfig::MCHID);
	$input->SetRefund_account('REFUND_SOURCE_RECHARGE_FUNDS');
	return WxPayApi::refund($input);
}
?>
