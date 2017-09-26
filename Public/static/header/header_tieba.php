<html>

<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8;" charset="UTF-8">
	<!--识别浏览设备-->
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
	<title><?php echo T('贴吧签到DEMO') ?></title>
	<!--<link href="<?php echo URL_ROOT; ?>static/css/material-design-icons/3.0.1/material-icons.min.css" rel="stylesheet">-->
	<link href="<?php echo URL_ROOT; ?>static/css/materialize/0.99.0/materialize_nofont.min.css" type="text/css"
		  rel="stylesheet" media="screen,projection">
	<!--<script type="text/javascript" src="<?php echo URL_ROOT; ?>static/js/jquery/3.2.1/jquery.min.js"></script>-->
	<!--<script type="text/javascript" src="<?php echo URL_ROOT; ?>static/js/materialize/0.99.0/materialize.min.js"></script>-->
	<link href="https://cdn.bootcss.com/material-design-icons/3.0.1/iconfont/material-icons.min.css" rel="stylesheet">
	<!--<link href="https://cdn.bootcss.com/materialize/0.100.2/css/materialize.min.css" rel="stylesheet" media="screen,projection">-->
	<script src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>
	<script src="https://cdn.bootcss.com/materialize/0.100.2/js/materialize.min.js"></script>
	<link href="<?php echo URL_ROOT; ?>static/css/diy.css" rel="stylesheet">
	<script>
		$(document).ready(function () {
			$(".button-collapse").sideNav();
		})
	</script>
</head>

