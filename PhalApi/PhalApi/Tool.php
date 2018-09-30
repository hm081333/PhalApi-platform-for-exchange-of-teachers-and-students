<?php

/**
 * PhalApi_Tool 工具集合类
 * 只提供通用的工具类操作，目前提供的有：
 * - IP地址获取
 * - 随机字符串生成
 * @package     PhalApi\Tool
 * @license     http://www.phalapi.net/license GPL 协议
 * @link        http://www.phalapi.net/
 * @author      dogstar <chanzonghuang@gmail.com> 2015-02-12
 */
class PhalApi_Tool
{
    
    /**
     * IP地址获取
     * @return string 如：192.168.1.1 失败的情况下，返回空
     */
    public function getClientIp()
    {
        $unknown = 'unknown';
        if (getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), $unknown)) {
            $ip = getenv('HTTP_CLIENT_IP');
        } else if (getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), $unknown)) {
            $ip = getenv('HTTP_X_FORWARDED_FOR');
        } else if (getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), $unknown)) {
            $ip = getenv('REMOTE_ADDR');
        } else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], $unknown)) {
            $ip = $_SERVER['REMOTE_ADDR'];
        } else {
            $ip = '';
        }
        
        return $ip;
    }
    
    /**
     * 随机字符串生成
     *
     * @param int $len 需要随机的长度，不要太长
     * @param string $chars 随机生成字符串的范围
     *
     * @return string
     */
    public function createRandStr($len, $chars = null)
    {
        if (!$chars) {
            $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        }
        
        return substr(str_shuffle(str_repeat($chars, rand(5, 8))), 0, $len);
    }
    
    /**
     * 生成一个字节的伪随机字符串
     * @param $len
     * @return string
     */
    public function createPseudoBytes($len)
    {
        return openssl_random_pseudo_bytes($len);
    }
    
    /**
     * 获取数组value值不存在时返回默认值
     * 不建议在大循环中使用会有效率问题
     *
     * @param array $arr 数组实例
     * @param string|int $key 数据key值
     * @param string $default 默认值
     *
     * @return string
     */
    public function arrIndex($arr, $key, $default = '')
    {
        
        return isset($arr[$key]) ? $arr[$key] : $default;
    }
    
    /**
     * 根据路径创建目录或文件
     *
     * @param string $path 需要创建目录路径
     *
     * @throws PhalApi_Exception_BadRequest
     */
    public function createDir($path)
    {
        
        $dir = explode('/', $path);
        $path = '';
        foreach ($dir as $element) {
            $path .= $element . '/';
            if (!is_dir($path) && !mkdir($path)) {
                throw new PhalApi_Exception_BadRequest(
                    T('create file path Error: {filePath}', array('filepath' => $path))
                );
            }
        }
    }
    
    /**
     * 删除目录以及子目录等所有文件
     *
     * - 请注意不要删除重要目录！
     *
     * @param string $path 需要删除目录路径
     */
    public function deleteDir($path)
    {
        
        $dir = opendir($path);
        while (false !== ($file = readdir($dir))) {
            if (($file != '.') && ($file != '..')) {
                $full = $path . '/' . $file;
                if (is_dir($full)) {
                    $this->deleteDir($full);
                } else {
                    unlink($full);
                }
            }
        }
        closedir($dir);
        rmdir($path);
    }
    
    /**
     * 清空目录以及子目录等所有文件--不删除目录
     * @param $path
     */
    public function emptyDir($path)
    {
        
        $dir = opendir($path);
        while (false !== ($file = readdir($dir))) {
            if (($file != '.') && ($file != '..')) {
                $full = $path . '/' . $file;
                if (is_dir($full)) {
                    $this->deleteDir($full);
                } else {
                    unlink($full);
                }
            }
        }
        closedir($dir);
    }
    
    /**
     * 遍历目录。。。无限遍历--注意超时！
     * @param $path
     * @param string $dir_name
     * @param int $i
     * @param array $all
     * @return array
     *      sort() 函数用于对数组单元从低到高进行排序。
     *      rsort() 函数用于对数组单元从高到低进行排序。
     *      asort() 函数用于对数组单元从低到高进行排序并保持索引关系。
     *      arsort() 函数用于对数组单元从高到低进行排序并保持索引关系。
     *      ksort() 函数用于对数组单元按照键名从低到高进行排序。
     *      krsort() 函数用于对数组单元按照键名从高到低进行排序。
     */
    Public function dirFile($path, $dir_name = '', $i = 0, $all = array())
    {
        $dir = opendir($path);//打开目录
        while (($file = readdir($dir)) != false) {
            //逐个文件读取，添加!=false条件，是为避免有文件或目录的名称为0
            if ($file == '.' || $file == '..') {//判断是否为.或..，默认都会有
                continue;
            }
            if (is_dir($path . '/' . $file)) {//如果为目录
                $rs = $this->dirFile($path . '/' . $file, $file);//继续读取该目录下的目录或文件
                $all += $rs;
            } else {
                $i += 1;
                if ($dir_name == '') {
                    $all[$i] = $file;
                } else {
                    $all[$dir_name][$i] = $file;
                }
            }
        }
        closedir($dir);
        return $all;
    }
    
    /**
     * 数组转XML格式
     *
     * @param array $arr 数组
     * @param string $root 根节点名称
     * @param int $num 回调次数
     *
     * @return string xml
     */
    public function arrayToXml($arr, $root = 'xml', $num = 0)
    {
        $xml = '';
        if (!$num) {
            $num += 1;
            $xml .= '<?xml version="1.0" encoding="utf-8"?>';
        }
        $xml .= "<$root>";
        foreach ($arr as $key => $val) {
            if (is_array($val)) {
                $xml .= self::arrayToXml($val, "$key", $num);
            } else {
                $xml .= "<" . $key . ">" . $val . "</" . $key . ">";
            }
        }
        $xml .= "</$root>";
        return $xml;
    }
    
    /**
     * XML格式转数组
     *
     * @param  string $xml
     *
     * @return mixed|array
     */
    public function xmlToArray($xml)
    {
        //禁止引用外部xml实体
        libxml_disable_entity_loader(true);
        $xmlstring = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
        $arr = json_decode(json_encode($xmlstring), true);
        return $arr;
    }
    
    /**
     * 去除字符串空格和回车
     *
     * @param  string $str 待处理字符串
     *
     * @return string
     */
    public function trimSpaceInStr($str)
    {
        $pat = array(" ", "　", "\t", "\n", "\r");
        $string = array("", "", "", "", "",);
        return str_replace($pat, $string, $str);
    }
    
    /**
     * 验证手机号码
     * @param string $mobile 手机号码
     * @return bool
     */
    public function isMobile($mobile)
    {
        return preg_match('/^13[\d]{9}$|^14[5,7]{1}\d{8}$|^15[^4]{1}\d{8}$|^17[0,3,6,7,8]{1}\d{8}$|^18[\d]{9}$/', $mobile) ? true : false;
    }
    
    
    /**
     * 验证邮箱格式
     * @desc 验证邮箱格式
     * @param string $email
     * @return bool
     */
    public function isEmail($email)
    {
        return strcmp($email, filter_var($email, FILTER_VALIDATE_EMAIL)) === 0 ? true : false;
    }
    
    /**
     * @param string $data
     * @return string
     */
    public function decode($data)
    {
        $privateKey = '@fdskalhfj2387A!';
        $iv = '@fdfpu+adj2387A!';
        $encryptedData = base64_decode($data);
        $decrypted = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $privateKey, $encryptedData, MCRYPT_MODE_CBC, $iv);
        $decrypted = rtrim($decrypted, "\0");//解密出来的数据后面会出现如图所示的六个红点；这句代码可以处理掉，从而不影响进一步的数据操作
        return $decrypted;
    }
    
    /**
     * @param string $data
     * @return string
     */
    public function encode($data)
    {
        $privateKey = '@fdskalhfj2387A!';
        $iv = '@fdfpu+adj2387A!';
        $encrypted = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $privateKey, $data, MCRYPT_MODE_CBC, $iv);
        $encode = base64_encode($encrypted);
        return $encode;
    }
    
    /**
     * OPEN_SSL加密
     * @param string $data
     * @return string
     */
    function encrypt($data, $method = 'AES-256-CFB')
    {
        $privateKey = '@fdskalhfj2387A!';
        $iv = '@fdfpu+adj2387A!';
        $encrypted = openssl_encrypt($data, $method, $privateKey, OPENSSL_ZERO_PADDING, $iv);
        $encode = base64_encode($encrypted);
        //openssl_encrypt 加密相当于将 mcrypt_encrypt 的加密结果执行一次 base64_encode
        return $encode;
    }
    
    /**
     * OPEN_SSL解密
     * @param string $data
     * @return string
     */
    function decrypt($data, $method = 'AES-256-CFB')
    {
        $privateKey = '@fdskalhfj2387A!';
        $iv = '@fdfpu+adj2387A!';
        //openssl_decode 解密相当于 先将加密结果执行一次base64_decode 然后再通过mcrypt_encrypt 解密
        $encryptedData = base64_decode($data);
        $decrypted = openssl_decrypt($encryptedData, $method, $privateKey, OPENSSL_ZERO_PADDING, $iv);
        //$decrypted = rtrim($decrypted, "\0");//解密出来的数据后面会出现如图所示的六个红点；这句代码可以处理掉，从而不影响进一步的数据操作
        return $decrypted;
    }
    
    /**
     * 是否微信打开
     * @return bool
     */
    public function is_weixin()
    {
        if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false) {
            return true;
        }
        return false;
    }
    
    public function is_mobile_request()
    {
        $_SERVER['ALL_HTTP'] = isset($_SERVER['ALL_HTTP']) ? $_SERVER['ALL_HTTP'] : '';
        $mobile_browser = '0';
        if (preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|iphone|ipad|ipod|android|xoom)/i', strtolower($_SERVER['HTTP_USER_AGENT'])))
            $mobile_browser++;
        if ((isset($_SERVER['HTTP_ACCEPT'])) and (strpos(strtolower($_SERVER['HTTP_ACCEPT']), 'application/vnd.wap.xhtml+xml') !== false))
            $mobile_browser++;
        if (isset($_SERVER['HTTP_X_WAP_PROFILE']))
            $mobile_browser++;
        if (isset($_SERVER['HTTP_PROFILE']))
            $mobile_browser++;
        $mobile_ua = strtolower(substr($_SERVER['HTTP_USER_AGENT'], 0, 4));
        $mobile_agents = array(
            'w3c ', 'acs-', 'alav', 'alca', 'amoi', 'audi', 'avan', 'benq', 'bird', 'blac',
            'blaz', 'brew', 'cell', 'cldc', 'cmd-', 'dang', 'doco', 'eric', 'hipt', 'inno',
            'ipaq', 'java', 'jigs', 'kddi', 'keji', 'leno', 'lg-c', 'lg-d', 'lg-g', 'lge-',
            'maui', 'maxo', 'midp', 'mits', 'mmef', 'mobi', 'mot-', 'moto', 'mwbp', 'nec-',
            'newt', 'noki', 'oper', 'palm', 'pana', 'pant', 'phil', 'play', 'port', 'prox',
            'qwap', 'sage', 'sams', 'sany', 'sch-', 'sec-', 'send', 'seri', 'sgh-', 'shar',
            'sie-', 'siem', 'smal', 'smar', 'sony', 'sph-', 'symb', 't-mo', 'teli', 'tim-',
            'tosh', 'tsm-', 'upg1', 'upsi', 'vk-v', 'voda', 'wap-', 'wapa', 'wapi', 'wapp',
            'wapr', 'webc', 'winw', 'winw', 'xda', 'xda-'
        );
        if (in_array($mobile_ua, $mobile_agents))
            $mobile_browser++;
        if (strpos(strtolower($_SERVER['ALL_HTTP']), 'operamini') !== false)
            $mobile_browser++;
        // Pre-final check to reset everything if the user is on Windows
        if (strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'windows') !== false)
            $mobile_browser = 0;
        // But WP7 is also Windows, with a slightly different characteristic
        if (strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'windows phone') !== false)
            $mobile_browser++;
        if ($mobile_browser > 0)
            return true;
        else
            return false;
    }
    
    /**
     * PHP压缩html js css的函数
     * 激进
     * 清除换行符,清除制表符,去掉注释标记
     *
     * 网页里面的js代码中不要使用//行注释
     * /* 块注释会自动剔除
     *
     * 函数自动剔除标记之间多余的空白
     *
     * 判断标记的属性的属性值是否被""包裹之间
     * 如果有就剔除属性和属性值之间的所有空格
     * 如果没有""就保留一个空格，避免破坏html结构。
     *
     * @param $string string HTML内容
     * @return $string 压缩后HTML内容
     */
    public function compress_html($string)
    {
        $string = str_replace("\r\n", '', $string); //清除换行符
        $string = str_replace("\n", '', $string); //清除换行符
        $string = str_replace("\t", '', $string); //清除制表符
        $pattern = array(//去掉注释标记
            "/> *([^ ]*) *</",
            "/[\s]+/",
            "/<!--[\\w\\W\r\\n]*?-->/",
            "/\" /",
            "/ \"/",
            "'/\*[^*]*\*/'"
        );
        $replace = array(
            ">\\1<",
            " ",
            "",
            "\"",
            "\"",
            ""
        );
        return preg_replace($pattern, $replace, $string);
    }
    
    /**
     * higrid.net 的 php压缩html函数
     * 没看懂
     * @param $higrid_uncompress_html_source
     * @return string
     */
    public function higrid_compress_html($higrid_uncompress_html_source)
    {
        $chunks = preg_split('/(<pre.*?\/pre>)/ms', $higrid_uncompress_html_source, -1, PREG_SPLIT_DELIM_CAPTURE);
        $higrid_uncompress_html_source = '';//[higrid.net]修改压缩html : 清除换行符,清除制表符,去掉注释标记
        foreach ($chunks as $c) {
            if (strpos($c, '<pre') !== 0) {
                //[higrid.net] remove new lines & tabs
                $c = preg_replace('/[\\n\\r\\t]+/', ' ', $c);
                // [higrid.net] remove extra whitespace
                $c = preg_replace('/\\s{2,}/', ' ', $c);
                // [higrid.net] remove inter-tag whitespace
                $c = preg_replace('/>\\s</', '><', $c);
                // [higrid.net] remove CSS & JS comments
                $c = preg_replace('/\\/\\*.*?\\*\\//i', '', $c);
            }
            $higrid_uncompress_html_source .= $c;
        }
        return $higrid_uncompress_html_source;
    }
    
    public function staticPath($path)
    {
        return URL_ROOT . 'static/' . $path;
    }
    
    /**
     * @param $fileName string 上传文件名
     * @param string $path 相对路径-文件夹名
     * @return array|string
     */
    public function upLoadImage($fileName, $path = 'pics')
    {
        if ($_FILES[$fileName]["error"] > 0) {
            return T('上传图片失败: {error}', array('error' => $_FILES[$fileName]['error']));
        } else {
            list ($width, $height, $type, $attr) = getimagesize($_FILES [$fileName] ['tmp_name']);
            switch ($type) {
                case 1 :
                    $ext = ".GIF";
                    break;
                case 2 :
                    $ext = ".JPG";
                    break;
                case 3 :
                    $ext = ".PNG";
                    break;
                default :
                    $ext = "...";
                    break;
            }
            if ($ext == '...') {
                return T('图片格式错误');
            }
            
            $info = '/upload/' . $path . '/' . date('Ym');
            $dir = API_ROOT . '/Public/static' . $info;
            if (!file_exists($dir)) {
                mkdir($dir, 0777);
            }
            
            $url = '/' . NOW_TIME . $ext;
            if (!@move_uploaded_file($_FILES[$fileName]['tmp_name'], $dir . $url)) {
                return T('上传失败');
            }
            @unlink($_FILES[$fileName]['tmp_name']);
            return array('url' => $info . $url);
        }
    }
    
    /**
     * 使用反斜线引用字符串或数组以便于SQL查询
     * 只引用'和\
     * @param string|array $s 需要转义的
     * @return string|array 转义结果
     */
    Public function sqlAdds($s)
    {
        if (is_array($s)) {
            $r = array();
            foreach ($s as $key => $value) {
                $k = str_replace('\'', '\\\'', str_replace('\\', '\\\\', $value));
                if (!is_array($value)) {
                    $r[$k] = str_replace('\'', '\\\'', str_replace('\\', '\\\\', $value));
                } else {
                    $r[$k] = $this->sqlAdds($value);
                }
            }
            return $r;
        } else {
            return str_replace('\'', '\\\'', str_replace('\\', '\\\\', $s));
        }
    }
    
    /**
     * 获取两段文本之间的文本
     * @param string $text 完整的文本
     * @param string $left 左边文本
     * @param string $right 右边文本
     * @return string “左边文本”与“右边文本”之间的文本
     */
    Public function textMiddle($text, $left, $right)
    {
        $loc1 = stripos($text, $left);
        if (is_bool($loc1)) {
            return "";
        }
        $loc1 += strlen($left);
        $loc2 = stripos($text, $right, $loc1);
        if (is_bool($loc2)) {
            return "";
        }
        return substr($text, $loc1, $loc2 - $loc1);
    }
    
    /**
     * 执行一个通配符表达式匹配
     * [可当preg_match()的简化版本去理解]
     * @param string $exp 匹配表达式
     * @param string $str 在这个字符串内运行匹配
     * @param int $pat 规定匹配模式，0表示尽可能多匹配，1表示尽可能少匹配
     * @return array 匹配结果，$matches[0]将包含完整模式匹配到的文本， $matches[1] 将包含第一个捕获子组匹配到的文本，以此类推。
     */
    Public function easy_match($exp, $str, $pat = 0)
    {
        $exp = str_ireplace('\\', '\\\\', $exp);
        $exp = str_ireplace('/', '\/', $exp);
        $exp = str_ireplace('?', '\?', $exp);
        $exp = str_ireplace('<', '\<', $exp);
        $exp = str_ireplace('>', '\>', $exp);
        $exp = str_ireplace('^', '\^', $exp);
        $exp = str_ireplace('$', '\$', $exp);
        $exp = str_ireplace('+', '\+', $exp);
        $exp = str_ireplace('(', '\(', $exp);
        $exp = str_ireplace(')', '\)', $exp);
        $exp = str_ireplace('[', '\[', $exp);
        $exp = str_ireplace(']', '\]', $exp);
        $exp = str_ireplace('|', '\|', $exp);
        $exp = str_ireplace('}', '\}', $exp);
        $exp = str_ireplace('{', '\{', $exp);
        if ($pat == 0) {
            $z = '(.*)';
        } else {
            $z = '(.*?)';
        }
        $exp = str_ireplace('*', $z, $exp);
        $exp = '/' . $exp . '/';
        preg_match($exp, $str, $r);
        return $r;
    }
    
    /**
     * 拼接联合查询sql语句条件
     * @param $conditions 查询条件 例如 $where['u.id=?']=1;$where['type>=?']=1
     * @param bool $where_flag 是否拼接 sql where条件
     * @return array 返回sql 语句和查询条件 返回结果例如 array('sql'=>' u.id=? and type>=?','params'=>array(1,1))
     */
    public function parseSearchWhere($conditions, $where_flag = false, $trim = true)
    {
        $where = array();
        $where['sql'] = '';
        $where['params'] = array();
        $keys = '';
        
        foreach ($conditions as $key => $condition) {//循环拼接sql语句
            $keys .= $key . ' and ';
            $where['params'][] = $condition;
        }
        if ($trim) {
            $keys = trim($keys, 'and ');
        }
        if ($where_flag && $keys) {//判断查询条件是否需要where语句，需要加上where
            $where['sql'] = ' where ';
        }
        $where['sql'] .= $keys;
        return $where;
    }
    
    
}