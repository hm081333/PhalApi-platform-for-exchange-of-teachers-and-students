<div class="row">
    <div class="row">
        <div class="col s12 center">
            <h4><?php echo T($topic['topic']); ?></h4>
        </div>
        <div class="col s12 center">
            <?php echo T('用户：') ?>
            <a class="btn-link" href="#user_info" data-user_id="<?php echo $topic['user_id']; ?>">
                <?php echo $topic['name']; ?>
            </a>
            <br/>
            <?php echo date('Y-m-d H:i:s', $topic['add_time']); ?>
        </div>
        <div class="col s12 cyan lighten-3">
            <div class="topic_detail">
                <?php
                //输出整理好的内容
                echo str_ireplace('<img', '<img class="materialboxed"', $topic['detail']);
                ?>
            </div>
        </div>
        <div class="col s12 lime lighten-4">
            <?php
            if (empty($reply['total'])) :
                echo T('暂无回复！');
            else :
                foreach ($reply['rows'] as $key => $row) : ?>
                    <dt>
                        <?php echo T('用户：') ?>
                        <a class="btn-link" href="#user_info" data-user_id="<?php echo $row['user_id']; ?>">
                            <?php echo $row['reply_name']; ?>
                        </a>
                        <?php echo date('Y-m-d H:i:s', $row['add_time']); ?>
                    </dt>
                    <dd>
                        <?php
                        //输出整理好的内容
                        echo str_ireplace('<img', '<img class="materialboxed"', $row['reply_detail']);
                        ?>
                    </dd>
                <?php endforeach; endif; ?>
        </div>
    </div>
</div>

<!--内容回复表单，开始-->
<!--<div class="replyText">-->
<!--判断用户是否登陆-->
<?php if (!isset($_SESSION['user_name'])) : ?>
    <div class="center">
        <?php echo T('请') ?>
        <a class="btn waves-effect waves-light" href="?service=User.register"><?php echo T('注册') ?></a>
        <?php echo T('或') ?>
        <a class="btn waves-effect waves-light" href="?service=User.login"><?php echo T('登录') ?></a>
        <?php echo T('再进行评论') ?>
    </div>
<?php else: ?>

    <div class="blue lighten-5">
        <form id="Reply_Topic" enctype="multipart/form-data" method="post" onsubmit="return false;">
            <input name="service" value="Reply.add_Reply" type="hidden">
            <input name="topic_id" type="hidden" value="<?php echo $topic['id']; ?>">
            <input name="user_id" type="hidden" value="<?php echo $_SESSION['user_id']; ?>">
            <div class="input-field col s12">
                <textarea name="reply_detail" class="materialize-textarea validate"></textarea>
                <label for="reply_detail"><?php echo T('回帖内容') ?></label>
            </div>
            <div class="col s12">
                <button type="submit" name="submit"
                        class="btn waves-effect waves-light"><?php echo T('回复该帖') ?></button>
            </div>
        </form>
    </div>
    <!--</div>-->

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

