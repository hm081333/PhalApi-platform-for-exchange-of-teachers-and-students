<?php

class Domain_Wechat
{
    private $appId;
    private $appSecret;
    
    public function __construct()
    {
        $config = Domain_Setting::getSetting('wechat');
        $this->appId = isset($config['app_id']) ? $config['app_id'] : '';
        $this->appSecret = isset($config['app_secret']) ? $config['app_secret'] : '';
    }
    
    /**
     * 获取access_token
     * @return mixed
     * @throws PhalApi_Exception 抛出错误
     */
    private function getAccessToken()
    {
        $access_token = DI()->cache->get('access_token');
        if (!isset($access_token) || empty($access_token)) {
            // 如果是企业号用以下URL获取access_token
            // $url = "https://qyapi.weixin.qq.com/cgi-bin/gettoken?corpid=$this->appId&corpsecret=$this->appSecret";
            $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=' . $this->appId . '&secret=' . $this->appSecret;
            $result = DI()->curl->json_get($url);
            if (empty($result)) {
                throw new PhalApi_Exception(T('获取access_token失败'));
            } else {
                $access_token = $result['access_token'];
                $expires_in = $result['expires_in'];
                DI()->cache->set('access_token', $access_token, $expires_in);
                return $access_token;
            }
        } else {
            return $access_token;
        }
    }
    
    /**
     * jsapi_ticket是公众号jsapi_ticket的有效期为7200秒用于调用微信JS接口的临时票据
     *
     * 通过access_token来获取
     * @return mixed
     */
    private function getJsApiTicket()
    {
        $jsapi_ticket = DI()->cache->get('jsapi_ticket');
        if (!isset($jsapi_ticket) || empty($jsapi_ticket)) {
            $accessToken = $this->getAccessToken();
            // 如果是企业号用以下 URL 获取 ticket
            // $url = "https://qyapi.weixin.qq.com/cgi-bin/get_jsapi_ticket?access_token=$accessToken";
            $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=$accessToken";
            $result = DI()->curl->json_get($url);
            if (empty($result) || $result['errcode'] != 0) {
                throw new PhalApi_Exception(T('获取jsapi_ticket失败'));
            } else {
                $jsapi_ticket = $result['ticket'];
                $expires_in = $result['expires_in'];
                DI()->cache->set('jsapi_ticket', $jsapi_ticket, $expires_in);
                return $jsapi_ticket;
            }
        } else {
            return $jsapi_ticket;
        }
    }
    
    /**
     * 拉取身份信息的唯一code
     * @param string $scope
     */
    public function getOpenIdCode($scope = 'snsapi_base')
    {
        if (DI()->tool->is_weixin()) {
            //$scope = 'snsapi_userinfo';
            //若提示“该链接无法访问”，请检查参数是否填写错误，是否拥有scope参数对应的授权作用域权限。
            $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . $this->appId . '&redirect_uri=' . urlencode(URL . 'tieba.php') . '&response_type=code&scope=' . $scope . '&state=STATE#wechat_redirect';
            Header("Location: $url");
            die;
        }
    }
    
    /**
     * 通过code拉取openid和access_token
     * @param $code
     * @return mixed
     * @throws PhalApi_Exception
     */
    public function getOpenId($code)
    {
        $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=' . $this->appId . '&secret=' . $this->appSecret . '&code=' . $code . '&grant_type=authorization_code';
        $result = DI()->curl->json_get($url);
        if (!empty($result)) {
            if (isset($result['errmsg'])) {
                throw new PhalApi_Exception(T($result['errmsg']));
            }
            return $result;
        } else {
            throw new PhalApi_Exception_Error(T('失败'));
        }
    }
    
    /**
     * $scope = 'snsapi_userinfo'的后续
     * @param $access_token
     * @param $open_id
     * @return mixed
     * @throws PhalApi_Exception
     */
    public function getSnsApiUserInfo($code)
    {
        $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=' . $this->appId . '&secret=' . $this->appSecret . '&code=' . $code . '&grant_type=authorization_code';
        $result = DI()->curl->json_get($url);
        if (!empty($result)) {
            if (isset($result['errmsg'])) {
                throw new PhalApi_Exception(T($result['errmsg']));
            }
            $url = 'https://api.weixin.qq.com/sns/userinfo?access_token=' . $result['access_token'] . '&openid=' . $result['open_id'] . '&lang=zh_CN ';
            $result = DI()->curl->json_get($url);
            if (!empty($result)) {
                if (isset($result['errmsg'])) {
                    throw new PhalApi_Exception(T($result['errmsg']));
                }
                return $result;
            } else {
                throw new PhalApi_Exception_Error(T('失败'));
            }
        } else {
            throw new PhalApi_Exception_Error(T('失败'));
        }
    }
    
