<?php
ini_set('date.timezone','Asia/Shanghai');
error_reporting(E_ERROR);
require_once "lib_new/WxPay.Api.php";


function printf_info($data)
{
    foreach($data as $key=>$value){
        echo "<font color='#f00;'>$key</font> : $value <br/>";
    }
}
$data=array();
if(isset($_REQUEST["transaction_id"]) && $_REQUEST["transaction_id"] != ""){
	$transaction_id = $_REQUEST["transaction_id"];
	$input = new WxPayRefundQuery();
	$input->SetTransaction_id($transaction_id);
	$data=WxPayApi::refundQuery($input);
	echo json_encode($data);die;
}

if(isset($_REQUEST["out_trade_no"]) && $_REQUEST["out_trade_no"] != ""){
	$out_trade_no = $_REQUEST["out_trade_no"];
	$input = new WxPayRefundQuery();
	$input->SetOut_trade_no($out_trade_no);
	$data=WxPayApi::refundQuery($input);
	echo json_encode($data);die;
}

if(isset($_REQUEST["out_refund_no"]) && $_REQUEST["out_refund_no"] != ""){
	$out_refund_no = $_REQUEST["out_refund_no"];
	$input = new WxPayRefundQuery();
	$input->SetOut_refund_no($out_refund_no);
	$data=WxPayApi::refundQuery($input);
	echo json_encode($data);die;
}

if(isset($_REQUEST["refund_id"]) && $_REQUEST["refund_id"] != ""){
	$refund_id = $_REQUEST["refund_id"];
	$input = new WxPayRefundQuery();
	$input->SetRefund_id($refund_id);
	$data=WxPayApi::refundQuery($input);
	echo json_encode($data);die;
}
	
?>
