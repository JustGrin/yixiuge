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
									 <h1 class="mainTitle">产品管理列表</h1>
									<span class="mainDescription">&nbsp;&nbsp;产品管理 </span>
								</div>
								<nav id="cl-effect-10" class="links cl-effect-10">
								<a data-hover="新增" href="<?php echo U('admin/upgrade/goods_edit');?>"><span>新增</span></a>

								</nav>
								<!-- <ol class="breadcrumb">
									<li>
										<span>Pages</span>
									</li>
									<li class="active">
										<span>Blank Page</span>
									</li>
								</ol> -->
							</div>
						</section>
						<!-- end: PAGE TITLE -->
						<!-- start: YOUR CONTENT HERE -->
<style type="text/css">
	.class_hidden{display:none;}
   .panel-body{padding: 5px;}
   .panel-heading{padding: 5px;min-height: 25px;}
</style>						
<div class="row">
<div class="col-lg-12 col-md-12 margin-top-20" >
	<div class="panel panel-white">
		<div class="panel-heading">
			<h5 class="panel-title">筛选</h5>
		</div>
	<form role="form" class="form-inline">	
		<div class="panel-body">
			<div class="form-group">
				<label for="goodsNumList" class="sr-only">
					商品货号
				</label>
				<select class="form-control" id="goodsNumList" name="goodsNum">
					<option value="0">-全部货号-</option>
					<?php if(is_array($goodsNumList)): $i = 0; $__LIST__ = $goodsNumList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><option value="<?php echo ($val["goods_sn"]); ?>" <?php if($_GET['goodsNum'] == $val['goods_sn']): ?>selected<?php endif; ?>><?php echo ($val["goods_sn"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
				</select>
			</div>
				<div class="form-group">
					  <label for="goods_name" class="sr-only">
						产品名称
					  </label>
						<input type="text" name="goods_name" value="<?php echo ($_GET["goods_name"]); ?>" id="goods_name" placeholder="产品名称" class="form-control">
				</div>
			    <div class="form-group">
					  <label for="goods_sn" class="sr-only">
						商品货号
					  </label>
						<input type="text" name="goods_sn" value="<?php echo ($_GET["goods_sn"]); ?>" id="goods_sn" placeholder="商品货号" class="form-control">
				</div>
				 <div class="form-group">
					<label for="is_on_sale" class="sr-only">
						
					</label>
					
					<select class="form-control" id="is_on_sale" name="is_on_sale">
<option value="all" <?php if($_GET['is_on_sale'] =='all'): ?>selected<?php endif; ?>>全部</option>
<option value="1" <?php if($_GET['is_on_sale'] =='1'): ?>selected<?php endif; ?>>上架</option>
<option value="0" <?php if($_GET['is_on_sale'] ==='0'): ?>selected<?php endif; ?>>下架</option>


					</select>
				</div>
				 <div class="form-group">
					<label for="is_on_sale" class="sr-only">
						
					</label>
					
					<select class="form-control" id="offline" name="offline">
<option value="all" <?php if($_GET['offline'] =='all'): ?>selected<?php endif; ?>>全部</option>
<option value="1" <?php if($_GET['offline'] =='1'): ?>selected<?php endif; ?>>货到付款</option>
<option value="0" <?php if($_GET['offline'] ==='0'): ?>selected<?php endif; ?>>非货到付款</option>


					</select>
				</div>		
				 <div class="form-group">
					<label for="is_on_sale" class="sr-only">
						
					</label>
					
					<select class="form-control" id="is_auditing" name="is_auditing">
<option value="all" <?php if($_GET['is_auditing'] =='all'): ?>selected<?php endif; ?>>全部</option>
<option value="1" <?php if($_GET['is_auditing'] =='1'): ?>selected<?php endif; ?>>已审核</option>
<option value="0" <?php if($_GET['is_auditing'] ==='0'): ?>selected<?php endif; ?>>未审核</option>


					</select>
				</div>	

				<div class="form-group  ">
			        <button class="btn btn-primary" type="submit">
					 提交
				    </button>
			    </div>
		</div>
    </form>
	</div>
</div>
		</div>
<table class="table table-hover" id="sample-table-1">
				<thead>
					<tr>
						<th class="center">#</th>
						<th>商品名称</th>
						<th class="hidden-xs">商品货号</th>
						<th>进价</th>
						<th>卖价</th>
						<!--<th>分享价</th>-->
						<th>上/下架</th>
						<th>审核状态</th>
						<!--<th>套餐</th>-->
						<!--<th>货到付款</th>-->
						<!--<th>新品</th>-->
						<!--<th>热销</th>-->
						<!--<th>首页显示</th>-->
						<th>库存</th>
						<th class="center">操作</th>
					</tr>
				</thead>
				<tbody>
        <?php if(is_array($list['list'])): $key = 0; $__LIST__ = $list['list'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($key % 2 );++$key;?><tr  >
						<td class="center"><img src="<?php echo ($vo["goods_img"]); ?>" class="img-rounded" style="max-width:50px;max-height:50px;" alt="无图片"></td>
						<td> 
							<?php echo ($vo["goods_name"]); ?>
						</td>
						<td class="hidden-xs"><?php echo ($vo["goods_sn"]); ?></td>
						<td ><?php echo ($vo["purchase_price"]); ?></td>
						<td ><?php echo ($vo["shop_price"]); ?></td>
						<!--<td ><?php echo ($vo["share_money"]); ?></td>-->
						<td id="is_sale<?php echo ($vo["goods_id"]); ?>"><?php if($vo["is_on_sale"] == 1): ?><a href="javascript:void(0)"
							class="btn btn-transparent btn-xs"  tooltip="Edit"  tooltip-placement="top"
							data-toggle="tooltip" onclick="changeStatus(<?php echo ($vo["goods_id"]); ?>,<?php echo ($vo["is_on_sale"]); ?>)"
							data-original-title="下架"><i class="fa fa-check-circle" ></i></a>
							<?php else: ?>
							<a href="javascript:void(0)"
							class="btn btn-transparent btn-xs"  tooltip="Edit"  tooltip-placement="top"
							data-toggle="tooltip" onclick="changeStatus(<?php echo ($vo["goods_id"]); ?>,<?php echo ($vo["is_on_sale"]); ?>)"
							data-original-title="上架"><i class="fa fa-check-circle" style="color:#ccc;"  value="1"></i></a><?php endif; ?>
							</td>
						<td ><?php echo ($vo['is_auditing']?'已审核':'未审核'); ?></td>
						<!--<td >-->
							<!--<?php if($vo["is_package"] == 1): ?>-->
							<!--<a href="javascript:void(0)"-->
							   <!--class="btn btn-transparent btn-xs"  tooltip="Edit"  tooltip-placement="top"-->
							   <!--data-toggle="tooltip" -->
							  <!--><!--<i class="fa fa-check-circle"></i></a>-->
							  <!--<?php else: ?>-->
							  <!--<a href="javascript:void(0)"-->
							   <!--class="btn btn-transparent btn-xs"  tooltip="Edit"  tooltip-placement="top"-->
							   <!--data-toggle="tooltip" -->
							  <!--><!--<i class="fa fa-check-circle" style="color:#ccc;"  value="1"></i></a>-->
                               <!--<?php endif; ?>-->
							<!--</td>-->
							<!--<td >-->
							<!--<?php if($vo["offline"] == 1): ?>-->
							<!--<a href="javascript:void(0)"-->
							   <!--class="btn btn-transparent btn-xs"  tooltip="Edit"  tooltip-placement="top"-->
							   <!--data-toggle="tooltip" -->
							  <!--><!--<i class="fa fa-check-circle"></i></a>-->
							  <!--<?php else: ?>-->
							  <!--<a href="javascript:void(0)"-->
							   <!--class="btn btn-transparent btn-xs"  tooltip="Edit"  tooltip-placement="top"-->
							   <!--data-toggle="tooltip" -->
							  <!--><!--<i class="fa fa-check-circle" style="color:#ccc;"  value="1"></i></a>-->
                               <!--<?php endif; ?>-->
							<!--</td>-->
						<!--<td ><?php echo ($vo['is_new']?'<i class="fa fa-check-circle"></i>':'<i class="fa fa-minus-circle"></i>'); ?></td>-->
						<!--<td ><?php echo ($vo['is_hot']?'<i class="fa fa-check-circle"></i>':'<i class="fa fa-minus-circle"></i>'); ?></td>-->
						<!--<td id="show<?php echo ($vo["goods_id"]); ?>">-->
							<!--<?php if($vo["is_show_index"] == 1): ?>-->
							<!--<a href="javascript:void(0)"-->
							   <!--class="btn btn-transparent btn-xs"  tooltip="Edit"  tooltip-placement="top"-->
							   <!--data-toggle="tooltip" onclick="changeShow(<?php echo ($vo["goods_id"]); ?>,<?php echo ($vo["is_show_index"]); ?>)"-->
							   <!--data-original-title="不显示首页"><i class="fa fa-check-circle" ></i></a>-->
							<!--<?php else: ?>-->
							<!--<a href="javascript:void(0)"-->
							   <!--class="btn btn-transparent btn-xs"  tooltip="Edit"  tooltip-placement="top"-->
							   <!--data-toggle="tooltip" onclick="changeShow(<?php echo ($vo["goods_id"]); ?>,<?php echo ($vo["is_show_index"]); ?>)"-->
							   <!--data-original-title="显示首页"><i class="fa fa-check-circle" style="color:#ccc;"  value="1"></i></a>-->
							<!--<?php endif; ?>-->
						<!--</td>-->
						<td ><?php echo ($vo["goods_number"]); ?></td>
						<td class="center">
						<div class="visible-md visible-lg hidden-sm hidden-xs">
							<a href="<?php echo U("Wap/Goods/Goods_detail",array('id'=>$vo['goods_id']));?>"
							class="btn btn-transparent btn-xs"  tooltip="Edit"  tooltip-placement="top"
							data-toggle="tooltip" data-original-title="查看" target="_blank">
							<i class="fa fa-file-text-o"></i></a>

                            <a href="<?php echo U("Admin/Upgrade/Goods_edit",array('id'=>$vo['goods_id']));?>"
							class="btn btn-transparent btn-xs"  tooltip="Edit"  tooltip-placement="top"
							data-toggle="tooltip" data-original-title="编辑">
							<i class="fa fa-pencil"></i></a>

                            <a href="<?php echo U("Admin/Upgrade/Goods_del",array('id'=>$vo['goods_id']));?>"
							class="btn btn-transparent btn-xs"  tooltip="Edit"  tooltip-placement="top"
							data-toggle="tooltip" onclick="return confirm('确取删除该商品？')"
							data-original-title="删除"><i class="fa fa-trash-o"></i></a>
						</div>
						</td>
					</tr><?php endforeach; endif; else: echo "" ;endif; ?>

		
				</tbody>
			</table>


 <div class="col-md-12 pages" align="center"><?php echo (($list['page'])?($list['page']):'暂无数据'); ?></div>


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
  
        
	</body>
<script type="text/javascript">
	function changeStatus(id,value){
		if(value==0){
			value =1;
			var sale='上架';
		}else{
			value=0;
			var sale='下架';
		}
		if(confirm('确定'+sale+'?')){
			$.ajax({
				url:'<?php echo U("Admin/Upgrade/is_on_sale");?>',
				type:'get',
				data:'id='+id+'&value='+value,
				dataType:'json',
				success:function(data){
					if(data.status==1){
						$('#is_sale'+id).empty();
						if(value){
							var content = '<a href="javascript:void(0)" class="btn btn-transparent btn-xs" tooltip = "Edit" tooltip - placement = "top" data - toggle = "tooltip" onclick = "changeStatus('+id+','+value+')" data - original - title = "'+sale+'" > <i class="fa fa-check-circle" ></i></a>'
						}else{
							var content = '<a href="javascript:void(0)" class="btn btn-transparent btn-xs" tooltip = "Edit" tooltip - placement = "top" data - toggle = "tooltip" onclick = "changeStatus('+id+','+value+')" data - original - title = "'+sale+'" > <i class="fa fa-check-circle" style="color:#ccc;"  value="1"></i></a>'
						}
						$('#is_sale'+id).append(content);
						layer.msg(sale+'成功',{icon:6});
					}else{
						layer.msg(data.error,{icon:5});
					}
				}
			})
		}
	}
	function changeShow(id,value){
		if(value==0){
			value =1;
			var sale='显示';
		}else{
			value=0;
			var sale='不显示';
		}
		if(confirm('确定'+sale+'?')){
			$.ajax({
				url:'<?php echo U("Admin/Upgrade/is_show_index");?>',
				type:'get',
				data:'id='+id+'&value='+value,
				dataType:'json',
				success:function(data){
					if(data.status==1){
						$('#show'+id).empty();
						if(value){
							var content = '<a href="javascript:void(0)" class="btn btn-transparent btn-xs" tooltip = "Edit" tooltip - placement = "top" data - toggle = "tooltip" onclick = "changeShow('+id+','+value+')" data - original - title = "'+sale+'" > <i class="fa fa-check-circle" ></i></a>'
						}else{
							var content = '<a href="javascript:void(0)" class="btn btn-transparent btn-xs" tooltip = "Edit" tooltip - placement = "top" data - toggle = "tooltip" onclick = "changeShow('+id+','+value+')" data - original - title = "'+sale+'" > <i class="fa fa-check-circle" style="color:#ccc;"  value="1"></i></a>'
						}
						$('#show'+id).append(content);
						layer.msg(sale+'成功',{icon:6});
					}else{
						layer.msg(data.error,{icon:5});
					}
				}
			})
		}
	}
</script>
</html>