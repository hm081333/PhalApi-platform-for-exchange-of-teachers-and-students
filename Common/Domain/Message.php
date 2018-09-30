<?php
/**
 * Created by PhpStorm.
 * User: LYi-Ho
 * Date: 2018/8/4
 * Time: 11:53
 */

class Domain_message
{
    
    public static function getTypeNames($type = false)
    {
        $types = [
            0 => '邮件',
            1 => '短信',
        ];
        if ($type !== false) {
            return isset($types[$type]) ? $types[$type] : false;
        }
        return $types;
    }
    
    
}