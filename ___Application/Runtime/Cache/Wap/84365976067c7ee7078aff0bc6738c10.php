<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>会员中心</title>
<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
<meta name="format-detection" content="telephone=no">
<meta name="apple-mobile-web-app-capable" content="yes">
<script src="__PUBLIC__/wap/js/flexible.js"></script>
<script type="text/javascript" src="__PUBLIC__/wap/js/jquery.min.js"></script>
<link href="__PUBLIC__/wap/css/icons.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="__PUBLIC__/wap/css/base.css" />
<link rel="stylesheet" type="text/css" href="__PUBLIC__/wap/css/membernew.css" />
<link rel="stylesheet" type="text/css" href="__PUBLIC__/wap/css/component.css" />
</head>
<body class="member">
<div class="top">
	<div class="topbglay">
		<div class="topbg bgcover" style="background:url(<?php echo (get_member_background_default($data["background_image"])); ?>)">
			<div class="change_back_img changebackimg"> <img  src="/Public/wap/img/member/bgicon.gif"/> </div>
			<a href="<?php echo U('wap/member/setup');?>" class="setting"> <i class="iconfont">&#xe629;</i> </a> </div>
	</div>
	<div class="mall person textCer">
		<div> <a href="<?php echo U('wap/member/setphoto');?>">
			<div  class="bgcover avatar" style="background:url(<?php echo (get_member_logo_default($data["member_logo"])); ?>) #fff"></div>
			</a> </div>
		<div class="name green num "><?php echo ($data["member_name"]); ?> </div>
		<div class="memberid "> 会员ID：<a class="num font40 num"><?php echo ($data["member_card"]); ?></a> </div>
	</div>
	<div class="shopinfo person">
		<ul>
			<li class="textCer font40"><a href="<?php echo U('wap/member/wallet',array('type'=>1));?>"><i class="iconfont ">&#xe663;</i> 积分 <span class="num orange "><?php echo (floor($MemberDetail["points"])); ?></span> </a></li>
			<li class="textCer font40 manage"><a  href="<?php echo U('wap/collect/index');?>"><i class="iconfont ">&#xe62d</i> 收藏 <span class="num orange "><?php echo ($MemberDetail["count_collect"]); ?></span> </a></li>
		</ul>
		<div class="clearfix"> </div>
	</div>
</div>
<div class="step person">
	<div class="field">
		<div class="list">
			<div class="title"> <a  href="<?php echo U('wap/goodsorder/index');?>"> <span class="green font34">我的订单</span> <span class="floatRgt gray font34">全部订单 <i class="iconfont">&#xe611</i></span> </a> </div>
		</div>
		<ul class="item">
			<li> <a href="<?php echo U('wap/goodsorder/index',array('order_state'=>0));?>">
				<?php if($member_count["order0"] > 0): ?><b><?php echo ($member_count["order0"]); ?></b><?php endif; ?>
				<img src="__PUBLIC__/wap/img/member/person-3.png"> <span class="font34">待付款</span> </a> </li>
			<li> <a href="<?php echo U('wap/goodsorder/index',array('order_state'=>1));?>">
				<?php if($member_count["order1"] > 0): ?><b><?php echo ($member_count["order1"]); ?></b><?php endif; ?>
				<img src="__PUBLIC__/wap/img/member/person-2.png"> <span class="font34">待发货</span> </a> </li>
			<li> <a href="<?php echo U('wap/goodsorder/index',array('order_state'=>2));?>">
				<?php if($member_count["order2"] > 0): ?><b><?php echo ($member_count["order2"]); ?></b><?php endif; ?>
				<img src="__PUBLIC__/wap/img/member/person-6.png"> <span class="font34">待收货</span> </a> </li>
			<li> <a href="<?php echo U('wap/goodsorder/index',array('order_state'=>3));?>">
				<?php if($member_count["order3"] > 0): ?><b><?php echo ($member_count["order3"]); ?></b><?php endif; ?>
				<img src="__PUBLIC__/wap/img/member/person-5.png"> <span class="font34">已完成</span> </a> </li>
			<li> <a href="<?php echo U('wap/goodsrefund/refund_list');?>">
				<?php if($member_count["order4"] > 0): ?><b><?php echo ($member_count["order4"]); ?></b><?php endif; ?>
				<img src="__PUBLIC__/wap/img/member/person-1.png"> <span class="font34">售后</span> </a> </li>

		</ul>
		<div class="clearfix"></div>
	</div>
	<div class="list mgb20 ">
		<?php if($vip_i > 0): ?><div class="title"> <a href="<?php echo U('wap/member/index_vip');?>"><i class="iconfont icon" style="color:#f9960f">&#xe633</i><span>店铺管理中心</span> <i class="iconfont floatRgt">&#xe611</i></a> </div><?php endif; ?>
			<a class="title" href="<?php echo U('wap/member/setup');?>"><i class="iconfont icon" style="color:#0ba7e4">&#xe630</i><span>个人设置</span> <i class="iconfont floatRgt">&#xe611</i></a>
		<div class="title">
			<a href="<?php echo U('wap/address/index');?>"><i class="iconfont icon" style="color:#f05a49">&#xe648</i><span>收货地址</span>  <i class="iconfont floatRgt">&#xe611</i></a>
		</div>
		<div class="title">
			<a href="<?php echo U('wap/carinfo/add_msg');?>"><i class="iconfont icon" style="color:#f05a49">&#xe644</i><span>发布二手车信息</span>  <i class="iconfont floatRgt">&#xe611</i></a>
		</div>
		<div class="title">
			<a href="<?php echo U('wap/carinfo/index');?>"><i class="iconfont icon" style="color:#f05a49">&#xe644</i><span>二手车信息中心</span>  <i class="iconfont floatRgt">&#xe611</i></a>
		</div>

		<div class="title"> <a href="<?php echo U('wap/about/about_us');?>"><i class="iconfont icon" style="color:#2ac198">&#xe659</i><span>关于我们</span> <i class="iconfont floatRgt">&#xe611</i></a> </div>
	</div>
</div>


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




<?php if($_GET['layer'] == 'upgrade_points'): ?><script type="text/javascript" src="__PUBLIC__/layer/layer.js"></script>
	<div class="points" style="display:none;">
		<a href="<?php echo U('wap/index/index');?>" >
			<img width="100%"  id="u_p_img" src="__PUBLIC__/wap/img/upgrade_points.png" border="0" alt="">
		</a>
	</div>
	<style>
		.layui-layer-demo{background: none !important;box-shadow: none !important;border-radius: 0px !important;}
	</style>
	<script type="text/javascript">
		$('#u_p_img').load(function () {
			$(function(){
				//捕获页
				layer.open({
					type: 1,
					shade: 0.8,//透明度
					title: false, //不显示标题
					skin: 'layui-layer-demo', //样式类名
					shadeClose: true, //开启遮罩关闭
					area: ['90%', 'auto'],
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
<script type="text/javascript">
	$(function(){
		//点击背景获取上传背景的按钮
		$('.mallbg').click(function () {
			if($(".change_back_img").css("display")=="none"){
				$(".change_back_img").show(200);
			}else{
				$(".change_back_img").hide();
			}
		});
		//跳转至背景图上传页面
		$(".change_back_img").click(function () {
			window.location.href="<?php echo U('wap/member/setbackimg'); ?>"
		});
	});
</script>
</body>