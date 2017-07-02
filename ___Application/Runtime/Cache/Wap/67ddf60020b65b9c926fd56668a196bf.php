<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<?php if($is_ajax != 1): ?><html>
<head>
    <meta charset="utf-8">
    <title>订单列表</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
    <meta name="format-detection" content="telephone=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <script src="__PUBLIC__/wap/js/flexible.js"></script>
    <link href="__PUBLIC__/wap/css/icons.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="__PUBLIC__/wap/css/base.css" />
    <link rel="stylesheet" type="text/css" href="__PUBLIC__/wap/css/order.css" />

</head>
<body class="order">
<div class="head">
    <a href="javascript:history.go(-1)" class="md-close back"><i class="iconfont">&#xe617</i></a>
    <h2 >订单管理</h2>

	<a href="<?php echo U('wap/goodsrefund/refund_list');?>" class="more "><span>售后订单</span><i class="iconfont">&#xe611</i></a>

</div>
<div class="type">
    <ul class="oder_state_c">
        <li data="all" class=" all_order_n <?php if($_GET['order_state'] == 'all'): ?>on<?php endif; ?>"  >
        <a href="javascript:void(0);">全部</a>
        <?php if($count): ?><b><?php echo ($count); ?></b><?php endif; ?>
        </li>
        <li data="0" class="nopay_order_n <?php if($_GET['order_state'] == '0'): ?>on<?php endif; ?>" >
        <a href="javascript:void(0);">待付款</a>
        <?php if($count00): ?><b><?php echo ($count00); ?></b><?php endif; ?>
        </li>
        <li data="1" class="pay_order_n <?php if($_GET['order_state'] == '1'): ?>on<?php endif; ?>" >
        <a href="javascript:void(0);">待发货</a>
        <?php if($count10): ?><b><?php echo ($count10); ?></b><?php endif; ?>
        </li>
        <li data="2" class="delivered <?php if($_GET['order_state'] == '2'): ?>on<?php endif; ?>" >
        <a href="javascript:void(0);">待收货</a>
        <?php if($count20): ?><b><?php echo ($count20); ?></b><?php endif; ?>
        </li>
        <li data="3" class="f_order_n <?php if($_GET['order_state'] == '3'): ?>on<?php endif; ?>" >
        <a href="javascript:void(0);">已完成</a>
        <?php if($count30): ?><b><?php echo ($count30); ?></b><?php endif; ?>
        </li>
    </ul>
