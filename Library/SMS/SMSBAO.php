<?php
/**
 * Created by PhpStorm.
 * User: LYi-Ho
 * Date: 2018/8/5
 * Time: 0:25
 */

class SMS_SMSBAO
{
    protected $setting;
    protected $url = 'https://api.smsbao.com/';
    protected $username;
    protected $password;
    
    /**
     * 构造函数，配置好所需参数
     * SMS_SMSBAO constructor.
     */
    public function __construct()
    {
        $this->setting = Domain_Setting::getSetting('sms');// 系统中保存的短信接口账号信息
        $this->username = isset($this->setting['username']) ? $this->setting['username'] : '';
        $this->password = isset($this->setting['password']) ? md5($this->setting['password']) : '';
        if (empty($this->username) || empty($this->password)) {
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
        $status_str = DI()->config->get('return_str.smsbao');// 返回code对应的信息数组
        DI()->response->setMsg(T($status_str[0]));
        $send_url = $this->url . "sms?u=" . $this->username . "&p=" . $this->password . "&m=" . $phone . "&c=" . urlencode($content);// 凭借请求地址
        $result = DI()->curl->get($send_url);// 发送GET请求
        if ($result != 0) {
            throw new PhalApi_Exception_Error(T($status_str[$result]));
        }
        return true;
    }
    
    
    /**
     * 查询短信宝剩余短信数量
     * @param string $username
     * @param string $password
     * @return array
     * @throws PhalApi_Exception_Error
     */
    public function query($username = false, $password = false)
    {
        DI()->response->setMsg(T('操作成功'));
        $status_str = DI()->config->get('return_str.smsbao');// 返回code对应的信息数组
        if ($username && $password) {// 用传入的账号密码替换掉配置信息中的账号密码
            $this->username = $username;// 传入的账号
            $this->password = $password;// 传入的密码
        }
        $url = $this->url . "query?u=" . $this->username . "&p=" . $this->password;// 拼接请求地址
        $result = DI()->curl->get($url);// 发送GET请求
        $result = str_replace([" ", "　", "\t", "\n", "\r"], [",", ",", ",", ",", ","], $result);// 把返回字符串中的空格与换行符替换为逗号
        $result = explode(',', $result); // 根据逗号 拆分成数组
        if ($result[0] == 0) {
            //拼接返回信息数组
            $rs = [
                'today_send' => $result[1],// 今日发送条数
                'over' => $result[2],// 剩余条数
            ];
        } else {
            // 返回code不为0代表查询失败。把code对应的失败信息抛出
            throw new PhalApi_Exception_Error(T($status_str[$result[0]]));
        }
        // var_dump($rs);
        return $rs;
    }
}