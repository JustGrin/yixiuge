<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<head xmlns="http://www.w3.org/1999/xhtml">
<head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title>订单</title>
	<meta http-equiv="MSThemeCompatible" content="Yes">
	<link rel="stylesheet" href="/Public/bootstrap/vendor/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="/Public/bootstrap/vendor/fontawesome/css/font-awesome.min.css">
	<link rel="stylesheet" href="/Public/bootstrap/assets/css/styles.css">
</head>
<body>
	<div class="container-fluid container-fullw bg-white" style="height:280px;">
<?php if($show_order['code'] == 'unconfirmed'): ?>
	<form action="" role="form" id="form3" novalidate="novalidate" method="post">
		<div class="form-group"  align="center">
			<label class="control-label">
				<h4>订单确认</h4>
			</label>
		</div>
		<div class="clip-radio radio-primary fa-hover Primary"  align="center">
			<input type="radio" value="1" name="order_status" id="id_yes" checked="checked">
			<label for="id_yes">
				确认订单（备货中）
			</label>
			<input type="radio" value="0" name="order_status" id="id_on" >
			<label for="id_on">
				无效订单
			</label>
		</div>
		<div style="padding-left: 90px">
			<input type="hidden"  value="<?php echo ($data["order_id"]); ?>" name="order_id">
			<button class="btn btn-primary btn-wide pull-left" type="button" onclick="setInvoicetwo(<?php echo ($data["order_id"]); ?>);">
				提交 <i class="fa fa-arrow-circle-right"></i>
			</button>
		</div>
	</form>
<?php elseif($show_order['code'] == 'nondelivery'): ?>
	
	<form action="" role="form" id="form3" novalidate="novalidate" method="post">
		<div class="form-group" align="center">
			<label class="control-label">
				<h4>订单发货</h4>
			</label>
		</div>
		<div class="form-group">
			<label class="control-label" style="display:inline-block; width:80px;" align="right">
				快递公司
			</label>
			<select name="express_code" id="express_code" style="width:230px;">
				<option value="0">情选择快递公司</option>
				<?php if(is_array($express)): $i = 0; $__LIST__ = $express;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["shipping_id"]); ?>"><?php echo ($vo["shipping_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
			</select>
		</div>
		<div class="form-group">
			<label class="control-label" style="display:inline-block; width:80px;" align="right">
				发货单号
			</label>
			<input type="text" placeholder="填写发货单号" class="form-control" value="" name="invoice_no" id="invoice_no" onblur="invoice();" style="width:230px; display: inline-block;">
		</div>
		<div style="padding-left: 90px">
			<input type="hidden"  value="1" name="shipping_status">
			<input type="hidden"  value="<?php echo ($data["order_id"]); ?>" name="order_id">
			<button class="btn btn-primary btn-wide pull-left" type="button" onclick="setInvoice(<?php echo ($data["order_id"]); ?>);">
				提交 <i class="fa fa-arrow-circle-right"></i>
			</button>
		</div>
	</form>
<?php elseif($show_order['code'] == 'delivered'): ?>
	
	<form action="" role="form" id="form3" novalidate="novalidate" method="post">
		<div class="form-group" align="center">
			<label class="control-label">
				<h4>修改发货单号</h4>
			</label>
		</div>
		<div class="form-group">
			<label class="control-label" style="display:inline-block; width:80px;" align="right">
				快递公司
			</label>
			<select name="express_code" id="express_code" style="width:230px;">
				<option value="0">情选择快递公司<?php echo ($shipping_info["shipping_id"]); ?></option>
				<?php if(is_array($express)): $i = 0; $__LIST__ = $express;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["shipping_id"]); ?>" <?php if($shipping_info['shipping_id'] == $vo['shipping_id']): ?>selected = selected<?php endif; ?>  > <?php echo ($vo["shipping_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
			</select>
		</div>
		<div class="form-group">
			<label class="control-label" style="display:inline-block; width:80px;" align="right">
				发货单号
			</label>
			<input type="text" placeholder="填写发货单号" class="form-control" value="<?php echo ($shipping_info["invoice_no"]); ?>" name="invoice_no" id="invoice_no" onblur="invoice();" style="width:230px; display: inline-block;">
		</div>
		<div style="padding-left: 90px">
			<input type="hidden"  value="2" name="shipping_status">
			<input type="hidden"  value="<?php echo ($data["order_id"]); ?>" name="order_id">
			<button class="btn btn-primary btn-wide pull-left" type="button" onclick="setInvoice(<?php echo ($data["order_id"]); ?>);">
				提交 <i class="fa fa-arrow-circle-right"></i>
			</button>
		</div>
	</form><?php endif; ?>
		</div>