    /**
     * 通过获取的openid达到自动登陆的效果
     * @param $code
     * @throws PhalApi_Exception
     * @throws PhalApi_Exception_Error
     */
    public function openIdLogin($code)
    {
        $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=' . $this->appId . '&secret=' . $this->appSecret . '&code=' . $code . '&grant_type=authorization_code';
        $result = DI()->curl->json_get($url);
        if (!empty($result)) {
            if (isset($result['errmsg'])) {
                throw new PhalApi_Exception(T($result['errmsg']));
            }
            $open_id = $result['openid'];
            $user_model = new Model_User();
            $user = $user_model->getInfo(array('open_id' => $open_id));
            if ($user) {
                //将用户名存如SESSION中
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['user_name'];
                $_SESSION['user_auth'] = $user['auth'];
            }
        } else {
            throw new PhalApi_Exception_Error(T('失败'));
        }
    }
    
    /**
     * 随机字符串
     * @param int $length
     * @return string
     */
    private function createNonceStr($length = 16)
    {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }
    
    /**
     * 生成jsSDK权限验证配置
     * @return array
     */
    public function getSignPackage()
    {
        $jsapiTicket = $this->getJsApiTicket();
        // 注意 URL 一定要动态获取，不能 hardcode.
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $timestamp = NOW_TIME;
        $nonceStr = $this->createNonceStr();
        // 这里参数的顺序要按照 key 值 ASCII 码升序排序
        $string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";
        $signature = sha1($string);
        $signPackage = array(
            "appId" => $this->appId,
            "nonceStr" => $nonceStr,
            "timestamp" => $timestamp,
            "url" => $url,
            "signature" => $signature,
            "rawString" => $string
        );
        return $signPackage;
    }
    
    /**
     * 发送贴吧签到详情
     * @param string $openid
     * @throws PhalApi_Exception
     */
    private function sendTiebaSignDetail($openid = '')
    {
        if (empty($openid)) {
            return;
        }
        $accessToken = $this->getAccessToken();
        $url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token={$accessToken}";
        $result = Domain_Tieba::getSignStatus($openid);
        if ($result == false) {
            return;
        }
        
        $send_array = array();
        $send_array['touser'] = $openid;
        $send_array['template_id'] = "Ogvc_rROWerSHvfgo1IOJIL103bso0H3jLYEAwTuKKg";//微信模板消息模板
        $send_array['url'] = "http://bbs.lyihe2.tk/tieba";
        $send_array['topcolor'] = "#FF0000";
        $send_array['data'] = array();
        $send_array['data']['user_name'] = array();
        $send_array['data']['user_name']['value'] = $result['user_name'];
        $send_array['data']['user_name']['color'] = "#173177";
        $send_array['data']['greeting'] = array();
        $send_array['data']['greeting']['value'] = $result['greeting'];
        $send_array['data']['greeting']['color'] = "#173177";
        $send_array['data']['tieba_count'] = array();
        $send_array['data']['tieba_count']['value'] = $result['tieba_count'];
        $send_array['data']['tieba_count']['color'] = "#173177";
        $send_array['data']['success_count'] = array();
        $send_array['data']['success_count']['value'] = $result['success_count'];
        $send_array['data']['success_count']['color'] = "#173177";
        $send_array['data']['fail_count'] = array();
        $send_array['data']['fail_count']['value'] = $result['fail_count'];
        $send_array['data']['fail_count']['color'] = "#173177";
        $send_array['data']['ignore_count'] = array();
        $send_array['data']['ignore_count']['value'] = $result['ignore_count'];
        $send_array['data']['ignore_count']['color'] = "#173177";
        
        unset($result);
        
        $send_json = json_encode($send_array, true);
        
        unset($send_array);
        
        $result = DI()->curl->json_post($url, $send_json);
        
        return true;
    }
    
    
    public function sendTiebaSignDetailByCron()
    {
        $user_model = new Model_User();
        $users = $user_model->getListByWhere(array('open_id IS NOT ?' => null, 'sign_notice' => 1), 'open_id');
        foreach ($users as $user) {
            $this->sendTiebaSignDetail($user['open_id']);
        }
    }
    
    
}
