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
<div class="new head"> <a href="javascript:history.go(-1)" class="md-close back"> <i class="iconfont">&#xe617</i> </a>
	<h2 >售后服务</h2>
	<a href="<?php echo U('wap/goodsrefund/refund_list');?>" class="more "><span>售后订单</span><i class="iconfont">&#xe611</i></a> </div>
<div class="bggreen">
	<div class="newbox cartlist">
		<ul>
			<li  class="goods">
				<div class="goodsimg imgnobg">
                    <div class="img bgcover " style=" background:url(<?php echo ($data["goods_image"]); ?>)">

                    </div>
				</div>
				<div class="info">
					<div class="title"> <?php echo ($data["goods_name"]); ?> </div>
					<div class="pay">
						<div class="pirce num green"> ￥<?php echo ($data["goods_price"]); ?> </div>
						<div class="qty">
                            <span class="num"><?php echo ($data["goods_number"]); ?></span>件&nbsp; &nbsp;
                            <?php if(($data['shipping_money']) == "0"): ?>包邮
                                <?php else: ?>
                                运费:￥<?php echo ($data["shipping_money"]); endif; ?>
                        </div>

					</div>
				</div>
				<div class="clearfix"> </div>
			</li>
		</ul>
	</div>
	<div class="kefu newbox2 bgwt"> <a class="tel green" href="tel:02386716108"><i class="iconfont green ">&#xe65f</i><span>客服电话</span></a> <a class="qq green" href="http://q.url.cn/s/p7xpDJm"><i class="iconfont green">&#xe64e</i><span>在线QQ</span></a> </div>
	<form id="form2" action=""  class="mlogin newbox bgwt" novalidate method="post" onSubmit="return false;">
		<div class="describe">
			<h4>问题描述</h4>
			<div class="field autoClear">
				<textarea id="refund_remark" name="refund_remark" cols="" rows="" placeholder="请您在此描述详细问题" class="desarea"></textarea>
			</div>
			<div  class="uploadfile">
				<div class="uploadbtnbox" style="display: inline-block">
					<input id="up_img_url" type="file" name="questionPic"  class="upload-btn upload_input">
				</div>
				<iframe name="uploadIfrm" style="display: none"></iframe>
			</div>
			<div class="clearfix"></div>
			<div class="msg">最多上传3张，每张不超过5M，支持JPG、BMP、PNG</div>
		</div>
		<div class="contact">
			<h4>联系人信息</h4>
			<div class="field autoClear">
				<div class="label"> 联系人 </div>
				<div class="field-control">
					<input type="text" class="input-required" id="refund_name" name="refund_name" value="<?php echo ($order["consignee"]); ?>" placeholder="联系人">
				</div>
			</div>
			<div class="field autoClear">
				<div class="label"> 联系电话 </div>
				<div class="field-control">
					<input type="text"  class="input-required" id="refund_tel"  name="refund_tel"   placeholder="联系电话" value="<?php echo ($order["mobile"]); ?>">
				</div>
			</div>
		</div>
		<input type="hidden" class="fm-text al"  name="order_id"   placeholder="" value="<?php echo ($data["order_id"]); ?>">
		<input type="hidden" class="fm-text al"  name="order_goods_id"   placeholder="" value="<?php echo ($data["rec_id"]); ?>">
		<!--		<input type="hidden" class="fm-text al"  name="refund_type"   placeholder="" value="<?php echo (($_GET["type"])?($_GET["type"]):'0'); ?>">-->
	</form>
	<div class="submit"> <a href="javascript:void(0);" class="refund_btn"> 提交 </a> </div>
</div>
<script type="text/javascript" src="__PUBLIC__/wap/js/jquery.min.js"></script>
<script src="__PUBLIC__/bootstrap/vendor/jquery-validation/jquery.validate.wap.js"></script>
<script src="__PUBLIC__/js/jquery.form.js"></script>
<script type="text/javascript" src="__PUBLIC__/layer/layer.js"></script>
 <script type="text/javascript" src="__PUBLIC__/js/common.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/return-top.js"></script>
 
