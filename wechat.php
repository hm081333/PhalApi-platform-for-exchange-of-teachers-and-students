<?php
/**
 * Created by PhpStorm.
 * User: LYi-Ho
 * Date: 2017/6/23
 * Time: 下午 10:09
 */
define('IS_JSON', true);

//echo $_GET['echostr'];
//die();

$_SERVER["HTTP_ACCEPT_ENCODING"] = ''; // gzip判断
$GLOBALS['HTTP_RAW_POST_DATA'] = file_get_contents("php://input"); // PHP7.0
if (!isset($GLOBALS['HTTP_RAW_POST_DATA'])) {
    die('Access denied!');
}
defined('NOW_WEB_SITE') || define('NOW_WEB_SITE', (isset($_SERVER['HTTPS']) && 'on' === $_SERVER['HTTPS'] ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']);
defined('URL_ROOT') || define('URL_ROOT', (isset($_SERVER['HTTPS']) && 'on' === $_SERVER['HTTPS'] ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . (dirname($_SERVER['PHP_SELF']) == '\\' ? '' : dirname($_SERVER['PHP_SELF'])) . '/Public/');

require_once dirname(__FILE__) . '/Public/init.php';

//装载项目代码和扩展类库
DI()->loader->addDirs(array('Common', 'Library'));

/** ---------------- 微信轻聊版 ---------------- **/
$setting = Domain_Setting::getSetting('wechat');
$token = isset($setting['token']) ? $setting['token'] : '';// 配置的Token
$wechat=new Domain_Wechat();

$robot = new Wechat_Lite($token, true);
$rs = $robot->response();
$rs->output();