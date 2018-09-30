<?php
/**
 * Created by PhpStorm.
 * User: LYi-Ho
 * Date: 2018/8/5
 * Time: 0:25
 */

class SMS_YUNPIAN
{
    protected $setting;
    protected $api_key;
    
    public function __construct()
    {
        $this->setting = Domain_Setting::getSetting('sms');// 短信配置信息
        $this->api_key = isset($this->setting['api_key']) ? $this->setting['api_key'] : '';// 用户唯一标识
        if (empty($this->api_key)) {
            throw new PhalApi_Exception_Error(T('参数错误'));
        }
    }
    
    /**
     * 发送短信
     * @param string $phone
     * @param string $content
     * @return bool
     * @throws PhalApi_Exception_Error
     */
    public function send($phone, $content)
    {
        $url = 'https://sms.yunpian.com/v2/sms/single_send.json';// 请求地址
        $param = [// 请求参数
            'apikey' => $this->api_key,
            'mobile' => $phone,
            'text' => $content,
        ];
        var_dump($param);
        die;
        
        $result = DI()->curl->post($url, $param);// 发送POST请求
        if ($result) {
            $result = json_decode($result);// 解码JSON
        } else {
            throw new PhalApi_Exception_Error(T('短信发送失败'));
        }
        if ($result['code'] != 0) {
            throw new PhalApi_Exception_Error(T($result['msg']));// 抛出错误信息
        }
        DI()->response->setMsg(T($result['msg']));// 返回成功信息
        return true;
    }
    
    
    /**
     * 查询剩余短信数量
     * @param bool|string $api_key
     * @return array
     * @throws PhalApi_Exception_Error
     */
    public function query($api_key = false)
    {
        $url = 'https://sms.yunpian.com/v2/user/get.json';// 请求地址
        if ($api_key) {
            $this->api_key = $api_key;// 用传入的APIKey替换存储的key
        }
        $param = [
            'apikey' => $this->api_key,
        ];
        $result = DI()->curl->post($url, $param);// 发送POST请求
        if ($result) {
            $result = json_decode($result);
        } else {
            throw new PhalApi_Exception_Error(T('查询失败'));
        }
        return [
            'over' => $result['balance'],// 账户剩余条数或者剩余金额（根据账户类型）
        ];
    }
}