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
								<div class="col-sm-8 col-md-offset-4">
									<h1 class="mainTitle"><?php echo ($msgtitle); ?></h1>
									<span class="mainDescription">&nbsp;&nbsp;&nbsp;&nbsp;账号信息</span>
								</div>
								
							</div>
						</section>
						<!-- end: PAGE TITLE -->
						<!-- start: YOUR CONTENT HERE -->
<div class="container-fluid container-fullw bg-white">
<form action="" role="form" id="form2" novalidate="novalidate" method="post">
<div class="row">
	<div class="col-md-6 col-md-offset-3">
	<div class="form-group">
		<label class="control-label">
			登录帐号 <span class="symbol required" aria-required="true"></span>
		</label>
		<?php if($data["id"] > 0): echo ($data["account"]); ?>
		<?php else: ?>
		<input type="text" placeholder="填写登录帐号" class="form-control" value="<?php echo ($data["account"]); ?>" id="account" name="account"><?php endif; ?>
	</div>
 <?php if(empty($data['id'])): ?><div class="form-group">
		<label>
			 密码
		</label>
		<input type="password" class="form-control" name="newapass" id="newapass">
	</div>
	<div class="form-group">
		<label>
			 重复密码
		</label>
		<input type="password" class="form-control" name="newapass2" id="newapass2">
	</div><?php endif; ?>
	<div class="form-group">
		<label class="control-label">
			昵称 <span class="symbol required" aria-required="true"></span>
		</label>
		<input type="text" placeholder="填写昵称" class="form-control" value="<?php echo ($data["name"]); ?>" id="name" name="name">
	</div>
    
    <div class="form-group">
		<label class="control-label">
			电话 
		</label>
		<input type="text" placeholder="填写电话" class="form-control" value="<?php echo ($data["mobile"]); ?>" id="mobile" name="mobile">
	</div>

	 <div class="form-group">
		<label class="control-label">
			邮箱  
		</label>
		<input type="text" placeholder="填写邮箱" class="form-control" value="<?php echo ($data["email"]); ?>" id="email" name="email">
	</div>

    <div class="form-group">
		<label class="control-label">
			帐号类型 <span class="symbol required" aria-required="true"></span>(帐号类型确定后不能修改)
		</label>
		
			<?php if($data["id"] > 0): if($data["type"] == 1): ?>管理员<?php else: ?>商家<?php endif; ?>
			<?php else: ?>
			   <select class="form-control" id="type" name="type">
			   	<?php if($_SESSION['topadmin'] > 0){ ?> 
			   	<option value="1">管理员</option>
			   	<?php } ?>
	            <option value="0">商家</option>
	           </select><?php endif; ?>
	</div>
<?php if($_SESSION['topadmin'] > 0){ ?> 	
  <div class="form-group">
		<label class="control-label">
			状态 <span class="symbol required" aria-required="true"></span>
		</label>
		<select class="form-control"   name="status">
			<?php if($data["id"] > 0): ?><option value="1" <?php if($data["status"] == 1): ?>selected<?php endif; ?> >正常</option>
            <option value="0" <?php if($data["status"] == 0): ?>selected<?php endif; ?> >冻结</option>
            <?php else: ?>
            <option value="1">正常</option>
            <option value="0">冻结</option><?php endif; ?>
		</select>
	</div>
<?php }else{ ?>
 
<?php if($_SESSION['auid']==$data['id'] && $data['fid']==$_SESSION['auid']){ ?> 	
<div class="form-group">
		<label class="control-label">
			状态 <span class="symbol required" aria-required="true"></span>
		</label>
		<?php echo ($data['status']?'正常':'冻结'); ?>
	</div>
<?php }else{ ?>

   <div class="form-group">
		<label class="control-label">
			状态 <span class="symbol required" aria-required="true"></span>
		</label>
		<select class="form-control"   name="status">
			<?php if($data["id"] > 0): ?><option value="1" <?php if($data["status"] == 1): ?>selected<?php endif; ?> >正常</option>
            <option value="0" <?php if($data["status"] == 0): ?>selected<?php endif; ?> >冻结</option>
            <?php else: ?>
            <option value="1">正常</option>
            <option value="0">冻结</option><?php endif; ?>
		</select>
	</div>
<?php } ?>

<?php } ?>
   <?php if($_SESSION['topadmin'] > 0): ?><div class="form-group">
			<label class="control-label">
				权限分组 <span class="symbol required" aria-required="true"></span>
			</label>
			<select class="form-control"   name="group">
				<?php if(is_array($grouplist)): $i = 0; $__LIST__ = $grouplist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>" <?php if($vo["id"] == $group_id): ?>selected<?php endif; ?> ><?php echo ($vo["title"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
			</select>
		</div>
	<?php else: ?>
