<?php
/**
 * 请在下面放置任何您需要的应用配置
 */

return array(
    
    /**
     * 应用接口层的统一参数
     */
    'apiCommonRules' => array(//'sign' => array('name' => 'sign', 'require' => true),
    ),
    
    /**
     * 接口服务白名单，格式：接口服务类名.接口服务方法名
     *
     * 示例：
     * - *.*            通配，全部接口服务，慎用！
     * - Default.*      Api_Default接口类的全部方法
     * - *.Index        全部接口类的Index方法
     * - Default.Index  指定某个接口服务，即Api_Default::Index()
     */
    /*'service_whitelist' => array(
        'Default.Index',
    ),*/
    
    'Wechat' => array(
        'plugins' => array(
            Wechat_InMessage::MSG_TYPE_TEXT => array('Plugin_Text',),
            Wechat_InMessage::MSG_TYPE_IMAGE => array('Plugin_Image',),
            Wechat_InMessage::MSG_TYPE_VOICE => array('Plugin_Voice',),
            Wechat_InMessage::MSG_TYPE_VIDEO => array('Plugin_Video',),
            Wechat_InMessage::MSG_TYPE_LOCATION => array('Plugin_Location',),
            Wechat_InMessage::MSG_TYPE_LINK => array('Plugin_Link',),
            Wechat_InMessage::MSG_TYPE_EVENT => array('Plugin_Event',),
            Wechat_InMessage::MSG_TYPE_DEVICE_EVENT => array('Plugin_DeviceEvent',),
            Wechat_InMessage::MSG_TYPE_DEVICE_TEXT => array('Plugin_DeviceText',),
        )
    ),
    
    'music_type_list' => array(
        'netease' => '网易',
        'qq' => 'ＱＱ',
        'kugou' => '酷狗',
        'kuwo' => '酷我',
        'xiami' => '虾米',
        'baidu' => '百度',
        '1ting' => '一听',
        'migu' => '咪咕',
        'lizhi' => '荔枝',
        'qingting' => '蜻蜓',
        'ximalaya' => '喜马拉雅',
        '5singyc' => '5sing 原创',
        '5singfc' => '5sing 翻唱',
        'soundcloud' => 'SoundCloud'
    ),
    
    'music_valid_patterns' => array(
        'name' => '/^.+$/i',
        'id' => '/^[\w\/\|]+$/i',
        'url' => '/^https?:\/\/\S+$/i'
    ),


);
