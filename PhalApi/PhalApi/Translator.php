<?php
/**
 * PhalApi_Translator 国际翻译
 *
 * - 根提供的语言包，进行翻译
 * - 优先使用应用级的翻译，其次是框架默认的
 *
 * <br>使用示例：<br>
 * ```
 *      //初始化，设置语言
 *      PhalApi_Translator::setLanguage('zh_cn');
 *
 *      //翻译
 *      $msg = T('hello {name}', array('name' => 'phper'));
 *      var_dump($msg);
 * ```
 *
 * @package     PhalApi\Translator
 * @license     http://www.phalapi.net/license GPL 协议
 * @link        http://www.phalapi.net/
 * @author      dogstar <chanzonghuang@gmail.com> 2015-02-04
 */

class PhalApi_Translator
{
    
    
    private static $userAgent = array(
        'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_6_8; en-us) AppleWebKit/534.50 (KHTML, like Gecko) Version/5.1 Safari/534.50',
        'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-us) AppleWebKit/534.50 (KHTML, like Gecko) Version/5.1 Safari/534.50',
        'Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; Trident/5.0;',
        'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.0; Trident/4.0)',
        'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.6; rv,2.0.1) Gecko/20100101 Firefox/4.0.1',
        'Mozilla/5.0 (Windows NT 6.1; rv,2.0.1) Gecko/20100101 Firefox/4.0.1',
        'Opera/9.80 (Macintosh; Intel Mac OS X 10.6.8; U; en) Presto/2.8.131 Version/11.11',
        'Opera/9.80 (Windows NT 6.1; U; en) Presto/2.8.131 Version/11.11',
        'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_7_0) AppleWebKit/535.11 (KHTML, like Gecko) Chrome/17.0.963.56 Safari/535.11',
        'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; Maxthon 2.0)',
        'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; TencentTraveler 4.0)',
        'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; TencentTraveler 4.0',
        'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1)',
        'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; 360SE)',
        'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; Avant Browser)',
        'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2859.0 Safari/537.36',
    );
    
    /**
     * @var array $message 翻译的映射
     */
    protected static $message = NULL;
    
    
    /**
     * @var array $language 语言
     */
    protected static $language = 'en';
    
    private static $zh_cn = NUll;
    
    private static $nowMessage = null;
    
    /**
     * 获取翻译
     * @param string $key 翻译的内容
     * @param array $params 直推参数
     * @return string
     */
    public static function get($key, $params = array())
    {
        if (self::$message === NULL) {
            self::setLanguage('en');
        }
        
        if (DI()->config->get('sys.translate')) {
            if (isset(self::$message[$key])) {
                $rs = self::$message[$key];
            } else {
                if (strtolower(self::$language) == 'zh_cn') {
                    $rs = self::translate($key, self::getLanguage());
                } else {
                    if (self::$zh_cn === null) {
                        $zh_cn = include self::getMessageFilePath(API_ROOT, 'zh_CN');
                    }
                    if (isset($zh_cn[$key])) {
                        $rs = self::translate($zh_cn[$key], self::getLanguage());
                    } else {
                        $rs = self::translate($key, self::getLanguage());
                    }
                    
                }
                
                self::$message[$key] = $rs;
                if (self::$nowMessage === null) {
                    $messages = include self::getMessageFilePath(API_ROOT, self::getLanguage());
                }
                $messages[$key] = $rs;
                file_put_contents(self::getMessageFilePath(API_ROOT, self::getLanguage()), "<?php \r\nreturn " . var_export($messages, true) . ';');
                
            }
        } else {
            $rs = isset(self::$message[$key]) ? self::$message[$key] : $key;
        }
        
        $names = array_keys($params);
        $names = array_map(array('PhalApi_Translator', 'formatVar'), $names);
        
        return str_replace($names, array_values($params), $rs);
    }
    
    public static function formatVar($name)
    {
        return '{' . $name . '}';
    }
    
    /**
     * 语言设置
     * @param string $language 翻译包的目录名
     */
    public static function setLanguage($language)
    {
        self::$language = $language;
        
        self::$message = array();
        
        self::addMessage(PHALAPI_ROOT);
        
        if (defined('API_ROOT')) {
            self::addMessage(API_ROOT);
        }
    }
    
    /**
     * 添加更多翻译
     *
     * - 为扩展类库或者外部提供更方便的方式追加翻译的内容
     *
     * @param string $path 待追加的路径
     * @return NULL
     */
    public static function addMessage($path)
    {
        $moreMessagePath = self::getMessageFilePath($path, self::$language);
        
        if (file_exists($moreMessagePath)) {
            self::$message = array_merge(self::$message, include $moreMessagePath);
        }
    }
    
