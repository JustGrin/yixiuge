<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>订单详情-<?php echo ($webseting["web_title"]); ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
<meta name="format-detection" content="telephone=no">
<meta name="apple-mobile-web-app-capable" content="yes">
<script src="__PUBLIC__/wap/js/flexible.js"></script>
<script type="text/javascript" src="__PUBLIC__/wap/js/jquery.min.js"></script>
<script type="text/javascript" src="__PUBLIC__/bootstrap/vendor/jquery-validation/jquery.validate.wap.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/jquery.form.js"></script>
<script type="text/javascript" src="__PUBLIC__/layer/layer.js"></script>
<link href="__PUBLIC__/wap/css/icons.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="__PUBLIC__/wap/css/base.css" />
<link rel="stylesheet" type="text/css" href="__PUBLIC__/wap/css/order.css" />
</head>
<body class="orderinfo">
<div class="orderstate"  style="background:#6ab42f  url(../images/member/1.png) no-repeat 90% center;background-size: auto 100%; ">
	<div class="state">
		<h4>
			<?php if($data["pay_status"] == 2): if($data["offline"] == 1): ?>货到付款
					<?php else: ?>
					<?php echo ($pay_status[$data['pay_status']]); endif; ?>
				<?php else: ?>
				<?php echo ($pay_status[$data['pay_status'] + 100]); endif; ?>
		</h4>
		<!--<p class="time">剩2天23小时自动关闭</p>-->
	</div>
</div>
<!--<a href="#">
		<div class="shiping">
			<i class="iconfont icon">&#xe632</i>
			<div class="shipinfo">
				<h4>您的商品已经通过快递发送给您</h4>
				<p><span class="num">2016-03-16 11:00:24</span></p>
			</div>
			<i class="iconfont more">&#xe611</i>
		</div>
	</a>-->
<div class="address">
	<i class="iconfont icon">&#xe621</i>
	<div class="addinfo">
		<dl class="name">
			<dt>收件人：</dt>
			<dd><?php echo ($data['consignee']); ?></dd>
			<dd class="tel num"><?php echo ($data['mobile']); ?></dd>
		</dl>
		<dl class="add">
			<dt>收货地址：</dt>
			<dd><?php echo ($data['address']); ?></dd>
			<dd class="addsub"><!--详细地址--></dd>
			<div class="clearfix">
			</div>
		</dl>
	</div>
	<div class="clearfix">
	</div>
</div>
<div class="cartlist">

		<div class="ordermessage clearfix font34">
			<span class="">订单编号：<span class="num"><?php echo ($data["order_sn"]); ?></span></span>
			<span class="floatRgt">订单时间：<span class="num"><?php echo (date("Y-m-d",$data["add_time"])); ?></span></span>
		</div>
		<ul>
			<?php $total_num = 0;?>

				<?php if(is_array($order_goods)): $i = 0; $__LIST__ = $order_goods;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; if($vo['order_id'] == $data['order_id']): ?><li  class="goods">
							<a href="#">
								<div class="goodsimg imgnobg">
									<img src="<?php echo ($vo["goods_image"]); ?>" class="img" alt="<?php echo ($vo["goods_name"]); ?>" />
								</div>
							</a>
							<div class="info">
								<a href="<?php echo U('Wap/goods/goods_detail',array('id'=>$vo['goods_id']));?>">
									<div class="title"> <?php echo ($vo["goods_name"]); ?> </div>
									<div class="type">
										<span><?php echo ($vo["goods_attr"]); ?></span>
									</div>
								</a>
								<div class="pay">
									<div class="pirce num orange"> ¥<?php echo ($vo["goods_price"]); ?> </div>
									<div class="qty">
										<span class="num"><?php echo ($vo["goods_number"]); ?></span>件
										<?php $total_num += $vo['goods_number'];?>
									</div>
									<div class="pirce ">
										<?php if(($vo['shipping_money']) == "0"): ?>包邮
										<?php else: ?>
											运费:<span class="num orange">¥<?php echo ($vo["shipping_money"]); ?></span><?php endif; ?>
									</div>
									<div class="clearfix"></div>
								</div>
							</div>
							<div class="clearfix"></div>
						</li><?php endif; endforeach; endif; else: echo "" ;endif; ?>
		</ul>
		<div class="total">
			<ul>
				<li class="subtotal">
					共<span class="num black"> <?php echo ($total_num); ?> </span>件商品 合计：
					<span class=" num orange">
						¥<?php echo (priceformat($data['order_amount_all'])); ?>
						<span class="num gray">(含运费¥<?php echo (priceformat($data['shipping_fee'])); ?>)</span>
					</span>
				</li>
			</ul>
		</div>
		<div class="clearfix"></div>

	<form action="" role="form" id="form2" novalidate method="post" onSubmit="return false;">
		<div class="paytype mgt2">
			<a href="javascript:get_weixin_pay();" class="wx">
				<i class="hbFntWes icon ">&#xf1d7</i>微信支付
			</a>
			<?php if(( $points > 0) and $only_weixinpay != 1): if($points > 0): ?><div class="jf clearfix">
						<div class="use_integral"><i class="iconfont icon">&#xe643</i>使用积分支付<span class="floatRgt"><b class="num green"><?php echo ($points); ?></b> 分</span></div>
						<input id="use_integral" name="use_integral" type="hidden" value="0">
						<div class="clearfix" style="border:none; display:none;"></div>
					</div><?php endif; ?>

				<input type="hidden"  class="input-required green" name="mem_password" placeholder="请输入您的密码" value="" id="mem_password">
				<script>
				
			</script>
				<?php else: ?>
				<input name="use_balance" type="hidden" value="0">
				<input name="use_integral" type="hidden" value="0"><?php endif; ?>
			<div class="clearfix"></div>
		</div>
		<input name="pay_type" type="hidden" value="pay_online">
		<input name="order_id" id="order_id" type="hidden" value="<?php echo ($order_id); ?>" placeholder="">
	</form>
	<div class="clearfix"></div>
