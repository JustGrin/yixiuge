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
        

<div id="app">
    <!-- sidebar -->
     <!-- start: left -->            
<div class="sidebar app-aside" id="sidebar">
        <div class="sidebar-container perfect-scrollbar">
            <nav>
                <!-- start: SEARCH FORM -->
               <!--  <div class="search-form">
                   <a class="s-open" href="#">
                       <i class="ti-search"></i>
                   </a>
                   <form class="navbar-form" role="search">
                       <a class="s-remove" href="#" target=".navbar-form">
                           <i class="ti-close"></i>
                       </a>
                       <div class="form-group">
                           <input type="text" class="form-control" placeholder="Search...">
                           <button class="btn search-button" type="submit">
                               <i class="ti-search"></i>
                           </button>
                       </div>
                   </form>
               </div> -->
                <!-- end: SEARCH FORM -->
                <!-- start: MAIN NAVIGATION MENU -->
                <div class="navbar-title">
                    <span>主导航</span>
                </div>
                <ul class="main-navigation-menu">
                    <li>
                        <a href="<?php echo U("Admin/index/index");?>">
                            <div class="item-content">
                                <div class="item-media">
                                    <i class="ti-home"></i>
                                </div>
                                <div class="item-inner">
                                    <span class="title"> 首页 </span>
                                </div>
                            </div>
                        </a>
                    </li>

             <?php if(is_array($menu)): $i = 0; $__LIST__ = $menu;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li <?php if($_GET["mu_pid"] == $vo['id']): ?>class="active open"<?php endif; ?>>
                         <?php if((empty($vo['tree']))): ?><a href="javascript:void(0)">
                            <div class="item-content">
                                <div class="item-media">
                                    <i class="ti-layout-grid2"></i>
                                </div>
                                <div class="item-inner">
                                    <span class="title"> <?php echo ($vo["title"]); ?> </span>
                                </div>
                            </div>
                        <?php else: ?>

                             <a href="javascript:void(0)">
                                <div class="item-content">
                                <div class="item-media">
                                    <i class="<?php echo ($vo["icon_style"]); ?>"></i>
                                </div>
                                <div class="item-inner">
                                    <span class="title"> <?php echo ($vo["title"]); ?> </span>
                                 <i class="icon-arrow"></i>
                                </div>
                            </div><?php endif; ?>
                            
                        </a>
                        <?php if((!empty($vo['tree']))): ?><ul class="sub-menu">
                             <?php if(is_array($vo['tree'])): $i = 0; $__LIST__ = $vo['tree'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$voo): $mod = ($i % 2 );++$i;?><li <?php if($_GET["mu_id"] == $voo['id']): ?>class="active"<?php endif; ?> >
                                <a href="<?php echo U($voo['name'],array('mu_pid'=>$vo['id'],'mu_id'=>$voo['id']));?>">
                                    <span class="title"> <?php echo ($voo["title"]); ?> </span>
                                </a>
                            </li><?php endforeach; endif; else: echo "" ;endif; ?>
                            
                        </ul><?php endif; ?>
                    </li><?php endforeach; endif; else: echo "" ;endif; ?>

                   
                  
                </ul>
                <!-- end: MAIN NAVIGATION MENU -->
                <!-- start: CORE FEATURES -->
                
                
                <!-- end: CORE FEATURES -->
                <!-- start: DOCUMENTATION BUTTON -->
               <!--  <div class="wrapper">
                   <a href="documentation.html" class="button-o">
                       <i class="ti-help"></i>
                       <span>Documentation</span>
                   </a>
               </div> -->
                <!-- end: DOCUMENTATION BUTTON -->
            </nav>
        </div>
    </div>
 <!-- end: left -->            
    <!-- / sidebar -->
    <div class="app-content">
        <!-- start: TOP NAVBAR -->
        <!-- start: navheader 用户头部 -->
