<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta content="telephone=no" name="format-detection" />
<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0,user-scalable = no"/>
<title>我的钱包</title>
<script src="__PUBLIC__/wap/js/flexible.js"></script>
<link rel="stylesheet" type="text/css" href="__PUBLIC__/wap/css/icons.css">
<link rel="stylesheet" type="text/css" href="__PUBLIC__/wap/css/base.css" />
<link rel="stylesheet" type="text/css" href="__PUBLIC__/wap/css/member.css" />
</head>
<body class="wallet" ontouchstart="" onmouseover="">
<div class="head">
	<a href="javascript:history.go(-1);" class="md-close back">
		<i class="iconfont">&#xe617</i>
	</a>
	<h2>我的钱包</h2>
	<a href="<?php echo U('Wap/Member/cashapply');?>" class="rgtnav">
		提现 <i class="iconfont">&#xe638</i>
	</a>
</div>
<div class="page">
	<div class="type newbox bgwt">
		<ul >
			<li class="<?php if($_GET['type'] != 1): ?>curr<?php endif; ?>
				" data="0">
				<a href="javascript:void(0);">
					<span class="icon"><i class="iconfont">&#xe638</i></span> 钱包
				</a>
			</li>
			<li class="<?php if($_GET['type'] == 1): ?>curr<?php endif; ?>
				" data="1">
				<a href="javascript:void(0);">
					<span class="icon"><i class="iconfont">&#xe641</i></span> 积分
				</a>
			</li>
		</ul>
		<div class="clearfix">
		</div>
		<div class="banner">
			<div class="common-wrapper">
				<div class="userdiv clearfix fm-userdiv money_type_0"  
				
				<?php if(!empty($_GET['type'])): ?>style="display:none"<?php endif; ?>
				>
				<div class="totalwallet" >
					<span>钱包总额：</span><span class="num green floatRgt" >¥<?php echo ($data["balance_all"]); ?><span class="font40">元</span></span>
				</div>
			</div>
			<div class="userdiv clearfix fm-userdiv money_type_1"   
				
			<?php if(empty($_GET['type'])): ?>style="display:none"<?php endif; ?>
			>
			<div class="totalwallet" >
				<span>积分总额：</span><span class="num green floatRgt"><?php echo (floor($data["points"])); ?><span class="font40">分</span></span>
			</div>
		</div>
	</div>
</div>
<div class="clearfix"></div>
</div>
<div class="type_order newbox2 col5">
	<ul class="floatLft ">
		<li class="order_all  on " data="all">
			<a href="javascript:void(0);">
				<span class="icon"><i class="iconfont">&#xe626</i></span> 全部
			</a>
		</li>
		<li data="store" class="order_store ">
			<a href="javascript:void(0);">
				<span class="icon"><i class="iconfont">&#xe61f</i></span> 店铺
			</a>
		</li>
		<li data="goods">
			<a href="javascript:void(0);">
				<span class="icon"><i class="iconfont">&#xe612</i></span> 商品
			</a>
		</li>
		<li  data="lottery" class="money_type_0 "
		<?php if($_GET['type'] == 1 ): ?>style='display:none'<?php endif; ?>
		>
		<a href="javascript:void(0);">
			<span class="icon"><i class="iconfont">&#xe61a</i></span> 抽奖
		</a>
		</li>
		<li  data="cashapply" class="money_type_0 "
		<?php if($_GET['type'] == 1 ): ?>style='display:none'<?php endif; ?>
		>
		<a href="javascript:void(0);">
			<span class="icon"><i class="iconfont">&#xe638</i></span> 提现
		</a>
		</li>
		<li data="refund" class="money_type_1 " <?php if($_GET['type'] != 1): ?>style='display:none'<?php endif; ?>>
			<a href="javascript:void(0);">
				<span class="icon"><i class="iconfont">&#xe642</i></span> 退款
			</a>
		</li>
		<li  data="other" class="money_type_1 "
		<?php if($_GET['type'] != 1): ?>style='display:none'<?php endif; ?>
		>
		<a href="javascript:void(0);">
			<span class="icon"><i class="iconfont">&#xe64d</i></span> 其他
		</a>
		</li>
	</ul>
	<div class="clearfix"></div>
