<?php if (!defined('THINK_PATH')) exit();?><html>
<head>
    <meta charset="utf-8">
    <title><?php echo ($webseting["web_title"]); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
    <meta name="format-detection" content="telephone=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <script src="__PUBLIC__/wap/js/flexible.js"></script>
    <script type="text/javascript" src="__PUBLIC__/wap/js/jquery.min.js"></script>
    <script type="text/javascript" src="__PUBLIC__/js/jquery.form.js"></script>
    <script src="__PUBLIC__/bootstrap/vendor/jquery-validation/jquery.validate.wap.js"></script>
    <script type="text/javascript" src="__PUBLIC__/layer/layer.js"></script>
    <link href="__PUBLIC__/wap/css/icons.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="__PUBLIC__/wap/css/base.css" />
    <link rel="stylesheet" type="text/css" href="__PUBLIC__/wap/css/member.css" />


    <link rel="stylesheet" type="text/css" href="__PUBLIC__/wap/css/swiper.min.css" />
    <link rel="stylesheet" type="text/css" href="__PUBLIC__/wap/css/public.css" />
</head>
<body class="index" ontouchstart="" onmouseover="">
<div class="shares_binding_wx" style=" height:100%; background:#f6f6f6 ;">
	<div class="new head">
		<a href="javascript:history.go(-1)" class="md-close back">
			<i class="iconfont">&#xe617</i>
		</a>
		<h2>完善资料</h2>
	</div>
<form id="binding_form"  role="form" class="mlogin" action="" novalidate method="post" onSubmit="return false;">
      <div class="field">
          <div class="label"> 姓名 </div>
          <div class="field-control">
              <input class="input-required" type="tel" name="real_name" id="real_name" placeholder="请输入用户姓名" value="<?php echo ($count ? $data['real_name'] : ''); ?>" >
          </div>
      </div>
	  <div class="field">
		  <div class="label"> 手机号 </div>
		  <div class="field-control">
			  <input class="input-required" type="tel" name="mobile" id="mobile" placeholder="请输入手机号码" value="<?php echo ($count ? $data['mobile'] : ''); ?>" maxlength="11">
		  </div>
	  </div>
      <div class="field">
          <div class="label"> 公司名称 </div>
          <div class="field-control">
              <input class="input-required" type="tel" name="company" id="company" placeholder="请输入公司名称" value="<?php echo ($count ? $data['company'] : ''); ?>" >
          </div>
      </div>
	  <input type="hidden" name="is_binding" id="is_binding" value="0">
      <input type="hidden" name="count" id="count" value="<?php echo ($count); ?>">
      <input type="hidden" name="status" id="status" value="<?php echo ($count ? $data['status'] : 0); ?>">
	  <div class="submit mgt4" style="position:static">
		  <button type="button" href="javascript: void(0);" class="button phone_binding_btn">完善资料</button>
	  </div>
	</form>
</div>
<script>
    jQuery(document).ready(function() {
        var status = $('#status').val();
        var count = $('#count').val();

        function add_msg(msg,_yes,_no) {
            //询问框
            //var msg='亲 浏览商品需要审核信息<br>请完善您的姓名(必填),电话(必填),以及工作单位,待后台工作人员审核通过之后方可浏览';
            layer.confirm(msg, {
                btn: [_yes,_no]//按钮
            }, function(){
                layer.closeAll();
            }, function(){
                location.href="<?php  echo U('wap/member/index');?>";
            });
        }

        var msg ='';
        var _yes = '';
        var _no = '';
        if(count == 0){
             msg='亲 浏览商品需要审核信息<br>请完善您的姓名(必填),电话(必填),以及工作单位,待后台工作人员审核通过之后方可浏览';
            _yes = '马上完善';
            _no = '算了不看了';
            add_msg(msg,_yes,_no);
        }else if(status == 0){
            msg='亲 您所完善的信息正在审核当中<br>请耐心等待,也可在此修改！';
            _yes = '我要修改';
            _no = '暂时不用';
            add_msg(msg,_yes,_no);
        }else if(status == 2){
            _yes = '好的';
            _no = '我不';
            msg='亲 您所完善的信息未通过审核<br/>原因:<br/><?php echo ($count ? $data["remark"] : ""); ?>';
            add_msg(msg,_yes,_no);
        }

        //FormValidator.init();
        jQuery.validator.addMethod ("checkType",function(value){
            var re =/^[\u4e00-\u9fa5]*$/.test($("#real_name").val());
            var ret = false;
            if(re){
                ret = true;
            }
            return ret;
        });
        $("#binding_form").validate({
            rules: {
                mobile: {
                    required:true,
                    number:true,
                    minlength:11,
                    remote: {
                        url: "<?php echo U('Wap/Login/cheack_binding_mobile');?>",
                        type: "get",
                        dataType: "json",
                        data: {
                            mobile: function () {
                                return $("#mobile").val();　　　　//这个是取要验证的数据
                            }
                        },
                        dataFilter: function (msg) {　　　　//判断控制器返回的内容
                            $('#is_binding').val(msg);
                            //0  未注册 1 已注册
                            return true;
                        }
                    }
                },
                real_name:{
                    required:true,
                    checkType:true
                }
            },
            messages: {
                mobile:{
                    required:'<span class="icon-warning icon_warning"></span>&nbsp;请输入手机号码',
                    number:'<span class="icon-warning icon_warning"></span>&nbsp;请输入正确的手机号码',
                    minlength:'<span class="icon-warning icon_warning"></span>&nbsp;请输入正确的手机号码',
                    remote:'<span class="icon-warning icon_warning"></span>&nbsp;手机号码已注册'
                },
                real_name: {
                    required:'<span></span>&nbsp;请输入真实姓名',
                    checkType:'<span class="icon-warning icon_warning"></span>&nbsp;姓名只能输入中文'
                }
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
                                window.location.href="<?php echo U('Wap/Member/index'); ?>";
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

    });

</script>
<script type="text/javascript">
    $(function(){
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
</body>
</html>