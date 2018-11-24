<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8;" charset="UTF-8">
    <meta name="format-detection" content="telephone=no">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <title><?php echo T('南洋师生交流平台DEMO'); ?></title>
    <link href="<?php echo path_url('css/material-design-icons/3.0.1/material-icons.min.css'); ?>" rel="stylesheet">
    <link href=" <?php echo path_url('css/materialize/0.100.2/materialize_nofont.min.css'); ?>" rel="stylesheet"
          media="screen,projection">

    <script src="<?php echo path_url('js/jquery/3.2.1/jquery.min.js'); ?>"></script>
    <script src="<?php echo path_url('js/materialize/0.100.2/materialize.min.js'); ?>"></script>

    <link href="<?php echo path_url('css/diy.css'); ?>" rel="stylesheet">

</head>

<body>
<!--头开始-->
<header style="display: grid;">
    <nav style="opacity: 0;"></nav>
    <nav class="cyan darken-4" style="position: fixed; z-index: 2;"><!--导航栏语句开始-->
        <div class="nav-wrapper container"><!--导航栏内容开始-->
            <?php if (!isset($back) && back) : ?>
                <a href="javascript:void(0);" onclick="history.back();" class="button-collapse show-on-large"
                   style="float: left !important;">
                    <i class="material-icons">arrow_back</i></a><!--网页LOGO-->
            <?php endif; ?>
            <a href="./" class="brand-logo">LYiHo</a><!--网页LOGO-->
            <ul class="right">
                <?php if (DI()->config->get('sys.translate')): ?>
                    <li>
                        <a class="dropdown-button" data-constrainWidth="false" data-activates="language">
                            <i class="material-icons">translate</i>
                        </a>
                    </li>
                <?php endif; ?>
                <li>
                    <a class="dropdown-button" data-constrainWidth="false" data-activates="menu">
                        <i class="material-icons">perm_identity</i>
                    </a>
                </li>
            </ul>
        </div>
    </nav><!--导航栏语句结束-->

    <ul id="menu" class="dropdown-content">
        <?php if (isset($_SESSION["user_name"])) : ?>
            <!-- 用户登录后 -->
            <li>
                <a onClick="location.href='?service=User.edit_Member'">
                    <?php echo $_SESSION['user_name']; ?>
                </a>
            </li>
            <li class="divider"></li>
            <li>
                <a onClick="location.href='?service=Topic.create_Topic'">
                    <?php echo T('发帖'); ?>
                </a>
            </li>
            <li class="divider"></li>
            <li>
                <a onClick="location.href='?service=Default.deliveryList'">
                    <?php echo T('查询快递'); ?></a>
            </li>
            <li class="divider"></li>
            <?php if (version_compare(phpversion(), '5.4', '>=')) : ?>
                <!--<li>
					<a onClick="location.href='?service=Music.Index'">
						<?php /*echo T('音乐搜索器'); */ ?></a>
				</li>
				<li class="divider"></li>-->
            <?php endif; ?>
            <li>
                <a onClick="location.href='./tieba.php'">
                    <?php echo T('跳转签到站'); ?></a>
            </li>
            <li class="divider"></li>
            <li><a onclick="logoff()"><?php echo T('退出登录'); ?></a></li>
        <?php else : ?>
            <!-- 用户未登录 -->
            <li><a href="?service=User.Register"><?php echo T('注册'); ?></a></li>
            <li class="divider"></li>
            <li><a href="?service=User.Login"><?php echo T('登录'); ?></a></li>
        <?php endif; ?>
    </ul>
    <?php if (DI()->config->get('sys.translate')): ?>
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
    <?php endif; ?>

</header>
<!-- 头结束 -->

<!-- 正文内容开始 -->
<main id="Content" class="container">
