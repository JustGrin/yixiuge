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
        
<style type="text/css">
    .class_hidden{display:none;}

</style>
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
                            <span class="mainDescription">订单信息</span>
                        </div>

                    </div>
                </section>
                <!-- end: PAGE TITLE -->
                <!-- start: YOUR CONTENT HERE -->
                <div class="container-fluid container-fullw bg-white">

                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label class="control-label">
                                    订单信息
                                </label>
                            </div>
                            <div class="form-group">
                                <label class="control-label">
                                    会员卡号：
                                </label>
                                <?php echo ($data["member_card"]); ?>
                            </div>
                            <div class="form-group">
                                <label class="control-label">
                                    会员电话：
                                </label>
                                <?php echo ($data["member_mobile"]); ?>
                            </div>
                            <div class="form-group">
                                <label class="control-label">
                                    订单编号：
                                </label>
                                <?php echo ($data["order_sn"]); ?>
                            </div>
                            <div class="form-group">
                                <label class="control-label">
                                    原价总金额：
                                </label>
                                <?php echo ($data["goods_amount"]); ?>
                            </div>
                            <div class="form-group">
                                <label class="control-label">
                                    订单金额：
                                </label>
                                <?php echo ($data['order_amount']+$data['shipping_fee']); ?>
                                [余额支付：￥<?php echo ($data['surplus']); ?>;
                                积分支付：￥<?php echo ($data['integral_money']); ?>(<?php echo ($data['integral']); ?>积分);
                                微信支付：￥<?php echo ($data['order_amount']-$data['surplus']-$data['integral_money']); ?>]
                            </div>
                            <div class="form-group">
                                <label class="control-label">
                                    运费：
                                </label>
                                <?php echo ($data["shipping_fee"]); ?>
                            </div>
                            <div class="form-group">
                                <label class="control-label">
                                    下单时间：
                                </label>
                                <?php echo (date("Y-m-d H:i:s",$data["add_time"])); ?>
                            </div>
                            <div class="form-group">
                                <label class="control-label">
                                    订单状态：
                                </label>
                                <?php echo ($data["code_name"]); ?>
                            </div>
                            <div class="form-group">
                                <label class="control-label">
                                    支付状态：
                                </label>
                                <?php if($data["pay_status"] == 2): if($data["offline"] == 1): ?>货到付款￥<?php echo ($data["offline_money"]); ?>
                                        <?php else: ?>
                                        <?php echo ($pay_status[$data['pay_status']]); endif; ?>
                                    <?php else: ?>
                                    <?php echo ($pay_status[$data['pay_status']]); endif; ?>
                                <?php if($data["pay_status"] == 2): ?>&nbsp;&nbsp;&nbsp;&nbsp;
                                    <?php echo (date("Y-m-d H:i:s",$data["pay_time"])); endif; ?>
                            </div>
                            <?php if($data["pay_status"] == 2): ?><div class="form-group">
                                    <label class="control-label">
                                        确认状态：
                                    </label>
                                    <?php echo ($order_status[$data['order_status']]); ?>
                                    <?php if($data["shipping_status"] == 1): echo (date("Y-m-d H:i:s",$data["confirm_time"])); endif; ?>
                                </div><?php endif; ?>
                            <?php if($data["pay_status"] == 2): ?><div class="form-group">
                                    <label class="control-label">
                                        商品配送情况：
                                    </label>
                                    <?php echo ($shipping_status[$data['shipping_status']]); ?>

                                    <?php if((in_array($data['shipping_status'],array('1','2')))): echo (date("Y-m-d H:i:s",$data["shipping_time"])); endif; ?>
                                </div>
                                <?php if((in_array($data['shipping_status'],array('1','2')))): ?>
                                    <div class="form-group">
                                        <label class="control-label">
                                            物流公司：
                                        </label>
                                        <?php echo ($data["express_name"]); ?>
                                    </div>
									<div class="form-group">
                                        <label class="control-label">
                                            发货单号：
                                        </label>
                                        <?php echo ($data["invoice_no"]); ?>
                                    </div><?php endif; endif; ?>

                        </div>


                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">
                                    收货人信息
                                </label>

                            </div>
                            <div class="form-group">
                                <label class="control-label">
                                    收货人：
                                </label>
                                <?php echo ($data["consignee"]); ?>
                            </div>
                            <div class="form-group">
                                <label class="control-label">
                                    联系电话：
                                </label>
                                <?php echo ($data["mobile"]); ?>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo ($data["tel"]); ?>
                            </div>
                            <div class="form-group">
                                <label class="control-label">
                                    地址：
                                </label>
                                <?php echo ($data["address"]); ?>
                            </div>
                            <?php if(!empty($data['zipcode'])): ?><div class="form-group">
                                    <label class="control-label">
                                        邮编：
                                    </label>
                                    <?php echo ($data["zipcode"]); ?>
                                </div><?php endif; ?>
                            <?php if(!empty($data['best_time'])): ?><div class="form-group">
                                    <label class="control-label">
                                        最佳送货时间：
                                    </label>
                                    <?php echo ($data["best_time"]); ?>
                                </div><?php endif; ?>
                            <?php if(!empty($data['sign_building'])): ?><div class="form-group">
                                    <label class="control-label">
                                        送货地址标志性建筑：
                                    </label>
                                    <?php echo ($data["sign_building"]); ?>
                                </div><?php endif; ?>
                            <?php if(!empty($data['email'])): ?><div class="form-group">
                                    <label class="control-label">
                                        email：
                                    </label>
                                    <?php echo ($data["email"]); ?>
                                </div><?php endif; ?>

                        </div>

                    </div>
                    <div class="row">
                        <div class="form-group">
                            <label class="control-label">
                                <h4>订单商品</h4>
                            </label>
                        </div>
                        <table class="table table-hover" id="sample-table-1">
                            <thead>
                            <tr>
                                <th class="center">#</th>
                                <th>商品名称</th>
                                <th>属性</th>
                                <th>数量</th>
                                <th>成交单价</th>
                                <th class="hidden-xs">成交总价</th>
                                <th>货到付款</th>
                                <th>分享返利</th>
                                <th>分享总额</th>
                                <th>分享人</th>
                                <th>包邮快递</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if(is_array($order_goods)): $key = 0; $__LIST__ = $order_goods;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($key % 2 );++$key;?><tr class="menu_p" data="<?php echo ($vo["rec_id"]); ?>" data-type="0">
                                    <td class="center hidden-xs">
                                          <i class="fa fa-angle-down  angle_down_<?php echo ($vo["rec_id"]); ?>"></i><i class="fa fa-angle-up angle_up_<?php echo ($vo["rec_id"]); ?> class_hidden"></i>
                                        <img src="<?php echo ($vo["goods_image"]); ?>" class="img-rounded"
                                             style="max-width:50px;max-height:50px;" alt="image">
                                    </td>
                                    <td>
                                        <?php echo ($vo["goods_name"]); ?>
                                    </td>
                                    <td><?php echo ($vo["goods_attr"]); ?></td>
                                    <td><?php echo ($vo["goods_number"]); ?></td>
                                    <td><?php echo ($vo["goods_price"]); ?></td>
                                    <td class="hidden-xs"><?php echo ($vo['goods_price']*$vo['goods_number']); ?></td>
                                    <td>
                                        <?php if($data["pay_status"] == 2): if($data['offline'] == 1 && $vo['offline'] == 1): ?>￥<?php echo ($vo['goods_price']*$vo['goods_number']); ?>
                                                <?php else: ?>
                                                --<?php endif; ?>
                                            <?php else: ?>
                                            --<?php endif; ?>
                                    </td>

                                    <td><?php echo ($vo["share_money"]); ?></td>
                                    <td><?php echo ($vo['share_money']*$vo['goods_number']); ?></td>
                                    <td><?php echo ($vo["share_card"]); ?></td>
                                    <td>
                                        <?php if(empty($vo['goods_shipping'])): ?>SF顺丰快递包邮
                                            <?php else: ?>
                                            <?php echo ($vo["goods_shipping"]); endif; ?>
                                    </td>
                                </tr>
                                
                                 <tr  class="class_hidden menu_p_c menu_p_<?php echo ($vo["rec_id"]); ?>" data="<?php echo ($vo["id"]); ?>" data-type="0">
                                    <td  style="    text-align: right;">
                                      ┗━
                                    </td>
                                   
                                    <td class="hidden-xs" colspan="10">  商品备注：<?php echo ($vo["seller_note"]); ?></td>
                                    
                                </tr><?php endforeach; endif; else: echo "" ;endif; ?>

                            </tbody>
                        </table>

                    </div>
                    <?php if($data["code"] == 'received'): ?><div class="row">
                            <div class="form-group">
                                <label class="control-label">
                                    <h4>订单返利：</h4>
                                </label>
                                <label class="control-label">
                                    <h4>平台总收益：<?php echo ($data["order_profit"]); ?>，</h4>
                                </label>
                                <?php if($data["profit_type"] == 1): ?><label class="control-label">
                                        <h4>平台返利收益：0，</h4>
                                    </label>
                                    <?php else: ?>
                                    <label class="control-label">
                                        <h4>平台返利收益：<?php echo ($discount_rebate-$data['order_rebate']); ?>，</h4>
                                    </label>
                                    <label class="control-label">
                                        <h4>会员返利总金额：<?php echo ($data["order_rebate"]); ?>，</h4>
                                    </label><?php endif; ?>
                                <label class="control-label">
                                    <h4>返利类型：
                                        <?php if(!empty($data['profit_type'])): if($data['profit_type'] == 2){ ?>
                                                VIP升级返利
                                            <?php }else{ ?>
                                                分享返利
                                            <?php } ?>
                                        <?php else: ?>
                                            正常返利<?php endif; ?>
                                    </h4>
                                </label>
                            </div>
                            <?php if(!empty($order_rebate_record)): ?><table class="table table-hover" id="sample-table-1">
                                    <thead>
                                    <tr>
                                        <th class="center">#</th>
                                        <th>用户卡号</th>
                                        <th>返利内容</th>
                                        <th>时间</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php if(is_array($order_rebate_record)): $key = 0; $__LIST__ = $order_rebate_record;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($key % 2 );++$key;?><tr>
                                            <td class="center">

                                            </td>
                                            <td>
                                                <?php echo ($vo["member_card"]); ?>
                                            </td>
                                            <td><?php echo ($vo["des"]); ?></td>
                                            <td><?php echo (date("Y-m-d H:i:s",$vo["add_time"])); ?></td>
                                            <td>
                                            </td>
                                        </tr><?php endforeach; endif; else: echo "" ;endif; ?>


                                    </tbody>
                                </table><?php endif; ?>
                        </div><?php endif; ?>

                    <div class="row">
                        <div class="form-group">
                            <label class="control-label">
                                <h4>订单日志</h4>
                            </label>
                        </div>
                        <table class="table table-hover" id="sample-table-1">
                            <thead>
                            <tr>
                                <th class="center">#</th>
                                <th>操作角色</th>
                                <th>操作内容</th>
                                <th>时间</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if(is_array($order_log)): $key = 0; $__LIST__ = $order_log;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($key % 2 );++$key;?><tr>
                                    <td class="center">

                                    </td>
                                    <td>
                                        <?php echo ($vo["log_role"]); ?>
                                    </td>
                                    <td><?php echo ($vo["log_msg"]); ?></td>
                                    <td><?php echo (date("Y-m-d H:i:s",$vo["log_time"])); ?></td>
                                    <td>
                                    </td>
                                </tr><?php endforeach; endif; else: echo "" ;endif; ?>


                            </tbody>
                        </table>

                    </div>
                    <div class="row">
                        <div class="form-group">
                            <label class="control-label">
                                <h4>配送状态</h4>
                            </label>
                        </div>
                        <table class="table table-hover" >
                            <thead>
                            <tr>
                                <th class="center">#</th>
                                <th>状态</th>
                                <th>时间</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if(is_array($status_before_deliver)): $key = 0; $__LIST__ = $status_before_deliver;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($key % 2 );++$key; if(($key) == "6"): ?><tr>
                                        <td class="center">

                                        </td>
                                        <td>备注:<?php echo ($vo["0"]); ?></td>
                                        <td></td>
                                        <td>
                                        </td>
                                    </tr>
                                    <?php else: ?>
                                    <tr>
                                        <td class="center">

                                        </td>
                                        <td><?php echo ($vo["0"]); ?></td>
                                        <td><?php echo (date("Y-m-d H:i:s",$vo["1"])); ?></td>
                                        <td>
                                        </td>
                                    </tr><?php endif; endforeach; endif; else: echo "" ;endif; ?>


                            </tbody>
                        </table>

                    </div>
					<div class="row">
						<div class="form-group">
							<label class="control-label">
								<h4>物流信息</h4>
							</label>
						</div>
						<?php if($order_express["status"] == 1): echo ($order_express["result"]); ?>
						<?php else: ?>
						<?php echo ($order_express["msg"]); endif; ?>
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

        $("#form2").validate({
            rules: {
                title: "required",
                name: "byteRangeLength"
            },
            messages: {
                title: "请输入菜单名称",
                name: "请输入菜单地址"
            }
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
 <script type="text/javascript">
    $(function(){
        $(".menu_p").click(function(){
            var data=$(this).attr("data");
            var data_type=$(this).attr("data-type");
            if(data_type==1){
                //当前展开  收起操作
                $(".menu_p_c").hide();
                $(this).attr("data-type",0);
                $(".angle_down_"+data).show();
                $(".angle_up_"+data).hide();
            }else{
               //当前收起 展开操作
               $(".menu_p_c").hide();
               $(".menu_p_"+data).show();
               $(this).attr("data-type",1);
               $(".angle_down_"+data).hide();
               $(".angle_up_"+data).show();
            }
            
        });
    });
</script> 
<!-- end: JavaScript Event Handlers for this page -->
</body>
</html>