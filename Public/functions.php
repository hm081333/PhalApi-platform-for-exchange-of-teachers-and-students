<?php
/**
 * Created by PhpStorm.
 * User: LYi-Ho
 * Date: 2018/8/5
 * Time: 11:29
 */

function unix_formatter($time = false, $full = false)
{
    if ($time <= 0) {
        return '-';
    }
    if ($full) {
        return date('Y-m-d H:i:s', $time);
    } else {
        return date('Y-m-d', $time);
    }
}

function url($Api = 'Default.Index', $param = [])
{
    if (is_array($param)) {
        $param = http_build_query($param);
    }
    return NOW_WEB_SITE . "?service={$Api}&{$param}";
}

function path_url($path = '')
{
    return URL_ROOT . 'static/' . $path;
}

/**
 * PHP-HTTP 断点续传 & 多线程下载 实现
 * @param string $path : 文件所在路径
 * @param string $file : 文件名
 * @return bool
 */
function download($path, $file)
{
    $real = $path . $file;
    if (!file_exists($real)) {
        header('HTTP/1.1 404 Not Found');
        fourZeroFour();
    }
    //注意文件不能大于2G
    $len = filesize($real);
    // $filename = basename($real);
    //类型设定为强制下载
    $ctype = "application/force-download";
    header("Cache-Control: public");
    header("Pragma: public");
    header("Content-Type: $ctype");
    //设定文件名
    //解决在IE中下载时中文乱码问题
    $ua = $_SERVER['HTTP_USER_AGENT'];
    if (preg_match('/MSIE/', $ua)) {// 表示正在使用 Internet Explorer。
        $ie_filename = str_replace('+', '%20', urlencode(basename($real)));
        header('Content-Disposition: attachment; filename=' . $ie_filename);
    } else {
        header("Content-Disposition: attachment; filename=" . basename($real));
    }
    // header("Content-Disposition: attachment; filename=\"$filename\"");
    header("Accept-Ranges: bytes");
    header("Accept-Length: " . $len);
    
    //范围默认从0开始
    $range_start = 0;
    //范围默认包括结尾
    $range_end = $len - 1;
    $content_length = $len;
    //检查请求是否包含Range段，
    //如果包括，取出并修改$range_start和$range_end
    if (isset($_SERVER['HTTP_RANGE'])) { //http_range表示请求一个实体/文件的一个部分,用这个实现多线程下载和断点续传！
        list($a, $range_info) = explode("=", $_SERVER['HTTP_RANGE']);
        preg_match("/(\d*)-(\d*)/", $range_info, $matches);
        if ($matches[1]) {
            $range_start = (int)$matches[1];
        } else {
            if ($matches[2]) {
                $range_start = $len - (int)$matches[2];
            }
        }
        $content_length = $range_end - $range_start + 1;
        header("HTTP/1.1 206 Partial Content");
    } else {
        header("HTTP/1.1 200 OK");
    }
    header("Content-Length: $content_length");
    header("Content-Range: bytes $range_start-$range_end/$len");
    
    $fp = fopen("$real", "rb+");// 打开文件
    fseek($fp, $range_start); //fseek:在打开的文件中定位,该函数把文件指针从当前位置向前或向后移动到新的位置，新位置从文件头开始以字节数度量。成功则返回 0；否则返回 -1。注意，移动到 EOF 之后的位置不会产生错误。
    while (!feof($fp)) { //feof:检测是否已到达文件末尾 (eof)
        set_time_limit(0);
        print(fread($fp, 1024 * 8)); //读取文件（可安全用于二进制文件,第二个参数:规定要读取的最大字节数）
        flush(); //刷新缓冲区的内容(严格来讲, 这个只有在PHP做为apache的Module(handler或者filter)安装的时候, 才有实际作用. 它是刷新WebServer(可以认为特指apache)的缓冲区.)
        ob_flush(); //刷新PHP自身的缓冲区
    }
    fclose($fp);
    exit;
}