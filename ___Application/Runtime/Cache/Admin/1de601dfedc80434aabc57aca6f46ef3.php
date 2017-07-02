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
        
<link href="__PUBLIC__/wap/css/icons.css" rel="stylesheet" type="text/css">
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
							<h1 class="mainTitle">售后信息管理</h1>
							<span class="mainDescription">订单信息</span>
						</div>
					</div>
				</section>
				
				<!-- end: PAGE TITLE -->
				<!-- start: YOUR CONTENT HERE -->
				<div class="container-fluid container-fullw bg-white">
					<div class="row margin-bottom-30" style="border:1px solid #e7e7e9;">
						<!-- 售后订单基本信息开始 -->
						<div class="col-md-12 no-padding border-right">
							<table class="table table-condensed table-hover no-margin" height="420px;">
								<tr>
									<td class="text-bold" style="width:85px;">订单状态：</td>
									<td class="text-right text-bold"><i class="text-orange fa fa-bell">&nbsp;<?php echo ($msg["show_status"]); ?></i></td>
								</tr>
								<tr>
									<td>订单总额：</td>
									<td><b class="text-red"> ￥<?php echo ($msg["order_amount"]); ?>
										[余额:<?php echo ($msg['surplus']); ?>
										积分:<?php echo ($msg['integral_money']); ?>(<?php echo ($msg['integral']); ?>积分)
										微信:<?php echo ($msg['order_amount'] - $msg['surplus'] - $msg['integral_money']); ?>
										] </b></td>
								</tr>
								<tr>
									<td>商品金额：</td>
									<td><b class="text-red">￥<?php echo ($msg["goods_price"]); ?></b></td>
								</tr>
                                <tr>
                                    <td>退单编号：</td>
                                    <td><?php echo ($msg["refund_sn"]); ?></td>
                                </tr>
								<tr>
									<td>订单编号：</td>
									<td><?php echo ($msg["order_sn"]); ?></td>
								</tr>
								<tr>
									<td>申请时间：</td>
									<td><?php echo (date('Y-m-d H:i:s',$msg["add_time"])); ?></td>
								</tr>
								<tr>
									<td height="60px">退货说明：</td>
									<td><?php echo ($msg["refund_remark"]); ?></td>
								</tr>
								<tr class="height-155">
									<td><img src="<?php echo ($msg["goods_image"]); ?>" class="img-rounded no-margin" alt="image" style="width:85px;height:85px;float:left;margin-left:10px;"></td>
									<td><p><?php echo ($msg["goods_name"]); ?></p>
										<p><?php echo ($msg["goods_attr"]); ?></p></td>
								</tr>
								<tr>
									<td>会员号：</td>
									<td><?php echo ($msg["member_card"]); ?></td>
								</tr>
								<tr>
									<td>会员名：</td>
									<td><?php echo ($msg["member_name"]); ?></td>
								</tr>
								<tr>
									<td>联系人：</td>
									<td><?php echo ($msg["refund_name"]); ?></td>
								</tr>
								<tr>
									<td>联系电话：</td>
									<td><?php echo ($msg["refund_tel"]); ?></td>
								</tr>
								<tr>
									<td>成交时间：</td>
									<td><?php echo (date('Y-m-d H:i:s',$msg["pay_time"])); ?></td>
								</tr>
							</table>
						</div>
						
						
						
						
						
						<div class="form-group">
							<label class="control-label">
							<h4>订单日志</h4>
							</label>
						</div>
						<table class="table table-hover" id="sample-table-1" height="20px" width="100%">
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
										<td class="center"></td>
										<td> <?php echo ($vo["log_role"]); ?> </td>
										<td><?php echo ($vo["log_msg"]); ?></td>
										<td><?php echo (date("Y-m-d H:i:s",$vo["log_time"])); ?></td>
										<td></td>
									</tr><?php endforeach; endif; else: echo "" ;endif; ?>
							</tbody>
						</table>
				
						
						
						
						
						
						
						<!-- 售后订单基本信息结束 -->
						<!-- 售后订单流程信息开始 -->
						<div class="col-md-12">
							<div class="height-230 border-bottom margin-top-30 ">
								<!--售后状态为待处理开始-->
								<?php if($msg['refund_status'] == 0 or $msg['refund_status'] == 10): ?><div>
										<h5 class="text-bold"><i class="fa fa-exclamation-circle"
                                                                 style="color:#007AFF;font-size: 30px; margin-right: 10px;"></i><?php echo ($msg["show_status"]); ?></h5>
										<p class="padding-bottom-30">
											<b>·</b>买家已申请售后,请您及时联系买家核实货物情况,主动与买家协商退款时宜,您的主动与理解,不但可以改善买家本次购物体验,也会增强买家对网购的信心,为您带来更多的交易机会! </p>
										<div style="text-align:center;" class="margin-top-30">
											<form method="post"
                                                  action="<?php echo U('admin/goodsorder/refundHandle',array('id'=>$msg.ref_id,'chat_id'=>$last_chat));?>">
												 若该商品售后需退还商品,需先退货
													<button class="btn btn-primary" id="Confirm" type="button"  > 同意退货 </button>
                                                <hr/>
                                                若该商品售后无需退还商品，则直接进行售后
													<button class="btn btn-primary" id="confirm_refund" type="button"  > 退款 </button>
													<button class="btn btn-primary" type="button" id="confirm_delivery"> 补发货 </button>
											</form>
										</div>
									</div>
									<!--售后状态为待处理结束-->
									<!--售后状态为退货并且等待商家退款开始 -->
									<?php elseif($msg["refund_status"] == 3): ?>
									<div class=" padding-left-30" style="width:70%">
										<h5 class="text-bold"><i class="fa fa-exclamation-circle"
                                                                 style="color:#007AFF;font-size: 30px; margin-right: 10px;"></i><?php echo ($msg["show_status"]); ?></h5>
										<div class="padding-bottom-30">
											<p><b>·</b>商家已收货,等待发货,或返款</p>
											<p><b>·</b>买家已使用<b class="text-red"><?php echo ($msg["invoice_no_name"]); ?></b>快递发出,单号为:<b
                                                    class="text-red"><?php echo ($msg["invoice_no_user"]); ?></b></p>
										</div>
										<div style="text-align:center;" class="margin-top-30">
											<button class="btn btn-primary" id="confirm_refund" type="button"> 同意退款 </button>
											<button class="btn btn-primary" type="button" id="confirm_delivery"> 补发货物 </button>
										</div>
									</div>
									<?php elseif($msg["refund_status"] == 6): ?>
									<div class=" padding-left-30" style="width:70%">
										<?php if($is_apply_check > 0): ?><h5 class="text-bold"><i class="fa fa-exclamation-circle"
                                                                     style="color:#007AFF;font-size: 30px; margin-right: 10px;"></i><?php echo ($msg["show_status"]); ?></h5>
											<?php else: ?>
											<h5 class="text-bold"><i class="fa fa-exclamation-circle"
                                                                 style="color:#007AFF;font-size: 30px; margin-right: 10px;"></i>退款申请被拒绝</h5>
											<div class="padding-bottom-30">
												<p class="padding-bottom-30">
													<b>拒绝理由:</b><?php echo ($deny_remarks); ?> </p>
											</div><?php endif; ?>
										<div style="text-align:center;" class="margin-top-30">
											<?php if($is_apply_check > 0): ?><button class="btn btn-primary" type="button"> <?php echo ($msg["show_status"]); ?> </button>
												<?php else: ?>
												退款存在问题，被拒绝
												<button class="btn btn-primary" id="confirm_refund" type="button"> 重新申请退款 </button>
												<button class="btn btn-primary" type="button" id="confirm_delivery"> 补发货物 </button><?php endif; ?>
										</div>
									</div>
									<!--售后状态为退货并且等待商家退款结束 -->
									<!--售后状态为换货并且等待商家收货开始 -->
									<?php elseif($msg["refund_status"] == 2): ?>
									<div class=" padding-left-30" style="width:70%">
										<h5 class="text-bold"><i class="fa fa-exclamation-circle"
                                                                 style="color:#007AFF;font-size: 30px; margin-right: 10px;"></i><?php echo ($msg["show_status"]); ?></h5>
										<div class="padding-bottom-30">
											<p><b>·</b>买家已发货,等待商家收货</p>
											<?php if(!empty($msg['invoice_no_name']) and !empty($msg['invoice_no_user'])): ?><p><b>·</b>买家已使用<b class="text-red"><?php echo ($msg["invoice_no_name"]); ?></b>快递发出,单号为:<b
                                                        class="text-red"><?php echo ($msg["invoice_no_user"]); ?></b></p><?php endif; ?>
										</div>
										<form method="post" action="<?php echo U('admin/goodsorder/refundHandle',array('id'=>$msg['ref_id'],'chat_id'=>$last_chat));?>">
											<div style="text-align:center;" class="margin-top-30">
												<button class="btn btn-primary" type="submit" name="status" value="2"> 商家确认收货 </button>
											</div>
										</form>
									</div>
									<!-- 售后状态为换货并且等待商家收货结束 -->
									<!-- 售后状态为发货,并且等等商家发货开始-->
									<!-- 售后状态为发货,并且等等商家收货结束-->
									<!-- 其它非商家操作信息开始-->
									<?php else: ?>
									<div class=" padding-left-30" style="width:70%">
										<h5 class="text-bold"><i class="fa fa-exclamation-circle" style="color:#007AFF;font-size: 30px; margin-right: 10px;"></i><?php echo ($msg["show_status"]); ?></h5>
										<div class="padding-bottom-30">
											<p><b>·</b>当前售后状态为<?php echo ($msg["show_status"]); ?></p>
										</div>
										<div style="text-align:center;" class="margin-top-30">
											<h1> <?php echo ($msg["show_status"]); ?> </h1>
										</div>
									</div>
									<!-- 其它费商家操作信息结束--><?php endif; ?>
                                <?php if($msg["refund_status"] != 5): ?><div style="text-align:center;" class="margin-top-30">
									<button class="btn btn-primary" id="Confirm_deny"  type="button" > 继续协商 </button>
								</div><?php endif; ?>
							</div>
														<div>
								<table width="100%"  >
									<tr class="border-bottom" >
										<td width="10%"></td>
										<td   >协商信息</td>
										<td class="border-left" width="30%" >图片信息</td>
									</tr>
									<?php if(is_array($list)): $key = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($key % 2 );++$key;?><tr class="border-bottom">
											<td><?php if($vo["type"] == 1): ?>买家 :
													<?php else: ?>
													客服 :<?php endif; ?></td>
											<td> <?php echo ($vo['content']); ?> </td>
											<td><?php if(!empty($vo['chat_img'])): if(is_array($vo['chat_img'])): $k = 0; $__LIST__ = $vo['chat_img'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($k % 2 );++$k;?><a href="<?php echo ($v); ?>" target="_blank">
															<div class="img-item fileinput " style="background-image:none;float: left"><img   class="img-rounded" src="<?php echo ($v); ?>" style="max-height: 100px;max-width: 100px;">
															</div>
														</a><?php endforeach; endif; else: echo "" ;endif; endif; ?></td>
										</tr><?php endforeach; endif; else: echo "" ;endif; ?>
								</table>
							</div>
						</div>
						<div class="clearfix"></div>
						<!-- 售后订单流程信息结束 -->
					</div>
                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label class="control-label">
                                    <h4>客户退货物流信息</h4>
                                </label>
                            </div>
                            <?php if($express_data['user']['status'] == 1): echo ($express_data['user']['result']); ?>
                                <?php else: ?>
                                <?php echo ($express_data['user']['msg']); endif; ?>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label class="control-label">
                                    <h4>补发货物流信息</h4>
                                </label>
                            </div>
                            <?php if($express_data['shop']['status'] == 1): echo ($express_data['shop']['result']); ?>
                                <?php else: ?>
                                <?php echo ($express_data['shop']['msg']); endif; ?>
                        </div>
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
<!-- end: JavaScript Event Handlers for this page -->
<script type="text/javascript">
    var chat_id = <?php echo ($last_chat); ?>;
    var goods_prince = <?php echo ($msg["goods_price"]); ?>;
    var wx_money = <?php echo ($msg['order_amount'] - $msg['surplus'] - $msg['integral_money']); ?>;
    $().ready(function(){
//退款
        $('#Confirm').click(function(){    //同意退货
            var content='<form class="form" method="post" action="<?php echo U('admin/goodsorder/refundHandle');?>?id=<?php echo ($msg["ref_id"]); ?>&chat_id='+chat_id+'">'+
                    '<div class="form-group">'+
                    '<label class="control-label">公司收货地址（收货人姓名、收货人电话等）</label>'+
                    '<textarea class="form-control autosize" id="send_address" name="send_address" data-autosize-on="true" value=""></textarea></div>'+
                    '<div class="form-group"><label class="control-label">备注</label>'+
                    '<textarea class="form-control autosize" id="shop_remark" name="shop_remark" data-autosize-on="true" value=""></textarea></div>'+
                    '<button class="btn btn-primary btn-wide pull-left col-md-offset-4" type="submit" name="status" value="1">'+
                    '提交 <i class="fa fa-arrow-circle-right"></i> </button>'+
                    '</form>';
            layer.open({
                type: 1,
                title: '请填写退货地址',
                shadeClose: true,
                shift: 2,
                shadeClose: true,
                area: ['380px', '300px'],
                content:content
            });
            $('.form').validate({
                rules: {
                    send_address: "required"
                },
                messages: {
                    send_address:"请输入退货地址"
                }
            })
        });

        $('#confirm_refund').click(function () {    //确认退款
            var content='<form class="form" method="post" action="<?php echo U('admin/goodsorder/refundHandle');?>?id=<?php echo ($msg["ref_id"]); ?>&chat_id='+chat_id+'">'+
                    '<div class="form-group">'+
                    '<label class="control-label">返还积分:</label>'+
                    '<input type="text" name="points"  id="points" placeholder="请输入返还的积分值" value=""/></div>'+
                    '<div class="form-group">'+
                    '<label class="control-label">返还余额:</label>'+
                    '<input type="text" name="balance"  id="balance" placeholder="请输入返还的余额" value=""/></div>'+
                    '<div class="form-group">'+
                    '<label class="control-label">微信退款:</label>'+
                    '<input type="text" name="wx_refund"  id="wx_refund" placeholder="请输入返还的微信金额" value=""/>'+
                    '<input type="hidden" name="is_refund"  id="is_refund" placeholder="是否需退货" value="<?php echo ($msg["is_refund"]); ?>"/></div>'+
                    '<div> *返还积分和余额的总价值不能超过订单总额</div>'+
                    '<div> 10积分=1元</div>'+
                    '<input type="hidden" name="total"  id="total" placeholder="返还总金额" value=""/>'+
                    '<div class="form-group">'+
                    '<label class="control-label">备注:</label>'+
                    '<input type="text" name="remarks1"  id="remarks1" placeholder="向财务说明退款信息" value=""/></div>'+
                    '<button class="btn btn-primary btn-wide pull-left col-md-offset-4" type="submit" name="status" value="4">'+
                    '提交 <i class="fa fa-arrow-circle-right"></i> </button>'+
                    '</form>';
            layer.open({
                type: 1,
                title: '请输入返还的金额',
                shadeClose: true,
                shift: 2,
                shadeClose: true,
                area: ['300px', '350px'],
                content:content
            });
            var total = 0;
            $('#balance ,#points ,#wx_refund').change(function () {
                var  balance_t = Number($('#balance').val());
                var  points_t = Number($('#points').val());
                var  wx_refund_t = Number($('#wx_refund').val());
                total =balance_t + wx_refund_t + parseFloat(points_t)/10 ;
                $('#total').val(total);
            });

            $('.form').validate({
                rules:{
                    points:{
                        max:goods_prince*10,
                        number: true,
                        min:0
                    },
                    balance:{
                        max:goods_prince,
                        number: true,
                        min:0
                    },
                    wx_refund:{
                        max:wx_money,
                        number: true,
                        min:0
                    },
                    total:{
                        required:true,
                        max:goods_prince,
                        number: true,
                        min:0.01
                    },
                    remarks:{
                        required:true
                    }
                },
                messages:{
                    points:{
                        max:'退款积分价值不能大于商品金额',
                        number:'输入积分数不合法',
                        min: '输入积分不合法'
                    },
                    balance:{
                        max:'退款金额不能大于商品金额',
                        number:'输入余额金额不合法',
                        min: '输入余额金额不合法'
                    },
                    wx_refund:{
                        max:'微信退款金额不能大于微信支付金额',
                        number:'输入微信金额不合法',
                        min: '输入微信金额不合法'
                    },
                    total:{
                        required:"请输入退款金额",
                        max:'退款总价值不能大于商品金额',
                        number:'输入金额不合法',
                        min: '输入金额不合法'
                    },
                    remarks:{
                        required:"请输入备注"
                    }
                }

            })

        });
        /////////////////////////////////////////////////////////////
//继续协商
        $('#Confirm_deny').click(function(){    //同意退货
            var content='<form class="form" method="post" action="<?php echo U('admin/goodsorder/refundHandle');?>?id=<?php echo ($msg["ref_id"]); ?>&chat_id='+chat_id+'">'+
                    '<div class="form-group"><label class="control-label">继续协商</label>'+
                    '<textarea class="form-control autosize" id="shop_remark" name="shop_remark" data-autosize-on="true" value=""></textarea></div>'+
                    '<button class="btn btn-primary btn-wide pull-left col-md-offset-4" type="submit" name="status" value="0">'+
                    '提交 <i class="fa fa-arrow-circle-right"></i> </button>'+
                    '</form>';
            layer.open({
                type: 1,
                title: '请填写协商内容',
                shadeClose: true,
                shift: 2,
                shadeClose: true,
                area: ['380px', '300px'],
                content:content
            });

            $('.form').validate({
                rules: {
                    shop_remark: "required"
                },
                messages: {
                    shop_remark:"请填写协商内容"
                }
            })
        });
        ////////////////////////////////////////////////////////////
        $('#confirm_delivery').click(function () {    //确认发货
            var content = '<form class="form" method="post" action="<?php echo U('admin/goodsorder/refundHandle');?>?id=<?php echo ($msg["ref_id"]); ?>&chat_id='+chat_id+'">' +
                    '<div class="form-group margin-top-20" style="text-align: center;">' +
                    '<select name="express_code" id="express_code" style="display: block;width:170px;margin:10px auto;">'+
                    '<option value="0">请选择快递公司</option>'+
                    '<?php if(is_array($express)): $i = 0; $__LIST__ = $express;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>'+
                    '<option value="<?php echo ($vo["shipping_id"]); ?>"><?php echo ($vo["shipping_name"]); ?></option>'+
                    '<?php endforeach; endif; else: echo "" ;endif; ?>'+
                    '</select>'+
                    //'<input type="text" name="invoice_no_shop_name" placeholder="快递名称" style="display: block;width:170px;margin:10px auto;" />' +
                    '<input type="text" name="invoice_no_shop" placeholder="请输入发货单号" style="display: block;width:170px;margin:10px auto;" />'+
                    '<button class="btn btn-primary btn-wide pull-left col-md-offset-4" type="submit" name="status" value="3">' +
                    '提交 <i class="fa fa-arrow-circle-right"></i> </button>' +
                    '</form>';
            layer.open({
                type: 1,
                title: '请输快递信息',
                shadeClose: true,
                shift: 2,
                shadeClose: true,
                area: ['400px', '250px'],
                content: content
            });
            $('.form').validate({
                rules:{
                    invoice_no_shop_name:'required',
                    invoice_no_shop:{
                        required:true,
                        rangelength:[10,14]
                    }
                },
                messages:{
                    invoice_no_shop_name:'请输入发货快递',
                    invoice_no_shop:{
                        required:'请输入快递单号',
                        rangelength:'请输入正确的快递单号'
                    }
                }
            })
        });
    });


    //图片上传
    $(document).ready(function (e) {
        ///上传商家logo
        $('.upload_file').on('change','.upload_input', function () {
            var goods_length=$(".refund_img").length;
            if(goods_length>=3){
                layer.msg('图片最多上传3张', {icon: 5});
            }else{
                ajaxFileUploadMore('url',"<?php echo U('admin/Imagecat/upload_ajax');?>");
            }
        });

        $('.upload_file').on('click','.delete_img', function () {
            var __thiss=$(this);
            layer.confirm('确定删除该图片？', {
                btn: ['确定','取消'] //按钮
            },function(){
                __thiss.remove();
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
                            $('.upload_file').prepend(str_img);
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
</body></html>