<?php if (!defined('THINK_PATH')) exit(); if($is_ajax != 1): ?><!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title><?php echo ($webseting["web_title"]); ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
<meta name="format-detection" content="telephone=no">
<meta name="apple-mobile-web-app-capable" content="yes">
<script type="text/javascript" src="__PUBLIC__/wap/js/jquery.min.js"></script>
<script src="__PUBLIC__/wap/js/flexible.js"></script>
<script type="text/javascript" src="__PUBLIC__/layer/layer.js"></script>
<link rel="stylesheet" type="text/css" href="__PUBLIC__/wap/css/icons.css">
<link rel="stylesheet" type="text/css" href="__PUBLIC__/wap/css/base.css" />
<link rel="stylesheet" type="text/css" href="__PUBLIC__/wap/css/swiper.min.css" />
<link rel="stylesheet" type="text/css" href="__PUBLIC__/wap/css/public.css" />
</head>
<body class="index" ontouchstart="" onmouseover="">
<div class="swiper-container goodsshow">
	<div class="swiper-wrapper">
		<?php if(is_array($ad1)): $i = 0; $__LIST__ = $ad1;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="swiper-slide"> <a href="<?php if($vo['link'] != ''): echo U($vo['link']); else: ?>javascript:void(0)<?php endif; ?>"> <img data-src="<?php echo ($vo["image_path"]); ?>" class="swiper-lazy"> </a>
				<div class="swiper-lazy-preloader swiper-lazy-preloader-white"> </div>
		    </div><?php endforeach; endif; else: echo "" ;endif; ?>
    </div>
	<div class="swiper-pagination swiper-pagination-white"> </div>
  </div>
  <div class="bggreen">
	  <div class="catnav">
		  <div class="cat-container">
			  <ul class="swiper-wrapper">
				  <?php if(is_array($g_category)): $i = 0; $__LIST__ = $g_category;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li  class="swiper-slide ">
						  <a href="<?php echo ($vo["category_url"]); ?>" title="<?php echo ($vo["cat_desc"]); ?>"> <img src="<?php echo thumbs_auto($vo['cat_icon'], 600, 255);?>g"> </a>
						  <h4><?php echo ($vo["cat_name"]); ?></h4>
					  </li><?php endforeach; endif; else: echo "" ;endif; ?>
			  </ul>
			  <div class="cat-pagination"></div>
		  </div>
	  </div>
  </div>
  <div class="rec clearfix">
	  <div class="col2 brdr" style="height:5rem"> <a href="<?php if($ad4[0]['link'] != ''): echo ($ad4[0]['link']); else: ?>javascript:void(0)<?php endif; ?>"> <img src="<?php echo thumbs_auto($ad4[0]['image_path'], 250, 310);?>"> </a> </div>
	  <div class="col3">
		  <div class="col100 brdb" style="height:2rem"> <a href="<?php if($ad4[1]['link'] != ''): echo ($ad4[1]['link']); else: ?>javascript:void(0)<?php endif; ?>"> <img src="<?php echo thumbs_auto($ad4[1]['image_path'], 372, 123);?>"> </a> </div>
		  <div class="clearfix"> </div>
		  <div class="col50 brdr" style="height:3rem"> <a href="<?php if($ad4[2]['link'] != ''): echo ($ad4[2]['link']); else: ?>javascript:void(0)<?php endif; ?>"><img src="<?php echo thumbs_auto($ad4[2]['image_path'], 186, 186);?>"></a></div>
		  <div class="col50 " style="height:3rem"> <a href="<?php if($ad4[3]['link'] != ''): echo ($ad4[3]['link']); else: ?>javascript:void(0)<?php endif; ?>"> <img src="<?php echo thumbs_auto($ad4[3]['image_path'], 186, 186);?>"> </a> </div>
		  <div class="clearfix"> </div>
	  </div>
  </div>
  <div class="indexlist more_html"><?php endif; ?>
	  <?php if(is_array($goods_list)): $i = 0; $__LIST__ = $goods_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="goods"> <a href="<?php echo U('wap/goods/goods_detail',array('id'=>$vo['goods_id'],'share'=>$share_para));?>">
	      <div class="item">
	        <div class="goodsimg imgnobg">
	          <img src="<?php echo thumbs_auto($vo['goods_img'], 200, 200);?>" alt="" /> </div>
	        <div class="info">
	          
	          <div class="title"> <?php echo ($vo["goods_name"]); ?> </div>
	          <div class="pirce"> 	            
	            <span class="blue">¥<span class="num"><?php echo ($vo["shop_price"]); ?></span></span>
	            <!--<div class="browse">已售:<span class="num"><?php echo ($vo["goods_browse"]); ?></span>件</div>--> </div>
	          </div>
	        </div>
	      </a>
	      <div class="addcart" onClick="" goods_id="<?php echo ($vo["goods_id"]); ?>" img_url="<?php echo thumbs_auto($vo['goods_img'], 200, 200);?>" goods_price="<?php echo ($vo["shop_price"]); ?>" goods_attr_id="<?php echo ($vo["goods_attr_id"]); ?>"> <i class="iconfont">&#xe612</i> </div>
	      </div><?php endforeach; endif; else: echo "" ;endif; ?>
  <?php if($is_ajax != 1): ?></div>
  <div class="clearfix"> </div>
  <div class="loading">
	  <div class="fm-footer-copyright get_more">
		  <?php if($count > 10 && count($list) == 10): ?>加载更多
			  <?php else: ?>
			  无更多数据<?php endif; ?>
	  </div>
  </div>
  <input type="hidden" name="type" value="<?php echo ($_GET['type']); ?>" />
  <input type="hidden" name="order" value="" />
  <input type="hidden" name="goods_attr_id" value="" >
  <input type="hidden" name="p" data="2" value="2" />