    protected static function getMessageFilePath($root, $language)
    {
        return implode(DIRECTORY_SEPARATOR,
            array($root, 'Language', strtolower($language), 'common.php'));
    }
    
    /**
     * 取当前的语言
     */
    public static function getLanguage()
    {
        return self::$language;
    }
    
    
    /**
     * @param $query 要翻译的内容
     * @param string $to 目标言
     * @param string $from 翻译语言
     * @param string $tool 使用的翻译语言工具
     * @return string 翻译后的内容，翻译出错后返回原值
     */
    public static function translate($query, $to = 'en', $from = 'zh_CN', $tool = 'google')
    {
        
        if (empty($query)) {
            return '';
        }
        if ($to == $from) {
            return $query;
        }
        $ua = PhalApi_Translator::$userAgent[rand(0, count(PhalApi_Translator::$userAgent))];
        $headers = array(
            'Cache-Control:max-age=0',
            'User-Agent:' . $ua,
            'Accept:text/html',
            'Accept-Language:en-GB, en'
        );
        
        $post = 0;
        $fields = '';
        switch ($tool) {
            case 'baidu':
                switch ($from) {
                    case 'zh_TW':
                        $from = 'cht';
                        break;
                    case 'zh_CN':
                        $from = 'zh';
                        break;
                }
                switch ($to) {
                    case 'zh_TW':
                        $to = 'cht';
                        break;
                    case 'zh_CN':
                        $to = 'zh';
                        break;
                }
                $url = 'http://fanyi.baidu.com/v2transapi';
                $fields = array('from' => $from, 'to' => $to, 'query' => $query);
                $post = 1;
                $result_json = self::sendHttpRequest($url, $post, $fields, $headers);
                $result = json_decode($result_json);
                if (isset($result->trans_result->data[0]->dst)) {
                    return $result->trans_result->data[0]->dst;
                }
                break;
            default :
                if ($to == 'zh_cn' || $to == 'zh_tw') {
                    $from = 'en';
                }
                $url = 'http://translate.google.cn/translate_a/single?client=t&sl=' . $from . '&tl=' . $to . '&hl=' . $from . '&dt=t&tk=' . self::TL($query) . '&q=' . urlencode($query);
                $result = self::sendHttpRequest($url, $post, $fields, $headers);
                $pattern = array(
                    '/,+/',
                    '/\[,/'
                );
                $replace = array(
                    ',',
                    '['
                );
                $result = json_decode(preg_replace($pattern, $replace, $result));
                $str = '';
                if (is_array($result[0])) {
                    foreach ($result[0] as $rs) {
                        $str .= $rs[0];
                    }
                } else {
                    return PhalApi_Translator::translate($query, $to, $from, 'baidu');
                }
                
                return $str;
        }
        
        return $query;
        
    }
    
    /**
     * @param $url 请求的url
     * @param $post 是否post请求
     * @param array $fields 请求的参数
     * @param array $headers 请求的头部
     * @return mixed 请求响应的结果
     */
    private static function sendHttpRequest($url, $post, $fields = array(), $headers = array(), $timeoutMs = 3000)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        if ($post) {
            curl_setopt($ch, CURLOPT_POST, $post);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT_MS, $timeoutMs);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
    
    /**
     * 无符号32位右移
     * @param mixed $x 要进行操作的数字，如果是字符串，必须是十进制形式
     * @param string $bits 右移位数
     * @return mixed 结果，如果超出整型范围将返回浮点数
     */
    private static function shr32($x, $bits)
    {
        // 位移量超出范围的两种情况
        if ($bits <= 0) {
            return $x;
        }
        if ($bits >= 32) {
            return 0;
        }
        //转换成代表二进制数字的字符串
        $bin = decbin($x);
        $l = strlen($bin);
        //字符串长度超出则截取底32位，长度不够，则填充高位为0到32位
        if ($l > 32) {
            $bin = substr($bin, $l - 32, 32);
        } elseif ($l < 32) {
            $bin = str_pad($bin, 32, '0', STR_PAD_LEFT);
        }
        //取出要移动的位数，并在左边填充0
        return bindec(str_pad(substr($bin, 0, 32 - $bits), 32, '0', STR_PAD_LEFT));
    }
    
    /**
     *
     * @param $str 指定字符串
     * @param $index 指定位置
     * @return null|number 返回指定位置的字符的 Unicode 编码
     */
    private static function charCodeAt($str, $index)
    {
        $char = mb_substr($str, $index, 1, 'UTF-8');
        
        if (mb_check_encoding($char, 'UTF-8')) {
            $ret = mb_convert_encoding($char, 'UTF-32BE', 'UTF-8');
            return hexdec(bin2hex($ret));
        } else {
            return null;
        }
    }
    
