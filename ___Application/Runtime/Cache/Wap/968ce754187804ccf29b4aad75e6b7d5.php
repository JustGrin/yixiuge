<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
<meta name="format-detection" content="telephone=no">
<meta name="apple-mobile-web-app-capable" content="yes">
<title><?php echo ($data["goods_name"]); ?>-<?php echo ($webseting["web_title"]); ?></title>
<script src="__PUBLIC__/wap/js/flexible.js"></script>
<script type="text/javascript" src="__PUBLIC__/wap/js/jquery.min.js"></script>
<script type="text/javascript" src="__PUBLIC__/layer/layer.js"></script>
<script type="text/javascript" src="__PUBLIC__/wap/js/swiper.min.js"></script>
<link href="__PUBLIC__/wap/css/icons.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="__PUBLIC__/wap/css/base.css" />
<link rel="stylesheet" type="text/css" href="__PUBLIC__/wap/css/swiper.min.css" />
<link rel="stylesheet" type="text/css" href="__PUBLIC__/wap/css/public.css" />
</head>
<body class="goods" ontouchstart="" onmouseover="">

<?php if($data["is_sell_out"] == 1): ?><div class="is_sell_out"></div><?php endif; ?>

<div class="head">
	<a href="javascript:history.go(-1);" class="back">
	<i class="iconfont">&#xe617</i>
	</a>
	
	
	<a href="<?php echo U('Wap/Cart/index');?>" class="cart">
	<i class="iconfont">&#xe612</i>
	</a>
	
	
</div>
<div class="page">
	<div class="swiper-container goodsshow">
		<div class="swiper-wrapper">
			<div class="swiper-slide">
				<img data-src="<?php echo ($data["goods_img"]); ?>" class="swiper-lazy">
				<div class="swiper-lazy-preloader swiper-lazy-preloader-white">
				</div>
			</div>
			<?php if(is_array($good_image_url)): $i = 0; $__LIST__ = $good_image_url;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="swiper-slide">
					<img data-src="<?php echo ($vo["img_url"]); ?>" class="swiper-lazy">
					<div class="swiper-lazy-preloader swiper-lazy-preloader-white">
					</div>
				</div><?php endforeach; endif; else: echo "" ;endif; ?>
		</div>
		<div class="swiper-pagination swiper-pagination-white">
		</div>
	</div>
	<div class="maininfo">
		<div class="title">
			<h2><?php echo ($data["goods_name"]); ?></h2>

			<h4 class="feeling" <?php echo ($data['feeling'] ?'':'style="display: none"'); ?>><i class="iconfont">&#xe652</i> <?php echo ($data["feeling"]); ?></h4>
			<h4 class="subtitle" <?php echo ($data['subtitle'] ?'':'style="display: none"'); ?>>
				<?php echo ($data["subtitle"]); ?></h4>

			<a href="javascript:;" class="share <?php if($collect > 0): ?>on<?php endif; ?>
			" id="is_on">
			<i class='iconfont' id="pd_product-collect" data="<?php echo ($data["goods_id"]); ?>" data-type="<?php echo ($collect); ?>" data-tpa="H5_DETAIL_STOREUP" data-trackersend="1">
			&#xe62d
			</i>
			<span id="is_collect">
			<?php if($collect > 0): ?>已收藏
				<?php else: ?>
				收藏<?php endif; ?>
			</span><span class="num gray" id="detailFavNums" nums="<?php echo ($data["goods_collect"]); ?>"><?php echo ($data["goods_collect"]); ?></span>
			</a>
		</div>
		<div class="price mgt15">
			特惠价：<span class="num orange">¥<?php echo ($data["shop_price"]); ?></span>元
		</div>
		<div class="oldprice gray ">
			市场价:<span class="num gray">¥<?php echo ($data["market_price"]); ?></span>元
		</div>
		<div class="shipping mgt15 ">
			<?php if(($shipping_money) > "0"): ?>运费:<span class="num gray">¥<?php echo ($shipping_money); ?> </span>元
				<?php else: ?>
				包邮<?php endif; ?>

		</div>
		<div class="oem">
		<div class="form">
		<img src="<?php echo ($data["base_logo"]); ?>"  alt="基地头像">
		<span><?php echo ($data["base_name"]); ?></span>
		</div></div>
		<div class="goodsnum">
			当前选择：<span class="col02 now_ch num"> 1 </span>件
			<section class="select">
				<strong class="floatLft">数量：</strong>
				<a class="btn-del  save_good">
				-
				</a>
				<input type="text" data="548" name="good_num" class="fm-txt good_num num" value="1" id="number" readonly>
				<a class="btn-add add_good" id="plus">
				+
				</a>
			</section>
		</div>
	</div>
	<div class="content">
		<div class="clearfix">
		</div>
		<div class="info">
			<?php echo ($data["goods_desc"]); ?><br>