<div class="tools">
    <i class="iconfont open" data_status="0"><span class="on">&#xe661</span><span class="off">&#xef11</span></i>
    <a class="qq" href="http://q.url.cn/s/p7xpDJm"><i class="iconfont">&#xe64e</i><span>在线QQ</span></a>
    <a class="tel closs" href="tel:02368880593"><i class="iconfont">&#xe65f</i><span>客服电话</span></a>
    <div class="code"><img src="/Public/wap/img/fwcode.jpg"/><span>长按 关注FG峰购<br>
送积分哦！</span></div>
</div>
<script>
    $(document).ready(function()
    {
        $(".tools .open").click(function(){
            var  status= status=$(this).attr('data_status');
            if(status == 1){
                $(".tools").animate({right:"-4.96rem"});
                $(this).attr('data_status',0);
                $(this).removeClass('close');
            }else {
                $(".tools").animate({right:"0rem"});
                $(this).attr('data_status',1);
                $(this).addClass('close');
            }

        });
    });

</script> 
<link rel="stylesheet" type="text/css" href="__PUBLIC__/wap/css/component.css" />

<div class="footer md-modal md-effect-3" id="modal-3">
    <div class="md-content  md-close">
        <div class="avatar">
            <span style="background:url(<?php echo (get_member_logo_default($member_logo)); ?>) #fff" class="bgcover"></span>
        </div>
        <div class="name">
            <a class="store btn2 btnsF34">
                <?php echo ($member_name); ?>的
            </a>
            <a href="#" class="btn1 up btnsF34">
                <?php echo ($level_name); ?>
            </a>
        </div>
        <div class="info">
            <img id="recommend_qr" src="<?php echo ($qrcode); ?>"/>
        </div>

    </div>
</div>
<div class="bottomside">
    <a href="<?php echo U('Wap/Index/index',array('share'=>$share_para));?>" class="<?php if($now_menu == 'home'): ?>on<?php endif; ?>">
        <i class="iconfont"><?php if($now_menu == 'home'): ?>&#xe60a<?php else: ?>&#xe61f<?php endif; ?></i> 首页
    </a>
    <!--<a href="<?php echo U('Wap/Goods/index');?>" class="on"><i class="iconfont">&#xef0f</i> 分类</a>-->
    <a href="<?php echo U('Wap/Goods/index', array('type'=>99,'share'=>$share_para));?>" class="<?php if($now_menu == 'shop'): ?>on<?php endif; ?>">
    <i class="iconfont"><?php if($now_menu == 'shop'): ?>&#xe60e<?php else: ?>&#xef0f<?php endif; ?></i>分类
    </a>
    <a href="<?php echo U('Wap/Cart/index',array('share'=>$share_para));?>"  class="<?php if($now_menu == 'cart'): ?>on<?php endif; ?>"> <i class="iconfont">&#xe612</i> 购物车</a>
    <a href="<?php echo U('Wap/Member/index',array('share'=>$share_para));?>" class="<?php if($now_menu == 'member'): ?>on<?php endif; ?>">
    <i class="iconfont"><?php if($now_menu == 'member'): ?>&#xe608<?php else: ?>&#xe60d<?php endif; ?></i>个人中心
    </a>
