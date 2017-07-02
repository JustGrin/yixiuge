<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>售后服务-<?php echo ($webseting["web_title"]); ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
<meta name="format-detection" content="telephone=no">
<meta name="apple-mobile-web-app-capable" content="yes">
<script src="__PUBLIC__/wap/js/flexible.js"></script>
<link href="__PUBLIC__/wap/css/icons.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="__PUBLIC__/wap/css/base.css" />
<link rel="stylesheet" type="text/css" href="__PUBLIC__/wap/css/order.css" />
<link href="__PUBLIC__/wap/css/service.css"  rel="stylesheet">
</head>
<body class="order">
<div class="new head">
	<a href="javascript:history.go(-1)" class="md-close back">
		<i class="iconfont">&#xe617</i>
	</a>
	<h2 >售后服务</h2>
		<a href="<?php echo U('wap/goodsorder/index');?>" class="more "><span>所有订单</span><i class="iconfont">&#xe611</i></a>

</div>
<div class="bggreen">
	<div>
		<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><a class="" href="<?php echo U('Wap/goodsrefund/index',array('id'=>$vo['order_goods_id']));?>">
                <div class="refundlist newbox bgwt">
                    <div class="refundtitle clearfix">
                        <span class="floatLft">售后编号:<span class="num green"><?php echo ($vo["refund_sn"]); ?></span></span>
                        <span class="floatRgt">申请时间:<span class="num gray"></span><?php echo ($vo["add_time"]); ?></span>
                    </div>
                    <div class="cartlist">
                        <ul>
                            <li  class="goods">
                                <div class="goodsimg imgnobg">
                                    <div class="img bgcover " style=" background:url(<?php echo ($vo["goods_image"]); ?>)">
                                       <!-- <img src="<?php echo ($vo["goods_image"]); ?>"/>-->
                                    </div>
                                </div>
                                <div class="info">
                                    <div class="title">
                                        <?php echo ($vo["goods_name"]); ?>
                                    </div>
                                    <div class="pay">
                                        <div class="pirce num green">
                                            ￥<?php echo ($vo["goods_price"]); ?>
                                        </div>
                                        <div class="qty">
                                            <span class="num"><?php echo ($vo["goods_number"]); ?></span>件
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix">
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="newbox2"><div class="refundinfo"> <?php echo ($vo["code_name"]); ?>
                        <?php if($vo["un_read"] != 0): echo ($vo["un_read"]); endif; ?>
                    </div>
                </div>
			</a><?php endforeach; endif; else: echo "" ;endif; ?>
	</div>
    <div class="clearfix">
    </div>

    <div class="loading">
		<div class="fm-footer-copyright get_more">

		</div>
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



<input type="hidden" name="p" value="2" />
<input type="hidden" name="order_state" value="<?php echo ($_GET['order_state']); ?>" />
<input type="hidden" name="order" value=""/>
<script type="text/javascript" src="__PUBLIC__/layer/layer.js"></script>
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
                         var html = '';
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
     $(".oder_state_c li").click(function(){
        
        $('.oder_state_c li').removeClass('curr');
        $(this).addClass('curr');
        var data=$(this).attr('data');
        $("input[name='order_state']").val(data);
        $("input[name='p']").val(1);
        get_more();
     });
     
   }); 
   function get_more(){
       load_more = 1;
       var html = '';
       $('.get_more').html('加载中' + html);
     var p=$("input[name='p']").val();
      var order_sta=$("input[name='order_state']").val();
           $.ajax({ 
            url: "<?php echo U('wap/goodsrefund/refund_list');?>",
            type:'post',
            data: {p:p,order_state:order_sta},
            dataType: 'json',
            success: function(data){
              //console.log(data);
               var con=1;
                var option='';
                if(data){
                      $.each(data, function(i, value) {
                        var order_list ='<a href="<?php echo U('Wap/goodsrefund/index');?>?id=' +value.order_goods_id+'>'
                          +'<div class="refundlist newbox bgwt">'
                          +'<div class="refundtitle clearfix">'
                          +'<span class="floatLft">售后编号:<span class="num green">'+value.refund_sn+'</span></span>'
                          +'<span class="floatRgt">申请时间:<span class="num gray"></span>'+add_time+'</span>'
                          +'</div>'
                          +'<div class="cartlist"> <ul> <li  class="goods"> <div class="goodsimg imgnobg">'
                          +'<div class="img bgcover " style=" background:url('+value.goods_image+')">'
                          +'<img src="'+value.goods_image+'"/> </div> </div> <div class="info"> <div class="title">'
                          +value.goods_name+'</div><div class="pay"> <div class="pirce num green">￥'+value.goodspr
                          +'</div> <div class="qty"> <span class="num">'+value.goods_number+'</span>件'
                          +'</div> </div> </div> <div class="clearfix"> </div> </li> </ul> </div> </div>
                          +'<div class="newbox2"><div class="refundinfo"> '+value.code_name
                          +is_read+'</div> </div> </a>'
                          option +=order_list;
                        con++;
                     });
                }
                 $('.more_html').append(option);
               /* if(p==1){
                  $('.more_html').html(option);
                }else{
                 
                }*/
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
   /* $('.more_html').on('click','.delivered_order',function(){
      var __this=$(this);
       var order_id=__this.attr('data');
       alert(order_id);
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
                  if(data.status==1){
                     var str=$('.oder_state_c .pay_order_n p:eq(0)').html();
                   $('.oder_state_c .pay_order_n p:eq(0)').html(parseInt(str) - 1);
                    var str=$('.oder_state_c .f_order_n p:eq(0)').html();
                     $('.oder_state_c .f_order_n p:eq(0)').html(parseInt(str) + 1);
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

     });*/

});
 </script>
</body>
</html>