<!-- start: JavaScript -->
<script src="__PUBLIC__/bootstrap/vendor/jquery/jquery.min.js"></script>
        <script src="__PUBLIC__/bootstrap/vendor/bootstrap/js/bootstrap.min.js"></script>
        <script src="__PUBLIC__/bootstrap/vendor/modernizr/modernizr.js"></script>
        <script src="__PUBLIC__/bootstrap/vendor/jquery-cookie/jquery.cookie.js"></script>
        <script src="__PUBLIC__/bootstrap/vendor/perfect-scrollbar/perfect-scrollbar.min.js"></script>
        <script src="__PUBLIC__/bootstrap/vendor/switchery/switchery.min.js"></script>
        <!-- end: MAIN JAVASCRIPTS -->
        <!-- start: CLIP-TWO JAVASCRIPTS -->
        <script src="__PUBLIC__/bootstrap/assets/js/main.js"></script>
        <script type="text/javascript" src="__PUBLIC__/layer/layer.js"></script>
        <!-- start: JavaScript Event Handlers for this page -->
        <script>
            jQuery(document).ready(function() {
                Main.init();
            });
        </script>
        <!--以下是新订单消息提醒-->
<script type="text/javascript">
    var newCount = '';   //付款新订单
    var refCount = '';   //退款新订单
	    var s_time=5*60*1000;///毫秒  //5分钟
    //付款订单提示
  jQuery(document).ready(function() {
     setTimeout("getNewOrderRemind()", 1000)  //触发新订单提醒
    setTimeout("getRefOrderRemind()", 1000)  //出发新退款订单提醒
});
      

    function getNewOrderRemind() {
        var status = 1;    //订单状态
        $.ajax({
            url: '<?php echo U("admin/Goodsorder/OrderRemind") ?>',
            data: 'status=' + status,
            type: 'post',
            dataType: 'json',
            success: function (re) {
                if (newCount) {
                    if (re > newCount) {    //产生新付款订单
                        $('#nsound').remove();
                        var content = '<audio src="__PUBLIC__/admin/sound/newOrder.wav" id="nsound" autoplay></audio>'
                        $('body').append(content);
                        setTimeout("getNewOrderRemind()", s_time);
                        newCount = re;
                    } else {
                        setTimeout("getNewOrderRemind()", s_time);
                    }
                }
                else {
                    newCount = re;
                    setTimeout("getNewOrderRemind()", s_time);
                }
            }
        })
    }
    //退货订单提示
    function getRefOrderRemind() {
        var status = 0;
        $.ajax({
            url: '<?php echo U("admin/Goodsorder/OrderRemind") ?>',
            data: 'status=' + status,
            type: 'post',
            dataType: 'json',
            success: function (re) {
                if (refCount) {
                    if (re > refCount) {    //产生新付款订单
                        $('#rsound').remove();
                        var content = '<audio src="__PUBLIC__/admin/sound/refOrder.wav" id="rsound" autoplay></audio>'
                        $('body').append(content);
                        setTimeout("getRefOrderRemind()", s_time);
                        refCount = re;
                    } else {
                        setTimeout("getRefOrderRemind()", s_time);
                    }
                }
                else {
                    refCount = re;
                    setTimeout("getRefOrderRemind()", s_time);
                }
            }
        })
    }
</script>
        <!-- end: JavaScript Event Handlers for this page -->
  
<script src="__PUBLIC__/bootstrap/vendor/jquery-validation/jquery.validate.wap.js"></script>
<script src="__PUBLIC__/js/jquery.form.js"></script>
<script type="text/javascript" src="__PUBLIC__/layer/layer.js"></script>  <!-- 小窗口js -->
<script src="__PUBLIC__/bootstrap/vendor/jquery/jquery.min.js"></script>

<script src="__PUBLIC__/bootstrap/vendor/jquery-validation/jquery.validate.min.js"></script>
<script src="__PUBLIC__/bootstrap/vendor/artDialog/artDialog.source.js?skin=default"></script>  <!-- 窗口js -->
<script src="__PUBLIC__/bootstrap/vendor/artDialog/plugins/iframeTools.source.js"></script>  <!-- 窗口js -->

