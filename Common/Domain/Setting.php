<?php
/**
 * Created by PhpStorm.
 * User: LYi-Ho
 * Date: 2018/8/4
 * Time: 11:53
 */

class Domain_Setting
{
    
    public static function getSetting($name = false)
    {
        $setting = DI()->cache->get('setting');// 从文件缓存获取
        if (!$setting) {// 文件缓存不存在
            $setting_model = new Model_Setting();
            $setting = $setting_model->get(1);// 从数据库获取
            DI()->cache->set('setting', $setting);// 生成文件缓存，避免多次读取数据库
        }
        if ($name !== false) {
            return isset($setting[$name]) ? unserialize($setting[$name]) : false;
        }
        return $setting;
    }
    
    /**
     * @param $name
     * @param array $data
     */
    public static function updateSetting($name, $data = [])
    {
        DI()->response->setMsg(T('操作成功'));
        $data = serialize($data);// 序列化 存进数据库的是一个序列
        $setting_model = new Model_Setting();
        $result = $setting_model->update(1, [$name => $data]);
        if ($result === false) {
            throw new PhalApi_Exception_InternalServerError(T('更新失败'));
        }
        $setting = self::getSetting();// 获取原有设置参数数据
        $setting[$name] = $data;// 替换当前修改的数据
        DI()->cache->set('setting', $setting);// 更新文件缓存
    }
}