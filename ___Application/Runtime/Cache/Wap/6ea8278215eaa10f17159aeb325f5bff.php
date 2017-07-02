<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>确认订单-<?php echo ($webseting["web_title"]); ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
<meta name="format-detection" content="telephone=no">
<meta name="apple-mobile-web-app-capable" content="yes">
<script type="text/javascript" src="__PUBLIC__/wap/js/flexible.js"></script>
<script type="text/javascript" src="__PUBLIC__/wap/js/jquery.min.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/jquery.form.js"></script>
<script type="text/javascript" src="__PUBLIC__/layer/layer.js"></script>
<script src="__PUBLIC__/bootstrap/vendor/jquery-validation/jquery.validate.wap.js"></script>
<link rel="stylesheet" type="text/css" href="__PUBLIC__/wap/css/icons.css">
<link rel="stylesheet" type="text/css" href="__PUBLIC__/wap/css/base.css" />
<link rel="stylesheet" type="text/css" href="__PUBLIC__/wap/css/component.css" />
<link rel="stylesheet" type="text/css" href="__PUBLIC__/wap/css/member.css" />
<link rel="stylesheet" type="text/css" href="__PUBLIC__/wap/css/address.css">
</head>
<body class="pay" ontouchstart="" onmouseover="">
<div class="head">
	<a href="javascript:history.go(-1);" class="md-close back"><i class="iconfont">&#xe617</i></a>
	<h2 >确认订单</h2>
