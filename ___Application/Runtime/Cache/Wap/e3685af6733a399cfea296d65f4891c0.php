<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="format-detection" content="telephone=no">
    <meta name="viewport" content="minimal-ui,width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <title>功能介绍-<?php echo ($webseting["web_title"]); ?></title>
    <link href="__PUBLIC__/wap/css/rest.css"  rel="stylesheet">
    <link href="__PUBLIC__/wap/css/user.css"  rel="stylesheet">
</head>
<body id="body">
       <!--绑定头部-->    
 
    <!--绑定头部-->
    <header id="header" class="u-header clearfix">
        <div class="u-hd-left f-left">
            <a href="javascript:history.go(-1);" class="J_backToPrev"><span class="u-icon-px i-hd-back"></span></a>
        </div>
        <div class="u-hd-tit"><span>功能介绍</span></div>
    </header>
    <div class="g-cjs">
        <?php echo ($introduction); ?>
    </div>
   
<div style="height:50px;">
   </div> 
     <!--end top-->
       <footer class="index_nav hide" style="display: block;">
        <a href="<?php echo U('Wap/Start/index');?>"> <div class="store_button"><img src="__PUBLIC__/wap/img/woyaokaidian.png" width="130px" height="80xp" alt="我要开店" title="我要开店" ></div> </a>

        <ul class="wd_nav">

           <a href="<?php echo U('Wap/Index/index');?>" > <li   class="classify <?php if($now_menu == 'home'): ?>classify_2<?php else: ?>classify_1<?php endif; ?>">首页</li></a>
             <a href="<?php echo U('Wap/Goods/index');?>" ><li   class="dynamic <?php if($now_menu == 'shop'): ?>dynamic_2<?php else: ?>dynamic_1<?php endif; ?>">商城</li></a>


            <a href="<?php echo U('Wap/Member/index');?>"   ><li class="store <?php if($now_menu == 'member'): ?>store_2<?php else: ?>store_1<?php endif; ?>" >个人中心</li></a>
            <a href="<?php echo U('Wap/Cart/index');?>"  > <li class="contact <?php if($now_menu == 'cart'): ?>contact_2<?php else: ?>contact_1<?php endif; ?>">购物车</li></a>

        </ul>
    </footer>
 
     <!--end top-->
       <footer class="index_nav hide" style="display: block;">
        <a href="<?php echo U('Wap/Start/index');?>"> <div class="store_button"><img src="__PUBLIC__/wap/img/woyaokaidian.png" width="130px" height="80xp" alt="我要开店" title="我要开店" ></div> </a>

        <ul class="wd_nav">

           <a href="<?php echo U('Wap/Index/index');?>" > <li   class="classify <?php if($now_menu == 'home'): ?>classify_2<?php else: ?>classify_1<?php endif; ?>">首页</li></a>
             <a href="<?php echo U('Wap/Goods/index');?>" ><li   class="dynamic <?php if($now_menu == 'shop'): ?>dynamic_2<?php else: ?>dynamic_1<?php endif; ?>">商城</li></a>


            <a href="<?php echo U('Wap/Member/index');?>"   ><li class="store <?php if($now_menu == 'member'): ?>store_2<?php else: ?>store_1<?php endif; ?>" >个人中心</li></a>
            <a href="<?php echo U('Wap/Cart/index');?>"  > <li class="contact <?php if($now_menu == 'cart'): ?>contact_2<?php else: ?>contact_1<?php endif; ?>">购物车</li></a>

        </ul>
    </footer>
 
     <script type="text/javascript" src="__PUBLIC__/wap/js/jquery.min.js"></script>
<script src="__PUBLIC__/bootstrap/vendor/jquery-validation/jquery.validate.wap.js"></script>
<script src="__PUBLIC__/js/jquery.form.js"></script>
<script type="text/javascript" src="__PUBLIC__/layer/layer.js"></script>
 <script type="text/javascript" src="__PUBLIC__/js/common.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/return-top.js"></script>


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
   var  binding_is_check=0;// 重复再次弹出 1是 0否
   var  binding_page='about_int';// //当前页面名称 不可重复
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
</body>
</html>