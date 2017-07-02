<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>会员登录<?php echo ($webseting["web_title"]); ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
<meta name="format-detection" content="telephone=no">
<script src="__PUBLIC__/wap/js/flexible.js"></script>
<link href="__PUBLIC__/wap/css/icons.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="__PUBLIC__/wap/css/base.css" />
<link rel="stylesheet" type="text/css" href="__PUBLIC__/wap/css/member.css" />
</head>
<body class="login">

<div class="head">

		<a href="<?php echo U('wap/index/index');?>" class="md-close back"><i class="iconfont">&#xe617</i></a>

	<h2>会员登录<?php echo ($webseting["web_title"]); ?></h2>
</div>
<div class="logo">
</div>
<form  class="mlogin" action="" role="form" id="form2" novalidate method="post" onSubmit="return false;">
	<div class="field autoClear">
		<div class="label">
			手机号
		</div>
		<div class="field-control">
			<input type="text" class="input-required" name="mobile" placeholder="请输入您注册时使用的手机号" value="" id="mobile">
		</div>
	</div>
	<div class="field autoClear">
		<div class="label">
			登录密码
		</div>
		<div class="field-control">
			<input type="password"  class="input-required" name="mem_password" placeholder="请输入密码" value="" id="mem_password">
		</div>
	</div>
	<div class="submit">
		<a  href="javascript: void(0);" class="phone_log_btn">
			<button type="button" class="button" id="submit-btn" >登录</button>
		</a>
	</div>
	<div class="other-link">
		<a href="<?php echo U('wap/login/findpwd');?>" class="floatLft">找回密码</a> <a href="<?php echo U('Wap/Login/register');?>" class="floatRgt">免费注册</a>
	</div>
</form>
<script type="text/javascript" src="__PUBLIC__/wap/js/jquery.min.js"></script>
<script src="__PUBLIC__/bootstrap/vendor/jquery-validation/jquery.validate.wap.js"></script>
<script src="__PUBLIC__/js/jquery.form.js"></script>
<script type="text/javascript" src="__PUBLIC__/layer/layer.js"></script>

<script>
	jQuery(document).ready(function() {
		//FormValidator.init();
		jQuery.validator.addMethod("byteRangeLength", function(value) {
			var dropdown=$("#dropdown").val();
			var ret=true;
			if(dropdown>0){
				if(!value){
					ret=false;
				}
			}
			return ret;
		});

		$("#form2").validate({
			rules: {
				mobile: {
					required:true,
					remote: {
						url: "<?php echo U('Wap/Login/cheack_account');?>",
						type: "get",
						dataType: "json",
						data: {
							mobile: function () {
								return $("#mobile").val();　　　　//这个是取要验证的数据
							}
						},
						dataFilter: function (msg) {　　　　//判断控制器返回的内容
							if (msg == "0") {
								return false;
							}else {
								return true;
							}
						}
					}
				},
				mem_password: {
					required: true,
					rangelength: [6, 20]
				}
			},
			messages: {
				mobile:{
					required:'<span class="icon-warning icon_warning"></span>&nbsp;请输入邮箱/手机/用户名',
					remote:'<span class="icon-warning icon_warning"></span>&nbsp;邮箱/手机/用户名不存在'
				},
				mem_password: {
					required:'<span class="icon-warning icon_warning"></span>&nbsp;请输入密码',
					rangelength:'<span class="icon-warning icon_warning"></span>&nbsp;密码请保持6-20位'
				}
			},
			focusInvalid:false,
			success: function(label) {
				// set  as text for IE 
				// alert('ok'); 
				//label.html(" ").addClass("checked"); 
			},
			/*执行ajaxsubmit  */
			submitHandler: function(editform) {

				var options = {
					url :  "<?php echo U('Wap/Login/dologin'); ?>",
					type : "post" ,
					dataType:'json',
					target : "#loader",
					error: function(){layer.msg("服务器没有返回数据，可能服务器忙，请重试",{icon:5});},
					onwait : "正在处理信息，请稍候...",
					success: function(response){
						console.log(response);
						//$("#loader").fadeIn(500).html(response.data).fadeOut(500); 
						//$('#editform').hide(2000); 
						if(response.status==1){
							// alert("登录成功");
							window.location.href=response.return_url;
						}else{

							layer.msg(response.error, {icon: 5});
							//alert(response.error);
						}
					}
				};
				setTimeout((function(opt){
					return function(){
						$('#form2').ajaxSubmit(opt);
					}
				})(options), 300);
				return false;
			}

		});

		$('.phone_log_btn').click(function(){
			$('#form2').submit();
		});
	});
</script>


</body>
</html>