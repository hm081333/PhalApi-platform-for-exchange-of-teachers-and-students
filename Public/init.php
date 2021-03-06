<?php
/**
 * 统一初始化
 */

/** ---------------- 根目录定义，自动加载 ---------------- **/

//开启GZIP
if (!headers_sent() && extension_loaded("zlib") && strstr($_SERVER["HTTP_ACCEPT_ENCODING"], "gzip")) {//开启gzip压缩
    ini_set('zlib.output_compression', 'On');
    ini_set('zlib.output_compression_level', '6');
}

date_default_timezone_set('Asia/Shanghai');

defined('PUB_ROOT') || define('PUB_ROOT', dirname(__FILE__) . '/');
defined('API_ROOT') || define('API_ROOT', dirname(__FILE__) . '/..');
// defined('URL') || define('URL', (isset($_SERVER['HTTPS']) && 'on' === $_SERVER['HTTPS'] ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . (dirname($_SERVER['PHP_SELF']) == '\\' ? '' : dirname($_SERVER['PHP_SELF'])) . '/');
defined('NOW_WEB_SITE') || define('NOW_WEB_SITE', (isset($_SERVER['HTTPS']) && 'on' === $_SERVER['HTTPS'] ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/' . MODULE);
if (strpos($_SERVER['SERVER_SOFTWARE'], 'nginx') !== FALSE) {
    defined('URL_ROOT') || define('URL_ROOT', (isset($_SERVER['HTTPS']) && 'on' === $_SERVER['HTTPS'] ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . (dirname(dirname($_SERVER['PHP_SELF'])) == '\\' ? '' : dirname(dirname($_SERVER['PHP_SELF']))) . '/');
} else {
    defined('URL_ROOT') || define('URL_ROOT', (isset($_SERVER['HTTPS']) && 'on' === $_SERVER['HTTPS'] ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . (dirname($_SERVER['PHP_SELF']) == '\\' ? '' : dirname($_SERVER['PHP_SELF'])) . '/');
}

require_once API_ROOT . '/PhalApi/PhalApi.php';

session_start();

$loader = new PhalApi_Loader(API_ROOT, 'Library');

if (file_exists(API_ROOT . 'vendor/autoload.php')) {
    require API_ROOT . 'vendor/autoload.php';
}

/** ---------------- 注册&初始化 基本服务组件 ---------------- **/

// 自动加载
DI()->loader = $loader;

// 配置
DI()->config = new PhalApi_Config_File(API_ROOT . '/Config');

DI()->config->get('constant'); // 常量

// 调试模式，$_GET['__debug__']可自行改名
DI()->debug = !empty($_GET['__debug__']) ? TRUE : DI()->config->get('sys.debug');

if (DI()->debug) {
    //DI()->tracer->mark();// 启动追踪器
    error_reporting(E_ALL);
    ini_set('display_errors', 1);//正式部署的时候请关闭
} else {
    ini_set('display_errors', 0);//正式部署的时候请关闭
}

// 日记纪录
DI()->logger = new PhalApi_Logger_File(API_ROOT . '/Runtime', PhalApi_Logger::LOG_LEVEL_DEBUG | PhalApi_Logger::LOG_LEVEL_INFO | PhalApi_Logger::LOG_LEVEL_ERROR);

// 数据操作 - 基于NotORM
DI()->notorm = new PhalApi_DB_NotORM(DI()->config->get('dbs'), DI()->debug);
//DI()->notorm = new PhalApi_DB_NotORM(DI()->config->get('dbs'), !empty($_GET['__sql__']));

if (!defined('IS_JSON')) {
    $accepts = DI()->request->getHeader('Accept');
    $accepts = explode(',', $accepts);
    $accept = $accepts[0];
    $service = DI()->request->getService();
    if (stripos('Public.neditor,', $service) === FALSE) {
        if (in_array('application/json', $accepts)) {
            defined('IS_JSON') || define('IS_JSON', TRUE);
        } else {
            defined('IS_JSON') || define('IS_JSON', FALSE);
            DI()->response = 'PhalApi_Response_Explorer';
        }
    }
}

// 翻译语言包设定
if (isset($_SESSION['Language'])) {
    $language = GL();
    if ($_SESSION['Language'] != $language) {
        SL($_SESSION['Language']);
    }
    unset($language);
} else {
    SL('zh_cn');
}

/** ---------------- 定制注册 可选服务组件 ---------------- **/

/**
 * // 签名验证服务
 * DI()->filter = 'PhalApi_Filter_SimpleMD5';
 */

//缓存 - Memcache/Memcached
DI()->cache = function () {
    return new PhalApi_Cache_File(DI()->config->get('sys.file'));
    //    return new PhalApi_Cache_Memcache(DI()->config->get('sys.mc'));
};

//cookie工具
DI()->cookie = function () {
    $config = [];
    $config['path'] = '/';
    return new PhalApi_Cookie($config);
};

//curl请求
DI()->curl = function () {
    return new PhalApi_CUrl();
};

//tool工具
DI()->tool = function () {
    return new PhalApi_Tool();
};

defined('client_ip') || define('client_ip', DI()->tool->getClientIp());

/**
 * // 支持JsonP的返回
 * if (!empty($_GET['callback'])) {
 * DI()->response = new PhalApi_Response_JsonP($_GET['callback']);
 * }
 */

register_shutdown_function('sys_error_func');// 捕获系统级错误
function sys_error_func()
{
    if ($error = error_get_last()) {
        DI()->logger->error('Type:' . $error['type'] . ' Msg: ' . $error['message'] . ' in ' . $error['file'] . ' on line ' . $error['line']);
    }
}

set_error_handler('error_func');// 捕获一般错误
function error_func($type, $message, $file, $line)
{
    DI()->logger->error($type . ':' . $message . ' in ' . $file . ' on ' . $line . ' line');
}

/* 客户端域名 */
$origin = strtolower(isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '');
/* 配置数组 */
$crossDomainConfig = DI()->config->get('sys.crossDomain');
/* 追加header 开启跨域访问 */
if ($crossDomainConfig['Access-Control-Allow-Origin'] == '*' || in_array($origin, $crossDomainConfig['Access-Control-Allow-Origin'])) {
    array_walk($crossDomainConfig, function ($value, $key) use ($origin) {
        if ($key == 'Access-Control-Allow-Origin') {
            header("{$key}:{$origin}");
        } else {
            if (is_array($value)) {
                $value = implode(',', $value);
            }
            header("{$key}:{$value}");
        }
    });
}
unset($origin, $crossDomainConfig);

if (strtolower($_SERVER['REQUEST_METHOD']) == 'options') {
    exit(TRUE);
}
