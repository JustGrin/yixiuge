<?php 
ini_set('date.timezone','Asia/Shanghai');
error_reporting(E_ERROR);
require_once "lib/WxPay.Api.php";
require_once "WxPay.JsApiPay.php";
require_once 'log.php';

//初始化日志
$logHandler= new CLogFileHandler("./logs/".date('Y-m-d').'.log');
$log = Log::Init($logHandler, 15);

//打印输出数组信息
function printf_info($data)
{
    foreach($data as $key=>$value){
        echo "<font color='#00ff55;'>$key</font> : $value <br/>";
    }
}
$order_pay_info['pay_sn']=$_GET['pay_sn'];
$order_pay_info['pay_amount']=$_GET['pay_amount'];
$order_pay_info['subject']=$_GET['subject'];
$url='pay_sn='.$order_pay_info['pay_sn']
     .'&pay_amount='.$order_pay_info['pay_amount']
     .'&subject='.$order_pay_info['subject'];
//$url=urlencode($url);     
$url= "http://".$_SERVER['HTTP_HOST'].'/api/payment/wpay/jsapi.php?'.$url;

//①、获取用户openid
$tools = new JsApiPay();

if($_GET['openid']){
	$_SESSION['pay_user_open_id']=$_GET['openid'];
}
if($_SESSION['pay_user_open_id']){
	$openId=$_SESSION['pay_user_open_id'];
}else{
	$openId = $tools->GetOpenid($url);
   $_SESSION['pay_user_open_id']=$openId;
}

$order_pay_info['subject']='商品购买_'.$order_pay_info['pay_sn'];
//②、统一下单
$input = new WxPayUnifiedOrder();
$input->SetBody($order_pay_info['subject']);
$input->SetAttach($order_pay_info['subject']);
$input->SetOut_trade_no($order_pay_info['pay_sn']);

$pay_amount=$order_pay_info['pay_amount']*100;//分
$input->SetTotal_fee($pay_amount);

$input->SetTime_start(date("YmdHis"));
if($_SESSION['pay_user_open_id']=='oupArwAl7xuME4nkXQgHxX4HRXQA'){
	$input->SetTime_expire(date("YmdHis", time() + 7200));
}else{
	$input->SetTime_expire(date("YmdHis", time() + 7200));
}

$input->SetGoods_tag($order_pay_info['subject']);

//$url_l="http://".$_SERVER['HTTP_HOST'].'/index.php/wap/returnpayment/index';
$url_l="http://".$_SERVER['HTTP_HOST'].'/api/payment/wpay/notify.php';
$url_l="http://".$_SERVER['HTTP_HOST'].'/index.php/wap/returnmallpayment/index';
$input->SetNotify_url($url_l);
$input->SetTrade_type("JSAPI");
$input->SetOpenid($openId);

$order = WxPayApi::unifiedOrder($input);
if($_SESSION['pay_user_open_id']=='oupArwAl7xuME4nkXQgHxX4HRXQA'){
	printf_info($order);
}
//echo '<font color="#f00"><b>统一下单支付单信息</b></font><br/>';
//printf_info($order);
$jsApiParameters = $tools->GetJsApiParameters($order);
if($_SESSION['pay_user_open_id']=='oupArwAl7xuME4nkXQgHxX4HRXQA'){
	var_dump('end');
}
$return_url="http://".$_SERVER['HTTP_HOST'].'/index.php/wap/goodsorder/order_pay/pay_sn/'.$order_pay_info['pay_sn'];
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

<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/> 

    <title>微信支付-支付</title>
      <link href="/Public/wap/css/rest.css" type="text/html" rel="stylesheet">
    <link href="/Public/wap/css/product.css" type="text/html" rel="stylesheet">  
    <script type="text/javascript">
	//调用微信JS api 支付
	function jsApiCall()
	{
		WeixinJSBridge.invoke(
			'getBrandWCPayRequest',
			<?php echo $jsApiParameters; ?>,
			function(res){
				WeixinJSBridge.log(res.err_msg);
				//alert(res.err_msg)
				if(res.err_msg == "get_brand_wcpay_request:ok" ) {
					 window.location.href="<?php echo $return_url;?>"; 
				}else{
					alert('未支付成功');
					 window.location.href="<?php echo $return_url;?>"; 
				}

				// 使用以上方式判断前端返回,微信团队郑重提示：res.err_msg将在用户支付成功后返回    ok，但并不保证它绝对可靠。 
			}
		);
	}

	function callpay()
	{
		if (typeof WeixinJSBridge == "undefined"){
		    if( document.addEventListener ){
		        document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
		    }else if (document.attachEvent){
		        document.attachEvent('WeixinJSBridgeReady', jsApiCall); 
		        document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
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
			'editAddress',
			<?php echo $editAddress; ?>,
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
	/*
	window.onload = function(){
		if (typeof WeixinJSBridge == "undefined"){
		    if( document.addEventListener ){
		        document.addEventListener('WeixinJSBridgeReady', editAddress, false);
		    }else if (document.attachEvent){
		        document.attachEvent('WeixinJSBridgeReady', editAddress); 
		        document.attachEvent('onWeixinJSBridgeReady', editAddress);
		    }
		}else{
			editAddress();
		}
	};*/
	
	</script>
</head>
<body class="payBoy">
    <div class="pay_View">
        <div class="pay_V_title">
            <h2>FG峰购 - 收银台</h2>
        </div>
        <div class="pay_V-nei">
            <ul>
              <!--   <li>
                  <label></label>
                  <div>
                     <h2> <?php echo $order_pay_info['subject'];?></h2>
                  </div>
              </li> -->
                <li>
                    <label>订单编号：</label>
                    <div>
                        <p><?php echo $order_pay_info['pay_sn'];?></p>
                    </div>
                </li>
                <li id="pay_V_JE">
                    <label>支付金额：</label>
                    <div>
                       <p class="pay_V_JE"><span>￥</span> <?php echo $order_pay_info['pay_amount'];?><span>元</span></p>
                    </div>
                </li>
                <li class="pay_Button" onclick="callpay()">
                    <a href="javascript:void(0);">
                        确认支付
                    </a>
                </li>
            </ul>
        </div>
    </div>

   
</body>
</html>