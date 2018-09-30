<?php
/**
 * Created by PhpStorm.
 * User: LYi-Ho
 * Date: 2017/5/30
 * Time: 上午 12:01
 */

class Api_Public extends PhalApi_Api
{
    
    public function getRules()
    {
        return array(
            'checkEmail' => array(
                'email' => array('name' => 'email', 'type' => 'string', 'require' => true, 'desc' => '邮箱'),
            ),
            'setLanguage' => array(
                'language' => array('name' => 'language', 'type' => 'string', 'require' => true, 'desc' => '语言'),
            ),
            'search' => array(
                'action' => array('name' => 'action', 'type' => 'string', 'default' => 'view', 'require' => true, 'desc' => '操作'),
                'keyword' => array('name' => 'keyword', 'type' => 'string', 'require' => false, 'desc' => '搜索关键字'),
                'term' => array('name' => 'term', 'type' => 'string', 'require' => false, 'desc' => '搜索类型'),
            ),
            'verify_Google_Auth_Code' => array(
                'secret' => array('name' => 'secret', 'type' => 'string', 'require' => true, 'desc' => '密钥'),
                'oneCode' => array('name' => 'code', 'type' => 'string', 'require' => true, 'desc' => '验证码'),
            ),
            'send_email' => array(
                'tousername' => array('name' => 'tousername', 'type' => 'string', 'require' => true, 'desc' => '密钥'),
                'title' => array('name' => 'title', 'type' => 'string', 'require' => true, 'desc' => '标题'),
                'content' => array('name' => 'content', 'type' => 'string', 'require' => true, 'desc' => '内容'),
            ),
            'send_sms' => array(
                'phone' => array('name' => 'phone', 'type' => 'string', 'require' => true, 'desc' => '密钥'),
                'content' => array('name' => 'content', 'type' => 'string', 'require' => true, 'desc' => '内容'),
            ),
            'ip' => array(
                'ip' => array('name' => 'ip', 'type' => 'string', 'require' => true, 'desc' => 'IP'),
            ),
            'neditor' => array(
                'action' => array('name' => 'action', 'type' => 'string', 'require' => true, 'desc' => '操作'),
                'callback' => array('name' => 'callback', 'type' => 'string', 'default' => '', 'desc' => '返回'),
            ),
        );
    }
    
    public function neditor()
    {
        // var_dump($this);die;
        $action = $this->action;
        $callback = $this->callback;
        switch ($action) {
            case 'config':
                $result = DI()->config->get('neditor');
                break;
            /* 上传图片 */
            case 'uploadimage':
                /* 上传涂鸦 */
            case 'uploadscrawl':
                /* 上传视频 */
            case 'uploadvideo':
                /* 上传文件 */
            case 'uploadfile':
                $result = Domain_Neditor::upload($action);
                break;
            /* 列出图片 */
            case 'listimage':
                $result = Domain_Neditor::list($action);
                break;
            /* 列出文件 */
            case 'listfile':
                $result = Domain_Neditor::list($action);
                break;
            
            /* 抓取远程文件 */
            case 'catchimage':
                $result = Domain_Neditor::crawler();
                break;
            
            default:
                $result = json_encode(array(
                    'state' => '请求地址出错'
                ));
                break;
        }
        /* 输出结果 */
        if (!empty($callback)) {
            if (preg_match("/^[\w_]+$/", $callback)) {
                return htmlspecialchars($callback) . '(' . $result . ')';
            } else {
                return array('state' => 'callback参数不合法');
            }
        } else {
            return $result;
        }
        
    }
    
