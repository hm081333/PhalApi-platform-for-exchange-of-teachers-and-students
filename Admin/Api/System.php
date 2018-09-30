<?php
/**
 * Created by PhpStorm.
 * User: 123
 * Date: 2017/9/8
 * Time: 21:36
 */

class Api_System extends PhalApi_Api
{
    public function getRules()
    {
        return array(
            'reset' => array(
                'action' => array('name' => 'action', 'type' => 'enum', 'range' => array('view', 'post'), 'default' => 'view', 'desc' => '操作类型'),
                'password' => array('name' => 'password', 'type' => 'string', 'desc' => '密码'),
            ),
            'backup' => array(
                'action' => array('name' => 'action', 'type' => 'enum', 'range' => array('view', 'post'), 'default' => 'view', 'desc' => '操作类型'),
                'password' => array('name' => 'password', 'type' => 'string', 'desc' => '密码'),
            ),
            'restore' => array(
                'name' => array('name' => 'name', 'type' => 'string', 'desc' => 'SQL文件名'),
                'password' => array('name' => 'password', 'type' => 'string', 'desc' => '密码'),
            ),
        );
    }
    
    public function reset()
    {
        if ($this->action == 'view') {
            DI()->view->show('system_reset');
        } else {
            if (empty($this->password)) {
                throw new PhalApi_Exception_Error('请输入管理员密码');
            }
            $admin_model = new Model_Admin();
            $admin = $admin_model->get(1, 'password');
            if (!Domain_Common::verify($this->password, $admin['password'])) {
                throw new PhalApi_Exception_Error(T('密码错误'), 1);// 抛出客户端错误 T标签翻译
            }
            $sql = '
			truncate table ly_user;
			truncate table ly_topic;
			truncate table ly_reply;
			truncate table ly_email_auth;
			truncate table ly_delivery;
			truncate table ly_tieba;
			truncate table ly_baiduid;
			';
            $admin_model->queryAll($sql);
            DI()->tool->emptyDir(PUB_ROOT . 'static/upload/pics');
            DI()->tool->emptyDir(PUB_ROOT . 'static/upload/wechat');
            DI()->response->setMsg(T('清空成功'));
            return;
        }
    }
    
    public function backup()
    {
        if ($this->action == 'view') {
            DI()->view->assign(array('files' => DI()->tool->dirFile(API_ROOT . '/Data/')));
            DI()->view->show('system_backup');
        } else {
            if (empty($this->password)) {
                throw new PhalApi_Exception_Error('请输入管理员密码');
            }
            $admin_model = new Model_Admin();
            $admin = $admin_model->get(1, 'password');
            if (!Domain_Common::verify($this->password, $admin['password'])) {
                throw new PhalApi_Exception_Error(T('密码错误'), 1);// 抛出客户端错误 T标签翻译
            }
            set_time_limit(0);
            ignore_user_abort(true);
            $dbs = DI()->config->get('dbs.servers');
            $db = $dbs[DB];
            $dir = API_ROOT . '/Data/' . date('Ym', NOW_TIME) . '/';
            if (!file_exists($dir)) {
                DI()->tool->createDir($dir);
            }
            $file_name = date('Y年m月d日-H时i分s秒', NOW_TIME) . '.sql';
            $file = $dir . $file_name;
            $return_val = true;
            system(MySQL . "mysqldump -u" . $db['user'] . " -p" . $db['password'] . " -h" . $db['host'] . " " . $db['name'] . " > " . $file, $return_val);
            if ($return_val) {
                throw new PhalApi_Exception_Error(T('备份失败'));
            }
            DI()->response->setMsg(T('备份成功'));
            return;
        }
    }
    
    public function restore()
    {
        if (empty($this->password)) {
            throw new PhalApi_Exception_Error('请输入管理员密码');
        }
        $admin_model = new Model_Admin();
        $admin = $admin_model->get(1, 'password');
        if (!Domain_Common::verify($this->password, $admin['password'])) {
            throw new PhalApi_Exception_Error(T('密码错误'), 1);// 抛出客户端错误 T标签翻译
        }
        set_time_limit(0);
        ignore_user_abort(true);
        $dbs = DI()->config->get('dbs.servers');
        $db = $dbs[DB];
        $dir = API_ROOT . "/Data/";
        $path = $this->name;
        $file = $dir . $path;
        if (!file_exists($file)) {
            throw new PhalApi_Exception_Error(T('找不到该文件'));
        }
        $return_val = true;
        system(MySQL . "mysql -u" . $db['user'] . " -p" . $db['password'] . " -h" . $db['host'] . " " . $db['name'] . " < " . $file, $return_val);
        if ($return_val) {
            throw new PhalApi_Exception_Error(T('还原失败'));
        }
        DI()->response->setMsg(T('还原成功'));
        return;
    }
    
}