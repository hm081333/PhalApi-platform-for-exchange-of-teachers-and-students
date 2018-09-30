<?php

/**
 * Created by PhpStorm.
 * User: LYi-Ho
 * Date: 2017/6/23
 * Time: 下午 11:49
 */
class Common_Function
{
    
    
    /**
     * @param $question string 问题
     * @return array
     */
    public static function tuling($question)
    {
        $tuling_url = 'http://www.tuling123.com/openapi/api';
        $setting = Domain_Setting::getSetting('tuling');
        $rs = DI()->curl->json_post($tuling_url, array('key' => $setting['api_key'], 'info' => $question));
        return $rs;
    }
    
    public static function baidu_map($data, $data_type = 'location')
    {
        $baidu_map_config = DI()->config->get('app.baidu_map_config');
        //API控制台申请得到的ak（此处ak值仅供验证参考使用）
        $ak = $baidu_map_config['ak'];
        //应用类型为for server, 请求校验方式为sn校验方式时，系统会自动生成sk，可以在应用配置-设置中选择Security Key显示进行查看（此处sk值仅供验证参考使用）
        $sk = $baidu_map_config['sk'];
        //以Geocoding服务为例，地理编码的请求url，参数待填
        if ($data_type == 'address') {
            $url = "http://api.map.baidu.com/geocoder/v2/?address=%s&output=%s&ak=%s&sn=%s";
        } elseif ($data_type == 'location') {
            $url = 'http://api.map.baidu.com/geocoder/v2/?location=%s&output=%s&ak=%s&sn=%s';
        }
        //get请求uri前缀
        $uri = '/geocoder/v2/';
        //地理编码的请求output参数
        $output = 'json';
        //构造请求串数组
        $param = array(
            $data_type => $data,
            'output' => $output,
            'ak' => $ak
        );
        //调用sn计算函数，默认get请求
        $sn = self::caculateAKSN($ak, $sk, $uri, $param);
        //请求参数中有中文、特殊字符等需要进行urlencode，确保请求串与sn对应
        $request = sprintf($url, urlencode($data), $output, $ak, $sn);
        //执行请求的url
        $rs = DI()->curl->json_get($request);
        if ($rs['status'] !== 0) {
            DI()->logger->error('百毒地图API报错，错误码：' . $rs['status'] . '，错误信息：' . $rs['message']);
        }
        return $rs;
    }
    
    public static function caculateAKSN($ak, $sk, $url, $param, $method = 'GET')
    {
        if ($method === 'POST') {
            ksort($param);
        }
        $querystring = http_build_query($param);
        return md5(urlencode($url . '?' . $querystring . $sk));
    }
    
    public static function getImage($url = '', $fileName = '', $path = 'wechat')
    {
        $dir = API_ROOT . '/Public/static/upload/' . $path;
        $result = DI()->curl->getFile($url, $dir . '/', $fileName . '.jpg');
    }
    
    /**
     * @param $code string 快递公司代码
     * @param $sn string 快递单号
     */
    public static function getLogistics($code, $sn)
    {
        return DI()->curl->json_get('http://www.kuaidi100.com/query?type=' . $code . '&postid=' . $sn);
    }
    
    
}