    public function ip()
    {
        $ip_model = new Model_Ip();
        $old_ip = $ip_model->getInfo(array('ip' => $this->ip));
        if ($old_ip === false) {
            $data = DI()->curl->json_post('http://ip.taobao.com/service/getIpInfo.php', array('ip' => $this->ip));
            if ($data['code'] !== 0) {
                throw new PhalApi_Exception_Error(T($data['data']));
            }
            $ip_info = $data['data'];
            $ip_model->insert(array('ip' => $this->ip, 'info' => serialize($ip_info), 'add_time' => NOW_TIME));
        } else {
            $ip_info = unserialize($old_ip['info']);
        }
        foreach ($ip_info as $key => &$item) {
            if ((strpos('country,area,region,city,isp', $key)) !== false) {
                $item = T($item);
            }
        }
        return $ip_info;
    }
    
    public function checkEmail()
    {
        $check = "/^[0-9a-zA-Z_-]+@[0-9a-zA-Z_-]+(\.[0-9a-zA-Z_-]+){0,3}$/";
        
        if (preg_match($check, $this->email)) {
            return true;
        } else {
            return false;
        }
    }
    
    public function setLanguage()
    {
        //刷新翻译支持文件
        /*$json = '{"data":{"languages":[{"language":"sq","name":"阿尔巴尼亚语"},{"language":"ar","name":"阿拉伯语"},{"language":"am","name":"阿姆哈拉语"},{"language":"az","name":"阿塞拜疆语"},{"language":"ga","name":"爱尔兰语"},{"language":"et","name":"爱沙尼亚语"},{"language":"eu","name":"巴斯克语"},{"language":"be","name":"白俄罗斯语"},{"language":"bg","name":"保加利亚语"},{"language":"is","name":"冰岛语"},{"language":"pl","name":"波兰语"},{"language":"bs","name":"波斯尼亚语"},{"language":"fa","name":"波斯语"},{"language":"af","name":"布尔语(南非荷兰语)"},{"language":"da","name":"丹麦语"},{"language":"de","name":"德语"},{"language":"ru","name":"俄语"},{"language":"fr","name":"法语"},{"language":"tl","name":"菲律宾语"},{"language":"fi","name":"芬兰语"},{"language":"fy","name":"弗里西语"},{"language":"km","name":"高棉语"},{"language":"ka","name":"格鲁吉亚语"},{"language":"gu","name":"古吉拉特语"},{"language":"kk","name":"哈萨克语"},{"language":"ht","name":"海地克里奥尔语"},{"language":"ko","name":"韩语"},{"language":"ha","name":"豪萨语"},{"language":"nl","name":"荷兰语"},{"language":"ky","name":"吉尔吉斯语"},{"language":"gl","name":"加利西亚语"},{"language":"ca","name":"加泰罗尼亚语"},{"language":"cs","name":"捷克语"},{"language":"kn","name":"卡纳达语"},{"language":"co","name":"科西嘉语"},{"language":"hr","name":"克罗地亚语"},{"language":"ku","name":"库尔德语"},{"language":"la","name":"拉丁语"},{"language":"lv","name":"拉脱维亚语"},{"language":"lo","name":"老挝语"},{"language":"lt","name":"立陶宛语"},{"language":"lb","name":"卢森堡语"},{"language":"ro","name":"罗马尼亚语"},{"language":"mg","name":"马尔加什语"},{"language":"mt","name":"马耳他语"},{"language":"mr","name":"马拉地语"},{"language":"ml","name":"马拉雅拉姆语"},{"language":"ms","name":"马来语"},{"language":"mk","name":"马其顿语"},{"language":"mi","name":"毛利语"},{"language":"mn","name":"蒙古语"},{"language":"bn","name":"孟加拉语"},{"language":"my","name":"缅甸语"},{"language":"hmn","name":"苗语"},{"language":"xh","name":"南非科萨语"},{"language":"zu","name":"南非祖鲁语"},{"language":"ne","name":"尼泊尔语"},{"language":"no","name":"挪威语"},{"language":"pa","name":"旁遮普语"},{"language":"pt","name":"葡萄牙语"},{"language":"ps","name":"普什图语"},{"language":"ny","name":"齐切瓦语"},{"language":"ja","name":"日语"},{"language":"sv","name":"瑞典语"},{"language":"sm","name":"萨摩亚语"},{"language":"sr","name":"塞尔维亚语"},{"language":"st","name":"塞索托语"},{"language":"si","name":"僧伽罗语"},{"language":"eo","name":"世界语"},{"language":"sk","name":"斯洛伐克语"},{"language":"sl","name":"斯洛文尼亚语"},{"language":"sw","name":"斯瓦希里语"},{"language":"gd","name":"苏格兰盖尔语"},{"language":"ceb","name":"宿务语"},{"language":"so","name":"索马里语"},{"language":"tg","name":"塔吉克语"},{"language":"te","name":"泰卢固语"},{"language":"ta","name":"泰米尔语"},{"language":"th","name":"泰语"},{"language":"tr","name":"土耳其语"},{"language":"cy","name":"威尔士语"},{"language":"ur","name":"乌尔都语"},{"language":"uk","name":"乌克兰语"},{"language":"uz","name":"乌兹别克语"},{"language":"es","name":"西班牙语"},{"language":"iw","name":"希伯来语"},{"language":"el","name":"希腊语"},{"language":"haw","name":"夏威夷语"},{"language":"sd","name":"信德语"},{"language":"hu","name":"匈牙利语"},{"language":"sn","name":"修纳语"},{"language":"hy","name":"亚美尼亚语"},{"language":"ig","name":"伊博语"},{"language":"it","name":"意大利语"},{"language":"yi","name":"意第绪语"},{"language":"hi","name":"印地语"},{"language":"su","name":"印尼巽他语"},{"language":"id","name":"印尼语"},{"language":"jw","name":"印尼爪哇语"},{"language":"en","name":"英语"},{"language":"yo","name":"约鲁巴语"},{"language":"vi","name":"越南语"},{"language":"zh_tw","name":"中文(繁体)"},{"language":"zh_cn","name":"中文(简体)"}]}}';
        $json=json_decode($json, True);
        $language = array();
        foreach ($json['data']['languages'] as $value) {
            $language['google'][$value['language']] = $value['name'];
        }
        var_dump($language);
        file_put_contents(API_ROOT . '/Config/translate.php', "<?php   \nreturn " . var_export($language, true) . ';');
        unset($language);
        exit;*/
        
        $google_support = DI()->config->get('translate.google');
        if (!isset($google_support[$this->language])) {
            throw new PhalApi_Exception_Error(T('暂时不支持此语言'), 1);// 抛出客户端错误 T标签翻译*/
        }
        $language = GL();
        if ($this->language != $language) {
            //语言代号存进session
            $_SESSION['Language'] = $this->language;
            DI()->response->setMsg(T('语言设置成功，请等待刷新！'));
        } else {
            throw new PhalApi_Exception_Error(T('选择语言已经是当前语言'), 1);// 抛出客户端错误 T标签翻译*/
        }
    }
    
    public function search()
    {
        if ($this->action == 'post') {
            var_dump($this);
        } else {
            DI()->view->show('search');
        }
    }
    
    public function verify_Google_Auth_Code()
    {
        $checkResult = Domain_Common::verify_Google_Auth_Code($this->secret, $this->oneCode);
        if ($checkResult) {
            DI()->response->setMsg(T('验证成功，请提交个人资料！'));
        } else {
            throw new PhalApi_Exception_Error(T('验证失败'), 1);// 抛出客户端错误 T标签翻译*/
        }
    }
    
    /**
     * 发送邮件
     * @throws PhalApi_Exception_Error
     */
    public function send_email()
    {
        $mailer = new PHPMailer_Lite(true);
        $rs = $mailer->send($this->tousername, $this->title, $this->content);
        if ($rs) {
            DI()->response->setMsg(T('发送成功，请查收邮件！'));
        } else {
            throw new PhalApi_Exception_Error(T('发送失败'), 1);// 抛出客户端错误 T标签翻译*/
        }
    }
    
}