<?php
/**
 * Created by PhpStorm.
 * User: LYi-Ho
 * Date: 2018/8/4
 * Time: 11:51
 */

class Api_Setting extends PhalApi_Api
{
    public function getRules()
    {
        return array(
            'config' => array(
                'action' => array('name' => 'action', 'type' => 'string', 'default' => 'view', 'require' => true, 'desc' => '操作'),
                'name' => array('name' => 'name', 'type' => 'string', 'require' => true, 'desc' => '配置类型'),
            ),
            'messageList' => array(
                'page' => array('name' => 'page', 'type' => 'int', 'default' => 1, 'min' => 1, 'require' => false, 'desc' => '当前页码'),
            ),
            'editMessage' => array(
                'action' => array('name' => 'action', 'type' => 'string', 'default' => 'view', 'require' => true, 'desc' => '操作'),
                'id' => array('name' => 'id', 'type' => 'string', 'default' => 0, 'desc' => '编辑的ID'),
            ),
        );
    }
    
    /**
     * 配置
     */
    public function config()
    {
        unset($_REQUEST['service']);
        $name = $this->name;
        if ($this->action == 'view') {
            $setting = Domain_Setting::getSetting($name);
            DI()->view->show("config/{$name}_config", ['name' => $name, 'setting' => $setting]);
        } else {
            $data = (array)$this;
            $data = array_diff_key($_REQUEST, $data);//差集
            Domain_Setting::updateSetting($name, $data);
        }
    }
    
    /**
     * 短信、邮件模板
     */
    public function messageList()
    {
        $message_model = new Model_Message();
        $lists = $message_model->getList((($this->page - 1) * each_page), ($this->page * each_page));
        DI()->view->show('massage_list', ['lists' => $lists, 'page' => $this->page]);
    }
    
    /**
     * 新增、编辑短信、邮件模板
     */
    public function editMessage()
    {
        unset($_REQUEST['service']);
        $id = $this->id;
        if ($this->action == 'view') {
            $info = ['id' => $id];
            if ($info['id'] > 0) {
                $message_model = new Model_Message();
                $info = $message_model->get($info['id']);
            }
            DI()->view->show('edit_message', ['info' => $info]);
        } else {
            DI()->response->setMsg(T('操作成功'));
            $data = (array)$this;
            $data = array_diff_key($_REQUEST, $data);//差集
            if (!isset($data['state'])) {
                $data['state'] = 0;
            }
            $message_model = new Model_Message();
            if ($id > 0) {
                $message_model->update($id, $data);
            } else {
                $message_model->insert($data);
            }
            DI()->response->setBack();// 返回上一页
        }
    }
    
}