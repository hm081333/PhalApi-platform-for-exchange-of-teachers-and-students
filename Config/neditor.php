<?php
return array( //前后端通信相关的配置,注释只允许使用多行方式
    /*上传图片配置项*/
    'imageActionName' => 'uploadimage', //执行上传图片的action名称
    'imageFieldName' => 'upfile', //提交的图片表单名称
    'imageMaxSize' => 2048000, //上传大小限制，单位B
    'imageAllowFiles' => //上传图片格式显示
        array(
            0 => '.png',
            1 => '.jpg',
            2 => '.jpeg',
            3 => '.gif',
            4 => '.bmp',
        ),
    'imageCompressEnable' => true, //是否压缩图片,默认是true
    'imageCompressBorder' => 1600, //图片压缩最长边限制
    'imageInsertAlign' => 'none', //插入的图片浮动方式
    'imageUrlPrefix' => '', //图片访问路径前缀
    'imagePathFormat' => 'static/upload/neditor/image/{yyyy}{mm}{dd}/{time}{rand:6}', //上传保存路径,可以自定义保存路径和文件名格式
    /**
     * {filename} 会替换成原文件名,配置这项需要注意中文乱码问题
     * {rand:6} 会替换成随机数,后面的数字是随机数的位数
     * {time} 会替换成时间戳
     * {yyyy} 会替换成四位年份
     * {yy} 会替换成两位年份
     * {mm} 会替换成两位月份
     * {dd} 会替换成两位日期
     * {hh} 会替换成两位小时
     * {ii} 会替换成两位分钟
     * {ss} 会替换成两位秒
     * 非法字符 \ : * ? " < > |
     * 具请体看线上文档: fex.baidu.com/neditor/#use-format_upload_filename
     */
    /*涂鸦图片上传配置项*/
    'scrawlActionName' => 'uploadscrawl', //执行上传涂鸦的action名称
    'scrawlFieldName' => 'upfile', //提交的图片表单名称
    'scrawlPathFormat' => 'static/upload/neditor/image/{yyyy}{mm}{dd}/{time}{rand:6}', //上传保存路径,可以自定义保存路径和文件名格式
    'scrawlMaxSize' => 2048000, //上传大小限制，单位B
    'scrawlUrlPrefix' => '', //图片访问路径前缀
    'scrawlInsertAlign' => 'none',
    'scrawlAllowFiles' => //上传图片格式显示
        array(
            0 => '.png',
            1 => '.jpg',
            2 => '.jpeg',
            3 => '.gif',
            4 => '.bmp',
        ),
    /*截图工具上传*/
    'snapscreenActionName' => 'uploadimage', //执行上传截图的action名称
    'snapscreenPathFormat' => 'static/upload/neditor/image/{yyyy}{mm}{dd}/{time}{rand:6}', //上传保存路径,可以自定义保存路径和文件名格式
    'snapscreenUrlPrefix' => '', //图片访问路径前缀
    'snapscreenInsertAlign' => 'none', //插入的图片浮动方式
    /*抓取远程图片配置*/
    'catcherLocalDomain' =>
        array(
            0 => '127.0.0.1',
            1 => 'localhost',
            2 => 'img.baidu.com',
        ),
    'catcherActionName' => 'catchimage', //执行抓取远程图片的action名称
    'catcherFieldName' => 'source', //提交的图片列表表单名称
    'catcherPathFormat' => 'static/upload/neditor/image/{yyyy}{mm}{dd}/{time}{rand:6}', //上传保存路径,可以自定义保存路径和文件名格式
    'catcherUrlPrefix' => '', //图片访问路径前缀
    'catcherMaxSize' => 2048000, //上传大小限制，单位B
    'catcherAllowFiles' => //抓取图片格式显示
        array(
            0 => '.png',
            1 => '.jpg',
            2 => '.jpeg',
            3 => '.gif',
            4 => '.bmp',
        ),
    /*上传视频配置*/
    'videoActionName' => 'uploadvideo', //执行上传视频的action名称
    'videoFieldName' => 'upfile', //提交的视频表单名称
    'videoPathFormat' => 'static/upload/neditor/video/{yyyy}{mm}{dd}/{time}{rand:6}', //上传保存路径,可以自定义保存路径和文件名格式
    'videoUrlPrefix' => '', //视频访问路径前缀
    'videoMaxSize' => 102400000, //上传大小限制，单位B，默认100MB
    'videoAllowFiles' => //上传视频格式显示
        array(
            0 => '.flv',
            1 => '.swf',
            2 => '.mkv',
            3 => '.avi',
            4 => '.rm',
            5 => '.rmvb',
            6 => '.mpeg',
            7 => '.mpg',
            8 => '.ogg',
            9 => '.ogv',
            10 => '.mov',
            11 => '.wmv',
            12 => '.mp4',
            13 => '.webm',
            14 => '.mp3',
            15 => '.wav',
            16 => '.mid',
        ),
    /*上传文件配置*/
    'fileActionName' => 'uploadfile', //controller里,执行上传视频的action名称
    'fileFieldName' => 'upfile', //提交的文件表单名称
    'filePathFormat' => 'static/upload/neditor/file/{yyyy}{mm}{dd}/{time}{rand:6}', //上传保存路径,可以自定义保存路径和文件名格式
    'fileUrlPrefix' => '', //文件访问路径前缀
    'fileMaxSize' => 51200000, //上传大小限制，单位B，默认50MB
    'fileAllowFiles' => //上传文件格式显示
        array(
            0 => '.png',
            1 => '.jpg',
            2 => '.jpeg',
            3 => '.gif',
            4 => '.bmp',
            5 => '.flv',
            6 => '.swf',
            7 => '.mkv',
            8 => '.avi',
            9 => '.rm',
            10 => '.rmvb',
            11 => '.mpeg',
            12 => '.mpg',
            13 => '.ogg',
            14 => '.ogv',
            15 => '.mov',
            16 => '.wmv',
            17 => '.mp4',
            18 => '.webm',
            19 => '.mp3',
            20 => '.wav',
            21 => '.mid',
            22 => '.rar',
            23 => '.zip',
            24 => '.tar',
            25 => '.gz',
            26 => '.7z',
            27 => '.bz2',
            28 => '.cab',
            29 => '.iso',
            30 => '.doc',
            31 => '.docx',
            32 => '.xls',
            33 => '.xlsx',
            34 => '.ppt',
            35 => '.pptx',
            36 => '.pdf',
            37 => '.txt',
            38 => '.md',
            39 => '.xml',
        ),
    /*列出指定目录下的图片*/
    'imageManagerActionName' => 'listimage', //执行图片管理的action名称
    'imageManagerListPath' => 'static/upload/neditor/image/', //指定要列出图片的目录
    'imageManagerListSize' => 20, //每次列出文件数量
    'imageManagerUrlPrefix' => '', //图片访问路径前缀
    'imageManagerInsertAlign' => 'none', //插入的图片浮动方式
    'imageManagerAllowFiles' => //列出的文件类型
        array(
            0 => '.png',
            1 => '.jpg',
            2 => '.jpeg',
            3 => '.gif',
            4 => '.bmp',
        ),
    /*列出指定目录下的文件*/
    'fileManagerActionName' => 'listfile', //执行文件管理的action名称
    'fileManagerListPath' => 'static/upload/neditor/file/', //指定要列出文件的目录
    'fileManagerUrlPrefix' => '', //文件访问路径前缀
    'fileManagerListSize' => 20, //每次列出文件数量
    'fileManagerAllowFiles' => //列出的文件类型
        array(
            0 => '.png',
            1 => '.jpg',
            2 => '.jpeg',
            3 => '.gif',
            4 => '.bmp',
            5 => '.flv',
            6 => '.swf',
            7 => '.mkv',
            8 => '.avi',
            9 => '.rm',
            10 => '.rmvb',
            11 => '.mpeg',
            12 => '.mpg',
            13 => '.ogg',
            14 => '.ogv',
            15 => '.mov',
            16 => '.wmv',
            17 => '.mp4',
            18 => '.webm',
            19 => '.mp3',
            20 => '.wav',
            21 => '.mid',
            22 => '.rar',
            23 => '.zip',
            24 => '.tar',
            25 => '.gz',
            26 => '.7z',
            27 => '.bz2',
            28 => '.cab',
            29 => '.iso',
            30 => '.doc',
            31 => '.docx',
            32 => '.xls',
            33 => '.xlsx',
            34 => '.ppt',
            35 => '.pptx',
            36 => '.pdf',
            37 => '.txt',
            38 => '.md',
            39 => '.xml',
        ),
);