</div>
<div class="page">
    <ul class="more_html"><?php endif; ?>
        <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li>
                <div class="cartlist">
                    <div class="store">
                        <?php echo ($vo["store_name"]); ?>的店铺<span class="state"><?php echo ($vo["order_state_name"]); ?></span>
                    </div>
                    <div class="user">
                        <i class="iconfont">&#xe621</i>
                        <a href="<?php echo U('Wap/goodsorder/order_detail',array('order_id'=>$vo['order_id']));?>"><div class="info">
                            <dl>
                                <dt>订单号：</dt>
                                <dd class="num"><?php echo ($vo["order_sn"]); ?></dd>
                            </dl>
                            <dl>
                                <dt>收件人：</dt>
                                <dd><?php echo ($vo["consignee"]); ?></dd>
                            </dl>
                            <dl>
                                <dt>地址：</dt>
                                <dd><?php echo ($vo["address"]); ?></dd>
                            </dl>
                            <dl>
                                <dt>手机号：</dt>
                                <dd class="num"><?php echo ($vo["mobile"]); ?></dd>
                            </dl>
                        </div></a>
                    </div>
                    <ul  <?php if(($vo['is_upgrade']) == "1"): ?>style='display:none'<?php endif; ?>  >
                        <?php if(is_array($vo['goods'])): $i = 0; $__LIST__ = $vo['goods'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><li  class="goods"> <a href="<?php echo U('Wap/goodsorder/order_detail',array('order_id'=>$vo['order_id']));?>">
                                <div class="goodsimg imgnobg">
                                    <div class="img bgcover " style=" background:url(<?php echo ($v["goods_image"]); ?>)">
                                    </div>
                                </div>
                                <div class="info">
                                    <div class="title">
                                        <?php echo ($v["goods_name"]); ?>
                                    </div>
                                    <div class="pay">
                                        <div class="pirce num green">
                                            ￥<?php echo ($v["goods_price"]); ?>
                                        </div>
                                        <div class="qty">
                                            <span class="num"><?php echo ($v["goods_number"]); ?></span>件&nbsp; &nbsp;
                                            <?php if(($v['shipping_money']) == "0"): ?>包邮
                                                <?php else: ?>
                                                运费:￥<?php echo ($v["shipping_money"]); endif; ?>

                                        </div>
                                    </div>
                                </div>

                            </a>
							<?php if($v["after_sale"] == 1): ?><a class="after_sales service type<?php echo ($v["refund_status"]); ?>"  href="<?php echo U('wap/goodsrefund/index',array('id'=>$v['rec_id']));?>"><?php echo ($v["refund_status_name"]); ?></a><?php endif; ?>
                                <div class="clearfix">
                                </div>
                            </li><?php endforeach; endif; else: echo "" ;endif; ?>
                    </ul>
                    <div class="total"   >
                        <ul>
                            <li class="subtotal">共<span class="num green"> <?php echo ($vo["num"]); ?> </span>件商品 合计：<span class=" num green">¥<?php echo ($vo["order_amount"]); ?></span></li>
                            <li class="typetotal" <?php if(($vo["pay_status"] == 2 and $vo["order_status"] == 0) or $vo["order_status"] == 5): ?>style='display:none'<?php endif; ?> >

                                <!--<a href="#" class="btn1 btnsF34">去付款</a>
                                    <a href="#" class="btn1 btnsF34">查看物流</a>
                                    <a href="#" class="btn2 btnsF34">确认收货</a>
                                    <a href="#" class="btn3 btnsF34">取消订单</a>-->
                                <?php if($vo["order_state"] == 'nopay'): if($vo["is_upgrade"] == 1): ?><a class="btn1 btnsF34" href="<?php echo U('wap/start/upgrade_pay',array('order_id'=>$vo['order_id']));?>">去付款</a>
                                        <?php else: ?>
                                        <a class="btn1 btnsF34" href="<?php echo U('wap/goodsorder/order_pay',array('order_id'=>$vo['order_id']));?>">去付款</a><?php endif; ?>
                                    <a class="g-tui delete_order btn3 btnsF34" data-type="1"  data="<?php echo ($vo["order_id"]); ?>" href="javascript:void(0)">取消订单</a>
								<?php elseif($vo["order_state"] == 'delivered' ): ?>
                                    <?php if($vo["pay_status"] == 2 and (in_array($vo['shipping_status'],array('1','2')))): ?><a class="btn1 btnsF34" href="<?php echo U('wap/goodsorder/order_express',array('order_id'=>$vo['order_id']));?>">查看物流</a>
											<a class="delivered_order btn2 btnsF34" data="<?php echo ($vo["order_id"]); ?>" href="javascript:void(0)">确认收货</a><?php endif; ?>
								<?php elseif( $vo["order_state"] == 'after_sale'): ?>

								<?php elseif($vo["order_state"] == 'received'): ?>
								<a class="g-tui delete_order btn3 btnsF34" data-type="0"  data="<?php echo ($vo["order_id"]); ?>" href="javascript:void(0)">删除</a>
								<?php elseif($vo["order_state"] == 'finish'): ?>
								<a class="g-tui delete_order btn3 btnsF34" data-type="0"  data="<?php echo ($vo["order_id"]); ?>" href="javascript:void(0)">删除</a>
								<?php else: ?>
                                  <!--  <?php echo ($vo["order_state_name"]); ?>-->
									<i class="iconfont floatLft">&#xe68c</i><span><?php echo ($vo["status_remarks"]); ?></span><?php endif; ?>
                            </li>
                        </ul>
                    </div>
                </div>
            </li><?php endforeach; endif; else: echo "" ;endif; ?>
<?php if($is_ajax != 1): ?></ul>
    <div class="loading">
        <div class="fm-footer-copyright get_more">
            <?php if($count > 10 && count($list) == 10): ?>加载更多
                <?php else: ?>
                无更多数据<?php endif; ?>
        </div>
    </div>
</div>



<div style="height:50px;">
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



<script type="text/javascript" src="__PUBLIC__/wap/js/jquery.min.js"></script>
<script src="__PUBLIC__/bootstrap/vendor/jquery-validation/jquery.validate.wap.js"></script>
<script src="__PUBLIC__/js/jquery.form.js"></script>
<script type="text/javascript" src="__PUBLIC__/layer/layer.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/return-top.js"></script>

<input type="hidden" name="p" value="2" />
<input type="hidden" name="order_state" value="<?php echo ($_GET['order_state']); ?>" />
<input type="hidden" name="order" value=""/>

<script type="text/javascript">
    var load_more = 0;
    var load_none = 0;
    $(function () {
        $(window).scroll(function () {
            var scrollTop = $(this).scrollTop();
            var scrollHeight = $(document).height();
            var windowHeight = $(this).height();
            if (scrollTop + windowHeight == scrollHeight) {
                //此处是滚动条到底部时候触发的事件，在这里写要加载的数据，或者是拉动滚动条的操作
                if (load_none != 1) {
                    if (load_more == 1) {
                        var html = ' <img src="__PUBLIC__/wap/img/icon_zhuan.gif" style="max-width:15px;">'
                        $('.get_more').html('加载中' + html);
                    } else {
                        get_more();
                    }
                }

            }
        });
    })
    $(function(){
        $(".get_more").click(function(){
            get_more();
        });
        $(window).load(function () {
            get_more();
        });
        $(".oder_state_c li").click(function(){
            $('.oder_state_c li').removeClass('on');
            $(this).addClass('on');
            var data=$(this).attr('data');
            $("input[name='order_state']").val(data);
            $("input[name='p']").val(1);
            get_more();
        });
        $('.more_html').on('click','.delete_order',function(){
            var __this=$(this);
            var order_id=__this.attr('data');
            var data_type=__this.attr('data-type');
            var types="删除";
			var order_del_url = "<?php echo U('wap/goodsorder/order_del');?>";
            if(data_type==1){
				order_del_url = "<?php echo U('wap/goodsorder/order_cancel');?>";
                types="取消";
            }
            layer.confirm('确定'+types+'该订单？', {
                btn: ['确定','取消'] //按钮
            }, function(){
                layer.msg(types+'中请稍候....', {time: 5000});
                $.ajax({
                    url: order_del_url,
                    type:'post',
                    data: {order_id:order_id, data_type:data_type},
                    dataType: 'json',
                    success: function(data){
                        //console.log(data);
                        if(data.status==1){
                            if( data_type == '0'){
                                var str=$('.oder_state_c .all_order_n b:eq(0)').html();
                                $('.oder_state_c .all_order_n b:eq(0)').html(parseInt(str) - 1);
                                if (parseInt(str) == 1){
                                    $('.oder_state_c .all_order_n b:eq(0)').css('display','none');
                                }
                            }
                            if(data.code=='nopay'){
                                var str=$('.oder_state_c .nopay_order_n b:eq(0)').html();
                                $('.oder_state_c .nopay_order_n b:eq(0)').html(parseInt(str) - 1);
                                if (parseInt(str) == 1){
                                    $('.oder_state_c .nopay_order_n b:eq(0)').css('display','none');
                                }
                                if( data_type == '1'){
                                    var str_3=$('.oder_state_c .f_order_n b:eq(0)').html();
                                    $('.oder_state_c .f_order_n b:eq(0)').html(parseInt(str_3) + 1);
                                    $('.oder_state_c .f_order_n b:eq(0)').css('display','show');
                                }
                            }else{
                                var str=$('.oder_state_c .f_order_n b:eq(0)').html();
                                $('.oder_state_c .f_order_n b:eq(0)').html(parseInt(str) - 1);
                                if (parseInt(str) == 1){
                                    $('.oder_state_c .f_order_n b:eq(0)').css('display','none');
                                }
                            }

                            layer.msg(types+'成功', {icon: 6,time: 1500},function(){
                                $("input[name='p']").val(1);
                                get_more();
                                ///刷新页面
                            });
                        }else{
                            layer.msg(data.error, {icon: 5});
                        }
                    }
                });
            }, function(){

            });

        });
    });
    function get_more(){
        load_more = 1;
        var html = ' <img src="__PUBLIC__/wap/img/icon_zhuan.gif" style="max-width:15px;">';
        $('.get_more').html('加载中' + html);
        var p=$("input[name='p']").val();
        var order_sta=$("input[name='order_state']").val();
        $.ajax({
            url: "<?php echo U('wap/goodsorder/index');?>",
            type:'post',
            data: {p:p,order_state:order_sta},
            dataType: 'html',
            success: function(data){
                //console.log(data);
                var con=1;
                var option = '';
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

</script>
<script type="text/javascript">
    $(function(){
        //确认收货
        $('.more_html').on('click','.delivered_order',function(){
            var __this=$(this);
            var order_id=__this.attr('data');
            layer.confirm('是否确认收货？确认收货后订单不可退换！', {
                btn: ['确定','取消'] //按钮
            }, function(){
                if(order_id){
                    $.ajax({
                        url: "<?php echo U('wap/goodsorder/order_delivered');?>",
                        type:'post',
                        data: {order_id:order_id},
                        dataType: 'json',
                        success: function(data){
                            console.log(data);
                            if(data.status==1){
                                var str=$('.oder_state_c .delivered b:eq(0)').html();
                                $('.oder_state_c .delivered b:eq(0)').html(parseInt(str) - 1);
                                if (parseInt(str) == 1){
                                    $('.oder_state_c .delivered b:eq(0)').css('display','none');
                                }
                                var str=$('.oder_state_c .f_order_n b:eq(0)').html();
                                $('.oder_state_c .f_order_n b:eq(0)').html(parseInt(str) + 1);
                                $('.oder_state_c .f_order_n b:eq(0)').css('display','show');
                                //alert("确认收货成功");
                                layer.msg('确认收货成功', {icon: 6,time: 1500},function(){
                                    $("input[name='p']").val(1);
                                    get_more();
                                    ///刷新页面
                                });
                                //$('.delivered_html').remove();
                            }else{
                                layer.msg(data.error, {icon: 5});
                                //alert(data.error)
                            }
                        }
                    });
                }else{
                    layer.msg('系统繁忙,请稍后再试', {icon: 5});
                    //alert('系统繁忙,请稍后再试')
                }

            }, function(){

            });

        });

    });
</script>
</body>
</html><?php endif; ?>