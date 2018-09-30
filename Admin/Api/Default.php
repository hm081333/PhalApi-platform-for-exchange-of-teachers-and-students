<?php

/**
 * 默认接口服务类
 *
 * @author: dogstar <chanzonghuang@gmail.com> 2014-10-04
 */
class Api_Default extends PhalApi_Api
{
    
    public function getRules()
    {
        return array(
            'index' => array(
                // 'limit' => array('name' => 'limit', 'type' => 'int', 'default' => 0, 'min' => 0, 'require' => false, 'desc' => ''),
                // 'offset' => array('name' => 'offset', 'type' => 'int', 'default' => each_page, 'min' => 0, 'require' => false, 'desc' => ''),
                'page' => array('name' => 'page', 'type' => 'int', 'default' => 1, 'min' => 1, 'require' => false, 'desc' => '当前页数'),
            ),
            'email_config' => array(
                'action' => array('name' => 'action', 'type' => 'string', 'default' => 'view', 'require' => true, 'desc' => '操作'),
                'host' => array('name' => 'host', 'type' => 'string', 'require' => false, 'desc' => 'SMTP服务器'),
                'username' => array('name' => 'username', 'type' => 'string', 'require' => false, 'desc' => 'SMTP服务器用户名'),
                'password' => array('name' => 'password', 'type' => 'string', 'require' => false, 'desc' => 'SMTP服务器密码'),
                'from' => array('name' => 'from', 'type' => 'string', 'require' => false, 'desc' => '发件人地址'),
                'fromName' => array('name' => 'fromName', 'type' => 'string', 'require' => false, 'desc' => '发件人名称'),
                'sign' => array('name' => 'sign', 'type' => 'string', 'require' => false, 'desc' => '邮件签名'),
            ),
            'smsbao_config' => array(
                'action' => array('name' => 'action', 'type' => 'string', 'default' => 'view', 'require' => true, 'desc' => '操作'),
                'username' => array('name' => 'username', 'type' => 'string', 'require' => false, 'desc' => '用户名'),
                'password' => array('name' => 'password', 'type' => 'string', 'require' => false, 'desc' => '密码'),
            ),
        );
    }
    
    /**
     * 默认接口服务
     * @return string title 标题
     * @return string content 内容
     * @return string version 版本，格式：X.X.X
     * @return int time 当前时间戳
     */
    public function index()
    {
        if (isset($_SESSION["admin_name"])) {
            $user_domain = new Domain_User();
            $user_list = $user_domain->getUserList((($this->page - 1) * each_page), ($this->page * each_page));
            //抛出多个变量
            DI()->view->assign(array('rows' => $user_list['rows'], 'total' => $user_list['total'], 'page' => $this->page));
            DI()->view->show('user_list');
        } else {
            DI()->view->show('login');
        }
    }
    
}
