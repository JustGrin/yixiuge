<?php 
ini_set('date.timezone','Asia/Shanghai');
//error_reporting(E_ALL);
require_once "lib_new/WxPay.Api.php";
require_once "unit/WxPay.JsApiPay.php";
require_once 'unit/log.php';

//初始化日志
$logHandler= new CLogFileHandler("./logs/".date('Y-m-d').'.log');
$log = Log::Init($logHandler, 15);
 
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

$order_pay_info['subject']='商品购买_'.$order_pay_info['pay_sn'];    
//获取用户openid
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

//统一下单
$input = new WxPayUnifiedOrder();
$input->SetBody($order_pay_info['subject']);
$input->SetAttach($order_pay_info['subject']);
$input->SetOut_trade_no(WxPayConfig::MCHID.date("YmdHis"));
$input->SetTotal_fee("1");
$input->SetTime_start(date("YmdHis"));
$input->SetTime_expire(date("YmdHis", time() + 7200));
$input->SetGoods_tag($order_pay_info['subject']);
$url_l="http://".$_SERVER['HTTP_HOST'].'/index.php/wap/returnmallpayment/index';
$input->SetNotify_url($url_l);
$input->SetTrade_type("JSAPI");
$input->SetOpenid($openId);
$order = WxPayApi::unifiedOrder($input);
//echo '<font color="#f00"><b>统一下单支付单信息</b></font><br/>';
if($_SESSION['pay_user_open_id']=='oupArwAl7xuME4nkXQgHxX4HRXQA'){
	printf_info($order);
}
if(empty($order)){
    echo '<script type="text/javascript">alert("支付错误，请刷新页面重试");history.go(-1);</script>';die;
}
if($order['return_code']=='FAIL'){
    $return_data['error']=$order['return_msg'];
  echo '<script type="text/javascript">alert("'.$order['return_msg'].'");history.go(-1);</script>';die;
}
if($order['return_msg']!='OK'){
    $return_data['error']=$order['return_msg'];
   echo '<script type="text/javascript">alert("'.$order['return_msg'].'");history.go(-1);</script>';die;
}
//printf_info($order);
$jsApiParameters = $tools->GetJsApiParameters($order);
//echo $jsApiParameters;
$return_url="http://".$_SERVER['HTTP_HOST'].'/index.php/wap/goodsorder/order_pay/pay_sn/'.$order_pay_info['pay_sn'];
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
					//alert(res.err_code+res.err_desc+res.err_msg);
					if(res.err_msg == "get_brand_wcpay_request:ok" ) {
					 window.location.href="<?php echo $return_url;?>"; 
					}else{
						alert('未支付成功');
						 window.location.href="<?php echo $return_url;?>"; 
					}
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