<header class="navbar navbar-default navbar-static-top">
                    <!-- start: NAVBAR HEADER -->
                    <div class="navbar-header">
                        <a href="#" class="sidebar-mobile-toggler pull-left hidden-md hidden-lg" class="btn btn-navbar sidebar-toggle" data-toggle-class="app-slide-off" data-toggle-target="#app" data-toggle-click-outside="#sidebar">
                            <i class="ti-align-justify"></i>
                        </a>
                        <a class="navbar-brand" href="#">
                            <img src="__PUBLIC__/bootstrap/assets/images/logo.png" alt="Clip-Two"/>
                        </a>
                        <a href="#" class="sidebar-toggler pull-right visible-md visible-lg" data-toggle-class="app-sidebar-closed" data-toggle-target="#app">
                            <i class="ti-align-justify"></i>
                        </a>
                        <a class="pull-right menu-toggler visible-xs-block" id="menu-toggler" data-toggle="collapse" href=".navbar-collapse">
                            <span class="sr-only">Toggle navigation</span>
                            <i class="ti-view-grid"></i>
                        </a>
                    </div>
                    <!-- end: NAVBAR HEADER -->
                    <!-- start: NAVBAR COLLAPSE -->
                    <div class="navbar-collapse  collapse ">
                        <ul class="nav navbar-right margin-right-0">
                            <!-- start: MESSAGES DROPDOWN -->
                            <!-- <li class="dropdown">
                                <a href class="dropdown-toggle" data-toggle="dropdown">
                                    <span class="dot-badge partition-red"></span>
                                     <i class="ti-comment"></i> <span>最新消息</span>
                                </a>
                                <ul class="dropdown-menu dropdown-light dropdown-messages dropdown-large">
                                    <li>
                                        <span class="dropdown-header"> 最新消息</span>
                                    </li>
                                    <li>
                                        <div class="drop-down-wrapper ps-container">
                                            <ul>
                                                <li class="unread">
                                                    <a href="javascript:;" class="unread">
                                                        <div class="clearfix">
                                                            <div class="thread-image">
                                                                <img src="__PUBLIC__/Public/bootstrap/assets/images/avatar-2.jpg" alt="">
                                                            </div>
                                                            <div class="thread-content">
                                                                <span class="author">Nicole Bell</span>
                                                                <span class="preview">Duis mollis, est non commodo luctus, nisi erat porttitor ligula...</span>
                                                                <span class="time"> Just Now</span>
                                                            </div>
                                                        </div>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="javascript:;" class="unread">
                                                        <div class="clearfix">
                                                            <div class="thread-image">
                                                                <img src="__PUBLIC__/bootstrap/assets/images/avatar-3.jpg" alt="">
                                                            </div>
                                                            <div class="thread-content">
                                                                <span class="author">Steven Thompson</span>
                                                                <span class="preview">Duis mollis, est non commodo luctus, nisi erat porttitor ligula...</span>
                                                                <span class="time">8 hrs</span>
                                                            </div>
                                                        </div>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="javascript:;">
                                                        <div class="clearfix">
                                                            <div class="thread-image">
                                                                <img src="__PUBLIC__/bootstrap/assets/images/avatar-5.jpg" alt="">
                                                            </div>
                                                            <div class="thread-content">
                                                                <span class="author">Kenneth Ross</span>
                                                                <span class="preview">Duis mollis, est non commodo luctus, nisi erat porttitor ligula...</span>
                                                                <span class="time">14 hrs</span>
                                                            </div>
                                                        </div>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                    <li class="view-all">
                                        <a href="#">
                                            See All
                                        </a>
                                    </li>
                                </ul>
                            </li> -->
                            <!-- end: MESSAGES DROPDOWN -->
                            
                            <!-- start: USER OPTIONS DROPDOWN -->
                            <li class="dropdown current-user">
                                <a href class="dropdown-toggle" data-toggle="dropdown">
                                    <!-- <img src="__PUBLIC__/bootstrap/assets/images/avatar-1.jpg" alt="<?php echo ($_SESSION["name"]); ?> "> --> <span class="username"><?php echo ($_SESSION["name"]); ?> <i class="ti-angle-down"></i></i></span>
                                </a>
                                <ul class="dropdown-menu dropdown-dark">
                                    <li>
                                        <a href="<?php echo U("Admin/Index/edit_pwd");?>">
                                            我的信息
                                        </a>
                                    </li>
                                   <!--  <li>
                                       <a href="pages_calendar.html">
                                           My Calendar
                                       </a>
                                   </li> -->
                                    <!-- <li>
                                        <a hef="pages_messages.html">
                                            My Messages (3)
                                        </a>
                                    </li> -->
                                    <li>
                                        <a href="<?php echo U("Admin/index/login_lock");?>?username=<?php echo ($_SESSION['account']); ?>&name=<?php echo ($_SESSION['name']); ?>&jump_url=<?php echo ($_SERVER['REQUEST_URI']); ?>">
                                            锁定屏幕
                                        </a>
                                    </li>
                                    <li>
                                        <a href="<?php echo U("Admin/index/loginout");?>">
                                            退出登录
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <!-- end: USER OPTIONS DROPDOWN -->
                        </ul>
                        <!-- start: MENU TOGGLER FOR MOBILE DEVICES -->
                        <div class="close-handle visible-xs-block menu-toggler" data-toggle="collapse" href=".navbar-collapse">
                            <div class="arrow-left"></div>
                            <div class="arrow-right"></div>
                        </div>
                        <!-- end: MENU TOGGLER FOR MOBILE DEVICES -->
                    </div>
                    
                    <!-- end: NAVBAR COLLAPSE -->
                </header>
        <!-- end: navheader 用户头部 -->
  
        <!-- end: TOP NAVBAR -->
        <div class="main-content">
            <div class="wrap-content container" id="container">
                <!-- start: PAGE TITLE -->
                <section id="page-title">
                    <div class="row">
                        <div class="col-sm-8">
                            <h1 class="mainTitle"><?php echo ($msgtitle); ?></h1>
                            <span class="mainDescription">商品信息</span>
                        </div>
                    </div>
                </section>
                <!-- end: PAGE TITLE -->
                <!-- start: YOUR CONTENT HERE -->
                <div class="container-fluid container-fullw bg-white">
                    <form action="" role="form" id="form2" novalidate="novalidate" method="post">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="tabbable">
                                    <ul class="nav nav-tabs tab-padding tab-space-3 tab-blue" id="myTab4">
                                        <li class="active">
                                            <a data-toggle="tab" href="#panel_overview">
                                                商品基本信息
                                            </a>
                                        </li>

                                        <li>
                                            <a data-toggle="tab" href="#shop_img">
                                                商品图片
                                            </a>
                                        </li>
                                        <li>
                                            <a data-toggle="tab" href="#postage_set" id="postage">
                                                邮费设置
                                            </a>
                                        </li>
                                    </ul>
                                    <div class="tab-content">
                                        <div id="panel_overview" class="tab-pane fade  in active">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <div class="form-group">
                                                        <label class="control-label">
                                                            商品分类 <span class="symbol required"
                                                                       aria-required="true"></span>
                                                        </label>
                                                        <select class="form-control" id="cat_id" name="cat_id">
                                                            <option value="">请选择</option>
                                                            <?php if(is_array($category)): $i = 0; $__LIST__ = $category;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["cat_id"]); ?>"
                                                                <?php if($data["cat_id"] == $vo['cat_id']): ?>selected<?php endif; ?>
                                                                ><?php echo ($vo["cat_name"]); ?></option>
                                                                <?php if(is_array($vo['tree'])): $i = 0; $__LIST__ = $vo['tree'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$voo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($voo["cat_id"]); ?>"
                                                                    <?php if($data["cat_id"] == $voo['cat_id']): ?>selected<?php endif; ?>
                                                                    >┗━<?php echo ($voo["cat_name"]); ?></option>
                                                                    <?php if(is_array($voo['tree'])): $i = 0; $__LIST__ = $voo['tree'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vot): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vot["cat_id"]); ?>"
                                                                        <?php if($data["cat_id"] == $vot['cat_id']): ?>selected<?php endif; ?>
                                                                        >&nbsp;&nbsp;&nbsp;&nbsp;┗━<?php echo ($vot["cat_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; endforeach; endif; else: echo "" ;endif; endforeach; endif; else: echo "" ;endif; ?>
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="control-label">
                                                            商品名称 <span class="symbol required"
                                                                       aria-required="true"></span>
                                                        </label>
                                                        <input type="text" name="goods_name" value="<?php echo ($data['goods_id'] ? $data['goods_name'] : ''); ?>"
                                                               id="goods_name" placeholder="商品名称" class="form-control">
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="control-label">
                                                            子标题 <span class="symbol required"
                                                                       aria-required="true"></span>
                                                        </label>
                                                        <input type="text" name="subtitle" value="<?php echo ($data['goods_id'] ? $data['subtitle'] : ''); ?>"
                                                               id=" subtitle" placeholder="子标题" class="form-control">
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="control-label">
                                                            情怀 <span class="symbol required"
                                                                      aria-required="true"></span>
                                                        </label>
                                                        <input type="text" name="feeling" value="<?php echo ($data['goods_id'] ? $data['feeling'] : ''); ?>"
                                                               id=" feeling" placeholder="情怀" class="form-control">
                                                    </div>
                                                    <div class="form-group">
                                                        <?php if((!empty($_GET['id']))): ?><label class="control-label">
                                                                商品货号<span class="symbol required"
                                                                          aria-required="true"></span>
                                                            </label>
                                                            <input type="text" disabled name="goods_sn"
                                                                   value="<?php echo ($data['goods_id'] ? $data['goods_sn'] : ''); ?>" id="goods_sn"
                                                                   placeholder="商品货号" class="form-control"/><?php endif; ?>

                                                    </div>
                                                    <div class="form-group">
                                                        <label class="control-label">
                                                            品牌
                                                        </label>
                                                        <select class="form-control" id="brand_id" name="brand_id">
                                                            <option value="">请选择</option>
                                                            <?php if(is_array($brand)): $i = 0; $__LIST__ = $brand;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["brand_id"]); ?>"
                                                                <?php if($data["brand_id"] == $vo['brand_id']): ?>selected<?php endif; ?>
                                                                ><?php echo ($vo["brand_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                                                        </select>
                                                    </div>

<!--                                                   <div class="form-group">
                                                        <label class="control-label">
                                                            包邮方式
                                                        </label>
                                                        <select class="form-control" id="shipping_id"
                                                                name="shipping_id">
                                                            <option value="0">顺丰快递</option>
                                                            <?php if(is_array($shipping)): $i = 0; $__LIST__ = $shipping;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["shipping_id"]); ?>"
                                                                <?php if($vo['shipping_id'] == $data['shipping_id']): ?>selected<?php endif; ?>
                                                                ><?php echo ($vo["shipping_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                                                        </select>
                                                       <input type="text"
                                                              value="包邮"
                                                              class="form-control" readonly>
                                                    </div>-->




                                                    <div class="form-group">
                                                        <label class="control-label">
                                                            商品库存<span class="symbol required"
                                                                      aria-required="true"></span>
                                                        </label>
                                                            <input type="text" name="goods_number" value="<?php echo ($data['goods_id'] ? $data['goods_number'] : 0); ?>"
                                                            id="goods_number" placeholder="商品库存" class="form-control">
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="control-label">
                                                            商品报警数量
                                                        </label>
                                                        <input type="text" name="warn_number"
                                                               value="<?php echo ($data['goods_id'] ? $data['warn_number'] : 0); ?>" id="warn_number"
                                                        placeholder="商品报警数量" class="form-control">
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="control-label">
                                                            商品重量
                                                        </label>

                                                        <div class="input-group">
                                                            <input type="text" name="goods_weight"
                                                                   value="<?php echo ($data['goods_id'] ? $data['goods_weight'] : 0); ?>"
                                                            id="goods_weight" placeholder="商品重量" class="form-control">
                                                            <span class="input-group-addon">kg</span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="control-label">
                                                            商品单价
                                                        </label>
                                                        <input type="text" name="unit_price"
                                                               value="<?php echo ($data['goods_id'] ? $data['unit_price'] : 0); ?>" id="unit_price"
                                                               placeholder="单位价格" class="form-control">
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="control-label">
                                                            商品单位
                                                        </label>
                                                        <input type="text" name="units"
                                                               value="<?php echo ($data['goods_id'] ? $data['units'] : ''); ?>" id="units"
                                                               placeholder="单位价格" class="form-control">
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="control-label">
                                                            供货价<span class="symbol required" aria-required="true"></span>
                                                        </label>

                                                        <div class="input-group">
                                                            <span class="input-group-addon">￥</span>
                                                            <input type="text" name="purchase_price"
                                                                   value="<?php echo ($data['goods_id'] ? $data['purchase_price'] : ''); ?>"
                                                            id="purchase_price" placeholder="供货价" class="form-control">
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="control-label">
                                                            本店售价<span class="symbol required"
                                                                      aria-required="true"></span>
                                                        </label>

                                                        <div class="input-group">
                                                            <span class="input-group-addon">￥</span>
                                                            <input type="text" name="shop_price"
                                                                   value="<?php echo ($data['goods_id'] ? $data['shop_price'] : ''); ?>" id="shop_price"
                                                                   placeholder="本店售价" class="form-control">
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="control-label">
                                                            市价<span class="symbol required" aria-required="true"></span>
                                                        </label>

                                                        <div class="input-group">
                                                            <span class="input-group-addon">￥</span>
                                                            <input type="text" name="market_price"
                                                                   value="<?php echo ($data['goods_id'] ? $data['market_price'] : ''); ?>" id="market_price"
                                                                   placeholder="市价" class="form-control">
                                                        </div>
                                                    </div>


                                                    <div class="form-group" >
                                                        <label class="control-label" >
                                                            购买送积分<span class="symbol required"
                                                                       aria-required="true"></span>
                                                        </label>

                                                        <div class="input-group">
                                                            <span class="input-group-addon">积分</span>
                                                            <input type="text" name="gift_integral" value="<?php echo ($data['goods_id'] ? $data['gift_integral'] : 0); ?>" id="gift_integral"  placeholder="购买送积分" class="form-control">
                                                        </div>

                                                    </div>

                                                    <div class="form-group">
                                                        <label class="control-label">
                                                            商品图片<span class="symbol required"
                                                                      aria-required="true"></span>(300px*300px,图片大小不大于1M)
                                                        </label>
                                                        <br>

                                                        <div class="upload_file">
                                                            <label>
                                                                <input type="file" name="head_photo"
                                                                       class="upload_input" id="head_photo" value=""
                                                                       style="display:none;">

                                                                <div class="fileinput fileinput-new"
                                                                     data-provides="fileinput">
                                                                    <div class="fileinput-new thumbnail"
                                                                         id="show_photo">
                                                                        <img id="head_photo_src"
                                                                             src="<?php echo ($data['goods_id'] ? $data['goods_img'] : ''); ?>"
                                                                             alt="">
                                                                    </div>

                                                                </div>
                                                                点击图片上传
                                                            </label>
                                                            <input type="hidden" name="goods_img" id="photo_pic"
                                                                   value="<?php echo ($data['goods_id'] ? $data['goods_img'] : ''); ?>">
                                                        </div>

                                                    </div>
                                                    <div class="form-group">
                                                        <label class="control-label">
                                                            商品首页展示图<span class="symbol required"
                                                                      aria-required="true"></span>(245px*160px,图片大小不大于30kb)
                                                        </label>
                                                        <br>

                                                        <div class="upload_file">
                                                            <label>
                                                                <input type="file" name="long_photo"
                                                                       class="upload_input" id="long_photo" value=""
                                                                       style="display:none;">

                                                                <div class="fileinput fileinput-new"
                                                                     data-provides="fileinput">
                                                                    <div class="fileinput-new thumbnail">
                                                                        <img id="long_photo_src"
                                                                             src="<?php echo ($data['goods_id'] ? $data['long_goods_img'] : ''); ?>"
                                                                             alt="">
                                                                    </div>

                                                                </div>
                                                                点击图片上传
                                                            </label>
                                                            <input type="hidden" name="long_goods_img" id="long_goods_pic"
                                                                   value="<?php echo ($data['goods_id'] ? $data['long_goods_img'] : ''); ?>">
                                                        </div>

                                                    </div>
                                                    <div class="form-group">
                                                        <label class="control-label">
                                                            商品关键字
                                                        </label>
                                                        <textarea id="keywords" name="keywords" class=" autosize"
                                                                  data-autosize-on="true"
                                                                  style="overflow: hidden; resize: horizontal; word-wrap: break-word;  width: 100%;  height: 100px;"><?php echo ($data['goods_id'] ? $data['keywords'] : ''); ?></textarea>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="control-label">
                                                            商品的简短描述<span class="symbol required"
                                                                         aria-required="true"></span>
                                                        </label>
                                                        <textarea id="goods_brief" name="goods_brief" class=" autosize"
                                                                  data-autosize-on="true"
                                                                  style="overflow: hidden; resize: horizontal; word-wrap: break-word;  width: 100%;  height: 100px;"><?php echo ($data['goods_id'] ? $data['goods_brief'] : ''); ?></textarea>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="control-label">
                                                            商品的详细描述<span class="symbol required"
                                                                         aria-required="true"></span>
                                                        </label>
                                                        <textarea id="goods_desc" name="goods_desc" class=" autosize"
                                                                  data-autosize-on="true"
                                                                  style="overflow: hidden; resize: horizontal; word-wrap: break-word;  width: 100%;  height: 300px;"><?php echo ($data['goods_id'] ? $data['goods_desc'] : ''); ?></textarea>
                                                        <input type="hidden" name="msg" id="msg" value="">
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="control-label">
                                                            状态
                                                        </label>

                                                        <div class="clip-radio radio-primary fa-hover Primary">
                                                            <?php if($data["goods_id"] > 0): ?><input type="radio" value="1" name="is_on_sale"
                                                                       id="id_no"
                                                                <?php if($data["is_on_sale"] == 1): ?>checked="checked"<?php endif; ?>
                                                                >
                                                                <label for="id_no">
                                                                    上架
                                                                </label>


                                                                <input type="radio" value="0" name="is_on_sale"
                                                                       id="id_yes"
                                                                <?php if($data["is_on_sale"] == 0): ?>checked="checked"<?php endif; ?>
                                                                >
                                                                <label for="id_yes">
                                                                    下架
                                                                </label>
                                                                <?php else: ?>
                                                                <input type="radio" value="1" name="is_on_sale"
                                                                       id="id_no" checked="checked">
                                                                <label for="id_no">
                                                                    上架
                                                                </label>

                                                                <input type="radio" value="0" name="is_on_sale"
                                                                       id="id_yes">
                                                                <label for="id_yes">
                                                                    下架
                                                                </label><?php endif; ?>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="control-label">
                                                            是否在首页显示
                                                        </label>

                                                        <div class="clip-radio radio-primary fa-hover Primary">
                                                            <input type="radio" value="1" name="is_show_index"
                                                                   id="is_show_index_yes"
                                                            <?php if($data['goods_id'] && $data["is_show_index"] == 1): ?>checked<?php endif; ?>
                                                            />
                                                            <label for="is_show_index_yes">
                                                                是
                                                            </label>
                                                            <input type="radio" value="0" name="is_show_index"
                                                                   id="is_show_index_no"
                                                            <?php if($data['goods_id'] && $data["is_show_index"] == 0): ?>checked<?php endif; ?>
                                                            />
                                                            <label for="is_show_index_no">
                                                                否
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="control-label">
                                                            是否支持货到付款
                                                        </label>

                                                        <div class="clip-radio radio-primary fa-hover Primary">
                                                            <input type="radio" value="1" name="offline"
                                                                   id="offline_yes" <?php echo ($data['goods_id'] ? $data['offline']?'checked':'' : ''); ?>
                                                            />
                                                            <label for="offline_yes">
                                                                是
                                                            </label>
                                                            <input type="radio" value="0" name="offline" id="offline_no"
                                                                   <?php echo ($data['goods_id'] ? $data['offline']?'':'checked' : ''); ?>
                                                            />
                                                            <label for="offline_no">
                                                                否
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <?php  if($_SESSION['topadmin'] ==1){ ?>
                                                    <div class="form-group">
                                                        <label class="control-label">
                                                            审核状态
                                                        </label>

                                                        <div class="clip-radio radio-primary fa-hover Primary">
                                                            <?php if($data["goods_id"] > 0): ?><input type="radio" value="1" name="is_auditing"
                                                                       id="id_nois_auditing"
                                                                <?php if($data["is_auditing"] == 1): ?>checked="checked"<?php endif; ?>
                                                                >
                                                                <label for="id_nois_auditing">
                                                                    已审核
                                                                </label>


                                                                <input type="radio" value="0" name="is_auditing"
                                                                       id="id_yesis_auditing"
                                                                <?php if($data["is_auditing"] == 0): ?>checked="checked"<?php endif; ?>
                                                                >
                                                                <label for="id_yesis_auditing">
                                                                    未审核
                                                                </label>
                                                                <?php else: ?>
                                                                <input type="radio" value="1" name="is_auditing"
                                                                       id="id_nois_auditing" checked="checked">
                                                                <label for="id_nois_auditing">
                                                                    已审核
                                                                </label>

                                                                <input type="radio" value="0" name="is_auditing"
                                                                       id="id_yesis_auditing">
                                                                <label for="id_yesis_auditing">
                                                                    未审核
                                                                </label><?php endif; ?>
                                                        </div>
                                                    </div>
                                                    <?php  }else{ ?>
                                                    <input type="hidden" value="0" name="is_auditing" id="is_auditing">
                                                    <?php  } ?>


                                                    <div class="form-group">
                                                        <!--    <div class="checkbox clip-check check-primary">
                                                          <input <?php if($data['is_best'] == 1): ?>checked=""<?php endif; ?> type="checkbox" value="1" name="is_best" id="is_best" >
                                                           <label for="is_best" class="control-label">
                                                               精品
                                                           </label>
                                                          </div>
                                                          <div class="checkbox clip-check check-primary">
                                                          <input <?php if($data['is_new'] == 1): ?>checked=""<?php endif; ?> type="checkbox" value="1" name="is_new" id="is_new" >
                                                           <label for="is_new" class="control-label">
                                                               新品
                                                           </label>
                                                          </div>
                                                          <div class="checkbox clip-check check-primary">
                                                          <input <?php if($data['is_hot'] == 1): ?>checked=""<?php endif; ?> type="checkbox" value="1" name="is_hot" id="is_hot" >
                                                           <label for="is_hot" class="control-label">
                                                               热销
                                                           </label>
                                                          </div> -->
<!--                                                        <div class="checkbox clip-check check-primary">
                                                            <?php if($data["goods_id"] > 0): ?><input
                                                                <?php if($data['is_refund'] == 1): ?>checked=""<?php endif; ?>
                                                                type="checkbox" value="1" name="is_refund"
                                                                id="is_refund" >
                                                                <?php else: ?>
                                                                <input checked="checked" type="checkbox" value="1"
                                                                       name="is_refund" id="is_refund"><?php endif; ?>
                                                            <label for="is_refund" class="control-label">
                                                                是否支持退货售后服务
                                                            </label>
                                                        </div>-->
                                                        <!--
                                                          <div class="checkbox clip-check check-primary">
                                                          <input <?php if($data['is_exchange'] == 1): ?>checked=""<?php endif; ?> type="checkbox" value="1" name="is_exchange" id="is_exchange" >
                                                              <label for="is_exchange" class="control-label">
                                                                  换货返修(售后服务换货/返修)
                                                              </label>
                                                          </div>
                                                        -->
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="control-label">
                                                            商品排序<span class="symbol required"
                                                                      aria-required="true"></span>
                                                        </label>
                                                        <input type="text" name="sort_order"
                                                               value="<?php echo ($data['goods_id'] ? $data['sort_order'] : 0); ?>"
                                                               id="sort_order" placeholder="商品排序"
                                                               class="form-control">
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="control-label">
                                                            商品的商家备注，仅商家可见
                                                        </label>
                                                        <textarea id="seller_note" name="seller_note" class=" autosize"
                                                                  data-autosize-on="true"
                                                                  style="overflow: hidden; resize: horizontal; word-wrap: break-word;  width: 100%;  height: 100px;"><?php echo ($data['goods_id'] ? $data['seller_note'] : ''); ?></textarea>
                                                    </div>


                                                </div>
                                            </div>
                                        </div>


                                        <div id="shop_img" class="tab-pane fade">
                                            <div class="row">
                                                <div class="col-md-8">

                                                    <label class="control-label">
                                                        商品图片<span class="symbol required" aria-required="true"></span>(300px*300px)排序由大到小排序
                                                        如果数字一样则按添加的顺序
                                                    </label>

                                                    <div class="form-group">
<span class="btn btn-success fileinput-button upload_file">

 <i class="glyphicon glyphicon-plus"></i> <span>上传文件...</span>
	<input type="file" name="files[]" class="upload_input" id="head_photo_url" multiple="">
</span>

                                                    </div>
                                                    <div class="form-group">

                                                        <br>

                                                        <div class="goods_image_url">
                                                            <?php if($data['goods_id']): if(is_array($good_image_url)): $i = 0; $__LIST__ = $good_image_url;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="fileinput fileinput-new"
                                                                     data-provides="fileinput">
                                                                    <div class="fileinput-new thumbnail">
                                                                        <img id="head_photo_src_background"
                                                                             src="<?php echo ($vo["img_url"]); ?>" alt="">
                    <span style="display: block;width: 150px;float: left;margin-top: -103px;margin-left: 200px;">排序：<input
                            type="text" name="img_order[]" value="<?php echo (($vo["img_order"])?($vo["img_order"]):'0'); ?>" placeholder="排序"
                            class="form-control valid" aria-required="true" aria-invalid="false">
                    </span>
                                                                        <button type="button"
                                                                                class="btn btn-danger delete delete_img"
                                                                                style="float: left;margin-top: -40px;margin-left: 200px;">
                                                                            <i class="glyphicon glyphicon-trash"></i>
                                                                            <span>删除</span>
                                                                        </button>
                                                                    </div>

                                                                    <input type="hidden" name="good_image_url[]"
                                                                           class="goods_image_count"
                                                                           value="<?php echo ($vo["img_url"]); ?>">
                                                                </div><?php endforeach; endif; else: echo "" ;endif; endif; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <!-- S 邮费设置-->
                                        <div id="postage_set" class="tab-pane fade">
                                            <div class="row">
                                                <div class="col-md-8" style=" width: 90%">
                                                      <div class="form-group">
                                                           <label class="control-label">
                                                               单位
                                                           </label>
                                                           <div class="clip-radio radio-primary fa-hover Primary">
                                                               <input type="radio" value="0" name="postage_unit"
                                                                      id="unit_piece" <?php echo ($data['goods_id'] ? $data['postage_unit']?'':'checked' : 'checked'); ?>
                                                               />
                                                               <label for="unit_piece">
                                                                   件数
                                                               </label>
                                                               <input type="radio" value="1" name="postage_unit"
                                                                      id="unit_weight" <?php echo ($data['goods_id'] ? $data['postage_unit']?'checked':'' : ''); ?>
                                                               />
                                                               <label for="unit_weight">
                                                                   重量(kg)
                                                               </label>
                                                               注:请设置正确的商品重量
                                                           </div>

                                                       </div>
                                                    <div class="form-group">
                                                        <table class="table table-hover" id="sample-table-1" >
                                                            <thead>
                                                            <tr>
                                                                <th style="width:50px">#</th>
                                                                <th class="hidden-xs" style="width:80px">地区</th>
                                                                <th style="width:50px" class="f_title"><?php echo ($data['goods_id'] ? $data['postage_unit']?'首重(kg)':'首件(件)' : '首件(件)'); ?></th>
                                                                <th style="width:50px" class="f_p_title"><?php echo ($data['goods_id'] ? $data['postage_unit']?'首重价':'首件价' : '首件价'); ?></th>
                                                                <th style="width:50px" class="a_title"><?php echo ($data['goods_id'] ? $data['postage_unit']?'续重(kg)':'续件(件)':'续件(件)'); ?></th>
                                                                <th style="width:50px" class="a_p_title"><?php echo ($data['goods_id'] ? $data['postage_unit']?'续重':'续件价':'续件价'); ?></th>
                                                                <th style="width:100px" colspan="2" >包邮条件</th>
                                                                <th style="width:100px">#</th>
                                                                <th class="hidden-xs" style="width:80px">地区</th>
                                                                <th style="width:50px" class="f_title"><?php echo ($data['goods_id'] ? $data['postage_unit']?'首重(kg)':'首件(件)':'首件(件)'); ?></th>
                                                                <th style="width:50px" class="f_p_title"><?php echo ($data['goods_id'] ? $data['postage_unit']?'首重价':'首件价':'首件价'); ?></th>
                                                                <th style="width:50px" class="a_title"><?php echo ($data['goods_id'] ? $data['postage_unit']?'续重(kg)':'续件(件)':'续件(件)'); ?></th>
                                                                <th style="width:50px" class="a_p_title"><?php echo ($data['goods_id'] ? $data['postage_unit']?'续重':'续件价':'续件价'); ?></th>
                                                                <th style="width:150px" colspan="2" >包邮条件</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                             <tr>
                                                                <td >
                                                                </td>
                                                                <td>
                                                                    全国
                                                                </td>
                                                                <td>
                                                                    <input type="text"   class="postage_num" id="first" value="1" style="width:50px"/>
                                                                </td>
                                                                <td>
                                                                    <input type="text"   class="postage_num" id="first_price" value="0" style="width:50px"/>元
                                                                </td>
                                                                <td>
                                                                    <input type="text"   class="postage_num" id="add" value="1" style="width:50px"/>
                                                                </td>
                                                                <td>
                                                                    <input type="text"   class="postage_num" id="add_price" value="0" style="width:50px"/>元
                                                                </td>
                                                                <td width="75">
                                                                    <input type="text"   class="postage_num by_condition" id="by_condition" value="0" style="width:100%"/>
                                                                </td>
                                                                <td width="80">
                                                                    <select class="form-control by_unit"  name="by_unit" id="by_unit">
                                                                        <option value="3" selected = "selected">包邮</option>
                                                                        <option class= 'by_piece' value="0" <?php echo ($data['postage_unit']?'style="display:none"':'select ="selected"'); ?> >件</option>
                                                                        <option class= 'by_weight' value="1"    <?php echo ($data['postage_unit']?'select ="selected" ':'style="display:none"'); ?>>kg</option>
                                                                        <option value="2">元</option>
                                                                        <option value="-1" > 不包邮</option>
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                    <button class="btn btn-primary btn-wide pull-left" id="unify" type="button">
                                                                        全国统一设置
                                                                    </button>
                                                                </td>
                                                            </tr>
                                                            <?php if(is_array($postage_info)): $key = 0; $__LIST__ = $postage_info;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($key % 2 );++$key; if(($key%2) == "1"): ?><tr><?php endif; ?>
                                                                <td >
                                                                    <input type="hidden"  name="provinceid[<?php echo ($key-1); ?>]"  value="<?php echo ($vo["provinceid"]); ?>" />
                                                                </td>
                                                                <td>
                                                                    <input type="hidden"  name="province[<?php echo ($key-1); ?>]"  value="<?php echo ($vo["province"]); ?>" />
                                                                    <?php echo ($vo["province"]); ?>
                                                                </td>
                                                                <td>
                                                                    <input type="text"  class="first postage_num" name="first[<?php echo ($key-1); ?>]"  value="<?php echo ($vo["first"]); ?>" style="width:50px"/>
                                                                </td>
                                                                <td>
                                                                    <input type="text"  class="first_price postage_num" name="first_price[<?php echo ($key-1); ?>]"  value="<?php echo ($vo["first_price"]); ?>" style="width:50px"/>元
                                                                </td>
                                                                <td>
                                                                    <input type="text"  class="add postage_num" name="add[<?php echo ($key-1); ?>]"  value="<?php echo ($vo["add"]); ?>" style="width:50px"/>
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="add_price postage_num" name="add_price[<?php echo ($key-1); ?>]"  value="<?php echo ($vo["add_price"]); ?>" style="width:50px"/>元
                                                                </td>
                                                                <td  width="50">
                                                                    <input type="text"  class="by_condition postage_num" name="by_condition[<?php echo ($key-1); ?>]"  value="<?php echo ($vo["by_condition"]); ?>" style="width:100%"/>
                                                                </td>
                                                                <td width="75">
                                                                    <select class="form-control by_unit"  name="by_unit[<?php echo ($key-1); ?>]" >
                                                                        <option value="3"   <?php if(($vo['by_unit']) == "3"): ?>selected = "selected"<?php endif; ?>   >包邮</option>
                                                                        <option class="by_piece" value="0"
                                                                            <?php if(($vo['by_unit']) == "0"): ?>selected = "selected"<?php endif; ?>
                                                                            <?php echo ($data['postage_unit']?'style="display:none"':''); ?>
                                                                        >件</option>
                                                                        <option class="by_weight" value="1"
                                                                            <?php if(($vo['by_unit']) == "1"): ?>selected = "selected"<?php endif; ?>
                                                                            <?php echo ($data['postage_unit']?'':'style="display:none"'); ?>
                                                                        >kg</option>
                                                                        <option value="2"  <?php if(($vo['by_unit']) == "2"): ?>selected = "selected"<?php endif; ?>    >元</option>
                                                                        <option value="-1"  <?php if(($vo['by_unit']) == "-1"): ?>selected = "selected"<?php endif; ?>  > 不包邮</option>
                                                                    </select>
                                                                </td>
                                                                <?php if(($key%2) == "0"): ?></tr><?php endif; endforeach; endif; else: echo "" ;endif; ?>


                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- E 邮费设置-->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <input type="hidden" value="<?php echo ($data["goods_id"]); ?>" name="goods_id">
                                <button class="btn btn-primary btn-wide pull-left" type="submit">
                                    提交 <i class="fa fa-arrow-circle-right"></i>
                                </button>
                            </div>

                            <div class="col-md-8">
                                <p>

                                </p>
                            </div>

                        </div>
                    </form>
                </div>


                <!-- end: YOUR CONTENT HERE -->
            </div>
        </div>
    </div>
    <!--  FOOTER and SETTINGS -->

    <!-- start: FOOTER -->
<footer>
                <div class="footer-inner">
                    <div class="pull-left">
                      <?php echo ($webseting["web_copyright"]); ?>
                      
                    </div>
                    <div class="pull-right">
                        <span class="go-top"><i class="ti-angle-up"></i></span>
                    </div>
                </div>
            </footer>
<!-- end: FOOTER -->   


<!-- start: SETTINGS -->
            <div class="settings panel panel-default hidden-xs hidden-sm" id="settings">
                <button ct-toggle="toggle" data-toggle-class="active" data-toggle-target="#settings" class="btn btn-default">
                    <i class="fa fa-spin fa-gear"></i>
                </button>
                <div class="panel-heading">
                    样式设置
                </div>
                <div class="panel-body">
                    <!-- start: FIXED HEADER -->
                    <div class="setting-box clearfix">
                        <span class="setting-title pull-left"> 固定页头</span>
                        <span class="setting-switch pull-right">
                            <input type="checkbox" class="js-switch" id="fixed-header" />
                        </span>
                    </div>
                    <!-- end: FIXED HEADER -->
                    <!-- start: FIXED SIDEBAR -->
                    <div class="setting-box clearfix">
                        <span class="setting-title pull-left">固定侧边栏</span>
                        <span class="setting-switch pull-right">
                            <input type="checkbox" class="js-switch" id="fixed-sidebar" />
                        </span>
                    </div>
                    <!-- end: FIXED SIDEBAR -->
                    <!-- start: CLOSED SIDEBAR -->
                    <div class="setting-box clearfix">
                        <span class="setting-title pull-left">关闭侧边栏</span>
                        <span class="setting-switch pull-right">
                            <input type="checkbox" class="js-switch" id="closed-sidebar" />
                        </span>
                    </div>
                    <!-- end: CLOSED SIDEBAR -->
                    <!-- start: FIXED FOOTER -->
                    <div class="setting-box clearfix">
                        <span class="setting-title pull-left">固定页脚</span>
                        <span class="setting-switch pull-right">
                            <input type="checkbox" class="js-switch" id="fixed-footer" />
                        </span>
                    </div>
                    <!-- end: FIXED FOOTER -->
                    <!-- start: THEME SWITCHER -->
                   <!--  <div class="colors-row setting-box">
                       <div class="color-theme theme-1">
                           <div class="color-layout">
                               <label>
                                   <input type="radio" name="setting-theme" value="theme-1">
                                   <span class="ti-check"></span>
                                   <span class="split header"> <span class="color th-header"></span> <span class="color th-collapse"></span> </span>
                                   <span class="split"> <span class="color th-sidebar"><i class="element"></i></span> <span class="color th-body"></span> </span>
                               </label>
                           </div>
                       </div>
                       <div class="color-theme theme-2">
                           <div class="color-layout">
                               <label>
                                   <input type="radio" name="setting-theme" value="theme-2">
                                   <span class="ti-check"></span>
                                   <span class="split header"> <span class="color th-header"></span> <span class="color th-collapse"></span> </span>
                                   <span class="split"> <span class="color th-sidebar"><i class="element"></i></span> <span class="color th-body"></span> </span>
                               </label>
                           </div>
                       </div>
                   </div>
                   <div class="colors-row setting-box">
                       <div class="color-theme theme-3">
                           <div class="color-layout">
                               <label>
                                   <input type="radio" name="setting-theme" value="theme-3">
                                   <span class="ti-check"></span>
                                   <span class="split header"> <span class="color th-header"></span> <span class="color th-collapse"></span> </span>
                                   <span class="split"> <span class="color th-sidebar"><i class="element"></i></span> <span class="color th-body"></span> </span>
                               </label>
                           </div>
                       </div>
                       <div class="color-theme theme-4">
                           <div class="color-layout">
                               <label>
                                   <input type="radio" name="setting-theme" value="theme-4">
                                   <span class="ti-check"></span>
                                   <span class="split header"> <span class="color th-header"></span> <span class="color th-collapse"></span> </span>
                                   <span class="split"> <span class="color th-sidebar"><i class="element"></i></span> <span class="color th-body"></span> </span>
                               </label>
                           </div>
                       </div>
                   </div>
                   <div class="colors-row setting-box">
                       <div class="color-theme theme-5">
                           <div class="color-layout">
                               <label>
                                   <input type="radio" name="setting-theme" value="theme-5">
                                   <span class="ti-check"></span>
                                   <span class="split header"> <span class="color th-header"></span> <span class="color th-collapse"></span> </span>
                                   <span class="split"> <span class="color th-sidebar"><i class="element"></i></span> <span class="color th-body"></span> </span>
                               </label>
                           </div>
                       </div>
                       <div class="color-theme theme-6">
                           <div class="color-layout">
                               <label>
                                   <input type="radio" name="setting-theme" value="theme-6">
                                   <span class="ti-check"></span>
                                   <span class="split header"> <span class="color th-header"></span> <span class="color th-collapse"></span> </span>
                                   <span class="split"> <span class="color th-sidebar"><i class="element"></i></span> <span class="color th-body"></span> </span>
                               </label>
                           </div>
                       </div>
                   </div> -->
                    <!-- end: THEME SWITCHER -->
                </div>
            </div>
            <!-- end: SETTINGS -->
    <!-- end: FOOTER -->
    <!-- start: OFF-SIDEBAR -->

    <!-- end: OFF-SIDEBAR -->

</div>
<!-- start: MAIN JAVASCRIPTS -->

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
  

<!-- end: CLIP-TWO JAVASCRIPTS -->

<!-- start: JAVASCRIPTS REQUIRED FOR THIS PAGE ONLY -->
<script src="__PUBLIC__/bootstrap/vendor/ckeditor/ckeditor.js"></script>
<script src="__PUBLIC__/bootstrap/vendor/ckeditor/adapters/jquery.js"></script>
<script src="__PUBLIC__/bootstrap/vendor/jquery-validation/jquery.validate.min.js"></script>
<!-- end: JAVASCRIPTS REQUIRED FOR THIS PAGE ONLY -->
<!-- start: CLIP-TWO JAVASCRIPTS -->
<!-- <script src="__PUBLIC__/bootstrap/assets/js/main.js"></script> -->
<!-- start: JavaScript Event Handlers for this page -->
<!--<script src="__PUBLIC__/bootstrap/assets/js/form-validation.js"></script>-->

<script src="__PUBLIC__/bootstrap/vendor/artDialog/artDialog.source.js?skin=default"></script>
<script src="__PUBLIC__/bootstrap/vendor/artDialog/plugins/iframeTools.source.js"></script>

<script src="__PUBLIC__/bootstrap/vendor/ajaxfileupload/ajaxfileupload.js"></script>
<!-- 配置文件 -->
<script type="text/javascript" src="__PUBLIC__/ueditor/ueditor.config.js"></script>
<!-- 编辑器源码文件 -->
<script type="text/javascript" src="__PUBLIC__/ueditor/ueditor.all.js"></script>
<!-- 实例化编辑器 -->
<script type="text/javascript">
    var ue = UE.getEditor('goods_desc');
</script>
<script>
    var get_city_url = "<?php echo U('wap/index/get_city');?>";
</script>
<script src="__PUBLIC__/js/common.js"></script>
<!-- start: datepicker -->
 <link href="__PUBLIC__/bootstrap/vendor/bootstrap-datepicker/bootstrap-datepicker3.standalone.min.css" rel="stylesheet" media="screen">

        <!-- <link href="__PUBLIC__/bootstrap/vendor/bootstrap-timepicker/bootstrap-timepicker.min.css" rel="stylesheet" media="screen"> -->
<script src="__PUBLIC__/bootstrap/vendor/bootstrap-datepicker/bootstrap-datepicker.js"></script>
<link href="__PUBLIC__/bootstrap/vendor/bootstrap-datetimepicker/bootstrap-datetimepicker_1.css" rel="stylesheet"
      media="screen">
<script src="__PUBLIC__/bootstrap/vendor/bootstrap-datetimepicker/bootstrap-datetimepicker_1.js"></script>

<!--<script src="__PUBLIC__/bootstrap/vendor/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>-->
        <!--<script src="__PUBLIC__/bootstrap/vendor/bootstrap-timepicker/bootstrap-timepicker.min.js"></script>  -->
        <!-- end: CLIP-TWO JAVASCRIPTS -->

        <script type="text/javascript">
           $(function(){    //不带时分秒
            var datePickerHandler = function() {
                $('.datepicker').datepicker({
                    autoclose: true,
                    todayHighlight: true,
                    clearBtn: true,//清楚按钮
                    todayBtn: true,//今天按钮
                    dateFormat: 'yyyy-mm-dd',//日期格式
                    format: 'yyyy-mm-dd',
                    language: 'zhcn',
                    numberOfMonths:1,//显示几个月
                    showButtonPanel:true,//是否显示按钮面板
                    clearText:"清除",//清除日期的按钮名称
                    closeText:"关闭",//关闭选择框的按钮名称
                    yearSuffix: '年', //年的后缀
                    showMonthAfterYear:true,//是否把月放在年的后面
                    language: 'zhcn',
                });
            };
            datePickerHandler();
           });
           $(function () {    //带时分秒
               var datePickerHandler = function () {
                   $('.datepicker_hour').datetimepicker({
                       language: 'zh_CN',
                       dateFormat: 'yyyy-mm-dd hh:ii:ss',//日期格式
                       format: 'yyyy-mm-dd hh:ii:ss',
                       weekStart: 1,
                       todayBtn: 1,
                       autoclose: 1,
                       todayHighlight: 1,
                       startView: 2,
//                       forceParse: 0,
                       showMeridian: 1
                   });
               };
               datePickerHandler();
           });
        </script>
        <!-- end: datepicker -->
  
<script>
    jQuery(document).ready(function () {
        $('#is_promote_yes').click(function () {  //参加促销
            $('#promote_msg_set').removeClass('hidden');
        });
        $('#is_promote_no').click(function () {  //不参加促销
            $('#promote_msg_set').addClass('hidden');
        });

        $('#activity_id').change(function () {
            var activity_id =$(this).val();
            if(activity_id == 0){
                $('#promote_msg_set').addClass('hidden');
				$('#activity99_set').addClass('hidden');
            }else if(activity_id == 1) {
                $('#promote_msg_set').removeClass('hidden');
				$('#activity99_set').addClass('hidden');
            }else if(activity_id == 2){
				$('#activity99_set').removeClass('hidden');
				$('#promote_msg_set').addClass('hidden');
			}
        });
        function getPromoteStatus() {  //获取促销状态
            var is_promote_yes = $("#activity_id").val();
            return is_promote_yes;
        }

        function getPromoteMsg(value) {   //获取促销时间,并转换成时间戳
            if (value == 'start') {
                var start_time = $('#star_time').val();
                var timestamp = Date.parse(new Date(start_time));
                return timestamp;
            }
            if (value == 'end') {
                var end_time = $('#end_time').val();
                var timestamp = Date.parse(new Date(end_time));
                return timestamp;
            }
        }

/*        jQuery.validator.addMethod('check_promote_price', function (value) {  //验证促销价格
            var ret = true;
            if (getPromoteStatus() == 1) {
                var shop_price = parseInt($('#shop_price').val());
                var promote_price = parseInt($('#promote_price').val());
                var reg = /^[0-9]+\.{0,1}[0-9]{0,2}$/;
                if (shop_price < promote_price) {  //促销价是否大于销售价
                    ret = false;
                }
                if (promote_price <= 0) {
                    ret = false;
                }
                if (!reg.test(promote_price)) {
                    ret = false;
                }
            }
            return ret;
        });
        jQuery.validator.addMethod('check_img', function (value) {  //验证促销图片
            var ret = true;
            if (getPromoteStatus() == 1) {
                var img_val = $('#promote_img').val();
                if (img_val.length <= 0) {
                    ret = false;
                }
            }
            return ret;
        });*/
        jQuery.validator.addMethod('check_start_time', function (value) { //验证开始时间
            var ret = true;
            if (getPromoteStatus() > 0) {
                var start_time = getPromoteMsg('start');
                var end_time = getPromoteMsg('end');
                if (start_time > end_time) {
                    ret = false;
                }
            }
            return ret;
        });
        jQuery.validator.addMethod('check_end_time', function (value) { //验证开始时间
            var ret = true;
            if (getPromoteStatus() > 0) {
                var end_time = getPromoteMsg('end');
                var now_time = Date.parse(new Date());
                if (end_time < now_time) {
                    ret = false;
                }
            }
            return ret;
        });
        jQuery.validator.addMethod("get_param_value", function (value) {
            var ret = true;
            ret = ue.hasContents();
            return ret;
        });
        $("#form2").validate({
            rules: {
                cat_id: "required",
                goods_name: "required",
                goods_sn: "required",
                goods_number: {
                    required: true,
                    digits: true,
                    max: 9999
                },
                purchase_price: {
                    number: true,
                    min: 0
                },
                // market_price: {
                // 	required:true,
                // 	number:true,
                // 	min:0.01
                // },
                shop_price: {
                    required: true,
                    number: true,
                    min: 0.01
                },
                share_money: {
                    number: true,
                    min: 0
                },
                promote_img: "check_img",
                goods_img: "required",
                goods_brief: "required",
                msg: "get_param_value",
                promote_price: {
                    check_promote_price: true
                },
                activity_start_date: {
                    check_start_time: true
                },
                activity_end_date: {
                    check_end_time: true
                }
            },
            messages: {
                cat_id: "请选择商品分类",
                goods_name: "请填写商品名称",
                goods_sn: "填写商品货号",
                goods_number: {
                    required: "请填写库存",
                    digits: "请填写正确的库存",
                    max: "库存最多9999"
                },
                purchase_price: {
                    number: "请填写正确的价格",
                    min: "价格不能小于0"
                },
                //market_price: {
                //  required:"请填写市场价",
                //   number:"请填写正确的价格",
                //    min:"价格不能小于0.01"
                //  },
                shop_price: {
                    required: "请填写本店售价",
                    number: "请填写正确的价格",
                    min: "价格不能小于0.01"
                },
                share_money: {
                    number: "请填写正确的返利金额",
                    min: "价格不能小于0"
                },
                promote_img: '请上传促销图片',
                goods_img: "请上传商品图片",
                goods_brief: "请输入商品的简短描述",
                msg: "请输入商品的详细描述",
                promote_price: {
                    check_promote_price: '促销价不能小于0且不能大于销售价'
                },
                activity_start_date: {
                    check_start_time: '开始时间不能大于结束时间'
                },
                activity_end_date: {
                    check_end_time: '结束时间不能小于当前时间'
                }
            }
        });

        //限制邮费信息 只能输入数字
        $('.postage_num').keyup(function () {
            var postage_num= $(this).val().replace(/\D/g,'');
            if(postage_num != '0'){
                postage_num = postage_num.replace(/^0*/g,'');
            }
            $(this).val(postage_num);
        });
        //邮费 计量单位选择
        $("input[name='postage_unit']").change(function () {
            var postage_unit = $("input[name='postage_unit']:checked").val();
            if( postage_unit == 0){
                var f_title ='首件(件)';
                var f_p_title ='首件价';
                var a_title = '续件(件)';
                var a_p_title = '续件价';
                $('.by_weight').hide();
                $('.by_piece').show(function () {
                    if($(this).parent().val() == 1){
                        $(this).parent().val(0);
                    }
                });
            }else {
                var f_title ='首重(kg)';
                var f_p_title ='首重价';
                var a_title = '续重(kg)';
                var a_p_title = '续重价';
                $('.by_weight').show(function () {
                    if($(this).parent().val() == 0){
                        $(this).parent().val(1);
                    }

                });
                $('.by_piece').hide();
            }
            $('.f_title').html(f_title);
            $('.f_p_title').html(f_p_title);
            $('.a_title').html(a_title);
            $('.a_p_title').html(a_p_title);
        });
        //包邮单位选择包邮 包邮条件变成0
        $('.by_unit').change(function () {
            var _value = $(this).val();
            if(_value == 3){
                $(this).parent().prev().find('.by_condition').val(0);
            }
        });
        // 将邮费 设置为 全国统一价
        $('#unify').click(function () {
            $('.first').val($('#first').val());
            $('.first_price').val($('#first_price').val());
            $('.add').val($('#add').val());
            $('.add_price').val($('#add_price').val());
            $('.by_condition').val($('#by_condition').val());
            $(".by_unit").val($('#by_unit').val());
        });

    });
</script>

<script>
    jQuery(document).ready(function () {
        //FormValidator.init();
        jQuery.validator.addMethod("byteRangeLength", function (value) {
            var dropdown = $("#dropdown").val();
            var ret = true;
            if (dropdown > 0) {
                if (!value) {
                    ret = false;
                }
            }
            return ret;
        });
        $("#dropdown").change(function () {
            var val = $(this).val();
            if (val > 0) {
                $(".menu_icons").hide();
            } else {
                $(".menu_icons").show();
            }

        });
    });
</script>
<script>
    $(document).ready(function (e) {
        ///上传商家logo
        $('.upload_file').on('change', '.upload_input', function () {
            var id = $(this).attr('id');
            if (id == 'head_photo') {
                //咯go 截取
                ajaxFileUploadview('head_photo', 'photo_pic', "<?php echo U('admin/Imagecat/upload_ajax');?>");
            } else if (id == 'promotion_photo') {
                ajaxFileUpload('promotion_photo', 'promote_img', "<?php echo U('admin/Imagecat/upload_ajax');?>");
            } else if(id == 'base_photo'){
                ajaxFileUpload('base_photo', 'base_logo', "<?php echo U('admin/Imagecat/upload_ajax');?>");
            } else if(id == 'long_photo'){
                ajaxFileUpload('long_photo', 'long_goods_pic', "<?php echo U('admin/Imagecat/upload_ajax');?>");
            }else {
                var goods_length = $(".goods_image_count").length;
                if (goods_length >= 5) {
                    //	alert('图片最多上传5张');
                    layer.msg('图片最多上传5张', {icon: 5});
                    //  var dialog = art.dialog({title: false, fixed: true, padding: 0});
                    // art.dialog('这是php点点通的教程', function () {alert('你点击确定了')},function(){alert('你点击取消了');});
                    // dialog.lock().time(2).content("");
                } else {
                    ajaxFileUploadMore('url', "<?php echo U('admin/Imagecat/upload_ajax');?>");
                }
            }
        });

        $('.goods_image_url').on('click', '.delete_img', function () {
            if (confirm('您确定删除该图片？')) {
                $(this).parents('.fileinput').remove();
            }
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
    //文件上传带预览
    function ajaxFileUploadview(imgid, hiddenid, url) {

        $.ajaxFileUpload({
            url: url,
            secureuri: false,
            fileElementId: imgid,
            dataType: 'json',
            data: {name: 'logan', id: 'id'},
            success: function (data, status) {

                if (typeof (data.error) != 'undefined') {

                    if (data.error != '') {
                        layer.msg(data.error, {icon: 5});
                        //var dialog = art.dialog({title: false, fixed: true, padding: 0});
                        //dialog.time(2).content("<div class='tips'>" + data.error + "</div>");
                    } else {

                        var resp = data.msg;
                        if (resp != '0000') {
                            layer.msg(data.error, {icon: 5});
                            //var dialog = art.dialog({title: false, fixed: true, padding: 0});
                            //dialog.time(2).content("<div class='tips'>" + data.error + "</div>");
                            return false;
                        } else {

                            $('#head_photo_src').attr('src', data.imgurl);
                            $('#photo_pic').val(data.imgurl);

                            /* art.dialog.open("<?php echo U('admin/Imagecat/index');?>?img=" + data.imgurl, {
                             title: '裁剪图片',
                             width: '605px',
                             height: '2900px'
                             });*/

                            //dialog.time(3).content("<div class='msg-all-succeed'>上传成功！</div>");
                        }
                    }
                }
            },
            error: function (data, status, e) {
                layer.msg(e, {icon: 5});
                //var dialog = art.dialog({title: false, fixed: true, padding: 0});
                //dialog.time(3).content("<div class='tips'>" + e + "</div>");
            }
        })
        return false;
    }


    //文件上传带预览
    function ajaxFileUpload(imgid, hiddenid, url) {

        $.ajaxFileUpload({
            url: url,
            secureuri: false,
            fileElementId: imgid,
            dataType: 'json',
            data: {name: 'logan', id: 'id'},
            success: function (data, status) {

                if (typeof (data.error) != 'undefined') {

                    if (data.error != '') {
                        layer.msg(data.error, {icon: 5});
                        // var dialog = art.dialog({title: false, fixed: true, padding: 0});
                        // dialog.time(2).content("<div class='tips'>" + data.error + "</div>");
                    } else {

                        var resp = data.msg;
                        if (resp != '0000') {
                            layer.msg(data.error, {icon: 5});
                            // var dialog = art.dialog({title: false, fixed: true, padding: 0});
                            // dialog.time(2).content("<div class='tips'>" + data.error + "</div>");
                            return false;
                        } else {

                            $("#" + hiddenid).val(data.imgurl);
                            $("#" + imgid + '_src').attr('src', data.imgurl);
//                                  art.dialog.open("<?php echo U('admin/Imagecat/index');?>?img=" + data.imgurl, {
//                                        title: '裁剪图片',
//                                        width: '605px',
//                                        height: '290px'
//                                    });

                            //dialog.time(3).content("<div class='msg-all-succeed'>上传成功！</div>");
                        }
                    }
                }
            },
            error: function (data, status, e) {
                //var dialog = art.dialog({title: false, fixed: true, padding: 0});
                // dialog.time(3).content("<div class='tips'>" + e + "</div>");
                layer.msg(e, {icon: 5});
            }
        })

        return false;
    }
    //多文件上传带预览
    function ajaxFileUploadMore(imgid, url) {

        $.ajaxFileUpload({
            url: url,
            secureuri: false,
            fileElementId: "head_photo_" + imgid,
            dataType: 'json',
            data: {name: 'logan', id: 'id'},
            success: function (data, status) {
                if (typeof (data.error) != 'undefined') {

                    if (data.error != '') {
                        layer.msg(data.error, {icon: 5});
                        // var dialog = art.dialog({title: false, fixed: true, padding: 0});
                        //dialog.time(2).content("<div class='tips'>" + data.error + "</div>");
                    } else {

                        var resp = data.msg;
                        if (resp != '0000') {
                            layer.msg(data.error, {icon: 5});
                            // var dialog = art.dialog({title: false, fixed: true, padding: 0});
                            // dialog.time(2).content("<div class='tips'>" + data.error + "</div>");
                            return false;
                        } else {


                            var str_img = ' <div class="fileinput fileinput-new" data-provides="fileinput">'
                                    + ' <div class="fileinput-new thumbnail" id="show_photo_background">'
                                    + '   <img  src="' + data.imgurl + '" alt="" >'
                                    + '<span  style="display: block;width: 150px;float: left;margin-top: -103px;margin-left: 200px;">排序： <input type="text" name="img_order[]" value="0" placeholder="排序" class="form-control valid" aria-required="true" aria-invalid="false"></span>'
                                    + ' <button type="button" class="btn btn-danger delete delete_img"  style="float: left;margin-top: -40px;margin-left: 200px;">'
                                    + '	<i class="glyphicon glyphicon-trash"></i>'
                                    + '	<span>删除</span>'
                                    + '</button>'

                                    + '</div>'

                                    + '<input type="hidden" name="good_image_url[]"  class="goods_image_count"  value="' + data.imgurl + '">'
                                    + ' </div>';
                            $('.goods_image_url').append(str_img);
                            //  $('#photo_pic_'+imgid).val(data.imgurl);
                            //   $("#head_photo_src_"+imgid).attr('src', );
                            /*  art.dialog.open("<?php echo U('admin/Imagecat/index');?>?img=" + data.imgurl, {
                             title: '裁剪图片',
                             width: '800px',
                             height: '400px'
                             });*/

                            //dialog.time(3).content("<div class='msg-all-succeed'>上传成功！</div>");
                        }


                    }
                }
            },
            error: function (data, status, e) {
                layer.msg(e, {icon: 5});
                //var dialog = art.dialog({title: false, fixed: true, padding: 0});
                // dialog.time(3).content("<div class='tips'>" + e + "</div>");
            }
        })

        return false;
    }
    function setlatlng(longitude, latitude) {
        art.dialog.data('longitude', longitude);
        art.dialog.data('latitude', latitude);
        art.dialog.open('<?php echo U('
        Admin / Index / setmap
        '); ?>', {
            lock: false,
            title: '设置经纬度',
            width: 600,
            height: 400,
            yesText: '关闭',
            background: '#000',
            opacity: 0.87
        }
    )
        ;
    }
    $(document).ready(function (e) {
        $("#goods_type").change(function () {

            var __this = $(this);
            var cat_id = __this.val();
            if (cat_id) {
                var goods_id = $('input[name="goods_id"]').val();
                $.ajax({
                    url: "<?php echo U('admin/goods/get_goods_attribute');?>",
                    type: 'get',
                    data: {goods_id: goods_id, cat_id: cat_id},
                    dataType: 'html',
                    success: function (data) {
                        //console.log(data);
                        $('.goods_type_html').html(data);

                    }
                });
            } else {
                $('.goods_type_html').html("");
            }
        });
        $(".goods_type_html").on('click', '.add_data_attr', function () {
            var __this = $(this);
            var attr_id = __this.attr('data');
            //var __this_attr=$('.attr_id_'+attr_id);
            var __this_attr = __this.parents('.data_attrs');
            var html = __this_attr.html();
            html = '<div class="col-md-10 data_attrs">' + html + '</div>';
            html = html.replace('+', '-');
            html = html.replace('add_data_attr', 'remove_data_attr');
            __this_attr.after(html);
            var vall = __this_attr.next().find('input[name="goods_attr_id[]"]').val('');
        });
        $(".goods_type_html").on('click', '.remove_data_attr', function () {
            var __this = $(this);
            __this.parents('.data_attrs').remove();
        });
        $('#goods_type').change();
    });
</script>
<!-- end: JavaScript Event Handlers for this page -->
</body>
</html>