</div>
<!--微信分享-->
<script type="text/javascript" src="__PUBLIC__/wap/js/jquery.min.js"></script>
<script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>

<script type="text/javascript">
          var  title = '<?php echo $shar_title; ?>'; // 分享标题
          var  link = '<?php echo $shar_url; ?>'; // 分享链接
          var  imgUrl= '<?php echo $shar_imgurl; ?>'; // 分享图标
          var  desc = '<?php echo $shar_desc; ?>'; // 分享描述
          //若点开二维码，链接内容↓
          var start_title ='<?php echo $share_link_arr["shar_title"]; ?>';
          var start_link ='<?php echo $share_link_arr["shar_url"]; ?>';
          var start_desc='<?php echo $share_link_arr["shar_desc"]; ?>';
          var start_imgUrl='<?php echo $share_link_arr["shar_imgurl"]; ?>';
          $(function(){
              wx.config({
                  debug: false, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
                  appId: '<?php echo $appid; ?>', // 必填，公众号的唯一标识
                  timestamp: '<?php echo $time; ?>', // 必填，生成签名的时间戳
                  nonceStr: '<?php echo $noncestr; ?>', // 必填，生成签名的随机串
                  signature: '<?php echo $signature; ?>',// 必填，签名，见附录1
                  jsApiList: ['onMenuShareTimeline','onMenuShareAppMessage','onMenuShareQQ','onMenuShareWeibo','onMenuShareQZone'] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
              });
          });
          var weixin_share_config = function (title,link,imgUrl,desc) {
              wx.ready(function(){
                  wx.onMenuShareTimeline({
                      title: title, // 分享标题
                      link: link, // 分享链接
                      imgUrl: imgUrl, // 分享图标
                      success: function () {
                          // 用户确认分享后执行的回调函数
                          //getShare();

                      },
                      cancel: function () {
                          // 用户取消分享后执行的回调函数

                      }
                  });
                  wx.onMenuShareAppMessage({
                      title: title, // 分享标题
                      desc: desc, // 分享描述
                      link: link, // 分享链接
                      imgUrl: imgUrl, // 分享图标
                      type: '', // 分享类型,music、video或link，不填默认为link
                      dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
                      success: function () {
                          // 用户确认分享后执行的回调函数
                      },
                      cancel: function () {
                          // 用户取消分享后执行的回调函数
                      }
                  });

                  wx.onMenuShareQQ({
                      title: title, // 分享标题
                      desc: desc, // 分享描述
                      link: link, // 分享链接
                      imgUrl: imgUrl, // 分享图标
                      success: function () {
                          // 用户确认分享后执行的回调函数
                      },
                      cancel: function () {
                          // 用户取消分享后执行的回调函数
                      }
                  });

                  wx.onMenuShareWeibo({
                      title: title, // 分享标题
                      desc: desc, // 分享描述
                      link: link, // 分享链接
                      imgUrl: imgUrl , // 分享图标
                      success: function () {
                          // 用户确认分享后执行的回调函数
                      },
                      cancel: function () {
                          // 用户取消分享后执行的回调函数
                      }
                  });

                  wx.onMenuShareQZone({
                      title: title, // 分享标题
                      desc: desc, // 分享描述
                      link: link, // 分享链接
                      imgUrl: imgUrl, // 分享图标
                      success: function () {
                          // 用户确认分享后执行的回调函数
                      },
                      cancel: function () {
                          // 用户取消分享后执行的回调函数
                      }
                  });
              });
          };
          weixin_share_config(title,link,imgUrl,desc);
        $(function(){
            $('.choice_option').each(function(index,e){
                $(e).find('a:eq(0)').click();
            });
        });
</script>
<script src="__PUBLIC__/wap/js/classie.js"></script>
<script src="__PUBLIC__/wap/js/modalEffects.js"></script>
<div class="md-overlay md-close back">
</div>