<!-- 通过ajax返回值来删除窗口	zm 2015.10.15 -->
<script type="text/javascript">
	function invoice(){
		var val=document.getElementById("invoice_no").value;		
		if(val==""){
			$("span").empty();
			$("#invoice_no").after("<span style='color:red'>&nbsp;&nbsp;发货单号不能为空</span>");
		}else{
			$("span").hide();
		}
	}

	
	//验证发货单号
	function setInvoice(id){
		var origin = art.dialog.open.origin;
		var val=document.getElementById("invoice_no").value;
		var express=$("#express_code").val();
		$("span").empty();

		if(express=="0"){
			$("#invoice_no").after("<span style='color:red'>&nbsp;&nbsp;请选择一家快递公司</span>");
			return false;
		}
		if(val==""){
				$("#invoice_no").after("<span style='color:red'>&nbsp;发货单号不能为空</span>");
		}else{
			var status=$("input[name='order_status']:checked").val();
			var status=$("[name='shipping_status']").val();
			var invoice_no=$("#invoice_no").val()
			var express_id=$("#express_code").val();
			$.ajax({
				url:"<?php echo U('Admin/goodsorder/order_exchange'); ?>",
				type:"post",
				data:"shipping_status="+status+"&order_id="+id+"&invoice_no="+invoice_no+"&express_id="+express_id,
				dataType:"json",
				success:function(data){
					// console.log(data);
					if(data.status==1){
						layer.msg("配送成功。", {icon: 6,time:1500}, function(){
							var origin = art.dialog.open.origin;
							// var nondelivery = origin.document.getElementById('delete_'+id);
							var name = origin.document.getElementById("name_"+id);
							name.innerHTML = "待收货";				
							//nondelivery.parentNode.removeChild(nondelivery);  //删除由id获取的这个对象
							
							var nondelivery = origin.document.getElementById('nondelivery_'+id);
							if(nondelivery){
								$(nondelivery).attr("data-original-title","修改运单");
								nondelivery.innerHTML='<i class="fa fa-magic"></i>';
							}
							
							art.dialog.close();  
							// top.art.dialog({id : id}).close();  //通过id删除窗口							
						});
					}else{
						layer.msg(data.error, {icon: 5,time:2000}, function(){
							var win = art.dialog.open.origin;  
							win.location.reload();  
							top.art.dialog({id : id}).close();  //通过id删除窗口
							// art.dialog.close();       //直接删除窗口
						});
					}
				}
			})
		}
	}
	
	//确认订单 
	function setInvoicetwo(id){
		var status=$("input[name='order_status']:checked").val();
		// var status2=$("[name='shipping_status']").val();
		//alert(status);
		$.ajax({
			url:"<?php echo U('Admin/goodsorder/order_exchange'); ?>",
			type:"post",
			data:"order_status="+status+"&order_id="+id,
			dataType:"json",
			success:function(data){
				if(data.status==1){
					if(status==0){
						layer.msg("取消成功", {icon: 1,time:1500}, function(){
							window.location.href = "<?php echo U('Admin/goodsorder/index');?>"
							var origin = art.dialog.open.origin;  
							var unconfirmed = origin.document.getElementById('unconfirmed_'+id);
							var name = origin.document.getElementById("name_"+id);
							unconfirmed.parentNode.removeChild(unconfirmed);
							name.innerHTML = "已取消";
							// top.art.dialog({id : id}).close();  //通过id删除窗口
							art.dialog.close();  
						});
					}else{
						layer.msg("确定成功。", {icon: 6,time:1500}, function(){
							// window.location.href = "<?php echo U('Admin/goodsorder/index');?>"
							var origin = art.dialog.open.origin;  
							var unconfirmed = origin.document.getElementById('unconfirmed_'+id);	
							var name = origin.document.getElementById("name_"+id);
							$(unconfirmed).attr("data-original-title","配送");
							unconfirmed.innerHTML='<i class="fa fa-truck"></i>';
							name.innerHTML = "备货中";			
							// origin.location.reload();  //刷新父页面
							// top.art.dialog({id : id}).close();  //通过id删除窗口
							art.dialog.close();  
						});
					}
				}else{
					layer.msg(data.error, {icon: 5,time:1500}, function(){
						window.location.href = "<?php echo U('Admin/goodsorder/index');?>"
						var win = art.dialog.open.origin;  
						win.location.reload();  
						top.art.dialog({id : id}).close();  //通过id删除窗口
						// art.dialog.close();       //直接删除窗口
					});
				}
			}
		});
	}
</script>

</body>
</html>