<style>
    .topic_detail * {
        max-width: 100%;
    }
</style>
<fieldset>
    <legend><?php echo T('正文') ?></legend>
    <table>
        <tr>
            <td class="center">
                <h4><?php echo T($topic['topic']); ?></h4>
            </td>
        </tr>
        <tr>
            <td class="center">
                <?php echo T('用户：') ?><a href="?service=User.user_Info&user_id=<?php echo $topic['user_id']; ?>">
                    <?php echo $topic['name']; ?></a><br/><?php echo date('Y-m-d H:i:s', $topic['add_time']); ?>
            </td>
        </tr>
        <tr>
            <td class="cyan lighten-3">
                <div class="topic_detail">
                    <?php
                    //输出整理好的内容
                    echo str_ireplace('<img', '<img class="materialboxed"', $topic['detail']);
                    ?>
                </div>
            </td>
        </tr>
        <tr>
            <td class="lime lighten-4">
                <dl>
                    <?php
                    if (empty($reply['total'])) :
                        echo T('暂无回复！');
                    else :
                        foreach ($reply['rows'] as $key => $row) : ?>
                            <dt>
                                <?php echo T('用户：') ?><a
                                        href="?service=User.user_Info&user_id=<?php echo $row['user_id']; ?>"><?php echo $row['reply_name']; ?></a>
                                <?php echo date('Y-m-d H:i:s', $row['add_time']); ?>
                            </dt>
                            <dd>
                                <?php
                                //输出整理好的内容
                                echo str_ireplace('<img', '<img class="materialboxed"', $row['reply_detail']);
                                ?>
                            </dd>
                        <?php endforeach; endif; ?>
                </dl>
            </td>
        </tr>
    </table>

    <!--内容回复表单，开始-->

    <div class="replyText">
        <!--判断用户是否登陆-->
        <?php if (!isset($_SESSION['user_name'])) : ?>
            <p class="center">
                <a class="btn waves-effect waves-light" href="?service=User.register"><?php echo T('注册') ?></a><br/>
                <?php echo T('或') ?><br/>
                <a class="btn waves-effect waves-light" href="?service=User.login"><?php echo T('登录') ?></a><br/>
                <?php echo T('进行评论') ?>
            </p>
        <?php else: ?>

        <fieldset>
            <legend><?php echo T('回复'); ?></legend>
            <div class="row">
                <form id="Reply_Topic" enctype="multipart/form-data" method="post" onsubmit="return false;" class="col s12">
                    <input name="service" value="Reply.add_Reply" type="hidden">
                    <input name="topic_id" type="hidden" value="<?php echo $topic['id']; ?>">
                    <input name="user_id" type="hidden" value="<?php echo $_SESSION['user_id']; ?>">
                    <div class="input-field col s12">
                        <script id="reply_detail" name="reply_detail" type="text/plain">
                        </script>
                    </div>
                    <div class="col s12 center">
                        <button type="submit" name="submit" class="btn waves-effect waves-light"><?php echo T('回复该帖'); ?></button>
                        <button type="reset" name="reset" class="btn waves-effect waves-light">重新输入</button>
                    </div>
                </form>
            </div>
        </fieldset>

    </div>
    <br>
    <!--内容回复表单，结束-->
    <!--如果是管理员用户，则输出“置顶”、“锁定”和“删除”按钮-->
    <?php if ($_SESSION['user_auth'] == 1) : ?>
        <!--管理员操作表单，开始-->
        <div class="center">
            <p><?php echo T('管理员操作') ?></p>
            <!--显示置顶操作按钮-->
            <?php if ($topic['sticky'] == 0) : ?>
                <button onclick="stick_topic(<?php echo $topic['id']; ?>)"
                        class="btn waves-effect waves-light"><?php echo T('置顶该贴') ?>
                </button>
            <?php else : ?>
                <button onclick="unstick_topic(<?php echo $topic['id']; ?>)"
                        class="btn waves-effect waves-light"><?php echo T('取消置顶') ?>
                </button>
            <?php endif; ?>
            <button onclick="delete_topic(<?php echo $topic['id']; ?>)"
                    class="btn waves-effect waves-light"><?php echo T('删除帖子') ?>
            </button>
        </div>
        <!--管理员操作表单，结束-->
    <?php endif;
    endif; ?>
</fieldset>

<script>
    window.NEDITOR_UPLOAD = '<?php echo NOW_WEB_SITE . '?service=Public.Neditor' ?>';
    window.UEDITOR_HOME_URL = '<?php echo URL_ROOT . "/static/js/neditor/"; ?>'
</script>

<script type="text/javascript" charset="utf-8" src="<?php echo DI()->tool->staticPath('js/neditor/neditor.config.js'); ?>"></script>
<script type="text/javascript" charset="utf-8" src="<?php echo DI()->tool->staticPath('js/neditor/neditor.all.js'); ?>"></script>
<!--建议手动加在语言，避免在ie下有时因为加载语言失败导致编辑器加载失败-->
<!--这里加载的语言文件会覆盖你在配置项目里添加的语言类型，比如你在配置项目里配置的是英文，这里加载的中文，那最后就是中文-->
<script type="text/javascript" charset="utf-8" src="<?php echo DI()->tool->staticPath('js/neditor/i18n/zh-cn/zh-cn.js'); ?>"></script>
<!-- 实例化编辑器 -->
<script type="text/javascript">
    /*实例化编辑器*/
    /*建议使用工厂方法getEditor创建和引用编辑器实例，如果在某个闭包下引用该编辑器，直接调用UE.getEditor('editor')就能拿到相关的实例*/
    var ue = UE.getEditor('reply_detail');
</script>
