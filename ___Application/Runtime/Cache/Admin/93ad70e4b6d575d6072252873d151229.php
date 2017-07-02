<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<!-- Template Name: Clip-Two - Responsive Admin Template build with Twitter Bootstrap 3.x | Author: ClipTheme -->
<!--[if IE 8]><html class="ie8" lang="en"><![endif]-->
<!--[if IE 9]><html class="ie9" lang="en"><![endif]-->
<!--[if !IE]><!-->
<html lang="en">
    <!--<![endif]-->
    <!-- start: HEAD -->
    <head>
        <title><?php echo ($webseting["web_title"]); ?>-后台管理</title>
        <!-- start: META -->
        <!--[if IE]><meta http-equiv='X-UA-Compatible' content="IE=edge,IE=9,IE=8,chrome=1" /><![endif]-->
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black">
        <meta content="" name="description" />
        <meta content="" name="author" />
        <!-- end: META -->
        <!-- start: GOOGLE FONTS -->
        <!-- <link href="http://fonts.googleapis.com/css?family=Lato:300,400,400italic,600,700|Raleway:300,400,500,600,700|Crete+Round:400italic" rel="stylesheet" type="text/css" /> -->
        <!-- end: GOOGLE FONTS -->
        <!-- start: MAIN CSS -->
        <link rel="stylesheet" href="__PUBLIC__/bootstrap/vendor/bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="__PUBLIC__/bootstrap/vendor/fontawesome/css/font-awesome.min.css">
        <link rel="stylesheet" href="__PUBLIC__/bootstrap/vendor/themify-icons/themify-icons.min.css">
        <link href="__PUBLIC__/bootstrap/vendor/animate.css/animate.min.css" rel="stylesheet" media="screen">
        <link href="__PUBLIC__/bootstrap/vendor/perfect-scrollbar/perfect-scrollbar.min.css" rel="stylesheet" media="screen">
        <link href="__PUBLIC__/bootstrap/vendor/switchery/switchery.min.css" rel="stylesheet" media="screen">
        <!-- end: MAIN CSS -->
        <!-- start: CLIP-TWO CSS -->
        <link rel="stylesheet" href="__PUBLIC__/bootstrap/assets/css/styles.css">
        <link rel="stylesheet" href="__PUBLIC__/bootstrap/assets/css/plugins.css">
        <link rel="stylesheet" href="__PUBLIC__/bootstrap/assets/css/themes/theme-1.css" id="skin_color" />
        <!-- end: CLIP-TWO CSS -->
        <!-- start: CSS REQUIRED FOR THIS PAGE ONLY -->
        <!-- end: CSS REQUIRED FOR THIS PAGE ONLY -->
          <link rel="stylesheet" href="__PUBLIC__/bootstrap/vendor/artDialog/skins/default.css?4.1.7">
    </head>
    <!-- end: HEAD -->
    <body>
        
<link href="__PUBLIC__/bootstrap/vendor/Jcrop/jquery.Jcrop.min.css" rel="stylesheet" media="screen">
  

  <div class="container-fluid container-fullw">
                            <div class="row">
                                <div class="col-md-12">
                                 
                                    <img src="<?php echo ($_GET['img']); ?>" id="target2" alt="[Jcrop Example]"  />
                                    
                                       <div id="preview-pane">
                                        <div class="preview-container">
                                            <img src="<?php echo ($_GET['img']); ?>" class="jcrop-preview" alt="Preview" />
                                        </div>
                                       
                                         
                                          
                                        </div>

      
  
                                </div>
                            </div>
                        </div>
<div class="row" style="margin-left:550px;top:250px;position: absolute;">
    
   <button class="btn btn-primary" type="button" id="confirmOk">
          确定
  </button>

</div> 

<form id="coords" class="coords margin-top-20" onsubmit="return false;" action="http://example.com/post.php">
      
        <div class="inline-labels" style="display:none;">
             <input type="" id="pic_name" name="pic_name" value="<?php echo ($_GET['img']); ?>">
            <label>
                X1
                <input type="text" size="4" id="x1" name="x1" />
            </label>
            <label>
                Y1
                <input type="text" size="4" id="y1" name="y1" />
            </label>
            <label>
                X2
                <input type="text" size="4" id="x2" name="x2" />
            </label>
            <label>
                Y2
                <input type="text" size="4" id="y2" name="y2" />
            </label>
            <label>
                W
                <input type="text" size="4" id="w" name="w" />
            </label>
            <label>
                H
                <input type="text" size="4" id="h" name="h" />
            </label>
        </div>
        
    </form>

    
     
        <!-- start: MAIN JAVASCRIPTS -->

         <script src="__PUBLIC__/bootstrap/vendor/jquery/jquery.min.js"></script>
        <script src="__PUBLIC__/bootstrap/vendor/bootstrap/js/bootstrap.min.js"></script>
     
      
      
        <!-- <script src="__PUBLIC__/bootstrap/assets/js/main.js"></script>-->
      

     <script src="__PUBLIC__/bootstrap/vendor/Jcrop/jquery.Jcrop.min.js"></script>
        <script src="__PUBLIC__/bootstrap/vendor/Jcrop/jquery.color.js"></script>
        <!-- end: JAVASCRIPTS REQUIRED FOR THIS PAGE ONLY -->
        <!-- start: CLIP-TWO JAVASCRIPTS -->
       <!--  <script src="__PUBLIC__/bootstrap/assets/js/main.js"></script> -->
        <!-- start: JavaScript Event Handlers for this page -->
        <script src="__PUBLIC__/bootstrap/assets/js/form-image-cropping.js"></script>
        <script src="__PUBLIC__/bootstrap/vendor/switchery/switchery.min.js"></script>

<script src="__PUBLIC__/bootstrap/vendor/artDialog/artDialog.source.js?skin=default"></script>
<script src="__PUBLIC__/bootstrap/vendor/artDialog/plugins/iframeTools.source.js"></script>

<script src="__PUBLIC__/bootstrap/vendor/ajaxfileupload/ajaxfileupload.js"></script>
        <script>
            jQuery(document).ready(function() {
                //Main.init();
                ImageCropping.init();

                //表单提交
        $("#confirmOk").click(function(){           
            if (parseInt($('#w').val())){

                var pic_name = $("#pic_name").val();
                var x = $("#x1").val();
                var y = $("#y1").val();
                var w = $("#w").val();
                var h = $("#h").val();
                $.get("<?php echo U('admin/Imagecat/crop_submit');?>",{pic_name:pic_name,x:x,y:y,w:w,h:h},function(data){
                    if(data.status==1){
                        
                        var _call_back="show_head";
                        var win = art.dialog.open.origin; 
                        if(_call_back && win[_call_back] && typeof(win[_call_back])=='function'){ 
                            try{ 
                                win[_call_back].call(this, data.file); 
                                art.dialog.close();
                            }catch(e){
                                alert('确认异常');          
                            }; 
                        } 
                    
                    }else{
                    
                        alert(data.error);
                        return false;
                    }                   
                    
                },"json");
                return false;
            }           
            return false;
        });
            });
        </script>
        <!-- end: CLIP-TWO JAVASCRIPTS -->
    </body>
</html>