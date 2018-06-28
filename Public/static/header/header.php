<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8;" charset="UTF-8">
    <meta name="format-detection" content="telephone=no">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <title><?php echo T('南洋师生交流平台DEMO'); ?></title>
    <!--压缩所有css并加载-->
    <?php
    
    use MatthiasMullie\Minify;
    
    if (DI()->debug || !file_exists(DI()->tool->staticTruePath('css/mini.css'))) {
        //css压缩到一个文件
        $minifier_css = new Minify\CSS();
        $minifier_css->add(DI()->tool->staticTruePath('css/material-design-icons/3.0.1/material-icons.min.css'));
        $minifier_css->add(DI()->tool->staticTruePath('css/materialize/0.100.2/materialize_nofont.min.css'));
        $minifier_css->add(DI()->tool->staticTruePath('css/diy.css'));
        $minifier_css->minify(DI()->tool->staticTruePath('css/mini.css'));
    }
    if (DI()->debug || !file_exists(DI()->tool->staticTruePath('js/mini.js'))) {
        //js压缩到一个文件
        $minifier_js = new Minify\JS();
        $minifier_js->add(DI()->tool->staticTruePath('js/jquery/3.2.1/jquery.min.js'));
        $minifier_js->add(DI()->tool->staticTruePath('js/materialize/0.100.2/materialize.min.js'));
        $minifier_js->add(DI()->tool->staticTruePath('js/page.js'));
        $minifier_js->add(DI()->tool->staticTruePath('js/diy.js'));
        $minifier_js->minify(DI()->tool->staticTruePath('js/mini.js'));
    }
    ?>
    <link href="<?php echo DI()->tool->staticPath('css/mini.css') ?>" rel="stylesheet" media="screen,projection">
    <script src="<?php echo DI()->tool->staticPath('js/mini.js'); ?>"></script>
    <script>
        window.NOW_WEB_SITE = '<?php echo NOW_WEB_SITE; ?>';
        window.URL_ROOT = '<?php echo URL; ?>';
    </script>
</head>

<body>
<!--头开始-->
<header style="display: grid;">
    <nav style="opacity: 0;"></nav>
    <nav class="cyan darken-4" style="position: fixed; z-index: 2;"><!--导航栏语句开始-->
        <div class="nav-wrapper container"><!--导航栏内容开始-->
            <?php if (!isset($back) && back) : ?>
                <a href="javascript:;" class="show-on-large btn-back hide"
                   style="float: left !important;">
                    <i class="material-icons">arrow_back</i></a><!--网页LOGO-->
            <?php endif; ?>
            <a href="#index" class="brand-logo btn-link">LYiHo</a><!--网页LOGO-->
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
                <a class="btn-link" href="#edit_member">
                    <?php echo $_SESSION['user_name']; ?>
                </a>
            </li>
            <li class="divider"></li>
            <li>
                <a class="btn-link" href="#create_topic">
                    <?php echo T('发帖'); ?>
                </a>
            </li>
            <li class="divider"></li>
            <li>
                <a class="btn-link" href="#delivery_list">
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
            <li><a href="?service=User.register"><?php echo T('注册'); ?></a></li>
            <li class="divider"></li>
            <li><a class="btn-link" href="#login"><?php echo T('登录'); ?></a></li>
        <?php endif; ?>
    </ul>
    <?php if (DI()->config->get('sys.translate')): ?>
        <ul id="language" class="dropdown-content">
            <li>
                <a class="btn-ajax lang" data-service="Public.SetLanguage" data-language="zh_cn">
                    简体中文
                </a>
            </li>
            <li class="divider"></li>
            <li>
                <a class="btn-ajax lang" data-service="Public.SetLanguage" data-language="zh_tw">
                    繁體中文
                </a>
            </li>
            <li class="divider"></li>
            <li>
                <a class="btn-ajax lang" data-service="Public.SetLanguage" data-language="en">
                    English
                </a>
            </li>
            <li class="divider"></li>
            <li>
                <!--de 德标 at 奥地利 ch 瑞士 ru 俄罗斯(欧境)-->
                <a class="btn-ajax lang" data-service="Public.SetLanguage" data-language="de">
                    Deutsch
                </a>
            </li>
            <li class="divider"></li>
            <li>
                <!--fr 法标 lu 卢森堡-->
                <a class="btn-ajax lang" data-service="Public.SetLanguage" data-language="fr">
                    Français
                </a>
            </li>
        </ul>
    <?php endif; ?>

</header>
<!-- 头结束 -->

<!-- 正文内容开始 -->
<main id="Content" class="container">