</div>
<div id="fm-mizhi" class="newbox" >
	<ul class="more_html" id="more_html">
		<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li  class="<?php echo ($vo["type_order"]); ?>">
				<div class="title"> <?php echo ($vo["typename"]); ?>
					<div class="pirce floatRgt num green font40"> <?php echo ($vo["status"]); echo ($vo["money"]); ?> </div>
				</div>
				<div class="time num"> <?php echo ($vo["des"]); ?>。<?php echo ($vo["add_time"]); ?> </div>
			</li><?php endforeach; endif; else: echo "" ;endif; ?>
	</ul>
</div>
<div class="loading">
	<div class="fm-footer-copyright get_more">
		<?php if($count > 10 && count($list) == 10): ?>加载更多
			<?php else: ?>
			无更多数据<?php endif; ?>
	</div>
</div>
</div>

<!--end top-->

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
 <script type="text/javascript" src="__PUBLIC__/js/common.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/return-top.js"></script>

<script type="text/javascript">
    var binding_is_check = 0;// 重复再次弹出 1是 0否
    var binding_page = 'member_wallet';// //当前页面名称 不可重复
</script>
<!--绑定-->
<?php if(empty($binding_mobile)): ?><style>
        .layui-layer-demo-wx{background: #fff !important;box-shadow: none !important;border-radius: 0px !important;}
    </style>
    <div class="shares_binding_wx" style="display:none;">
        <div class="new head">
        </div>
        <div class="referee">
            <div class="avatar">
                <img src="<?php echo (get_member_logo_default($recommend_info["member_logo"])); ?>">
            </div>
            <div class="tjinfo">
                <div class="name " >
                    <span class=""> 推　荐　人：</span><?php echo ($recommend_info["member_name"]); ?> </div>
                <div class="tel" >
                    <span class="">推荐人电话：</span><span class="num"><?php echo ($recommend_info["mobile"]); ?></span>
                </div>
            </div>
        </div>
        <div class="bggreen">
            <form id="binding_form"  role="form" class="mlogin newbox" action="" novalidate method="post" onSubmit="return false;">
                <input  type="hidden" name="binding_code" id="binding_code"  value="<?php echo ($share_info["member_card"]); ?>" readonly/>
                <div class="field">
                    <div class="label">
                        手机号
                    </div>
                    <div class="field-control">
                        <input class="input-required" type="tel" name="binding_mobile" id="binding_mobile" placeholder="请输入手机号码" maxlength="11">
                    </div>
                </div>
                <div class="field">
                    <div class="label">
                        验证码
                    </div>
                    <div class="field-control">
                        <input class="input-required codeinput" type="text" name="binding_mobile_code" id="binding_mobile_code" placeholder="请输入验证码" maxlength="6">
                    </div>
                    <a href="javascript:void(0);" class=" code again_message  binding_send_sms">
                        发送验证码
                        <span style="display: none" class="binding_countdown">（<em class="binding_num">60</em>S）</span>
                    </a>
                </div>
                <div class="field">
                    <div class="label">
                        密码
                    </div>
                    <div class="field-control">
                        <input class="input-required " type="password" name="binding_mem_password" id="binding_mem_password"  placeholder="输入密码(6-20位字母，数字或符号组合)" maxlength="20">
                    </div>
                </div>
                <div class="field">
                    <div class="label">
                        重复密码
                    </div>
                    <div class="field-control">
                        <input class="input-required  " type="password" name="binding_rep_password" id="binding_rep_password"  placeholder="重复密码（请与上面密码一致）" maxlength="20">
                    </div>
                </div>
                <input type="hidden" name="is_binding" id="is_binding" value="0">
                <div class="info" style="font-size:.36rem; line-height:1.4rem; padding:0 .4rem;">
                    <input type="checkbox" name="fuwu" id="fuwu" value="1" checked>
                    点击完善资料，表示您同意FG峰购
                    <a href="<?php echo U('wap/Login/fuwu');?>" >
                    《用户协议》
                </a>
                </div>
                <div class="submit mgt4" style="position:static">
                    <button type="button" href="javascript: void(0);" class="button phone_binding_btn">完善资料</button>
                </div>
            </form>
        </div>
    </div>
        <script type="text/javascript">
            $(function(){
                //捕获页 自动弹窗
                $.ajax({
                    url: "<?php echo U('wap/login/get_check_binding');?>",
                    type:'get',
                    data:{binding_is_check:binding_is_check,binding_page:binding_page},
                    dataType: 'json',
                    success: function(data){
                        if(data.status==1){
                            get_binding()
                        }else if(data.status==2){
                            $('.shares_binding_wx').hide();
                        }else{
                            $('.shares_binding_wx').hide();
                        }
                    }
                });

            });
        </script>


        <script type="text/javascript">

            $('.verify_refresh').click(function(){
                //重载验证码
                var time = new Date().getTime();
                var src='<?php echo U('Wap/Login/verify');?>?'+time;
                $(this).attr('src',src);
            });
        </script>

        <script type="text/javascript">
            function get_binding(){
                // var h = $(window).height();//获取页面的高度
                layer.open({
                    type: 1,
                    shade: 0.8,//透明度
                    title: false, //不显示标题
                    skin: 'layui-layer-demo-wx', //样式类名
                    closeBtn: false,//关闭按钮
                    shadeClose: true, //开启遮罩关闭
                    area: ['100%', "100%"],
                    scrollbar: false,//浏览器滚动锁定
                    content: $('.shares_binding_wx'), //捕获的元素
                    cancel: function(index){

                        layer.close(index);
                        this.content.show();
                        $('.shares_binding_wx').hide();
                        //layer.msg('捕获就是从页面已经存在的元素上，包裹layer的结构',{time: 5000});
                    }
                });
            }
        </script>
        <script>
            jQuery(document).ready(function() {

                //FormValidator.init();

                jQuery.validator.addMethod("binding_pwd_required", function(value) {
                    var is_binding=$("#is_binding").val();
                    var ret=true;
                    if(is_binding=='0'){
                        if(value){

                        }else{
                            ret=false;
                        }
                    }
                    return ret;
                });
                jQuery.validator.addMethod("binding_pwd_rangelength", function(value) {
                    var is_binding=$("#is_binding").val();
                    var ret=true;
                    if(is_binding=='0'){
                        if(value){
                            var leng=value.length;
                            if(leng<6 || leng>20){
                                ret=false;
                            }
                        }else{
                            ret=false;
                        }
                    }
                    return ret;
                });
                jQuery.validator.addMethod("binding_rep_required", function(value) {
                    var is_binding=$("#is_binding").val();
                    var ret=true;
                    if(is_binding=='0'){
                        if(value){

                        }else{
                            ret=false;
                        }
                    }
                    return ret;
                });
                jQuery.validator.addMethod("binding_rep_rangelength", function(value) {
                    var is_binding=$("#is_binding").val();
                    var ret=true;
                    if(is_binding=='0'){
                        if(value){
                            var leng=value.length;
                            if(leng<6 || leng>20){
                                ret=false;
                            }
                        }else{
                            ret=false;
                        }
                    }
                    return ret;
                });
                jQuery.validator.addMethod("binding_rep_equalTo", function(value) {
                    var binding_mem_password=$("#binding_mem_password").val();
                    var is_binding=$("#is_binding").val();
                    var ret=true;
                    if(is_binding=='0'){
                        if(value==binding_mem_password){

                        }else{
                            ret=false;
                        }
                    }
                    return ret;
                });


                $("#binding_form").validate({
                    rules: {
                        binding_mobile: {
                            required:true,
                            number:true,
                            minlength:11,
                            remote: {
                                url: "<?php echo U('Wap/Login/cheack_binding_mobile');?>",
                                type: "get",
                                dataType: "json",
                                data: {
                                    mobile: function () {
                                        return $("#binding_mobile").val();　　　　//这个是取要验证的数据
                                    }
                                },
                                dataFilter: function (msg) {　　　　//判断控制器返回的内容
                                    $('#is_binding').val(msg);
                                    //0  未注册 1 已注册
                                    if(msg=='0'){//填写密码
                                        $('.binding_set_pwd').show();
                                    }else{
                                        $('.binding_set_pwd').hide();
                                    }
                                    return true;
                                }
                            }
                        },
                        binding_mobile_code: {
                            required:true,
                            remote: {
                                url: "<?php echo U('Wap/Login/check_send_reg');?>",
                                type: "get",
                                dataType: "json",
                                data: {
                                    mobile: function () {
                                        return $("#binding_mobile").val();　　　　//这个是取要验证的数据
                                    },
                                    mobile_code: function () {
                                        return $("#binding_mobile_code").val();　　　　//这个是取要验证的数据
                                    }
                                },
                                dataFilter: function (msg) {　　　　//判断控制器返回的内容
                                    if (msg == "1") {
                                        return true;
                                    }else {
                                        return false;
                                    }
                                }
                            }
                        },
                        binding_mem_password: {
                            binding_pwd_required: true,
                            binding_pwd_rangelength: true,
                        },
                        binding_rep_password: {
                            binding_rep_required: true,
                            binding_rep_rangelength: true,
                            binding_rep_equalTo: true
                        },
                        fuwu:'required'
                    },
                    messages: {
                        binding_mobile:{
                            binding_pwd_required:'<span class="icon-warning icon_warning"></span>&nbsp;请输入手机号码',
                            number:'<span class="icon-warning icon_warning"></span>&nbsp;请输入正确的手机号码',
                            minlength:'<span class="icon-warning icon_warning"></span>&nbsp;请输入正确的手机号码',
                            remote:'<span class="icon-warning icon_warning"></span>&nbsp;手机号码已注册'
                        },
                        binding_mobile_code: {
                            required:'<span class="icon-warning icon_warning"></span>&nbsp;请输入手机验证码',
                            remote:'<span class="icon-warning icon_warning"></span>&nbsp;手机验证码不正确'
                        },
                        binding_mem_password: {
                            binding_pwd_required:'<span class="icon-warning icon_warning"></span>&nbsp;请输入密码',
                            binding_pwd_rangelength:'<span class="icon-warning icon_warning"></span>&nbsp;密码请保持6-20位'
                        },
                        binding_rep_password: {
                            binding_rep_required:'<span class="icon-warning icon_warning"></span>&nbsp;请输入重复密码',
                            binding_rep_rangelength:'<span class="icon-warning icon_warning"></span>&nbsp;重复密码请保持6-20位',
                            binding_rep_equalTo:'<span class="icon-warning icon_warning"></span>&nbsp;两次密码输入不一致'
                        },
                        fuwu:'<span class="icon-warning icon_warning"></span>&nbsp;请同意服务协议'
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
                            url :  "<?php echo U('Wap/Login/binding'); ?>",
                            type : "post" ,
                            dataType:'json',
                            target : "#loader",
                            error: function(){layer.msg("服务器没有返回数据，可能服务器忙，请重试",{icon:5});},
                            onwait : "正在处理信息，请稍候...",
                            success: function(response){
                                // console.log(response);
                                //$("#loader").fadeIn(500).html(response.data).fadeOut(500); 
                                //$('#editform').hide(2000); 
                                if(response.status==1){
                                    //alert("注册成功");
                                    layer.msg('绑定成功', {icon: 6},function(){
                                        //关闭后的操作
                                        if(return_next == 'join'){
                                            $(".join").click();
                                        }else{
                                            window.location.href="<?php echo U('Wap/Member/index'); ?>";
                                        }

                                    });
                                }else{
                                    layer.msg(response.error, {icon: 5});
                                    // alert(response.error);
                                }
                            }
                        };
                        setTimeout((function(opt){
                            return function(){
                                $('#binding_form').ajaxSubmit(opt);
                            }
                        })(options), 300);
                        return false;
                    }

                });



                $('.phone_binding_btn').click(function(){
                    $('#binding_form').submit();
                });

                $('.binding_send_sms').click(function(){
                    var is_send=$(this).hasClass('disable');
                    if(is_send){
                        return false;
                    }else{
                        //短信发送
                        $.ajax({
                            url: "<?php echo U('Wap/Login/binding_send_sms');?>",
                            type: "get",
                            dataType: "json",
                            data: {
                                mobile: function () {
                                    return $("#binding_mobile").val();　　　　//这个是取要验证的数据
                                }
                            },
                            success: function (msg) {　　　　//判断控制器返回的内容
                                if (msg.status == "1") {
                                    //disable
                                    // $('#mobile_code').val(msg.code);
                                    // alert("验证码发送成功");
                                    layer.msg('验证码发送成功', {icon: 6});
                                    run_time_out();
                                }else {
                                    // alert(msg.msg);
                                    layer.msg(msg.msg, {icon: 5});
                                }
                            }
                        });
                    }
                });

            });
            function run_time_out(){
                var num=$('.binding_num').html();
                num=parseInt(num);
                var is_send=$('.binding_send_sms').hasClass('disable');
                if(num>0){
                    $('.binding_get_sms').hide();
                    $('.binding_countdown').show();
                    $('.binding_send_sms').css('cssText','background-color: #eee;color: #666;-webkit-appearance: none;');
                    num--;
                    $('.binding_num').html(num);//显示倒计时
                    if(!is_send){
                        $('.binding_send_sms').addClass('disable');///禁止点击
                    }
                }else{
                    $('.binding_countdown').hide();
                    $('.binding_get_sms').show();
                    $('.binding_num').html(60);//初始化倒计时
                    $('.binding_send_sms').removeClass('disable');///可以点击
                    $('.binding_send_sms').removeAttr('style');
                    return false;
                }
                setTimeout('run_time_out()',1000);
            }
        </script><?php endif; ?>
