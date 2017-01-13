<?php 
ini_set('date.timezone','Asia/Shanghai');
error_reporting(E_ERROR);
function post_go($url,$data='')
{
	$post='';
	$row = parse_url($url);
	$host = $row['host'];
	$port = $row['port'] ? $row['port']:80;
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
 $user_agent = $_SERVER['HTTP_USER_AGENT'];
if (strpos($user_agent, 'MicroMessenger') === false) {
     echo '<script type="text/javascript">alert("请在微信上打开支付");history.go(-1);</script>';die;
}
$pay_sn=$_GET['pay_sn'];
if(empty($pay_sn)){
	echo '<script type="text/javascript">alert("订单号错误");history.go(-1);</script>';die;
}
$openid=$_GET['openid'];
$data['pay_sn']=$pay_sn;
$url="http://" . $_SERVER['HTTP_HOST'] . '/index.php/wap/Goodsorderquery/get_weixinpay_msg';
$re=post_go($url,$data);
$re=json_decode($re,true);
if(empty($re['status'])){
	echo '<script type="text/javascript">alert("'.$re['error'].'");history.go(-1);</script>';die;
}
$data=$re['data'];
$order_pay_info=$data;
/*
$jsApiParameters = $_GET['payvalue'];
$order_pay_info['pay_sn']=$_GET['pay_sn'];
$order_pay_info['pay_amount']=$_GET['pay_amount'];
if($_SESSION['pay_user_open_id']=='oupArwAl7xuME4nkXQgHxX4HRXQA'){
	var_dump($_GET);
}*/
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
	<script type="text/javascript" src="/Public/wap/js/jquery.min.js"></script>
	<script type="text/javascript" src="/Public/layer/layer.js"></script>

    <script type="text/javascript">
		var jsApiParameters;
	//调用微信JS api 支付
	function jsApiCall()
	{
		//var jsApiParameters=$('#jsApiParameters').val();
		WeixinJSBridge.invoke(
			'getBrandWCPayRequest',
				{
					"appId":jsApiParameters.appId,
					"nonceStr":jsApiParameters.nonceStr,
					"package":jsApiParameters.package,
					"signType":jsApiParameters.signType,
					"timeStamp":jsApiParameters.timeStamp,
					"paySign":jsApiParameters.paySign
				},
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
	<script type="text/javascript">
		function get_weixin_pay_val(){
			layer.msg("正在处理信息，请稍候...",{time:100000});
			 $.ajax({
			 url: "<?php echo 'http://'.$_SERVER['HTTP_HOST'].'/api/payment/wpay/get_pay_jsapi_test.php?pay_sn='.$data['pay_sn'].'&openid='.$openid; ?>",
			 type:'get',
			 // data: {order_id:order_id},
			 dataType: 'json',
			 success: function(data){
				 if(data.status==1){
					 jsApiParameters=data.payvalue;
					 layer.closeAll();
					 callpay();
				 }else{
				 layer.msg(data.error, {icon: 5});
				 //alert(data.error)
				 }
			 }
			 });
		}
	</script>
</head>
<body class="payBoy">
    <div class="pay_View">
        <div class="pay_V_title">
            <h2>Fg峰购 - 收银台</h2>
        </div>
        <div class="pay_V-nei">
			<input type="hidden" id="jsApiParameters" value="">
            <ul>
              <!--   <li>
                  <label></label>
                  <div>
                     <h2> <?php echo $order_pay_info['subject'];?></h2>
                  </div>
              </li> -->
			  <?php echo 'http://'.$_SERVER['HTTP_HOST'].'/api/payment/wpay/get_pay_jsapi_test.php?pay_sn='.$data['pay_sn'].'&openid='.$openid; ?>
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
                <li class="pay_Button" onclick="get_weixin_pay_val()">
                    <a href="javascript:void(0);">
                        确认支付
                    </a>
                </li>
            </ul>
        </div>
    </div>

   
</body>
</html>