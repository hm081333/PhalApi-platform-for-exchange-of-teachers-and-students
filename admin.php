<?php
/**
 * $APP_NAME 统一入口
 */

defined('MODULE') || define('MODULE', 'admin');
defined('NOW_WEB_SITE') || define('NOW_WEB_SITE', (isset($_SERVER['HTTPS']) && 'on' === $_SERVER['HTTPS'] ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']);
defined('URL_ROOT') || define('URL_ROOT', (isset($_SERVER['HTTPS']) && 'on' === $_SERVER['HTTPS'] ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . (dirname($_SERVER['PHP_SELF']) == '\\' ? '' : dirname($_SERVER['PHP_SELF'])) . '/Public/');

require_once dirname(__FILE__) . '/Public/init.php';

//装载你的接口
DI()->loader->addDirs('Admin');
DI()->loader->addDirs('Common');

DI()->view = new View_Lite('Admin');

/** ---------------- 响应接口请求 ---------------- **/

$admin_token = DI()->cookie->get(ADMIN_TOKEN);
if (!empty($admin_token) && empty($_SESSION['admin_id'])) {
	$admin = unserialize(DI()->tool->decrypt($admin_token));
	if ($admin) {
		$_SESSION['admin_id'] = $admin['id'];
		$_SESSION['admin_name'] = $admin['user_name'];
		$_SESSION['admin_auth'] = $admin['auth'];
	}
}

//过滤后台未登陆的操作
if (empty($_SESSION['admin_id']) && !empty($_GET['service']) && $_GET['service'] != 'Public.setLanguage') {
	echo("<script>alert('" . T('未登录') . "');window.location.href='./admin.php'</script>");
	exit;
}

$api = new PhalApi();
$rs = $api->response();
$rs->output();
