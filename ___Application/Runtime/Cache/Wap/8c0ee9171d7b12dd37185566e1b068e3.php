<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="format-detection" content="telephone=no">
    <meta name="viewport" content="minimal-ui,width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <title>二手车信息</title>
    <script src="__PUBLIC__/wap/js/flexible.js"></script>
    <link href="__PUBLIC__/wap/css/icons.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="__PUBLIC__/wap/css/base.css" />
    <link rel="stylesheet" type="text/css" href="__PUBLIC__/wap/css/order.css" />
    <link href="__PUBLIC__/wap/css/service.css"  rel="stylesheet">
</head>

<body class="order service">
<div class="new head">
    <a href="javascript:history.go(-1)" class="md-close back">
        <i class="iconfont">&#xe617</i>
    </a>
    <h2 >售后服务</h2>
</div>
<div class="bggreen">
    <div class="newbox  cartlist">
        <ul>
            <li  class="goods bgwt">
                <div class="goodsimg imgnobg">
                    <div class="img bgcover " style=" background:url(<?php echo ($data["goods_image"]); ?>)">

                    </div>
                </div>
                <div class="info">
                    <div class="title">
                        <?php echo ($data["goods_name"]); ?>
                    </div>
                    <div class="pay">
                        <div class="pirce num green">
                            ￥<?php echo ($data["goods_price"]); ?>
                        </div>
                        <div class="qty">
                            <span class="num"><?php echo ($data["goods_number"]); ?></span>件&nbsp; &nbsp;
                            <?php if(($data['shipping_money']) == "0"): ?>包邮
                                <?php else: ?>
                                运费:￥<?php echo ($data["shipping_money"]); endif; ?>
                        </div>
                    </div>
                </div>
                <div class="clearfix">
                </div>
            </li>
        </ul>
    </div>
    <div class="kefu newbox2 bggray">
       <a class="tel green" href="tel:02386716108"><i class="iconfont green ">&#xe65f</i><span>客服电话</span></a>
    <a class="qq green" href="http://q.url.cn/s/p7xpDJm"><i class="iconfont green">&#xe64e</i><span>在线QQ</span></a>
    	</div>
	<div class="boxtop bggray">
		<div class="detailsinfo">
            <div class="clearfix">
                <span class="floatLft">售后编号：<span class="num green"><?php echo ($refund['refund_sn']); ?></span></span>
                <span class="floatRgt">状态：<?php echo ($refund['code_name']); ?></span>
            </div>
            <div class="clearfix">
                <span class="floatLft">联系人:<?php echo ($refund['refund_name']); ?></span>
                <span class="floatRgt">联系电话:<span class="num green"><?php echo ($refund['refund_tel']); ?></span></span>
            </div>
            <?php if($refund['refund_money'] != 0 && $refund['code'] == 5): ?><div class="clearfix">
                    <span class="floatLft">退款金额：￥<?php echo ($refund['refund_money']); ?></span>
                </div><?php endif; ?>
        </div>
        
        </div>
    	
    	
    	
    	
    <div class="newbox bgwt">
 <h4><i class="iconfont">&#xe604</i>售后记录</h4>
        <div class="goods-apply-report">
            <?php if(is_array($list)): $key = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($key % 2 );++$key;?><div class="info">
                    <h6 class="clearfix">
                        <?php if($vo["type"] == 1): ?>我的售后信息
                            <?php else: ?>
                            FG峰购售后客服<?php endif; ?>
                        <span class="floatRgt num gray"><?php echo (date('Y.m.d',$vo['add_time'] )); ?></span>
                    </h6>
                    <div class="fm-form">
                        <?php echo ($vo['content']); ?>
                    </div>
                    <?php if(!empty($vo['chat_img'])): ?><div  class="img-wrapper ">
                            <?php if(is_array($vo['chat_img'])): $k = 0; $__LIST__ = $vo['chat_img'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($k % 2 );++$k;?><a href="<?php echo ($v); ?>">
                                    <div class="img-item fileinput " style="background-image:none;">
                                        <span><img   src="<?php echo ($v); ?>"></span>
                                    </div>
                                </a><?php endforeach; endif; else: echo "" ;endif; ?>
                        </div>
                        <div class="clearfix"></div><?php endif; ?>
                </div><?php endforeach; endif; else: echo "" ;endif; ?>
        </div>
        <form id="form_chat" action=""  novalidate="novalidate" method="post" onSubmit="return false;" >
            <?php if($last_chat['type'] == 2 and $refund['code'] == 0): ?><div class="describe">
                <h4>与售后协商</h4>
                    <div class="field autoClear">
                        <textarea id="content" name="content" cols="" rows="" placeholder="请您在此描述详细问题" class="desarea"></textarea>
                    </div>
                    <input type="hidden" value="" name="content_encode" id="content_encode">
                    <h4>上传图片</h4>
                    <div  class="uploadfile upload_file_chat">
                        <div class="uploadbtnbox" style="display: inline-block">
                            <input id="up_img_chat" type="file" name="questionPic"  class="upload-btn upload_input">
                        </div>
                        <iframe name="uploadIfrm" style="display: none"></iframe>
                    </div>
                    <div class="clearfix"></div>
                    <div class="msg">最多上传4张，每张不超过5M，支持JPG、BMP、PNG</div>
                </div>
                <input type="hidden" class="fm-text al"  name="ref_id"   placeholder="" value="<?php echo ($refund["ref_id"]); ?>">
                <input type="hidden" class="fm-text al"  name="chat_id"   placeholder="" value="<?php echo ($last_chat["id"]); ?>"><?php endif; ?>
        </form>
            </div>

            
        <form id="form2" action=""   novalidate="novalidate" method="post" onSubmit="return false;">
            <?php if(!empty($refund['send_address'])): ?><div class="newbox bgwt">
                <div class="check-cnt">
                    <?php if(!empty($refund['send_address']) && $refund['code'] != 1): ?><h4>商品返回地址</h4>
                        <?php else: ?>
                        <h4>请按以下地址寄回</h4><?php endif; ?>

                    <div class="check-cnt-box" id="check-cnt-box2">
                        地址: <?php echo ($refund['send_address']); ?>
                    </div>
                    <div class="check-cnt-box" id="check-cnt-box1">
                        备注: <?php echo ($refund['shop_remark']); ?>
                    </div>
                    <!--//in_array($refund['code'],array(2,3,4,5,6))-->
                    <?php if($refund['code'] != 0 && $refund['code'] != 1 && !empty($refund['invoice_no_user'])): ?><div class="fm-form">
                                <h4 class="bggray green">我的退货信息</h4>
                                <div class="field">
                                    <span class="label">快递名称</span>
                                    <div class="field-control">
                                        <?php echo ($refund['invoice_no_name']); ?>
                                    </div>
                                </div>
                                <div class="field but_user">
                                    <span class="label">快递单号</span>
                                    <div class="field-control num">
                                        <?php echo ($refund['invoice_no_user']); ?>
                                    </div>
                                    
                                        <a class="btn1 btnsF34 floatRgt  express_down"  type="user" data-no="<?php echo ($refund['invoice_no_user']); ?>"  data-code="<?php echo ($refund['invoice_no_code']); ?>"  href="javascript:void(0);">查看物流</a>
                                        <a class="btn1 btnsF34  floatRgt express_up"  type="user" href="javascript:void(0);" style="display: none">收起物流</a>
                                   
                                </div>
                                
                            </div>

                        <?php else: ?>
                        <div class="always-show checked_dan">
                            <div class="fm-form">
                                <h4 class="bggray green">请输入您的退货信息</h4>
                                <div class="field autoClear">
                                    <span class="label">快递名称</span>
                                    <div class="field-control">
                                        <select name="express_code" id="express_code">
                                            <option value="0">情选择快递公司</option>
                                            <?php if(is_array($express)): $i = 0; $__LIST__ = $express;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["shipping_id"]); ?>"><?php echo ($vo["shipping_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="field autoClear">
                                    <span class="label">快递单号</span>
                                    <div class="field-control">
                                        <input type="text" class="fm-text al" id="invoice_no_user"  name="invoice_no_user"   placeholder="请输入你寄出快递的运单号码" value="">
                                    </div>
                                </div>
                            </div>
                        </div><?php endif; ?>
					</div></div>
                <div class="invoice_user" style="display: none;">
                </div><?php endif; ?>

            <?php if(in_array($refund['code'],array(4,5)) && $refund['refund_type'] == 0): ?><div class="fm-form">
                    <div class="newbox bgwt">
                        <h4>FG峰购补发货单</h4>
                        <div class="field">
                            <span class="label">快递名称</span>
                            <div class="field-control">
                                <?php echo ($refund['invoice_no_shop_name']); ?>
                            </div>
                        </div>
                        <div class="field but_shop">
                            <span class="label">快递单号</span>
                            <div class="field-control num">
                                <?php echo ($refund['invoice_no_shop']); ?>
                            </div>
                                <a class="btn1 btnsF34  floatRgt express_down"  type="shop" data-no="<?php echo ($refund['invoice_no_shop']); ?>"  data-code="<?php echo ($refund['invoice_no_shop_code']); ?>" href="javascript:void(0);" >查看物流</a>
                                <a class="btn1 btnsF34  floatRgt express_up"  type="shop" href="javascript:void(0);" style="display: none">收起物流</a>
                        </div>

                    </div>
                </div>
                <div class="invoice_shop " style="display: none">
                </div><?php endif; ?>
            <?php if($refund['code'] == 5 && $refund['refund_type'] == 1): ?><div class="check-cnt newbox bgwt">
                        <h4>FG峰购退款信息</h4>
                    <div class="check-cnt-box" >
                        积分退还: <?php echo ($refund['refund_points']); ?>积分
                    </div>
                    <div class="check-cnt-box" >
                        余额退还: <?php echo ($refund['refund_money']); ?>元
                    </div>
                    <div class="check-cnt-box" >
                        微信退还:  <?php echo ($refund['refund_wx']); ?>元
                    </div>
                </div><?php endif; ?>
            <input type="hidden" class="fm-text al"  name="ref_id"   placeholder="" value="<?php echo ($refund["ref_id"]); ?>">
            </div>
        </form>
        <div class="clearfix"></div>





                        

    <?php if($refund['code'] == 1): ?><div class="submit">
            <a href="javascript:void(0);" class="dispatch_btn">提交发货单</a>
        </div>
        <?php elseif($refund['code'] == 4): ?>
        <div class="submit">
            <a href="javascript:void(0);" class="delivery_btn">确认收货</a>
        </div>
        <?php elseif($last_chat['type'] == 2 && $refund['code'] == 0): ?>
        <div class="submit">
            <a href="javascript:void(0);" class="refund_chat_btn">提交</a>
        </div><?php endif; ?>

 <script type="text/javascript" src="__PUBLIC__/wap/js/jquery.min.js"></script>