<!--关注送积分-->
<?php if($_GET['layer'] == 'follow_points'): ?><script type="text/javascript" src="__PUBLIC__/layer/layer.js"></script>
    <div class="points" style="display:none;">
        <a href="<?php echo U('wap/goods/index',array('share'=>$share_para));?>" >
            <img id="f_p_img" width="100%" height="auto" src="__PUBLIC__/wap/img/follow_points.png" border="0" alt="">
        </a>
    </div>
    <style>
        .layui-layer-demo{background: none !important;box-shadow: none !important;border-radius: 0px !important;}
    </style>
    <script type="text/javascript">

       $('#f_p_img').load(function () {
           $(function(){
               //捕获页
               layer.open({
                   type: 1,
                   shade: 0.8,//透明度
                   title: false, //不显示标题
                   skin: 'layui-layer-demo', //样式类名
                   shadeClose: true, //开启遮罩关闭
                   area: ['90%', '8rem'],
                   content: $('.points'), //捕获的元素
                   cancel: function(index){

                       layer.close(index);
                       this.content.show();
                       $('.points').hide();
                       //layer.msg('捕获就是从页面已经存在的元素上，包裹layer的结构',{time: 5000});
                   }
               });


           });
       });
    </script><?php endif; ?>


 
<script src="__PUBLIC__/wap/js/swiper.min.js"></script> 
<script>
	function add_adress() {
		//询问框
		var msg='亲 您已成功升级为<?php echo ($level_name); ?><br>属于你的峰购大礼包迷路了，请完善你的收货地址，大礼包即时送货上门喔！';
		layer.confirm(msg, {
			btn: ['马上领取','暂时不用']//按钮
		}, function(){
			location.href="<?php  echo U('wap/start/gift_address',array('order_sn'=>$add_address['order_sn']));?>";
		}, function(){

		});
	}


	var swiper = new Swiper('.swiper-container', {
		pagination: '.swiper-pagination',
		preloadImages: false,
		lazyLoading: true,
		autoplay: 3000
	});



	var swiper = new Swiper('.cat-container', {
		pagination: '.cat-pagination',
		slidesPerView:5,
		paginationClickable: true
	});
	
	
	    var swiper = new Swiper('.swiper-book', {
		autoplay: 3000,
        spaceBetween: 30,
        effect: 'fade'
    });
	
	
var load_more=0;
	var load_none=0;
	$(function(){
		//滚动加载
		$(window).scroll(function () {
			var scrollTop = $(this).scrollTop();
			var scrollHeight = $(document).height();
			var windowHeight = $(this).height();
			if (scrollTop + windowHeight == scrollHeight) {
				//此处是滚动条到底部时候触发的事件，在这里写要加载的数据，或者是拉动滚动条的操作
				if(load_none!=1){
					if(load_more==1){
						$('.get_more').html('加载中......');
					}else{
						get_more();
					}
				}
			}
		});
		$(window).load(function () {
			get_more();
		});

		$(".get_more").click(function(){
			get_more();
		});

		function get_more(){
			load_more=1;
			var html=' <img src="__PUBLIC__/wap/img/icon_zhuan.gif" style="max-width:15px;">';
			$('.get_more').html('加载中'+html);
			var p=$("input[name='p']").attr('data');
			$.ajax({
				url: "<?php echo U('wap/index/index');?>",
				type:'get',
				data: {p:p},
				dataType: 'html',
				success: function(data){
					//console.log(data);
					var con=1;
					if (data != undefined || data != null){
						option = data;
					}

					if(p==1){
						$('.more_html').html(option);
					}else{
						$('.more_html').append(option);
					}
					load_more = 0;
					if (option) {
						$("input[name='p']").val(parseInt(p) + 1);
					}
					if(con<10){
						load_none = 1;
						$('.get_more').html('无更多数据');
						$('.get_more').fadeOut('3000');
					}
				}
			});
		}
	});
	//加入购物车
	$(document).on('click', '.addcart', function () {
		var goods_id = $(this).attr('goods_id');
		var goods_attr_id = $(this).attr('goods_attr_id');
		var img = $(this).attr('img');
		var goods_num = 1;
		var share = $('input[name="share"]').val();
		$.ajax({
			url: "<?php echo U('wap/cart/add_cart');?>",
			type:'get',
			data: {goods_id:goods_id,goods_attr_id:goods_attr_id,goods_num:goods_num,share:share},
			dataType: 'json',
			success: function(data){
				if(data.status==1){
					layer.msg('加入成功', {icon: 6});
					var flyer = $('<img style="display:block;width:50px;height:50px;border-radius:50px;position: fixed;z-index: 9999;" src="' + img + '">');      //抛物体对象
				}else{
					layer.msg(data.error, {icon: 5});
				}
			}
		});
	}); 
</script>
</body>
</html><?php endif; ?>