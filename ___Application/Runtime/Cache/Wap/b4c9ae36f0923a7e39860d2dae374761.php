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
				<?php if($data['pay_status'] == 2): if($data['offline'] == 1): ?>货到付款
					<?php elseif(($data['offline'] != 1) and ($data['shipping_status'] != 0)): ?>
						<?php echo ($shipping_status[$data['shipping_status']]); ?>
					<?php else: ?>
						<?php echo ($pay_status[$data['pay_status']]); endif; ?>
				<?php elseif($data['order_status'] == 2): ?>
					<?php echo ($order_status[$data['order_status']]); ?>
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
				<dd><?php echo ($data["consignee"]); ?></dd>
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
			<div class="ordermessage font34">
				<span  >订单编号：<span class="num"><?php echo ($data["order_sn"]); ?></span></span>
				<span class="floatRgt"></span>
			</div>
			<ul  <?php if(($$data['is_upgrade']) == "1"): ?>style="display:none"<?php endif; ?> >
				<?php $total_num = 0;?>
					<?php if(is_array($order_goods)): $i = 0; $__LIST__ = $order_goods;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; if($vo['order_id'] == $data['order_id']): ?><li  class="goods"> <a href="#">
								<div class="goodsimg imgnobg">
									<img src="<?php echo ($vo["goods_image"]); ?>" class="img" alt="<?php echo ($vo["goods_name"]); ?>" />
								</div>
								</a>
								<div class="info">
									<a href="<?php echo U('Wap/goods/goods_detail',array('id'=>$vo['goods_id']));?>">
										<div class="title">
											<?php echo ($vo["goods_name"]); ?>
										</div>
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
						共<span class="num black"> <?php echo ($total_num); ?> </span>件商品 合计：<span class=" num orange">¥<?php echo (priceformat($data['order_amount_all'])); ?></span>
					</li>
				</ul>
			</div>
			<div class="clearfix"></div>

	</div>
	<div class="ordermessage mgt2 ">
		<p  >支付编号:<span class="num floatRgt"><?php echo ($pay_sn); ?></span></p>
		<!--<p  >支付宝交易号：<span class="num">2016031621001001690267754225</span></p>-->
		<p  >订单时间:<span class="num floatRgt"><?php echo (date("Y-m-d H:i:s",$data['add_time'])); ?></span></p>
		<?php if($data["pay_status"] == 2): ?><p  >付款时间:<span class="num floatRgt"><?php echo (date("Y-m-d H:i:s",$data['pay_time'])); ?></span></p><?php endif; ?>
		<?php if($data["pay_status"] == 2 and $data["shipping_status"] == 1): ?><p  >确认时间:<span class="num floatRgt"><?php echo (date("Y-m-d H:i:s",$data['confirm_time'])); ?></span></p><?php endif; ?>
		<?php if($data["pay_status"] == 2 and (in_array($data['shipping_status'],array('1','2')))): ?><p  >配送时间:<span class="num floatRgt"><?php echo (date("Y-m-d H:i:s",$data['shipping_time'])); ?></span></p><?php endif; ?>
		<?php if(is_array($$data['status_remarks'])): $key = 0; $__LIST__ = $$data['status_remarks'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($key % 2 );++$key; if(($key) == "6"): ?><p >备注:<?php echo ($vo[0]); ?></p>
				<?php else: ?>
				<p ><?php echo ($vo[0]); ?><span class="num floatRgt"><?php echo (date("Y-m-d H:i:s",$vo[1])); ?></span></p><div class="clearfix"></div><?php endif; endforeach; endif; else: echo "" ;endif; ?>
		<?php if($data["pay_status"] == 2 and (in_array($data['shipping_status'],array('1','2')))): ?><p  >物流公司:<span class="num floatRgt"><?php echo ($data['express_name']); ?></span></p><?php endif; ?>
		<?php if($data["pay_status"] == 2 and (in_array($data['shipping_status'],array('1','2')))): ?><p  >物流单号:<span class="num floatRgt"><?php echo ($data['invoice_no']); ?></span></p><?php endif; ?>
	</div>
			<?php if($data['pay_status'] == 2 and (in_array($data['shipping_status'],array('1','2'))) and $express_data['status'] == 0): ?><div class="submit">
			<a href="__URL__/order_express?order_id=<?php echo ($data['order_id']); ?>">物流信息</a>

			</div><?php endif; ?>

	<?php if($data['order_state'] == 'nopay'): ?><div class="submit">
		<?php if(($$data['is_upgrade']) == "1"): ?><a href="<?php echo U('wap/start/upgrade_pay',array('order_id'=>$order_pay_sn));?>"  class="new-abtn-type new-mg-tb30">去付款</a>
			<?php else: ?>
			<a href="<?php echo U('wap/goodsorder/order_pay',array('order_id'=>$_GET['order_id']));?>" class="new-abtn-type new-mg-tb30">去付款</a><?php endif; ?>
	</div><?php endif; ?>


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
    <?php if($member_level >= 1): ?><a href="#" class="open md-trigger" data-modal="modal-3">
            <i class="iconfont">&#xe61e</i><span class="kd">推荐开店 </span>
        </a>
    <?php else: ?>
    <a href="<?php echo U('wap/start/index');?>" class="open"><i class="iconfont">&#xe61b</i><span class="kd">我要开店</span></a><?php endif; ?>
    <a href="<?php echo U('Wap/Cart/index',array('share'=>$share_para));?>"  class="<?php if($now_menu == 'cart'): ?>on<?php endif; ?>"> <i class="iconfont">&#xe612</i> 菜篮子</a>
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



</body>
</html>