<script src="__PUBLIC__/bootstrap/vendor/jquery-validation/jquery.validate.wap.js"></script>
<script src="__PUBLIC__/js/jquery.form.js"></script>
<script type="text/javascript" src="__PUBLIC__/layer/layer.js"></script>
 <script type="text/javascript" src="__PUBLIC__/js/common.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/return-top.js"></script>

<script src="__PUBLIC__/bootstrap/vendor/jquery-validation/jquery.validate.wap.js"></script>
<script src="__PUBLIC__/bootstrap/vendor/ajaxfileupload/ajaxfileupload.js"></script>

<script>
jQuery(document).ready(function() {

$("#form2").validate({
  rules: {
     invoice_no_name:'required',
    invoice_no_user: {
    required:true,
    number:true,
    rangelength: [10, 14],
   }
  },
  messages: {
    invoice_no_name:'<span class="icon-warning icon_warning"></span>&nbsp;请输入快递名称',
       invoice_no_user:{
        required:'<span class="icon-warning icon_warning"></span>&nbsp;请输入快递单号',
        number:'<span class="icon-warning icon_warning"></span>&nbsp;请输入正确的快递单号',
        rangelength:'<span class="icon-warning icon_warning"></span>&nbsp;请输入正确的快递单号'
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
                url :  "<?php echo U('Wap/goodsrefund/refund_status'); ?>", 
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
                        layer.msg('提交成功', {icon: 6,time:1500},function(){
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


$('.dispatch_btn').click(function() {
    $('#form2').submit();
});


$('.delivery_btn').click(function(){
      var options = { 
                url :  "<?php echo U('Wap/goodsrefund/refund_status1'); ?>", 
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
                        layer.msg('操作成功', {icon: 6,time:1500},function(){
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
});


    //文本框 去代码

    function cleanSpelChar(th){
        var this_=$(th);
        var value_=this_.val();
        if(/["'<>%;)(&+]/.test(value_)){
            //this_.val(value_.replace(/["'<>%;)(&+]/,""));
            this_.val(value_.replace(/[<>&"]/g,function(c)
            {
                return {'<':'&lt;','>':'&gt;','&':'&amp;','"':'&quot;'}[c];}
            ));
        }
    }


    $("#form_chat").validate({
        rules: {
            content:'required'
        },
        messages: {
            content:'<span class="icon-warning icon_warning"></span>&nbsp;请输入描述'
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
                url :  "<?php echo U('Wap/goodsrefund/refund_chat'); ?>",
                type : "post" ,
                dataType:'json',
                target : "#loader",
                error: function(){layer.msg("服务器没有返回数据，可能服务器忙，请重试",{icon:5});},
                onwait : "正在处理信息，请稍候...",
                success: function(response){
                    console.log(response);
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
                    $('#form_chat').ajaxSubmit(opt);
                }
            })(options), 300);
            return false;
        }

    });

    $('.refund_chat_btn').click(function(){
        $('#form_chat').submit();
    });



});


    $(document).ready(function (e) {
        //上传对话图片
        $('.upload_file_chat').on('change','.upload_input', function () {
            var goods_length=$(".chat_img").length;
            if(goods_length>=4){
                layer.msg('图片最多上传4张', {icon: 5});
            }else{
                ajaxFileUploadMore('chat',"<?php echo U('admin/Imagecat/upload_ajax');?>");
            }
        });

        $('.upload_file_chat').on('click','.delete_img', function () {
            var __thiss=$(this);
            layer.confirm('确定删除该图片？', {
                btn: ['确定','取消'] //按钮
            },function(){
                __thiss.remove();
                var goods_length=$(".chat_img").length;
                if(goods_length < 4){
                    $('.upload_file_chat').find('.uploadbtnbox').show();
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
                                    if(imgid == 'chat'){
                                        var str_img=' <div class="img-item fileinput delete_img"><span><img   src="'+data.imgurl
                                                +'"><input type="hidden" name="chat_img[]"  class="chat_img"  value="'
                                                +data.imgurl+'"></span></div>';
                                        $('.upload_file_chat').append(str_img);
                                        var goods_length=$(".chat_img").length;
                                        if(goods_length >= 4) {
                                            $('.upload_file_chat').find('.uploadbtnbox').hide();
                                        }
                                    }else if(imgid == 'url'){
                                        var str_img=' <div class="img-item fileinput delete_img"><span><img  class="user_send_img"   src="'
                                            +data.imgurl+'"></span></div>';
                                        $('.upload_file_refund').append(str_img);
                                        $('input[name="user_send_img"]').val(data.imgurl);
                                        var goods_length=$(".user_send_img").length;
                                        if(goods_length >= 1) {
                                            $('.upload_file_refund').find('.uploadbtnbox').hide();
                                        }
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

jQuery(document).ready(function() {

    function get_refund_express(invoice_no,invoice_code,type){
        $.ajax({
            url: "<?php echo U('wap/goodsrefund/refund_express');?>",
            type:'get',
            data: {
                invoice_no:invoice_no,
                invoice_code:invoice_code
            },
            dataType: 'json',
            success: function(data){
               console.log(data);
                var option = '';
                if(data.status == 1){
                   var  option = data.result;
                }else{
                   var  option = "<br/><div class='newbox2 pdb2 textCer'>"+data.msg+"</div>";
                }
                $('.invoice_'+type).html(option);
                $('.invoice_'+type).slideDown(1000,function () {
                    $('.but_'+type).find('.express_up').show();
                    $('.but_'+type).find('.express_down').hide();
                });
            }
        });
    }
    //展开
    $('.express_down').click(function(){
        var clic_ele=$(this);
        var invoice_no = clic_ele.attr('data-no');
        var invoice_code = clic_ele.attr('data-code');
        var type = clic_ele.attr('type');
        get_refund_express(invoice_no,invoice_code,type);
    });
    //收起
    $('.express_up').click(function () {
        var clic_ele=$(this);
        var type = clic_ele.attr('type');
        $('.invoice_'+type).slideUp(1000,function () {
            clic_ele.hide();
            clic_ele.prev().show();
        });
    });


});

        </script>
</body>
</html>