<body>
<header>
	<nav class="cyan darken-4">
		<!--导航栏语句开始-->
		<div class="nav-wrapper container">
			<!--导航栏内容开始-->
			<!-- 头开始 -->
			<?php if (isset($_SESSION["user_id"])) : //判断用户是否登录，从而显示不同的导航界面 ?>
				<!-- 用户登录后 -->
				<a href="#" data-activates="slide-out" class="button-collapse show-on-large">
					<i class="material-icons">menu</i>
				</a>
				<ul id="slide-out" class="side-nav">
					<li class="no-padding">
						<ul class="collapsible collapsible-accordion">
							<li>
								<div class="userView">
									<div class="background">
										<img src="<?php echo URL_ROOT; ?>static/images/office.jpg">
									</div>
									<a><img class="circle" src="<?php echo URL_ROOT; ?>static/images/user.jpg"></a>
									<a><span class="white-text name"><?php echo T('会员：') ?><?php echo $_SESSION['user_name']; ?></span></a>
								</div>
							</li>
						</ul>
						<ul class="collapsible collapsible-accordion">
							<li>
								<div class="divider"></div>
							</li>
						</ul>
						<ul class="collapsible collapsible-accordion">
							<li>
								<a class="bold collapsible-header waves-effect waves-teal"><?php echo T('贴吧签到管理') ?><i
											class="material-icons">arrow_drop_down</i></a>
								<div class="collapsible-body">
									<ul>
										<li>
											<a class="waves-effect waves-teal"
											   href="?service=Default.Index"><?php echo T('BDUSS列表') ?></a>
										</li>
										<li>
											<a class="waves-effect waves-teal"
											   href="?service=Tieba.AddBduss"><?php echo T('添加BUDUSS') ?></a>
										</li>
										<li>
											<a class="waves-effect waves-teal"
											   href="?service=Tieba.TiebaList"><?php echo T('贴吧列表') ?></a>
										</li>
										<li>
											<a class="waves-effect waves-teal"
											   href="?service=User.admin_list"><?php echo T('管理管理员') ?></a>
										</li>

									</ul>
								</div>
							</li>
						</ul>
						<ul class="collapsible collapsible-accordion">
							<li>
								<a class="bold collapsible-header waves-effect waves-teal"><?php echo T('帖子管理') ?><i
											class="material-icons">arrow_drop_down</i></a>
								<div class="collapsible-body">
									<ul>
										<li>
											<a class="waves-effect waves-teal"
											   href="?service=Topic.create_Topic"><?php echo T('添加新帖子') ?></a>
										</li>
										<li>
											<a class="waves-effect waves-teal"
											   href="?service=Topic.topic_List"><?php echo T('管理帖子') ?></a>
										</li>
									</ul>
								</div>
							</li>
						</ul>
						<ul class="collapsible collapsible-accordion">
							<li>
								<a class="bold collapsible-header waves-effect waves-teal"><?php echo T('分类管理') ?><i
											class="material-icons">arrow_drop_down</i></a>
								<div class="collapsible-body">
									<ul>
										<li>
											<a class="waves-effect waves-teal"
											   href="?service=Class.create_Class"><?php echo T('添加分类') ?></a>
										</li>
										<li>
											<a class="waves-effect waves-teal"
											   href="?service=Class.class_List"><?php echo T('管理分类') ?></a>
										</li>
									</ul>
								</div>
							</li>
						</ul>
						<ul class="collapsible collapsible-accordion">
							<li>
								<a class="bold collapsible-header waves-effect waves-teal"><?php echo T('系统设置') ?><i
											class="material-icons">arrow_drop_down</i></a>
								<div class="collapsible-body">
									<ul>
										<li>
											<a class="waves-effect waves-teal"
											   href="?service=Default.email_config"><?php echo T('配置邮箱') ?></a>
										</li>
										<li>
											<a class="waves-effect waves-teal"
											   href="?service=Default.smsbao_config"><?php echo T('配置短信宝') ?></a>
										</li>
										<li>
											<a class="waves-effect waves-teal"
											   href="?service=System.backup"><?php echo T('备份还原') ?></a>
										</li>
										<li>
											<a class="waves-effect waves-teal"
											   href="?service=System.reset"><?php echo T('重置系统') ?></a>
										</li>
									</ul>
								</div>
							</li>
						</ul>
						<ul class="collapsible collapsible-accordion">
							<li>
								<div class="divider"></div>
							</li>
						</ul>
						<ul class="collapsible collapsible-accordion">
							<li>
								<a class="subheader"><?php echo T('退出登陆') ?></a>
							</li>
							<li>
								<a class="waves-effect waves-teal" onclick="logoff()"><?php echo T('退出登陆') ?></a>
							</li>
						</ul>
					</li>
				</ul>
			<?php endif; ?>
			<a href="./tieba.php" class="center brand-logo"><?php echo T('首页') ?></a>
			<ul class="right">
				<?php if (DI()->config->get('sys.translate')): ?>
					<li>
						<a class="dropdown-button" data-constrainWidth="false" data-activates="language">
							<i class="material-icons">translate</i>
						</a>
					</li>
				<?php endif; ?>
			</ul>
			<ul id="language" class="dropdown-content">
				<li>
					<a onclick="javascript:set_language('zh_cn')"><?php echo T('简体中文'); ?></a>
				</li>
				<li class="divider"></li>
				<li>
					<a onclick="javascript:set_language('zh_tw')"><?php echo T('繁体中文'); ?></a>
				</li>
				<li class="divider"></li>
				<li>
					<a onclick="javascript:set_language('en')"><?php echo T('英语'); ?></a>
				</li>
				<li class="divider"></li>
				<li>
					<!--de 德标 at 奥地利 ch 瑞士 ru 俄罗斯(欧境)-->
					<a onclick="javascript:set_language('de')"><?php echo T('德语'); ?></a>
				</li>
				<li class="divider"></li>
				<li>
					<!--fr 法标 lu 卢森堡-->
					<a onclick="javascript:set_language('fr')"><?php echo T('法语'); ?></a>
				</li>
			</ul>


		</div>
	</nav>
	<!--导航栏语句结束-->
</header>
<!-- 头结束 -->

<!-- 正文内容开始 -->
<main id="Content" class="container">