<script src="__PUBLIC__/bootstrap/vendor/ajaxfileupload/ajaxfileupload.js"></script> 
<script src="__PUBLIC__/bootstrap/vendor/jquery-validation/jquery.validate.wap.js"></script> 
<script src="__PUBLIC__/js/jquery.form.js"></script> 
<script>
jQuery(document).ready(function() {

$("#form2").validate({
  rules: {
     refund_remark:'required',
    refund_name:'required',
    refund_tel: {
    required:true,
    number:true,
    minlength:11
   }
  },
  messages: {
    refund_remark:'<span class="icon-warning icon_warning"></span>&nbsp;请输入问题描述',
       refund_name:'<span class="icon-warning icon_warning"></span>&nbsp;请输入联系人',
       refund_tel:{
        required:'<span class="icon-warning icon_warning"></span>&nbsp;请输入手机号码',
        number:'<span class="icon-warning icon_warning"></span>&nbsp;请输入正确的手机号码',
        minlength:'<span class="icon-warning icon_warning"></span>&nbsp;请输入正确的手机号码'
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
                url :  "<?php echo U('Wap/goodsrefund/refund'); ?>", 
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
                        layer.msg('提交成功', {icon: 6},function(){
                      //关闭后的操作
                      window.location.href="<?php echo U('Wap/goodsrefund/index',array('id'=>$data['rec_id'])); ?>"; 
                      }); 
                    }else{
                        layer.msg(response.error, {icon: 5}); 
                       // alert(response.error);
                    }
                }
            };
            setTimeout((function(opt){
                return function(){
                    $('#form2').ajaxSubmit(opt);
                }
            })(options), 300); 
            return false; 
        }

  });


$(".refund_type li").click(function(){
    var val=$(this).attr('data-type');
    $('input[name="refund_type"]').val(val);
    $(".refund_type li").removeClass('active');
    $(this).addClass('active');
});
$('.refund_btn').click(function(){
    $('#form2').submit();
});


});


    $(document).ready(function (e) {
         ///上传商家logo
        $('.uploadfile').on('change','.upload_input', function () {
                 var goods_length=$(".refund_img").length;
                if(goods_length>=4){
                     layer.msg('图片最多上传4张', {icon: 5});
                }else{
                    ajaxFileUploadMore('url',"<?php echo U('admin/Imagecat/upload_ajax');?>");
                }
        });

         $('.uploadfile').on('click','.delete_img', function () {
            var __thiss=$(this);
             layer.confirm('确定删除该图片？', {
              btn: ['确定','取消'] //按钮
             },function(){
                 __thiss.remove();
                 var goods_length=$(".refund_img").length;
                 if(goods_length < 4){
                     $('.uploadbtnbox').show();
                 }
               layer.msg('已删除', {icon: 6,time:1000}); 
              }, function(){
          
              });
        });


    });

    function show_head(head_file) {

        //插入数据库
        $("#head_photo_src").attr('src', head_file);
        var img = head_file.split('/');
      //  var file = '/' + img[3] + '/' + img[4] + '/' + img[5] + '/' + img[6];
        $("#photo_pic").val(head_file);
        //});

    }

//多文件上传带预览
    function ajaxFileUploadMore(imgid,  url) {

        $.ajaxFileUpload({
                    url: url,
                    secureuri: false,
                    fileElementId: "up_img_"+imgid,
                    dataType: 'json',
                    data: {name: 'logan', id: 'id'},
                    success: function (data, status)
                    {

                        if (typeof (data.error) != 'undefined')
                        {

                            if (data.error != '')
                            {
                                layer.msg(data.error, {icon: 5}); 
                            } else {

                                var resp = data.msg;
                                if (resp != '0000') {
                                    layer.msg(data.error, {icon: 5}); 
                                    return false;
                                } else {
                               var str_img=' <div class="img-item fileinput delete_img"><span><img   src="'+data.imgurl
                               +'"><input type="hidden" name="refund_img[]"  class="refund_img"  value="'
                               +data.imgurl+'"></span></div>';
                                 $('.uploadfile').append(str_img);
                                    var goods_length=$(".refund_img").length;
                                    if(goods_length >= 4){
                                        $('.uploadbtnbox').hide();
                                    }
                                }

                            }
                        }
                    },
                    error: function (data, status, e)
                    {
                        layer.msg('上传错误', {icon: 5}); 
                    }
                });

        return false;
    }

        </script>
</body>
</html>