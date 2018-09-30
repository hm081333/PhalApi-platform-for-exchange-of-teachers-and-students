<?php
/**
 * Created by PhpStorm.
 * User: LYi-Ho
 * Date: 2018/8/5
 * Time: 0:24
 */

class SMS_Lite
{
    protected $setting;
    protected $sms_type;
    protected $sign_name = '';
    
    public function __construct()
    {
        // 【云片网】
        $this->setting = Domain_Setting::getSetting('sms');// 短信配置信息
        $this->sms_type = $this->setting['sms_type'];// 短信类型
        if (isset($this->setting['use_sign'])) {
            $this->sign_name = isset($this->setting['sign_name']) ? '【' . $this->setting['sign_name'] . '】' : '';
        }
    }
    
    /**
     * 发送短信
     * @param string $phone
     * @param string $content
     * @return bool
     * @throws PhalApi_Exception_Error
     */
    public function send($phone = '', $content = '')
    {
        if (!DI()->tool->isMobile($phone)) {
            throw new PhalApi_Exception_Error(T('手机号码有误'));
        }
        if (empty($content)) {
            throw new PhalApi_Exception_Error(T('短信模板错误'));
        }
        $content = $this->sign_name . $content;// 短信内容开头加上签名
        switch ($this->sms_type) {
            case 0:
                $sms = new SMS_SMSBAO();
                break;
            case 1:
                $sms = new SMS_YUNPIAN();
                break;
            default:
                throw new PhalApi_Exception_Error(T('请正确配置短信'));
                break;
        }
        return $sms->send($phone, $content);
    }
    
    
}