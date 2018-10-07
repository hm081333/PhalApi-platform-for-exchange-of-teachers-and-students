<?php
/**
 * Created by PhpStorm.
 * User: LYi-Ho
 * Date: 2018/7/21
 * Time: 23:06
 */

$REQUEST_URI = $_SERVER['REQUEST_URI'];
$REQUEST_URIs = explode('?', $REQUEST_URI);
$REQUEST_URI = $REQUEST_URIs[0];
$module = substr($REQUEST_URI, 1);
defined('MODULE') || define('MODULE', $module == '' ? 'bbs' : $module);
require_once dirname(__FILE__) . '/init.php';

//装载你的接口
DI()->loader->addDirs(ucfirst(MODULE));
DI()->loader->addDirs('Common');

DI()->view = new View_Lite(ucfirst(MODULE));

/** ---------------- 响应接口请求 ---------------- **/

if (MODULE == 'admin') {
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
        echo("<script>alert('" . T('未登录') . "');window.location.href='./admin'</script>");
        exit;
    }
} else {
    $user_token = DI()->cookie->get(USER_TOKEN);
    if (!empty($user_token) && empty($_SESSION['user_id'])) {
        $user = Domain_User::user();
        if ($user) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['user_name'];
            $_SESSION['user_auth'] = $user['auth'];
        }
    }
}

$api = new PhalApi();
$rs = $api->response();
$rs->output();
