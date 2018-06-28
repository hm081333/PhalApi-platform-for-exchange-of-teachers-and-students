<?php

/**
 * 默认接口服务类
 *
 * @author: dogstar <chanzonghuang@gmail.com> 2014-10-04
 */
class Api_Topic extends PhalApi_Api
{
    
    public function getRules()
    {
        return array(
            'topic_List' => array(
                'class_id' => array('name' => 'class_id', 'type' => 'int', 'default' => 0, 'min' => 0, 'require' => true, 'desc' => 'ID'),
                'page' => array('name' => 'page', 'type' => 'int', 'default' => 1, 'min' => 1, 'require' => false, 'desc' => '当前页数')
            ),
            'topic' => array(
                'topic_id' => array('name' => 'topic_id', 'type' => 'int', 'default' => 0, 'min' => 0, 'require' => true, 'desc' => 'ID')
            ),
            'create_Topic' => array(
                'action' => array('name' => 'action', 'type' => 'string', 'default' => 'view', 'require' => true, 'desc' => '操作'),
                'topic' => array('name' => 'topic', 'type' => 'string', 'require' => false, 'desc' => '文章标题'),
                'detail' => array('name' => 'detail', 'type' => 'string', 'require' => false, 'desc' => '正文内容'),
                'class_id' => array('name' => 'class_id', 'type' => 'int', 'require' => false, 'desc' => '课程'),
                //				'pics' => array('name' => 'pics', 'type' => 'file', 'range' => array('image/jpeg', 'image/png', 'image/gif', 'image/bmp'), 'ext' => array('jpg', 'jpeg', 'png', 'gif', 'bmp'), 'desc' => '图片'),
                'sticky' => array('name' => 'sticky', 'type' => 'string', 'default' => 'off', 'desc' => '顶置')
            ),
            'stick_Topic' => array(
                'topic_id' => array('name' => 'topic_id', 'type' => 'int', 'default' => 0, 'min' => 0, 'require' => true, 'desc' => 'ID')
            ),
            'unstick_Topic' => array(
                'topic_id' => array('name' => 'topic_id', 'type' => 'int', 'default' => 0, 'min' => 0, 'require' => true, 'desc' => 'ID')
            ),
            'delete_Topic' => array(
                'topic_id' => array('name' => 'topic_id', 'type' => 'int', 'default' => 0, 'min' => 0, 'require' => true, 'desc' => 'ID')
            ),
        );
    }
    
    public function topic_List()
    {
        $topic_domain = new Domain_Topic();
        $topic_list = $topic_domain->getTopicList((($this->page - 1) * each_page), ($this->page * each_page), array('class_id' => $this->class_id));
        DI()->view->assign(array('page' => $this->page));
        DI()->view->assign(array('total' => $topic_list['total'], 'rows' => $topic_list['rows']));
        DI()->view->assign(array('class' => $topic_list['class']));
        return DI()->view->post('topic_list');
    }
    
    public function topic()
    {
        $topic_model = new Model_Topic();
        $topic = $topic_model->get($this->topic_id);
        $topic_model->update($this->topic_id, array('view' => new NotORM_Literal("view + 1")));//浏览量加1
        $reply_domain = new Domain_Reply();
        $reply_list = $reply_domain->getReplyList(array('topic_id' => $this->topic_id));
        DI()->view->assign(array('topic' => $topic, 'reply' => $reply_list));
        return DI()->view->post('view_topic');
    }
    
    public function create_Topic()
    {
        if ($this->action == 'post') {
            $pic_path = '';
            if (empty($this->topic)) {
                throw new PhalApi_Exception_Error(T('请输入文章标题'), 1);// 抛出普通错误 T标签翻译
            } elseif (empty($this->detail)) {
                throw new PhalApi_Exception_Error(T('请输入正文内容'), 1);// 抛出普通错误 T标签翻译
            } elseif (empty($this->class_id)) {
                throw new PhalApi_Exception_Error(T('请选择课程'), 1);// 抛出普通错误 T标签翻译
            } elseif (!empty($_FILES['pics']['tmp_name'])) {
                $reback = DI()->tool->upLoadImage('pics');
                if (is_array($reback)) {
                    $pic_path = $reback['url'];
                } else {
                    throw new PhalApi_Exception_InternalServerError(T('图片上传失败'), 2);// 抛出服务端错误
                }
            }
            $user_model = new Model_User();
            $user = $user_model->get($_SESSION['user_id'], 'id, email, user_name');
            $topic_model = new Model_Topic();
            $insert_data = array();
            $insert_data['class_id'] = $this->class_id;
            $insert_data['topic'] = $this->topic;
            $insert_data['detail'] = $this->detail;
            $insert_data['pics'] = $pic_path;
            $insert_data['user_id'] = $user['id'];
            $insert_data['name'] = $user['user_name'];
            $insert_data['email'] = $user['email'];
            $insert_data['add_time'] = NOW_TIME;
            if ($this->sticky == 'on') {
                $insert_data['sticky'] = 1;
            }
            $topic_id = $topic_model->insert($insert_data);
            if ($topic_id) {
                DI()->response->setMsg(T('发布成功'));
                return array('topic_id' => $topic_id);
            } else {
                throw new PhalApi_Exception_InternalServerError(T('发布失败'), 2);// 抛出服务端错误
            }
        } else {
            $class = Domain_Class::getAllClassList();
            DI()->view->assign(array('class' => $class));
            return DI()->view->post('create_topic');
        }
    }
    
    public function stick_Topic()
    {
        $topic_model = new Model_Topic();
        $rs = $topic_model->update($this->topic_id, array('sticky' => 1));
        if ($rs) {
            DI()->response->setMsg(T('顶置成功'));
            return;
        } else {
            throw new PhalApi_Exception_InternalServerError(T('顶置失败'), 2);// 抛出服务端错误
        }
    }
    
    public function unstick_Topic()
    {
        $topic_model = new Model_Topic();
        $rs = $topic_model->update($this->topic_id, array('sticky' => 0));
        if ($rs) {
            DI()->response->setMsg(T('取消顶置成功'));
            return;
        } else {
            throw new PhalApi_Exception_InternalServerError(T('取消顶置失败'), 2);// 抛出服务端错误
        }
    }
    
    public function delete_Topic()
    {
        $topic_model = new Model_Topic();
        $rs = $topic_model->delete($this->topic_id);
        if ($rs) {
            DI()->response->setMsg(T('删除成功'));
            return 'user';
        } else {
            throw new PhalApi_Exception_InternalServerError(T('删除失败'), 2);// 抛出服务端错误
        }
    }
    
}