<?php if($_SESSION['auid'] != $_SESSION['afid'] && $_SESSION['auid'] != $data['id']): ?><div class="form-group">
			<label class="control-label">
				权限分组 <span class="symbol required" aria-required="true"></span>
			</label>
			<select class="form-control"   name="group">
				<?php if(is_array($grouplist)): $i = 0; $__LIST__ = $grouplist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>" <?php if($vo["id"] == $group_id): ?>selected<?php endif; ?> ><?php echo ($vo["title"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
			</select>
		</div><?php endif; endif; ?>
	
	
</div>
</div>
<div class="row">
<div class="col-md-4 col-md-offset-5">
	<input type="hidden"  value="<?php echo ($data["id"]); ?>" name="id">
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
<?php if($data["id"] > 0): ?>
	<script>

		jQuery(document).ready(function() {
				//FormValidator.init();

	$("#form2").validate({
	  rules: {
	   name: "required",
		  email: "email",
		  mobile: "digits"
	  },

	  messages: {
	   name: "请输入昵称",
		  email:"请输入正确的邮箱格式",
		  mobile: "电话只能是整数"
	  },


	  });
	});
		</script>		
<?php else: ?>

	<script>
		// 验证电话号码
//		jQuery.validator.addMethod("mobile", function(value, element) {
//			return this.optional(element) || /^((d{3,4}-)|d{3.4}-)?d{7,8}$/.test(value);
//		},"电话号码格式不对");
jQuery(document).ready(function() {
				//FormValidator.init();

	$("#form2").validate({
	  rules: {
		  email: {
			  email:true
		  },
	   account:{
	   	required:true,
	   	remote: {
                        url: "<?php echo U('admin/System/cheack_admin');?>",
                        type: "get",
                        dataType: "json",
                        data: {
                            account: function () {
                                return $("#account").val();　　　　//这个是取要验证的密码
                            }
                        },
                        dataFilter: function (msg) {　　　　//判断控制器返回的内容
                            if (msg == "0") {
                                return true;
                            }else {
                                return false;
                            }
                        }
                    }
	   },
	   newapass: {
                    required: true,
                    rangelength: [6, 16]
                },
        newapass2: {
            required: true,
            rangelength: [6, 16],
            equalTo: "#newapass"
        },
	   mobile:{
		   digits: true,
//		   mobile:true

	   },
	   name: "required"
	  },
	  messages: {
	   account: {
	   	required:"请输入帐号",
	   	remote:"该账户已被占用"
	   },
	   newapass: {
                    required: "请填写密码",
                    rangelength: "密码长度在6-12位之间！"
                },
        newapass2: {
            required: "请填写确认密码！",
            rangelength: "密码长度在6-12位之间！",
            equalTo: "两次输入密码不一致！"
        },
		 email:{
			  email:"请输入正确的邮箱格式"
		  },
		mobile: {
			digits: "电话只能是整数"
		},
	   name: "请输入昵称"
	  }
	  });
	});
		</script><?php endif; ?>	
		<!-- end: JavaScript Event Handlers for this page -->
	</body>
</html>