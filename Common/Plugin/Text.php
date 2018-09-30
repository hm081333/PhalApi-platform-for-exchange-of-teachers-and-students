<?php

/**
 * Created by PhpStorm.
 * User: LYi-Ho
 * Date: 2017/6/23
 * Time: 下午 10:13
 */
class Plugin_Text implements Wechat_Plugin_Text
{
    public function handleText($inMessage, &$outMessage)
    {
        //file_put_contents(API_ROOT . '/Config/test.php', "<?php   \nreturn " . var_export($inMessage, true) . ';');
        switch ($inMessage->getContent()) {
            case '贴吧签到情况':
                $user_model = new Model_User();
                $openid = $inMessage->getFromUserName();
                $user = $user_model->getInfo(array('open_id' => $openid), 'id,user_name');
                $h = date('G', NOW_TIME);
                if ($h < 11) {
                    $t = '早上好！';
                } else if ($h < 13) {
                    $t = '中午好！';
                } else if ($h < 17) {
                    $t = '下午好！';
                } else {
                    $t = '晚上好！';
                }
                $day_time = DateHelper::getDayTime();
                $tieba_model = new Model_Tieba();
                $total = $tieba_model->getCountByWhere(array('user_id=?' => $user['id']));
                $success_count = $tieba_model->getCountByWhere(array('user_id=?' => $user['id'], 'no=?' => 0, 'status=?' => 0, 'latest>=?' => $day_time['begin'], 'latest<=?' => $day_time['end']));//签到成功
                $fail_count = $tieba_model->getCountByWhere(array('user_id=?' => $user['id'], 'no=?' => 0, 'status>?' => 0, 'latest>=?' => $day_time['begin'], 'latest<=?' => $day_time['end']));//签到失败
                $no_count = $tieba_model->getCountByWhere(array('user_id=?' => $user['id'], 'no>?' => 0));//忽略签到
                $content = "用户" . $user['user_name'] . $t . "\n您的贴吧总数量为：" . $total . "个。\n今天签到情况：\n签到成功：" . $success_count . "个，\n签到失败：" . $fail_count . "个，\n忽略签到：" . $no_count . "个";
                $outMessage = new Wechat_OutMessage_Text();
                $outMessage->setContent($content);
                break;
            default:
                $ask = Common_Function::tuling($inMessage->getContent());
                switch ($ask['code']) {
                    case 100000:
                        $outMessage = new Wechat_OutMessage_Text();
                        $outMessage->setContent($ask['text']);
                        break;
                    case 200000 && empty($ask['list']):
                        // case 302000 && empty($ask['list']):
                        $outMessage = new Wechat_OutMessage_News();
                        $item = new Wechat_OutMessage_News_Item();
                        $item->setTitle($inMessage->getContent())
                            ->setDescription($ask['text'])
                            //->setPicUrl($ask['url'])
                            ->setUrl($ask['url']);
                        $outMessage->addItem($item);
                        break;
                    default:
                        $outMessage = new Wechat_OutMessage_News();
                        foreach ($ask['list'] as $key => $rs) {
                            if ($key >= 8) {
                                break;
                            }
                            $title = isset($rs['article']) ? $rs['article'] : $rs['name'];
                            $desc = isset($rs['article']) ? $rs['article'] : $rs['info'];
                            $item = new Wechat_OutMessage_News_Item();
                            $item->setTitle($title)
                                ->setDescription($desc)
                                ->setPicUrl($rs['icon'])
                                ->setUrl($rs['detailurl']);
                            $outMessage->addItem($item);
                        }
                        break;
                }
                break;
        }
    }
}