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
            'main' => array(
                'page' => array('name' => 'page', 'type' => 'int', 'default' => 1, 'min' => 1, 'desc' => '当前页数'),
            ),
            'index' => array(
                'page' => array('name' => 'page', 'type' => 'int', 'default' => 1, 'min' => 1, 'desc' => '当前页数'),
            ),
            'deliveryList' => array(
                'page' => array('name' => 'page', 'type' => 'int', 'default' => 1, 'min' => 1, 'desc' => '当前页数'),
            ),
            'addDelivery' => array(
                'code' => array('name' => 'code', 'type' => 'string', 'require' => true, 'desc' => '快递公司代号'),
                'sn' => array('name' => 'sn', 'type' => 'string', 'require' => true, 'desc' => '快递单号'),
                'memo' => array('name' => 'memo', 'type' => 'string', 'require' => true, 'desc' => '快递备注'),
            ),
            'deliveryView' => array(
                'id' => array('name' => 'id', 'type' => 'int', 'require' => true, 'min' => 1, 'desc' => 'ID'),
            ),
            'delDelivery' => array(
                'id' => array('name' => 'id', 'type' => 'int', 'require' => true, 'min' => 1, 'desc' => 'ID'),
            ),
        );
    }
    
    public function deliveryList()
    {
        $delivery_model = new Model_Delivery();
        $delivery_list = $delivery_model->getList((($this->page - 1) * each_page), ($this->page * each_page), array('user_id' => $_SESSION['user_id']));
        $delivery_list['page_total'] = ceil($delivery_list['total'] / each_page);
        $logistics_model = new Model_Logistics();
        $logistics = $logistics_model->getListByWhere(array('state' => 1), 'name, code, used', 'used DESC, sort DESC, id DESC');
        return DI()->view->post('delivery_list', array('rows' => $delivery_list['rows'], 'total' => $delivery_list['total'], 'page' => $this->page, 'logss' => $logistics));
    }
    
    public function addDelivery()
    {
        $logistics_model = new Model_Logistics();
        $log = $logistics_model->getInfo(array('code' => $this->code));
        if ($log === false) {
            throw  new PhalApi_Exception(T('不存在该快递公司代码，请联系管理员'));
        }
        $delivery_model = new Model_Delivery();
        DI()->notorm->beginTransaction('db_forum');
        $insert = array();
        $insert['code'] = $log['code'];
        $insert['log_name'] = $log['name'];
        $insert['sn'] = $this->sn;
        $insert['memo'] = $this->memo;
        $insert['add_time'] = NOW_TIME;
        $insert['user_id'] = $_SESSION['user_id'];
        $rs = $delivery_model->insert($insert);
        unset($insert);
        $update_log_used = $logistics_model->update($log['id'], array('used' => new NotORM_Literal('used + 1')));
        if ($rs === false || $update_log_used === false) {
            DI()->notorm->rollback('db_forum');
            throw new PhalApi_Exception(T('添加失败'));
        } else {
            DI()->notorm->commit('db_forum');
            DI()->response->setMsg(T('添加成功'));
            return true;
        }
    }
    
    public function delDelivery()
    {
        $delivery_model = new Model_Delivery();
        DI()->notorm->beginTransaction('db_forum');
        $rs = $delivery_model->delete($this->id);
        unset($insert);
        if ($rs === false) {
            DI()->notorm->rollback('db_forum');
            throw new PhalApi_Exception(T('删除失败'));
        } else {
            DI()->notorm->commit('db_forum');
            DI()->response->setMsg(T('删除成功'));
            return true;
        }
    }
    
    public function deliveryView()
    {
        $delivery_model = new Model_Delivery();
        $delivery = $delivery_model->get($this->id);
        $logistics = Common_Function::getLogistics($delivery['code'], $delivery['sn']);
        $update = array();
        if ($logistics['status'] != 200) {
            // throw new PhalApi_Exception(T($logistics['message']));
            $logistics = unserialize($delivery['last_message']);
        } else {
            $update['last_message'] = serialize($logistics);
        }
        $update['last_time'] = NOW_TIME;
        if ($delivery['state'] != $logistics['state']) {
            $update['state'] = $logistics['state'];
            if ($logistics['state'] == 3) {
                $year = ((int)substr($logistics['data'][0]['time'], 0, 4));//取得年份
                $month = ((int)substr($logistics['data'][0]['time'], 5, 2));//取得月份
                $day = ((int)substr($logistics['data'][0]['time'], 8, 2));//取得几号
                $hour = ((int)substr($logistics['data'][0]['time'], 11, 2));//取得小时
                $min = ((int)substr($logistics['data'][0]['time'], 14, 2));//取得分钟
                $sec = ((int)substr($logistics['data'][0]['time'], 17, 2));//取得秒
                //$update['end_time'] = NOW_TIME;
                $update['end_time'] = mktime($hour, $min, $sec, $month, $day, $year);
                unset($hour, $min, $sec, $month, $day, $year);
            }
        }
        $delivery_model->update($delivery['id'], $update);
        unset($update);
        return DI()->view->post('delivery_view', array('delivery' => $delivery, 'logistics' => $logistics));
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
        $class_domain = new Domain_Class();
        $class_list = $class_domain->getClassList((($this->page - 1) * each_page), ($this->page * each_page));
        $class_list['page_total'] = ceil($class_list['total'] / each_page);
        DI()->view->assign(['rows' => $class_list['rows'], 'total' => $class_list['total'], 'page' => $this->page]);
        if (ISAPP) {
            return DI()->view->getAll();
        }
        return DI()->view->show('index');
    }
    
}
