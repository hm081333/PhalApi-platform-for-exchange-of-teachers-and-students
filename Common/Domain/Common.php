<?php

class Domain_Common
{
    public static function checkEmail($email)
    {
        $check = "/^[0-9a-zA-Z_-]+@[0-9a-zA-Z_-]+(\.[0-9a-zA-Z_-]+){0,3}$/";
        
        if (preg_match($check, $email)) {
            return true;
        } else {
            return false;
        }
    }
    
    public static function hash($password, $algo = PASSWORD_DEFAULT, $options = null)
    {
        if (empty($options)) {
            return password_hash($password, $algo);
        } else {
            return password_hash($password, $algo, $options);
        }
    }
    
    public static function verify($password, $hash)
    {
        return password_verify($password, $hash);
    }
    
    /**
     * @return string
     */
    public static function create_Google_Auth()
    {
        $ga = new GoogleAuthenticator_Lite();
        return $secret = $ga->createSecret();
    }
    
    /**
     * @param $secret
     * @param string $name 标识
     * @return string
     */
    public static function get_Google_Auth_Url($secret, $name = 'LYi-Ho')
    {
        $ga = new GoogleAuthenticator_Lite();
        return $qrCodeUrl = $ga->getQRCodeGoogleUrl($name, $secret);
    }
    
    /**
     * @param $secret
     * @return string
     */
    public static function get_Google_Auth_Code($secret)
    {
        $ga = new GoogleAuthenticator_Lite();
        return $oneCode = $ga->getCode($secret);
    }
    
    /**
     * @param $secret 服务端的 "安全密匙SecretKey"
     * @param $oneCode 手机上看到的 "一次性验证码"
     * @param $discrepancy 容差时间,这里是2 那么就是 2* 30 sec 一分钟.
     * @return bool
     */
    public static function verify_Google_Auth_Code($secret, $oneCode, $discrepancy = 1)
    {
        $ga = new GoogleAuthenticator_Lite();
        $checkResult = $ga->verifyCode($secret, $oneCode, $discrepancy);
        if ($checkResult) {
            return true;
        } else {
            return false;
        }
    }
    
}
