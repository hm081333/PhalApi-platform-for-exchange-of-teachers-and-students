<?php
/**
 * Created by PhpStorm.
 * User: LYi-Ho
 * Date: 2017/5/29
 * Time: 下午 1:30
 */
defined('DB') || define('DB', 'db_forum');
defined('each_page') || define('each_page', 8);
defined('NOW_TIME') || define('NOW_TIME', isset($_SERVER['REQUEST_TIME']) ? $_SERVER['REQUEST_TIME'] : time());
defined('SERVER_TIME') || define('SERVER_TIME', time());
defined('back') || define('back', TRUE);
defined('ISAPP') || define('ISAPP', DI()->request->getHeader('Isapp') || false);
defined('IS_AJAX') || define('IS_AJAX', ((isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')) ? true : false);
defined('MySQL') || define('MySQL', PHP_OS == 'WINNT' ? "C:\\phpStudy\\MySQL\\bin\\" : "");
defined('PREFIX') || define('PREFIX', DI()->config->get('dbs.tables')['__default__']['prefix']);
defined('USER_TOKEN') || define('USER_TOKEN', '85cc39d65ded51e8ffb949503e83ed65');
defined('ADMIN_TOKEN') || define('ADMIN_TOKEN', '1858d7d84c1f48cd63eda4f989dbf9e7');

// SoundCloud 客户端 ID，如果失效请更改
defined('MC_SC_CLIENT_ID') || define('MC_SC_CLIENT_ID', '2t9loNQH90kzJcsFCODdigxfp325aq4z');
// Curl 代理地址，解决翻墙问题。例如：define('MC_PROXY', 'http://10.10.10.10:8123');
defined('MC_PROXY') || define('MC_PROXY', false);