<img src="/Public/wap/img/service.png" width="100%" height="auto"	>
		</div>
		
	</div>
</div>
<?php if($data["is_sell_out"] == 1): ?><div class="bottomside sell_out">
<?php else: ?>
	<div class="bottomside">
	
    <a class="qq" href="http://q.url.cn/s/p7xpDJm"><i class="iconfont open">&#xe64e</i><span>在线QQ</span></a>
    <a class="tel closs" href="tel:02386716108"><i class="iconfont open ">&#xe65f</i><span>客服电话</span></a><?php endif; ?>

	
	<input type="hidden" name="goods_id" value="<?php echo ($data["goods_id"]); ?>" >
	<input type="hidden" name="goods_attr_id" value="" >
	<input type="hidden" name="share" value="<?php echo ($_GET['share']); ?>" >
	<input type="hidden" name="goods_price" value="<?php echo ($data["shop_price"]); ?>" >
	<input type="hidden" name="is_sell_out" value="<?php echo ($data["is_sell_out"]); ?>" >
	<input type="hidden" name="base_id" value="<?php echo ($data["base_id"]); ?>" >
	<?php if(empty($data['is_upgrade'])): ?><button class="buy" id="directorder"><i class="iconfont">&#xe623</i>直接购买</button>
		<button class="addcart" id="add_cart"><i class="iconfont">&#xe612</i>放入菜篮</button>
		<?php else: ?>
		<button class="buy" id="directorder_up"><i class="iconfont">&#xe623</i>直接购买</button><?php endif; ?>
</div>
<!--start 红包弹窗-->

   <div class="hongbao" style="display:none;">
              <a href="javascript:void(0)" id="read_point1" >
                <img id="read_point" width="100%" src="" border="0" alt="">
            </a>
    </div>  
   <style>
.layui-layer-demo{background: none !important;box-shadow: none !important;border-radius: 0px !important;}
</style>      
<script type="text/javascript">
$("#read_point1").click(function(){
	 layer.closeAll();
});
function read_point(num){
	$("#read_point").attr("src","__PUBLIC__/wap/img/read_point/"+num+"fen.png");
  //捕获页
	layer.open({
		type: 1,
		shade: 0.9,//透明度
		offset: '20%',//TOP距离
		closeBtn: 0,//按钮取消
		title: false, //不显示标题
		skin: 'layui-layer-demo', //样式类名
		shadeClose: true, //开启遮罩关闭
		area: ['80%', '80%'],
		content: $('.hongbao'), //捕获的元素
		cancel: function(index){
	
			layer.close(index);
			this.content.show();
			$('.hongbao').hide();
			//layer.msg('捕获就是从页面已经存在的元素上，包裹layer的结构',{time: 5000});
		}
	});

}
</script>
<!--end 红包弹窗-->
<script>
    var swiper = new Swiper('.swiper-container', {
        pagination: '.swiper-pagination',
        paginationClickable: true,
        preloadImages: false,
        lazyLoading: true,
				autoplay: 3000

    });


