<?php

/**
 * 默认接口服务类
 *
 * @author: dogstar <chanzonghuang@gmail.com> 2014-10-04
 */
class Api_Class extends PhalApi_Api
{
    
    public function getRules()
    {
        return array(
            'class_List' => array(
                'action' => array('name' => 'action', 'type' => 'string', 'default' => 'view', 'require' => true, 'desc' => '操作'),
                'page' => array('name' => 'page', 'type' => 'int', 'default' => 1, 'min' => 1, 'require' => false, 'desc' => '当前页数')
            ),
            'update_Class' => array(
                'class_id' => array('name' => 'class_id', 'type' => 'int', 'require' => true, 'desc' => '课程ID'),
                'name' => array('name' => 'name', 'type' => 'string', 'require' => true, 'desc' => '课程名'),
                'tips' => array('name' => 'tips', 'type' => 'string', 'require' => true, 'desc' => '课程说明')
            ),
            'create_Class' => array(
                'action' => array('name' => 'action', 'type' => 'string', 'default' => 'view', 'require' => true, 'desc' => '操作'),
                'name' => array('name' => 'name', 'type' => 'string', 'require' => false, 'desc' => '课程名'),
                'tips' => array('name' => 'tips', 'type' => 'string', 'require' => false, 'desc' => '课程说明')
            ),
            'delete_Class' => array(
                'class_id' => array('name' => 'class_id', 'type' => 'int', 'default' => 0, 'min' => 0, 'require' => true, 'desc' => 'ID')
            ),
        );
    }
    
    public function class_List()
    {
        if ($this->action == 'post') {
        } else {
            $class_domain = new Domain_Class();
            $class_list = $class_domain->getClassList((($this->page - 1) * each_page), ($this->page * each_page));
            DI()->view->assign(array('page' => $this->page, 'total' => $class_list['total'], 'rows' => $class_list['rows']));
            DI()->view->show('class_list');
        }
    }
    
    public function update_Class()
    {
        if (empty($this->name)) {
            throw new PhalApi_Exception_Error(T('请输入课程名'), 1);// 抛出普通错误 T标签翻译
        }/* elseif (empty($this->tips)) {
			throw new PhalApi_Exception_Error(T('请输入课程说明'), 1);// 抛出普通错误 T标签翻译
		}*/
        
        $class_model = new Model_Class();
        $insert_data = array();
        $insert_data['name'] = $this->name;
        $insert_data['tips'] = $this->tips;
        $rs = $class_model->update($this->class_id, $insert_data);
        unset($insert_data);
        if ($rs) {
            DI()->response->setMsg(T('修改课程成功'));
            return;
        } else {
            throw new PhalApi_Exception_InternalServerError(T('修改课程失败'), 2);// 抛出服务端错误
        }
    }
    
    public function create_Class()
    {
        if ($this->action == 'post') {
            if (empty($this->name)) {
                throw new PhalApi_Exception_Error(T('请输入课程名'), 1);// 抛出普通错误 T标签翻译
            }/* elseif (empty($this->tips)) {
				throw new PhalApi_Exception_Error(T('请输入课程说明'), 1);// 抛出普通错误 T标签翻译
			}*/
            
            $class_model = new Model_Class();
            $insert_data = array();
            $insert_data['name'] = $this->name;
            $insert_data['tips'] = $this->tips;
            $rs = $class_model->insert($insert_data);
            unset($insert_data);
            if ($rs) {
                DI()->response->setMsg(T('添加课程成功'));
                return;
            } else {
                throw new PhalApi_Exception_InternalServerError(T('添加课程失败'), 2);// 抛出服务端错误
            }
        } else {
            DI()->view->show('create_class');
        }
    }
    
    
    public function delete_Class()
    {
        $class_model = new Model_Class();
        $rs = $class_model->delete($this->class_id);
        if ($rs) {
            DI()->response->setMsg(T('删除成功'));
            return;
        } else {
            throw new PhalApi_Exception_InternalServerError(T('删除失败'), 2);// 抛出服务端错误
        }
    }
    
}