</div>
<div class="submit">
	<a href="javascript:void(0);" id="go_pay" data="<?php echo ($data['pay_record_id']); ?>" is_pwd=0 data-tpa="GOTO_CHECKOUT" class="new-abtn-type new-mg-tb30 phone_log_btn">
		去支付
	</a>
</div>
<div class="payFs m-t-15  pay_online_goo" style="display: none;">
	<!--<div class="order-width">
			<a href="javascript:get_alipay_pay();" style="margin: 20px 0;" id="zfb" class="new-abtn-type pay_online_type onPay" data="alipay">支付宝</a>
		</div>-->
	<?php if($is_weixin == 1): ?><div class="order-width">
			<a href="javascript:get_weixin_pay();" id="wx" class="new-abtn-type pay_online_type green " data="wxpay">
				<i class="hbFntWes ">&#xf1d7</i>微信支付
			</a>
		</div><?php endif; ?>
</div>
<script>
	//使用余额
	$('.use_balance').click(function(){
		var data = parseInt($('#go_pay').attr('is_pwd'));
		if($('#use_balance').val() == 0){
			$('#use_balance').val(1);
			$('.ye').addClass('on');
			$('#go_pay').attr('is_pwd', data + 1);
		}else{
			$('#use_balance').val(0);
			$('.ye').removeClass('on');
			$('#go_pay').attr('is_pwd', data - 1);
		}
		//pwd_is_show();
	});
	
	//使用积分
	$('.use_integral').click(function(){
		var data = parseInt($('#go_pay').attr('is_pwd'));
		if($('#use_integral').val() == 0){
			$('#use_integral').val(1);
			$('.jf').addClass('on');
			$('#go_pay').attr('is_pwd', data + 1);
		}else{
			$('#use_integral').val(0);
			$('.jf').removeClass('on');
			$('#go_pay').attr('is_pwd', data - 1);
		}
		//pwd_is_show();
	});
	function go_pay_online(){
		layer.closeAll(); //疯狂模式，关闭所有层
		layer.open({
			type: 1,
			shade: 0.8,//透明度
			title: false, //不显示标题
			skin: 'layui-layer-demo', //样式类名
			shadeClose: true, //开启遮罩关闭
			closeBtn: 0, //不显示关闭按钮
			area: ['80%', 'auto'],
			scrollbar: false,//浏览器滚动锁定
			content: $('.pay_online_goo'), //捕获的元素
			cancel: function(index){

				layer.close(index);
				this.content.show();
				$('.pay_online_goo').hide();
				//layer.msg('捕获就是从页面已经存在的元素上，包裹layer的结构',{time: 5000});
			}
		});
	}
	
	function get_weixin_pay(url){
		///需要支付
		var pay_url="<?php echo 'http://'.$_SERVER['HTTP_HOST'].'/api/payment/wpay/jsapi_go_pay.php?pay_sn='.$pay_sn.'&order_id='.$order_id; ?>";
		window.location.href=pay_url+"&openid=<?php echo $_SESSION['user_open_id'];?>";
	}
	
	jQuery(document).ready(function() {
		//FormValidator.init();
		jQuery.validator.addMethod("passwordRequired", function(value) {
			var use_balance=$('input[name="use_balance"]').val();
			var use_integral=$('input[name="use_integral"]').val();
			var ret=true;
			if(use_balance == 1 || use_integral ==1 ){
				if(!value){
					ret=false;
				}
			}
			return ret;
		});

		$("#form2").validate({
			/*rules: {
				mem_password: {
					passwordRequired: true,
					rangelength: [6, 20]
				}
			},
			messages: {
				mem_password:{
					passwordRequired:'<span class="icon-warning icon_warning"></span>&nbsp;请输入密码',
					rangelength:'<span class="icon-warning icon_warning"></span>&nbsp;密码请保持6-20位'
				}
			},*/
			focusInvalid:false,
			success: function(label){},
			/*执行ajaxsubmit  */
			submitHandler: function(editform){
				var options = {
					url :  "<?php echo U('Wap/goodsorder/go_pay'); ?>",
					type : "post" ,
					dataType:'json',
					target : "#loader",
					error: function(){layer.msg("服务器没有返回数据，可能服务器忙，请重试",{icon:5});},
					onwait : "正在处理信息，请稍候...",
					success: function(response){
						$('#mem_password').val('');
						console.log(response);
						//return false;
						if(response.status==1){
							if(response.need_pay==1){
								//go_pay_online();
								var pay_url="<?php echo 'http://'.$_SERVER['HTTP_HOST'].'/api/payment/wpay/jsapi_go_pay.php?pay_sn='.$pay_sn.'&order_pay_sn='.$order_pay_sn; ?>";
								window.location.href=pay_url+"&openid=<?php echo $_SESSION['user_open_id'];?>";
							}else{
								layer.msg('支付成功',{icon:6,time: 1500},function(){
									window.location.href="<?php  echo U('wap/goodsorder/order_detail',array('order_id'=>$order_id));?>";
								});
							}
						}else{
							layer.msg(response.error,{icon:5, time:1000}, function(){
								if(response.error == '密码错误'){
									$('#go_pay').click();
								}
							});
						}
					}
				};
				setTimeout((function(opt){
					return function(){
						layer.msg("正在处理信息，请稍候...",{time:100000});
						$('#form2').ajaxSubmit(opt);
					}
				})(options), 300);
				return false;
			}
		});
		var activity_end_date = '<?php echo ($data["activity_end_date"]); ?>' ? '<?php echo ($data["activity_end_date"]); ?>' :0;
		var activity_id = <?php echo ($data["activity_id"]); ?>;

		$('#go_pay').click(function(){
			var is_pwd = $('#go_pay').attr('is_pwd');
			var mem_password = $('#mem_password').val();
			if(is_pwd && !mem_password){
				var msg_html = '<h6>输入您注册时填写的密码</h6><input type="password" value="" id="input_pwd" class="input_pwd" />';
				var index = layer.confirm(msg_html, {
					skin: 'pwd_box',
					btn: ['确定'], //按钮
					title:'请输入密码'
				}, function(){
					var input_pwd = $('#input_pwd').val();
					if(input_pwd.length < 6 || input_pwd.length > 20){
						alert('请输入正确的密码');
					}else{
						$('#mem_password').val(input_pwd);
						$('#go_pay').click();
						//layer.close(index);
					}
				}, function(){

				});
				return false;
			}
			if(activity_id == 1){
				var timestamp = new Date().getTime();
				if(activity_end_date < timestamp/1000){
					layer.msg("活动已结束",function () {
						window.location.href="<?php  echo U('wap/activity/oneYuan');?>";
					});
					return false;
				}
			}
			var use_balance=$('input[name="use_balance"]').val();
			var pay_type=$('input[name="pay_type"]').val();
			$('#form2').submit();
		});
	});
</script>
</body>
</html>