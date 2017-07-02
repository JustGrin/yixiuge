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
        <div class="main-content" >
            <div class="wrap-content container" id="container">
                <!-- start: PAGE TITLE -->
                <section id="page-title">
                    <div class="row">
                        <div class="col-sm-8">
                            <h1 class="mainTitle">售后退款申请</h1>
                            <span class="mainDescription">退款申请信息</span>
                        </div>

                    </div>
                </section>
                <!-- end: PAGE TITLE -->
                <!-- start: YOUR CONTENT HERE -->
                <div class="row">
                    <div class="col-md-5">
                        <div class="form-group">
                            <label class="control-label">
                                商品信息
                            </label>
                        </div>
                        <div class="form-group">
                            <label class="control-label">
                                订单编号：
                            </label>
                            <?php echo ($list['order']['order_sn']); ?>
                        </div>
                        <div class="form-group">
                            <label class="control-label">
                                商品名：
                            </label>
                            <?php echo ($list['order_goods']['goods_name']); ?>
                        </div>
                        <div class="form-group">
                            <label class="control-label">
                                订单金额：
                            </label>
                            ￥<?php echo ($list['order']['order_amount']); ?>
                        </div>
                        <div class="form-group" style="color: #ff0000!important">
                            <label class="control-label">
                                退单商品价格：
                            </label>
                            ￥<?php echo ($list['order_goods']['goods_price']); ?>
                        </div>
                        <div class="form-group">
                            <label class="control-label">
                                下单时间：
                            </label>
                            <?php echo (date("Y-m-d H:i:s",$list['order']['add_time'])); ?>
                        </div>
                        <div class="form-group">
                            <label class="control-label">
                                支付金额：
                            </label>
                            <?php echo ($list['order']['order_amount']+$list['order']['shipping_fee']); ?>[
                                余额:￥<?php echo ($list['order']['surplus']); ?>
                                积分:￥<?php echo ($list['order']['integral_money']); ?>(<?php echo ($list['order']['integral']); ?>积分)
                                微信:￥<?php echo ($list['order']['order_amount']+$vo['shipping_fee'] - $list['order']['surplus'] - $list['order']['integral_money']); ?>
                            ]
                        </div>
                    </div>


                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">
                                会员信息
                            </label>
                        </div>
                        <div class="form-group">
                            <label class="control-label">
                                会员名：
                            </label>
                            <?php echo ($list['member']['member_name']); ?>
                        </div>
                        <div class="form-group">
                            <label class="control-label">
                                会员卡号：
                            </label>
                            <?php echo ($list['member']['member_card']); ?>
                        </div>
                    </div>

                </div>
                <div class="container-fluid container-fullw bg-white">
                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label class="control-label">
                                    申请时间：
                                </label>
                                <?php echo (date("Y-m-d H:i",$data["add_time"])); ?>
                            </div>
                            <div class="form-group" style="color: #ff0000!important">
                                <label class="control-label">
                                    申请退款金额：
                                </label>
                                <ul style="list-style-type:none">
                                    <li>
                                        <label class="control-label">
                                            积分：
                                        </label>
                                        ￥<?php echo ($data['integral']/10); ?>(积分<?php echo ($data['integral']); ?>)
                                    </li>
                                    <li>
                                        <label class="control-label">
                                            余额：
                                        </label>
                                        ￥<?php echo ($data["balance"]); ?>
                                    </li>
                                    <li>
                                        <label class="control-label">
                                            微信钱包：
                                        </label>
                                        ￥<?php echo ($data["wx_refund"]); ?>
                                    </li>
                                    <li>
                                        <label class="control-label">
                                            总金额：
                                        </label>
                                        ￥<?php echo ($data["total"]); ?>
                                    </li>
                                </ul>
                            </div>
                            <div class="form-group">
                                <label class="control-label">
                                    客服备注：
                                </label>
                                <?php echo ($data["remarks1"]); ?>
                            </div>
                            <div class="form-group">
                                <label class="control-label">
                                    当前状态：
                                </label>
                                <?php if($data['status'] == 0): ?>待审核
                                    <?php elseif($data['status'] == 1): ?>
                                    已退款
                                    <?php elseif($data['status'] == 2): ?>
                                    拒绝退款:(<?php echo ($data["remarks"]); ?>)<?php endif; ?>
                            </div>
                        </div>


                        <div class="col-md-6 wx_refund_info">
                            <div class="form-group">
                                <label class="control-label">
                                    微信退单信息
                                </label>
                            </div>
                        </div>
                    </div>
                        <div class="row">
                            <?php if($_GET['open'] == handle and $data['status'] == 0): ?><form action="<?php echo U('admin/orderinfo/do_refund');?>" role="form" id="form2" novalidate="novalidate" method="post" >
                                    <div class="form-group">
                                        <label class="control-label">
                                            审核操作：
                                        </label>
                                        <div class="clip-radio radio-primary fa-hover Primary">
                                            <input type="radio" value="1" name="auid_status" id="id_yes" >
                                            <label for="id_yes">
                                                同意退款
                                            </label>
                                            <input type="radio" value="2" name="auid_status" id="id_no"  checked="checked" >
                                            <label for="id_no">
                                                审核不通过
                                            </label>
                                        </div>
                                    </div>
                                    <input type="hidden" value="<?php echo ($data["id"]); ?>" name="id" >
                                    <div class="form-group">
                                        <label class="control-label">
                                            审核(不通过)意见：
                                        </label>
                                        <div>
                                            <textarea id="remarks" name="remarks"  class=" autosize" data-autosize-on="true" style="overflow: hidden; resize: horizontal; word-wrap: break-word;  width: 100%;  height: 100px;"><?php echo ($data["remarks1"]); ?></textarea>
                                        </div>
                                    </div>
                                </form>
                                <div class="row">
                                    <div class="col-md-4">
                                        <button class="btn btn-primary  btn-wide btn-sub" >
                                            提交 <i class="fa fa-arrow-circle-right"></i>
                                        </button>
                                    </div>

                                    <div class="col-md-8">
                                        <p>

                                        </p>
                                    </div>
                                </div>
                                <?php else: ?>
								<?php if($data['status'] == 1 and $data['wx_refund'] > 0): ?><div class="row">
                                    <div class="col-md-4">
                                        <button class="btn btn-primary  btn-wide refund_query" >
                                            查看微信退款信息 <i class="fa fa-arrow-circle-right"></i>
                                        </button>
                                    </div>
                                </div><?php endif; endif; ?>
                        </div>
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
  