<script type="text/javascript">
    $(function(){
        $('.binding_phone_close').click(function(){
            $('.binding_phone_head_all').hide();
        });

        $('.binding_phone_go').click(function(){
            get_binding()
        });

        $('.is_guanzhu_close').click(function(){
            $('.is_guanzhu_all').remove();
        });
        set_scroll()

    });
    $(window).scroll(function () {
        set_scroll()
    });
    function set_scroll(){
        if ($(window).scrollTop() > 5) {
            $(".is_guanzhu_all").css('cssText', 'position:fixed;top:0;z-index:999;');
            ;
        }
        else {
            $(".is_guanzhu_all").css('cssText', '');
            ;
        }
    }
</script>
<!--绑定-->

<!--end top-->
<input type="hidden" name="p" value="2" />
<input type="hidden" name="type" value="<?php echo ($_GET["type"]); ?>" />
<input  type="hidden" name="type_order" value="">
<!--微信分享-->
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
<script type="text/javascript">
	var type=$("input[name='type']").val();
   $(function(){
     $(".get_more").click(function(){
        get_more();
     });
      $(".type li").click(function(){
        var　__this=$(this);
        $('.type li').removeClass('curr');
        __this.addClass('curr');
        var type=__this.attr('data');
        $("input[name='p']").val(1);
        $("input[name='type']").val(type);
        if(type==1){
          $('.money_type_0').hide();
          $('.money_type_1').show();
			$('.order_all').click();
        }else{
          $('.money_type_1').hide();
          $('.money_type_0').show();
			$('.order_all').click();
        }
     });

	   //type_order 点击显隐
		$('.type_order li').click(function () {
			var type_order=$(this).attr('data');
			$('.type_order li').removeClass('on');
			$(this).addClass('on');
/*			if(type_order == 'all'){
				$('.more_html li').show();
			}else {
				$('.more_html li').hide();
				$('.more_html').find('.'+type_order).show();
			}*/
			$("input[name='type_order']").val(type_order);
			$("input[name='p']").val(1);
			$('.get_more').show();
			get_more();
/*			if(content){
				$('#fm-mizhi').show();
			}else {
				$('#fm-mizhi').hide();
			}*/
		});
   }); 
   function get_more(){
     var p=$("input[name='p']").val();
     var type=$("input[name='type']").val();
	 var type_order=$("input[name='type_order']").val();
      if(p==1){
          $('.more_html').html("");
        }
           $.ajax({ 
            url: "<?php echo U('wap/member/wallet');?>",
            type:'get',
            data: {p:p,type:type,type_order:type_order},
            dataType: 'json',
            success: function(data){
				//console.log(data);
               var con=1;
                 var option='';
              if(data){
                 $.each(data, function(i, value) {
                //this指向当前元素
                //i表示Array当前下标
                //value表示Array当前元素
                 option=option+'<li class="'+value.type_order+'">'
                   +' <div class="title">'+value.typename+'<div class="pirce floatRgt num green font40">'+value.status+value.money+'</div></div>'
                  +'  '
                   +' <div class="time num">'+value.des+'。'+value.add_time+'</div>'
                    +' </li>';
                    con++;
                 });
              }

              if(p==1){
                $('#more_html').html(option);
              }else{
                 $('#more_html').append(option);
              }
              $("input[name='p']").val(parseInt(p)+1);
              if(con<10){
                 $('.get_more').html('无更多数据');
				  $('.get_more').fadeOut(3000);
              }
				var content=$('.more_html').children('li').length;
				if(content == 0){
					$('#fm-mizhi').hide();
				}else {
					$('#fm-mizhi').show();
				}
            }
           });
   }
</script>
</body>
</html>