var timer = setInterval(time,1000);
var time_num = 0;
function time(){
	if(time_num<=4){
		time_num++;
	}
	
}
//在滚动条拉到90%以上时执行判断执行一次，每次刷新只有执行一次的机会
var base_points_num = 0;
$(document).scroll(function(){
	if($(document).scrollTop()/($(document).height()-$(window).height()) >= 0.95 && time_num>=4){
		if(base_points_num != 1){
			base_points_num = 1;
			setTimeout(base_points,300);
		}
	}
});
function base_points(){
	var goods_id = $('input[name="goods_id"]').val();
	var base_id = $('input[name="base_id"]').val();
	$.ajax({
		url: "<?php echo U('wap/goods/base_points');?>",
		type:'post',
		data: {goods_id:goods_id,base_id:base_id},
		dataType: 'json',
		success: function(data){
			if(data.status==1){
				setTimeout(read_point(data.msg),200);
			}else{
				//layer.msg(data.error, {icon: 5});
			}
		}
	});
}



$(function(){
	//收藏
	$("#pd_product-collect").click(function(){
        var _this=$(this);
		var goods_id=_this.attr('data');
		var type=_this.attr('data-type');
		$.ajax({
			url: "<?php echo U('wap/goods/goods_collect');?>",
			type:'post',
			data: {goods_id:goods_id},
			dataType: 'json',
			success: function(data){
				if(data.status==1){
					if(type>0){
						layer.msg('取消收藏成功', {icon: 6}); 
						//alert("取消成功")
						$("#is_collect").html('收藏');
						_this.attr('data-type',0);
						$('#is_on').removeClass('on');
						var sc=parseInt($('#detailFavNums').html());
						$('#detailFavNums').html(sc-1);
					}else{
						//alert("收藏成功")
						layer.msg('收藏成功', {icon: 6}); 
						$("#is_collect").html('已收藏');
						$('#is_on').addClass('on');
						_this.attr('data-type',1)
						var sc=parseInt($('#detailFavNums').html());
						$('#detailFavNums').html(sc+1);
					}
				}else{
					layer.msg(data.error, {icon: 5});
					//alert(data.error)
				}
			}
		});
	});
	
	//加入购物车
	$("#add_cart").click(function (event){
		var goods_id = $('input[name="goods_id"]').val();
		var goods_attr_id=$('input[name="goods_attr_id"]').val();
		var goods_num=$('input[name="good_num"]').val();
		var share=$('input[name="share"]').val();
		var img = $('#img_url').attr('src');       //获取当前点击图片链接
		
		var is_sell_out=$('input[name="is_sell_out"]').val();
		if(is_sell_out == 1){
			layer.msg("商品已售罄", {icon: 5});
		}else{
			$.ajax({
				url: "<?php echo U('wap/cart/add_cart');?>",
				type:'get',
				data: {goods_id:goods_id,goods_attr_id:goods_attr_id,goods_num:goods_num,share:share},
				dataType: 'json',
				success: function(data){
					if(data.status==1){
						layer.msg('加入成功', {icon: 6});
					}else{
						layer.msg(data.error, {icon: 5});
					}
				}
			});
		}
	});
	
	//立即购买
	$('#directorder').click(function(){
		var goods_id=$('input[name="goods_id"]').val();
		var goods_attr_id=$('input[name="goods_attr_id"]').val();
		var goods_num=$('input[name="good_num"]').val();
		var share=$('input[name="share"]').val();
		var is_sell_out=$('input[name="is_sell_out"]').val();
		if(is_sell_out == 1){
			layer.msg("商品已售罄", {icon: 5});
		}else{
			$.ajax({ 
				url: "<?php echo U('wap/cart/add_cart');?>",
				type:'get',
				data: {goods_id:goods_id,goods_attr_id:goods_attr_id,goods_num:goods_num,share:share},
				dataType: 'json',
				success: function(data){
					//console.log(data);
					if(data.status==1){
						var url="<?php echo U('Wap/cart/order_add'); ?>?cart_id="+data.cart_id;
						window.location.href=url;
					}else{
						layer.msg(data.error, {icon: 5});
					}
				}
			});
		}
	});
	$('#directorder_up').click(function () {
		var goods_id = $('input[name="goods_id"]').val();
		var goods_attr_id = $('input[name="goods_attr_id"]').val();
		var goods_num = $('input[name="good_num"]').val();
		var share = $('input[name="share"]').val();
		var is_sell_out=$('input[name="is_sell_out"]').val();
		if(is_sell_out == 1){
			layer.msg("商品已售罄", {icon: 5});
		}else{
			var url = "<?php echo U('Wap/cart/upgrade_order_add'); ?>?goods_id=" + goods_id + '&goods_attr_id=' + goods_attr_id + '&goods_num=' + goods_num;
			window.location.href = url;
		}
	});
	
	
	//增加数量
	$(".add_good").click(function(){
		var _this_num=$(".good_num");
		var goods_num=_this_num.val();
		goods_num++;
		_this_num.val(goods_num)
		//显示当前选择
		set_now_chk();
		//计算商品价格  
		get_goods_price();
	});
	//减少数量
	$(".save_good").click(function(){
		var _this_num=$(".good_num");
		var goods_num=_this_num.val();
		goods_num--;
		if(goods_num<=0){
		}else{
			_this_num.val(goods_num)
		}
		//显示当前选择
		set_now_chk();
		//计算商品价格 
		get_goods_price(); 
	});
	
	function set_now_chk(){
		var text="";
		if($(".now_chk").length){
			$(".now_chk").each(function(){
				var data=$(this).attr('data');
				var title=$(this).attr('title');
				text+=data+":"+title+'&nbsp;&nbsp;';
			});
		}
		var num=$(".good_num").val();
		text+='&nbsp;'+num+'&nbsp;';
		$('.now_ch').html(text);
	}
	
	 //计算商品价格  
function get_goods_price(){
	var goods_price=$('input[name="goods_price"]').val();
	goods_price=parseFloat(goods_price);
	var goods_attr_id='';
	/*  if($(".now_chk").length){
	$(".now_chk").each(function(){
	var data_price=$(this).attr('data-price');
	data_price=data_price?data_price:0;
	var data_attr=$(this).attr('data-attr-goods');
	data_price=parseFloat(data_price);
	goods_price=goods_price+data_price;
	goods_attr_id=goods_attr_id+','+data_attr;
	});
	}*/
	var goods_price_arr=new Array()
	if($(".now_chk").length){
		$(".now_chk").each(function(i){
			var data_price=$(this).attr('data-price');
			data_price=data_price?data_price:0;
			var data_attr=$(this).attr('data-attr-goods');
			goods_price_arr[i]=parseFloat(data_price);
			goods_attr_id=goods_attr_id+','+data_attr;
		});
	}
	goods_price_arr= Math.max.apply(null, goods_price_arr);//最大值
	// goods_price=goods_price+data_price;
	if(goods_price_arr>0){
		goods_price=goods_price_arr;
	}
	if(goods_attr_id){
		goods_attr_id=goods_attr_id.substr(1);
	}
	$('input[name="goods_attr_id"]').val(goods_attr_id);
	var total=parseFloat(0);
	var all_num=0;
	var goods_id_str='';
	$(".good_num").each(function(){
		var num=$(this).val();
		num=parseInt(num);
		if(num>0){
			var goods_id=$(this).attr('data');
			total=total+goods_price*num;
			goods_id_str=goods_id_str+','+goods_id+'|'+num;
			all_num=all_num+num;
		}
	});
	if(goods_id_str){
		goods_id_str=goods_id_str.substr(1);
	}
	total=total.toFixed(2)
	if(total>0){
		$('.go_pay').removeAttr('style');
	}else{
		$('.go_pay').css('cssText','background-color:rgb(171, 168, 168) !important;');
	}
	$('.goods_price').html(total);
	//$('.all_price').html('￥'+total);
	//$('.total').html(total);
	//var discount_price=discount_consume*total;
	//discount_price=discount_price.toFixed(2);

	//$('input[name="goods_id"]').val(goods_id_str);
	//$('.all_num').html(all_num);
	}
});
</script>
</body>
</html>