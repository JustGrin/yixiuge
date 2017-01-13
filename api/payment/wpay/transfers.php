<?php
ini_set('date.timezone','Asia/Shanghai');
error_reporting(E_ERROR);
require_once "lib_new/WxPay.Api.php";
//初始化日志
require_once 'unit/log.php';

//初始化日志
$logHandler= new CLogFileHandler("logs/transfers/".date('Y-m-d').'.log');
$log = Log::Init($logHandler, 15);


function printf_info($data)
{
	$logs="";
	foreach($data as $key=>$value){
		$logs.= $key.":". $value."\n" ;
	}
	return $logs;
}
//$_POST["out_trade_no"]= "122531270220150304194108";
///$_POST["total_fee"]= "1";
//$_POST["refund_fee"] = "1";
$partner_trade_no = $_POST["partner_trade_no"];
$amount = $_POST["amount"];
$openid = $_POST['openid'];
$re_user_name =$_POST['rel_user_name'];
$input = new WxPayToUser();
$input->SetPartner_trade_no($partner_trade_no);
$input->SetOpenid($openid);

/*校验用户姓名选项	check_name		是		String
*NO_CHECK：不校验真实姓名
*FORCE_CHECK：强校验真实姓名（未实名认证的用户会校验失败，无法转账）
*OPTION_CHECK：针对已实名认证的用户才校验真实姓名（未实名认证用户不校验，可以转账成功）
 * */
$input->SetCheck_name('OPTION_CHECK');
/*收款用户姓名		re_user_name	可选	 String	收款用户真实姓名。 如果check_name设置为FORCE_CHECK或OPTION_CHECK，则必填用户真实姓名*/
$input->SetRe_user_name($re_user_name);
$input->SetAmount($amount*100);
$input->SetDesc('FG峰购微信提现');
$res=WxPayApi::payToUser($input);
Log::DEBUG("\n".printf_info($res));
echo json_encode($res);;	exit();
?>
<!-- 如果错误，所返回的错误参数
NOAUTH			没有权限	没有授权请求此api	请联系微信支付开通api权限
AMOUNT_LIMIT	付款金额不能小于最低限额	付款金额不能小于最低限额	每次付款金额必须大于1元
PARAM_ERROR		参数错误	参数缺失，或参数格式出错，参数不合法等	请查看err_code_des，修改设置错误的参数
OPENID_ERROR	Openid错误	Openid格式错误或者不属于商家公众账号	请核对商户自身公众号appid和用户在此公众号下的openid。
NOTENOUGH		余额不足	帐号余额不足	请用户充值或更换支付卡后再支付
SYSTEMERROR		系统繁忙，请稍后再试。	系统错误，请重试	使用原单号以及原请求参数重试
NAME_MISMATCH	姓名校验出错	请求参数里填写了需要检验姓名，但是输入了错误的姓名	填写正确的用户姓名
SIGN_ERROR		签名错误	没有按照文档要求进行签名 签名前没有按照要求进行排序。没有使用商户平台设置的密钥进行签名
参数有空格或者进行了encode后进行签名。
XML_ERROR	Post内容出错	Post请求数据不是合法的xml格式内容	修改post的内容
FATAL_ERROR	两次请求参数不一致	两次请求商户单号一样，但是参数不一致	如果想重试前一次的请求，请用原参数重试，如果重新发送，请更换单号。
CA_ERROR	证书出错	请求没带证书或者带上了错误的证书
到商户平台下载证书
请求的时候带上该证书
-->