</div>
<div class="cartlist">

	<form action="" role="form" id="form1" novalidate method="post" onSubmit="return false;">
		<ul>
			<?php if(is_array($list['goods_list'])): $i = 0; $__LIST__ = $list['goods_list'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li  class="goods">
					<div class="goodsimg imgnobg">
						<img src="<?php echo ($vo["goods_img"]); ?>" class="img" alt="<?php echo ($vo["goods_name"]); ?>" />
					</div>
					<div class="info">
						<div class="title">
							<?php if(($vo['rec_type']) > "0"): ?>【<?php echo ($vo["activity_name"]); ?>】<?php endif; ?>
							<?php echo ($vo["goods_name"]); ?> <?php echo ($vo["goods_attr"]); ?>
						</div>
						<div class="pay">
							<div class="pirce num orange">
								¥<?php echo ($vo["all_goods_price"]); ?>
							</div>
							<div class="qty">
								<span class="num"><?php echo ($vo["goods_number"]); ?></span>件
							</div>
						</div>
					</div>


					<div class="clearfix">
					</div>
				</li><?php endforeach; endif; else: echo "" ;endif; ?>
		</ul>
		<input type="hidden" name="address_id" id="address_id" value="<?php echo ($default_address["address_id"]); ?>" />
		<input type="hidden" name="cart_id" value="<?php echo ($_GET['cart_id']); ?>">
		<input type="hidden" name="need_pay" value="<?php echo ($list["need_pay"]); ?>">
		<input type="hidden" name="discount" value="<?php echo ($_GET['discount']); ?>">
	</form>

</div>



<?php if(empty($default_address)): ?><div class="noaddress">
	<a href="#" class="md-trigger "  data-modal="modal-2"><i class="iconfont icon">&#xe684</i>
		<div class="addinfo">
			您还没添加收货地址<br>
			请先添加收货地址
		</div>
	</a>
</div>
<div class="address" style="display:none;">
<?php else: ?>
<div class="address"><?php endif; ?>
	<i class="iconfont icon">&#xe621</i>
	<div class="addinfo">
		<dl class="name">
			<dt>收件人：</dt>
			<dd class="realname"><?php echo ($default_address["consignee"]); ?></dd>
			<dd class="tel num mobile"><?php echo ($default_address["mobile"]); ?></dd>
		</dl>
		<dl class="add">
			<dt>收货地址：</dt>
			<dd class="address_name"><?php echo ($default_address["address_name"]); ?></dd>
			<dd class="addsub address_info"> <?php echo ($default_address["address"]); ?></dd>
			<div class="clearfix">
			</div>
		</dl>
	</div>
	<div class="clearfix">
	</div>
	<div class="addedit">
		<a href="#" class="md-trigger "  data-modal="modal-2">
		<span>更改</span><i class="iconfont">&#xe611</i>
		</a>
	</div>
</div>

<!--<div class="payinfo">
	<dl>
		<dt>配送方式</dt>
		<dd>快递包邮</dd>
	</dl>
</div>-->

<div class="submit" id="set_order">
	<?php if(empty($default_address)): ?><a type="button" class="button off" id="no-address-btn">确认订单</a>
	<?php else: ?>
		<a type="submit" class="button " id="submit-btn" onclick="set_order()">确认订单</a><?php endif; ?>
</div>
<!--选择收货地址-->
<div class="md-modal md-effect-2" id="modal-2">
	<div class="md-content">
		<div class="head">
			<a class="md-close back"><i class="iconfont">&#xe617</i></a>
			<h2 >选择收货地址</h2>
		</div>
		<div class="addlist">
			<ul id="address_list">
				<?php if(is_array($address)): $i = 0; $__LIST__ = $address;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li class='address_box_<?php echo ($vo["address_id"]); ?> <?php if($vo['address_id'] == $default_address['address_id']): ?>on<?php endif; ?>' address_id="<?php echo ($vo["address_id"]); ?>" consignee="<?php echo ($vo["consignee"]); ?>" mobile="<?php echo ($vo["mobile"]); ?>" address_name="<?php echo ($vo["address_name"]); ?>" address="<?php echo ($vo["address"]); ?>">
					<i class="iconfont on">&#xe621</i>
					<div class="addinfo">
						<dl class="name">
							<dt>收件人：</dt>
							<dd><?php echo ($vo["consignee"]); ?></dd>
							<dd class="tel num"><?php echo ($vo["mobile"]); ?></dd>
						</dl>
						<dl class="add">
							<dt>收货地址：</dt>
							<dd><?php echo ($vo["address_name"]); ?></dd>
							<dd class="addsub"> <?php echo ($vo["address"]); ?></dd>
							<div class="clearfix">
							</div>
						</dl>
					</div>
				</li><?php endforeach; endif; else: echo "" ;endif; ?>
			</ul>
		</div>
		<div class="submit">
			<a href="#" class="md-trigger "  data-modal="modal-3">
			新增收货地址
			</a>
		</div>
	</div>
</div>
<!--新增收货地址-->
<div class="md-modal md-effect-2" id="modal-3">
	<div class="md-content">
		<div class="head">
			<a class="md-close back"><i class="iconfont">&#xe617</i></a>
			<h2 >新增收货地址</h2>
		</div>
		<div class="page pdt10 member">
			<form id="form2" class="mlogin " action="" method="post" role="form" onSubmit="return false;">
				<div class="field autoClear">
					<div class="label">
						收件人
					</div>
					<div class="field-control">
						<input type="text" class="input-required" name="consignee" placeholder="请填写收件人的真实姓名" value="" id="consignee">
					</div>
				</div>
				<div class="field autoClear">
					<div class="label">
						手机号码
					</div>
					<div class="field-control">
						<input type="text" class="input-required" name="mobile" placeholder="请填写收件人的手机号码" value="" id="mobile">
					</div>
				</div>
				<div class="field autoClear">
					<div class="label">
						省市地区
					</div>
					<div class="field-control">
						<input type="text" class="input-required" readonly placeholder="请选择地区" value="" name="area_name" id="select_area">
						<input id="area_info" type="hidden" name="area_info" />
					</div>
				</div>
				<div class="field autoClear">
					<div class="label">
						详细地址
					</div>
					<div class="field-control">
						<input type="text" class="input-required" name="address" placeholder="请填写详细地址" value="">
					</div>
				</div>
				<div class="field autoClear">
					<div class="label">
						邮编
					</div>
					<div class="field-control">
						<input type="text" class="input-required" name="zipcode" placeholder="请填写邮编" value="">
					</div>
				</div>
			</form>

		<div class="submit">
			<a href="javascript:;" id="save_address">保存收货地址</a>
		</div></div>
	</div>
</div>

<div class="md-overlay">
</div>

</body>
<script type="text/javascript" src="__PUBLIC__/wap/js/jquery.min.js"></script>
<script src="__PUBLIC__/bootstrap/vendor/jquery-validation/jquery.validate.wap.js"></script>
<script src="__PUBLIC__/js/jquery.form.js"></script>
<script type="text/javascript" src="__PUBLIC__/layer/layer.js"></script>
 <script type="text/javascript" src="__PUBLIC__/js/common.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/return-top.js"></script>

<script src="__PUBLIC__/wap/js/classie.js"></script>
<script src="__PUBLIC__/wap/js/modalEffects.js"></script>
<script src="__PUBLIC__/wap/js/address.js"></script>
<script>
var province = <?php echo ($city_group["province"]); ?>, city = <?php echo ($city_group["city"]); ?>, area = <?php echo ($city_group["area"]); ?>;
    var area2 = new LArea();
    area2.init({
        'trigger': '#select_area',
        'valueTo': '#area_info',
        'keys': {
            id: 'value',
            name: 'text'
        },
        'type': 2,
        'data': [province, city, area]
    });

jQuery(document).ready(function() {
	//FormValidator.init();
	jQuery.validator.addMethod("byteRangeLength", function(value) {
		var area_info=$("#area_info").val();
		var ret = false;
		if(area_info > 0){
			ret = true;
		}
		return ret;
	});
	jQuery.validator.addMethod ("checkType",function(value){
		var re =/^[\u4e00-\u9fa5]*$/.test($("#consignee").val());
		var ret = false;
		if(re){
			ret = true;
		}
		return ret;
	});
	$('#no-address-btn').click(function () {
		layer.msg('请添加收货地址', {icon:5});
	});

	$("#form2").validate({
		rules: {
			consignee:{
				required:true,
				checkType:true
			},
			mobile:{
				required:true,
				number:true,
				minlength:11,
				maxlength:11
			},
			select_area: 'byteRangeLength',
			address:'required'
		},
		messages: {
			consignee:{
				required:'',
				checkType:'<span class="icon-warning icon_warning"></span>&nbsp;姓名只能输入中文'
			},
			mobile:{
				required:'',
				number:'<span class="icon-warning icon_warning"></span>&nbsp;请输入正确的手机号码',
				minlength:'<span class="icon-warning icon_warning"></span>&nbsp;请输入正确的手机号码',
				maxlength:'<span class="icon-warning icon_warning"></span>&nbsp;请输入正确的手机号码'
			},
			select_area: '<span class="icon-warning icon_warning"></span>&nbsp;请选择完整的省市区',
			address:''
		},
		focusInvalid:false,
		success: function(label) {
			// set  as text for IE 
			// alert('ok'); 
			//label.html(" ").addClass("checked"); 
		}, 
		/*执行ajaxsubmit  */ 
		submitHandler: function(editform){
			var options = {
				url : "<?php echo U('Wap/Address/set_address'); ?>", 
				type : "post" , 
				dataType : 'json', 
				target : "#loader", 
				error: function(){layer.msg("服务器没有返回数据，可能服务器忙，请重试",{icon:5});}, 
				onwait : "正在处理信息，请稍候...",
				success: function(res){
					//$("#loader").fadeIn(500).html(res.data).fadeOut(500);
					//$('#editform').hide(2000); 
					if(res.status==1){
						var data = res.save_data;
						var html = '';
						html = '<li class="address_box_'+data.address_id+' on" address_id="'+data.address_id+'" consignee="'+data.consignee+'" mobile="'+data.mobile+'" address_name="'+data.address_name+'" address="'+data.address+'">';
						html += '<i class="iconfont on">&#xe621</i><div class="addinfo"><dl class="name"><dt>收件人：</dt>'
						html += '<dd>'+data.consignee+'</dd>';
						html += '<dd class="tel num">'+data.mobile+'</dd></dl>';
						html += '<dl class="add"><dt>收货地址：</dt>';
						html += '<dd>'+data.address_name+'</dd>';
						html += '<dd class="addsub">'+data.address+'</dd>';
						html += '<div class="clearfix"></div></dl></div></li>';

						$('#address_list li').removeClass('on');
						$('#address_list').append(html);

						//$(document).on('click', '.address_box_'+data.address_id);

						$('.noaddress').hide();
						$('.address').show();
						select_addr(data.consignee, data.mobile, data.address_name, data.address, data.address_id);
						layer.msg('添加成功', {icon:6});
						$('#set_order').html('<a type="submit" class="button " id="submit-btn" onclick="set_order()">确认订单</a>');
						$('#modal-3').removeClass('md-show');
					}else{
						layer.msg(res.error, {icon:5});
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

	$('#save_address').click(function(){
		$('#form2').submit();
	});

	$(document).on('touchstart', '#address_list li', function(){
		var _this = $(this);
		consignee = _this.attr('consignee');
		mobile = _this.attr('mobile');
		address_name = _this.attr('address_name');
		address = _this.attr('address');
		address_id = _this.attr('address_id');

		$('#address_list li').removeClass('on');
		$(this).addClass('on');

		select_addr(consignee, mobile, address_name, address, address_id);
		$('#modal-2').removeClass('md-show');
	});

	function select_addr(consignee, mobile, address_name, address, address_id){
		$('.realname').html(consignee);
		$('.mobile').html(mobile);
		$('.address_name').html(address_name);
		$('.address_info').html(address);
		$('#address_id').val(address_id);
	}
});

var options = {
	url : "<?php echo U('wap/goodsorder/set_order'); ?>", 
	type : "post" , 
	dataType : 'json', 
	target : "#loader", 
	error : function(){layer.msg("服务器没有返回数据，可能服务器忙，请重试",{icon:5});}, 
	onwait : "正在处理信息，请稍候...",
	success : function(response){
		//console.log(response);
		//return false;
		//$("#loader").fadeIn(500).html(response.data).fadeOut(500); 
		//$('#editform').hide(2000); 
		if(response.status==1){
			//alert(response.pay_sn);
			window.location.href="<?php echo U('wap/goodsorder/order_pay'); ?>?order_id="+response.add_order;
		}else if(response.status==2){
			//alert(response.pay_sn);
			//未绑定 手机
			layer.msg(response.error, {icon: 5,time:2000},function(){
				get_binding();
			});
		}else{
			//alert(response.error);
			if(response.error){
				layer.msg(response.error, {icon: 5});
			}else{
				layer.msg('该订单已提交', {icon: 5});
			}
		}
	}
};
function set_order() {
    var cart_id=$("input[name='cart_id']").val();
    var address_id=$("#address_id").val();

    if(!cart_id){
       layer.msg('商品信息错误', {icon: 5});
       //alert("商品信息错误");
    }else{
		setTimeout((function(opt){
			return function(){
				$('#form1').ajaxSubmit(opt);
			}
		})(options), 300);
    }
};
</script>
</html>