<script src="__PUBLIC__/bootstrap/vendor/ckeditor/ckeditor.js"></script>
<script src="__PUBLIC__/bootstrap/vendor/ckeditor/adapters/jquery.js"></script>
<script src="__PUBLIC__/bootstrap/vendor/jquery-validation/jquery.validate.min.js"></script>
<script>
    function check(auid_status) {
        //询问框
        var stat_name='';
        if(auid_status == 1){
            stat_name='同意退款';
        }else if(auid_status == 2){
            stat_name='拒绝退款';
        }
        var msg='确认'+stat_name+'？确认之后不能更改';
        layer.confirm(msg, {
            btn: ['是的,我'+stat_name,'再考虑一下']//按钮
        }, function(){
            $('#form2').submit();
        }, function(){

        });
    }
    jQuery(document).ready(function() {
        //FormValidator.init();
        $('.btn-sub').click(function(){
            var auid_status=$("input:radio[name='auid_status']:checked").val();
            check(auid_status);
        });

        var		transaction_id="<?php echo ($list['wx_refund_data']['transaction_id']); ?>";
        var     out_trade_no="<?php echo ($list['wx_refund_data']['out_trade_no']); ?>";
        var     out_refund_no="<?php echo ($list['wx_refund_data']['out_refund_no']); ?>";
        var     refund_id="<?php echo ($list['wx_refund_data']['refund_id']); ?>";
        $('.refund_query').click(function () {
            $.ajax({
                url: "<?php echo 'http://'.$_SERVER['HTTP_HOST'].'/api/payment/wpay/refundquery.php'; ?>",
                type:'post',
                data: {transaction_id:transaction_id,out_trade_no:out_trade_no,out_refund_no:out_refund_no,refund_id:refund_id},
                dataType: 'json',
                success: function(data){
                    console.log(data);
                    var option = '';
                    option+='<div class="form-group"><label class="control-label">申请金额：</label>'
                            +parseFloat(parseFloat(data.cash_fee)/100)+'元</div>';
                    option+='<div class="form-group"><label class="control-label">商品退单号：</label>'
                            +data.out_refund_no_0+'</div>';
                    option+='<div class="form-group"><label class="control-label">微信退单号：</label>'
                            +data.refund_id_0+'</div>';
                    option+='<div class="form-group"><label class="control-label">商品订单号：</label>'
                            +data.out_trade_no+'</div>';
                    option+='<div class="form-group"><label class="control-label">微信订单号：</label>'
                            +data.transaction_id+'</div>';
                    if(data.refund_status_0 == 'SUCCESS'){
                       var  refund_status_0 ='退单到账';
                    }else {
                        var  refund_status_0 ='退单未到账';
                    }
                    option+='<div class="form-group"><label class="control-label">微信退单状态：</label>'
                            +refund_status_0+'</div>';

                    $('.wx_refund_info').html( option );
                }
            });
        });

    });
//可查询到的数据
/*    cash_fee:"100"
    out_refund_no_0:"R16110912155751"
    out_trade_no:"614691497415"
    refund_count:"1"
    refund_fee:"100"
    refund_fee_0:"100"
    refund_id_0:"2001492001201611090574626720"
    refund_recv_accout_0:"支付用户的零钱"
    refund_status_0:"SUCCESS"
    result_code:"SUCCESS"
    return_code:"SUCCESS"
    return_msg:"OK"
    total_fee:"100"
    transaction_id:"4001492001201611089158196001"*/

    //若返回签名错误 可能是total_fee 为空
</script>
</body>
</html>