    /**
     * 获取google翻译的tk参数
     * @param $a  要翻译的内容
     * @return float 根据内容返回指定google参数
     */
    private static function TL($a)
    {
        
        $tkk = explode('.', self::TKK());
        $b = (float)$tkk[0];//数据切割后强转为数字类型
        
        for ($d = array(), $e = 0, $f = 0; $f < mb_strlen($a, 'UTF-8'); $f++) {
            $g = self::charCodeAt($a, $f);
            if (128 > $g) {
                $d [$e++] = $g;
            } else {
                if (2048 > $g) {
                    $d [$e++] = $g >> 6 | 192;
                } else {
                    if (55296 == ($g & 64512) && $f + 1 < mb_strlen($a, 'UTF-8') && 56320 == (self::charCodeAt($a, $f + 1) & 64512)) {
                        $g = 65536 + (($g & 1023) << 10) + (self::charCodeAt($a, ++$f) & 1023);
                        $d [$e++] = $g >> 18 | 240;
                        $d [$e++] = $g >> 12 & 63 | 128;
                    } else {
                        $d [$e++] = $g >> 12 | 224;
                        $d [$e++] = $g >> 6 & 63 | 128;
                    }
                }
                $d [$e++] = $g & 63 | 128;
            }
        }
        $a = $b;
        for ($e = 0; $e < count($d); $e++) {
            $a += $d [$e];
            $a = self::RL($a, '+-a^+6');
        }
        $a = self::RL($a, "+-3^+b+-f");
        $a ^= (float)$tkk[1];//数据切割后强转为数字类型
        if (0 > $a) {
            $a = ($a & 2147483647) + 2147483648;
        }
        $a = fmod($a, pow(10, 6));
        return $a . "." . ($a ^ $b);
    }
    
    
    /**
     * google翻译的内容参数算法加密算出一个值
     * @param $a
     * @param $b
     * @return int
     */
    private static function RL($a, $b)
    {
        for ($c = 0; $c < strlen($b) - 2; $c += 3) {
            $d = $b{$c + 2};
            $d = $d >= 'a' ? self::charCodeAt($d, 0) - 87 : intval($d);
            $d = $b{$c + 1} == '+' ? self::shr32($a, $d) : $a << $d;
            $a = $b{$c} == '+' ? ($a + $d & 4294967295) : $a ^ $d;
        }
        return $a;
    }
    
    
    /**
     * 获取google参数
     * @return string
     */
    private static function TKK()
    {
        //    unset($_SESSION['engine_google_token']);
        $a = 561666268;
        $b = 1526272306;
        return 406398 . '.' . ($a + $b);
        
        if (isset($_SESSION['engine_google_token']) && !empty($_SESSION['engine_google_token'])) {
            return $_SESSION['engine_google_token'];
        }
        
        
        // Random user agents DB
        $ua = PhalApi_Translator::$userAgent[rand(0, count(PhalApi_Translator::$userAgent))];
        /*if(isset($_SERVER['HTTP_USER_AGENT'])){
            $ua = $_SERVER['HTTP_USER_AGENT'];
        }else{
            $ua = Translation::$userAgent[rand(0,count(Translation::$userAgent))];
        }*/
        
        $_SESSION['User-Agent'] = $ua;
        
        
        $headers = array(
            'Cache-Control:max-age=0',
            'User-Agent:' . $ua,
            'Accept:text/html',
            'Referer:http://translate.google.cn/',
            'Accept-Language:en-GB, en'
        );
        
        $bodyResponsePage = self::sendHttpRequest("http://translate.google.cn", 0, '', $headers);
        //if ( isset($_GET['debug']) ) var_dump($bodyResponsePage);
        
        preg_match('/TKK.*return\s?-?\d+/', $bodyResponsePage, $AandBArray);
        
        $periodsExploded = explode(';', $AandBArray[0]);
        
        // First var $a
        $aExploded = explode('\x3d', $periodsExploded[0]);
        $a = $aExploded[1];
        
        // Second var $b
        $bExploded = explode('\x3d', $periodsExploded[1]);
        $b = $bExploded[1];
        
        // Third var hours - Unix elapsed
        $hoursExploded = explode('return', $periodsExploded[2]);
        $hoursElapsed = trim($hoursExploded[1]);
        
        $_SESSION['engine_google_token'] = $hoursElapsed . '.' . ($a + $b);
        
        //if ( isset($_GET['debug']) ) var_dump($_SESSION['engine_google_token']);
        
        return $hoursElapsed . '.' . ($a + $b);
    }
    
    
}

