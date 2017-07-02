<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>发布信息-<?php echo ($webseting["web_title"]); ?></title>
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
	<h2 >发布信息</h2>
 </div>
<div class="bggreen">
	<form id="form2" action=""  class="mlogin newbox bgwt" novalidate method="post" onSubmit="return false;">
		<div class="describe">
            <div class="field autoClear">
                <div class="label"> 标题 </div>
                <div class="field-control">
                    <input type="text" class="input-required" id="title" name="title" value="<?php echo ($_GET['id'] ? $data['title'] : null); ?>" placeholder="信息标题">
                </div>
            </div>
            <h4 class="label"> 信息内容 </h4>
			<div class="field autoClear">
				<textarea id="content" name="content" cols="" rows="" placeholder="请您填写信息内容" class="desarea"><?php echo ($_GET['id'] ? $data['content'] : null); ?></textarea>
			</div>
			<div  class="uploadfile">
               <?php if(is_array($data['imgs_a'])): $i = 0; $__LIST__ = $data['imgs_a'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v_i): $mod = ($i % 2 );++$i;?><div class="img-item fileinput delete_img">
                       <span>
                           <img   src="<?php echo ($v_i); ?>">
                           <input type="hidden" name="msg_img[]"  class="msg_img"  value="<?php echo ($v_i); ?>">
                       </span>
                   </div><?php endforeach; endif; else: echo "" ;endif; ?>
				<div class="uploadbtnbox" style="display: inline-block">
					<input id="up_img_url" type="file" name="goods_images"  class="upload-btn upload_input">
				</div>
				<iframe name="uploadIfrm" style="display: none"></iframe>
			</div>
			<div class="clearfix"></div>
			<div class="msg">最多上传4张，每张不超过5M，支持JPG、BMP、PNG</div>
		</div>
        <input type="hidden" name="id" value="<?php echo ($_GET['id']); ?>">
	</form>
	<div class="submit"> <a href="javascript:void(0);" class="sub_btn"> 提交 </a> </div>
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
      title:'required',
      content:'required',
      msg_img:'required'
  },
  messages: {
      title:'<span class="icon-warning icon_warning"></span>&nbsp;请输入商品名称',
      content:'<span class="icon-warning icon_warning"></span>&nbsp;请输入商品描述',
      msg_img:'<span class="icon-warning icon_warning"></span>&nbsp;请上传商品图片'
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
                url :  "<?php echo U('Wap/Carinfo/add_msg'); ?>", 
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
                        layer.msg('编辑成功', {icon: 6},function(){
                      //关闭后的操作
                      window.location.href="<?php echo U('Wap/carinfo/index'); ?>";
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


$('.sub_btn').click(function(){
    $('#form2').submit();
});


});


    $(document).ready(function (e) {
         ///上传商家logo
        $('.uploadfile').on('change','.upload_input', function () {
                 var goods_length=$(".msg_img").length;
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
                 var goods_length=$(".msg_img").length;
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
                               +'"><input type="hidden" name="msg_img[]"  class="msg_img"  value="'
                               +data.imgurl+'"></span></div>';
                                 $('.uploadfile').append(str_img);
                                    var goods_length=$(".msg_img").length;
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