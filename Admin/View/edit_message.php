<h3 class="center"><?php echo T('信息模板') ?></h3>

<fieldset>
    <legend><?php echo T('编辑模板') ?></legend>
    <div class="row">
        <form method="post" onsubmit="return false;" class="col s12">
            <input name="action" value="post" type="hidden">
            <input name="id" value="<?php echo $info['id'] ?>" type="hidden">
            <input name="service" value="Setting.EditMessage" type="hidden">
            <div class="col s12">
                <div class="input-field">
                    <i class="material-icons prefix">account_box</i>
                    <input id="title" name="title" type="text" value="<?php echo isset($info['title']) ? $info['title'] : ''; ?>"/>
                    <label for="title"><?php echo T('标题') ?></label>
                </div>
            </div>
            <div class="col s12">
                <div class="input-field">
                    <i class="material-icons prefix">account_box</i>
                    <input id="content" name="content" type="text" value="<?php echo isset($info['content']) ? $info['content'] : ''; ?>"/>
                    <label for="content"><?php echo T('模板内容') ?></label>
                </div>
            </div>
            <div class="center col s12" style="margin: 15px 0px;">
                <div class="switch">
                    <label>
                        <b><?php echo T('关闭'); ?></b>
                        <input type="checkbox" name="state" value="1"<?php echo isset($info['state']) && $info['state'] == 1 ? ' checked' : ''; ?>>
                        <span class="lever"></span>
                        <b><?php echo T('开启'); ?></b>
                    </label>
                </div>
            </div>
            <div class="col s12 center">
                <button type="submit" name="submit" class="btn waves-effect waves-light"><?php echo T('提交') ?></button>
            </div>
        </form>
</fieldset>