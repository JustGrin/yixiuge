<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>菜篮子</title>
<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
<meta name="format-detection" content="telephone=no">
<meta name="apple-mobile-web-app-capable" content="yes">
<script src="__PUBLIC__/wap/js/flexible.js"></script>
<script type="text/javascript" src="__PUBLIC__/wap/js/jquery.min.js"></script>
<script type="text/javascript" src="__PUBLIC__/layer/layer.js"></script>
<link href="__PUBLIC__/wap/css/icons.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="__PUBLIC__/wap/css/base.css" />
<link rel="stylesheet" type="text/css" href="__PUBLIC__/wap/css/component.css" />
<link rel="stylesheet" type="text/css" href="__PUBLIC__/wap/css/cart.css" />
</head>
<body class="cart">
<div class="head">
	<a href="javascript:history.go(-1)" class="md-close back">
	<i class="iconfont">&#xe617</i>
	</a>
	<h2 >菜篮子 <span class="num"></span></h2>
</div>
<div class="page greenbg">
<div class="cartlist">
	<?php if(is_array($list['goods_list'])): $i = 0; $__LIST__ = $list['goods_list'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="goods cart_goods_<?php echo ($vo["rec_id"]); ?>">
			<div class="select">
				<input type="checkbox" id="cbtest_<?php echo ($vo["rec_id"]); ?>" checked="" data="<?php echo ($vo["rec_id"]); ?>"  class="cart_chk cart_chk_<?php echo ($vo["rec_id"]); ?>"/>
				<label for="cbtest_<?php echo ($vo["rec_id"]); ?>" class="check-box click click_<?php echo ($vo["rec_id"]); ?>" data="<?php echo ($vo["rec_id"]); ?>"></label>
			</div>
			<div class="goodsimg imgnobg">
				<a href="<?php echo U('wap/goods/goods_detail',array('id'=>$vo['goods_id']));?>">
				<div class="img bgcover " style=" background:url(<?php echo ($vo["goods_img"]); ?>)">
				</div>
				</a>
			</div>
			<div class="info">
				<div class="title">
					<?php echo ($vo["goods_name"]); ?>  <?php echo ($vo["goods_attr"]); ?>
				</div>
				<div class="editqty">
					<input type="hidden" class="good_price_<?php echo ($vo["rec_id"]); ?>" size="4" value="<?php echo ($vo["goods_price"]); ?>" name="good_price" data="<?php echo ($vo["rec_id"]); ?>">
					<?php if($vo['rec_type'] == 1): ?><a class="minus save_good save_good_<?php echo ($vo["rec_id"]); ?>" data="<?php echo ($vo["rec_id"]); ?>"  href="javascript:void(0);">
						-
						</a>
						<div class="num">
							<input type="text" style="border:none;width: 25px"  data="<?php echo ($data["rec_id"]); ?>" name="good_num" class="  good_num_<?php echo ($vo["rec_id"]); ?>" value="<?php echo ($vo["goods_number"]); ?>" readonly >
						</div>
						<a class="plus add_good add_good_<?php echo ($vo["rec_id"]); ?>" data="<?php echo ($vo["rec_id"]); ?>" href="javascript:void(0);">
						+
						</a>
						<?php else: ?>
						<a class="minus save_good save_good_<?php echo ($vo["rec_id"]); ?>" data="<?php echo ($vo["rec_id"]); ?>"   href="javascript:void(0);">
						-
						</a>
						<div class="num">
							<input type="text" data="<?php echo ($data["rec_id"]); ?>" name="good_num" class="good_num_<?php echo ($vo["rec_id"]); ?>" value="<?php echo ($vo["goods_number"]); ?>" readonly>
						</div>
						<a class="plus add_good add_good_<?php echo ($vo["rec_id"]); ?>" data="<?php echo ($vo["rec_id"]); ?>" href="javascript:void(0);">
						+
						</a><?php endif; ?>
				</div>
				<div class="pay">
					<div class="pirce">
						<span class="num orange">￥<?php echo ($vo["goods_price"]); ?></span>
						<span class="subpirce num gray textline"></span>
					</div>
				</div>
				<div class="del_good del_good_<?php echo ($vo["rec_id"]); ?>" data="<?php echo ($vo["rec_id"]); ?>" >
					<i class="iconfont">&#xe65a</i>
				</div>
			</div>
			<div class="clearfix">
			</div>
		</div><?php endforeach; endif; else: echo "" ;endif; ?>
</div>

<div class="nogoods" <?php if($list["all_num"] > 0): ?>style='display:none'<?php endif; ?>>
<i class="iconfont">&#xe612</i><br>

您的菜蓝子还是空空的哦，去看看心仪的商品吧……<br>

<a href="<?php echo U('wap/goods/index');?>">
去选购
</a>
</div>

</div>

<div class="bottomside"  <?php if($list["all_num"] == 0): ?>style='display:none'<?php endif; ?>>
	<div class="select_box floatLft">
		<div class="select floatLft">
			<input type="checkbox" id="cbtestall"  checked=""/>
			<label for="cbtestall" class="check-box all_check_c" checked=""></label>
		</div>
		&nbsp;&nbsp;
		<span class="gray3 floatLft">全选</span>
	</div>

<a onclick="javascript:void(0);" data-tpa="GOTO_CHECKOUT" class="go_pay floatRgt">结算</a>
<div class="totprice floatRgt"><span class="font34"><span class="all_num num green"><?php echo ($list["all_num"]); ?> </span> 件商品</span> 总价<span class="num green all_price">￥<?php echo ($list["need_pay"]); ?></span></div>
	<input type="hidden" name="cart_id" value="<?php echo ($list["cart_id"]); ?>"/>
	<input type="hidden" name="discount" value="0"/>


</div>



</body>
<script type="text/javascript">
	$(function(){
		get_goods_price();
		$('input[name="discount_val"]').change(function(){
			var _this=$(this);
			var check=_this.prop('checked');
			if(check){
				$('input[name="discount_val"]').prop('checked',false);
				_this.prop('checked',true);
			}
			get_goods_price();
		});
		
		//全选
		$('.all_check_c').click(function(){
			if($('#cbtestall').is(':checked')){
				$('input:checkbox').each(function(i, e){
					if(e.id != 'cbtestall'){
						var rec_id = $(this).attr('data');
						if($('#cbtest_'+rec_id).is(':checked')){
							//$('.click_'+rec_id).click();
							$('#cbtest_'+rec_id).prop('checked',false);
						}
					}
				});
			}else{
				$('input:checkbox').each(function(i, e){
					if(e.id != 'cbtestall'){
						var rec_id = $(this).attr('data');
						$('.click_'+rec_id).click();
						if(!$('#cbtest_'+rec_id).is(':checked')){
							//$('.click_'+rec_id).click();
							$('#cbtest_'+rec_id).prop('checked',true);
						}
					}
				});
			}
			//计算商品价格
			get_goods_price();
		});
	
		//选择商品
		$('.cart_chk').click(function(){
			var _this = $(this);
			var rec_id = $(this).attr('data');
			if($('#cbtest_'+rec_id).is(':checked')){
				var is_checked = true;
				$('input:checkbox').each(function(i, e){
					if(e.id != 'cbtestall'){
						var id = $(this).attr('data');
						if(!$('#cbtest_'+id).is(':checked')){
							is_checked = false;
						}
					}
				});
				if(is_checked){
					$('#cbtestall').prop('checked',true);
				}
			}else{
				$('#cbtestall').prop('checked',false);
			}
			
			//计算商品价格
			get_goods_price();
		});
		
		//增加数量
		$(".add_good").click(function(){
			var _this_add=$(this);
			var good_id=_this_add.attr('data');
			
			var _this_save=$(".save_good_"+good_id);
			var _this_del=$(".del_good_"+good_id);
			var _this_num=$(".good_num_"+good_id);
			var goods_num=_this_num.val();
			goods_num++;
			_this_num.val(goods_num);
			$.ajax({
				url: "<?php echo U('wap/cart/save_cart');?>",
				type:'get',
				data: {rec_id:good_id,goods_num:goods_num},
				dataType: 'json',
				success: function(data){
					if(data.status==1){
						//计算商品价格
						get_goods_price();
					}else{
						goods_num--;
						_this_num.val(goods_num);
						//alert(data.error)
						layer.msg(data.error, {icon: 5});
					}
				}
			});

		});
		//减少数量
		$(".save_good").click(function(){
			var _this_save=$(this);
			var good_id=_this_save.attr('data');
			var _this_num=$(".good_num_"+good_id);
			var goods_num=_this_num.val();
			goods_num--;
			if(goods_num<=0){
				goods_num=1;
				_this_num.val(1);
			}else{
				_this_num.val(goods_num);
			}
			$.ajax({
				url: "<?php echo U('wap/cart/save_cart');?>",
				type:'get',
				data: {rec_id:good_id,goods_num:goods_num},
				dataType: 'json',
				success: function(data){
					if(data.status==1){
						//计算商品价格
						get_goods_price();
					}else{
						goods_num++;
						_this_num.val(goods_num);
						//alert(data.error)
						layer.msg(data.error, {icon: 5});
					}
				}
			});
		});
		
		//删除数量
		$(".del_good").click(function(){
			var _this_del=$(this);
			var good_id=_this_del.attr('data');
			$.ajax({
				url: "<?php echo U('wap/cart/del_cat');?>",
				type:'get',
				data: {rec_id:good_id},
				dataType: 'json',
				success: function(data){
					if(data.status==1){
						$('.cart_goods_'+good_id).remove();
						//计算商品价格
						get_goods_price();
						var all_num=$('.all_num').html();
						if(all_num==0){
							$('.bottomside').hide();
							$('.nogoods').show();
						};
					}else{
						//alert(data.error)
						layer.msg(data.error, {icon: 5});
					}
				}
			});

		});

		//去支付
		$(".go_pay").click(function(){
			var all_num=$(".all_num").html();
			all_num=parseInt(all_num);

			if(all_num>0){
				var cart_id=$("input[name='cart_id']").val();
				var discount=$('input[name="discount"]').val();
				var url="<?php echo U('Wap/cart/order_add'); ?>?cart_id="+cart_id+'&discount='+discount;
				window.location.href=url;
			}
		});

	});
	
	//计算商品价格
	function get_goods_price(){
		var discount_consume=$('input[name="discount_consume"]').val();
		discount_consume=parseFloat(discount_consume);
		if(discount_consume<=0){
			discount_consume=1;
		}
		var total=parseFloat(0);
		var all_num=0;
		var goods_id_str='';
		$(".cart_chk").each(function(){
			var __this=$(this);
			if(__this.prop("checked")){
				var goods_id=__this.attr('data');
				var num=$('.good_num_'+goods_id).val();
				num=parseInt(num);
				var good_price=$(".good_price_"+goods_id).val();
				good_price=parseFloat(good_price);
				total=total+good_price*num;
				goods_id_str=goods_id_str+','+goods_id;
				all_num=all_num+num;
			}
		});
		if(goods_id_str){
			goods_id_str=goods_id_str.substr(1);
		}
		if(total>0){
			if(total<68){
				$('.shipping_fee').show();
			}else{
				$('.shipping_fee').hide();
			}
		}else{
			$('.shipping_fee').hide();
		}
		total=total.toFixed(2);
		var discount=get_discount();//折扣
		if(discount){
			$('.all_old_price').html('￥'+total);
			$('.discount_price').show();
			discount=parseFloat(discount);
			total=total*discount;
			total=total.toFixed(2);
		}else{
			$('.discount_price').hide();
		}
		if(total>0){
			$('.go_pay').removeAttr('style');
		}else{
			$('.go_pay').css('cssText','background-color:rgb(171, 168, 168) !important;');
		}
		$('.all_price').html('￥'+total);
		$('input[name="cart_id"]').val(goods_id_str);
		$('.all_num').html(all_num);
	}
	
	//获取折扣
	function get_discount(){
		_this=$('input[name="discount_val"]:checked');
		if(_this.length){
			$('input[name="discount"]').val(_this.val());
			return  _this.attr('data');
		}else{
			$('input[name="discount"]').val(0);
			return 0;
		}
	}
</script>
</html>