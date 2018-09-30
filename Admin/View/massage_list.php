<h3 class="center"><?php echo T('消息模板'); ?></h3>
<fieldset>
    <legend><?php echo T('消息模板'); ?></legend>
    <table>
        <thead>
        <th width="25%">
            <?php echo T('标题'); ?>
        </th>
        <th><?php echo T('类型'); ?></th>
        <th><?php echo T('代号'); ?></th>
        <th><?php echo T('编辑时间'); ?></th>
        <th width="8%"><?php echo T('操作'); ?></th>
        </thead>
        <tbody>
        <?php foreach ($lists['rows'] as $key => $row) : ?>
            <tr>
                <td>
                    <?php echo $row['title']; ?>
                </td>
                <td>
                    <?php echo Domain_message::getTypeNames($row['type']); ?>
                </td>
                <td>
                    <?php echo $row['code']; ?>
                </td>
                <td>
                    <?php echo unix_formatter($row['edit_time']); ?>
                </td>
                <td>
                    <a href='<?php echo url('Setting.EditMessage', ['id' => $row['id']]) ?>' class="btn-floating waves-effect waves-light">
                        <i class="material-icons">edit</i>
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
        <tr>
            <td colspan="3">
                <?php //上一页
                if ($page > 1) {
                    ?>
                    <a class="btn waves-effect waves-light"
                       href="<?php echo url('Setting.MessageList', ['page' => $page - 1]); ?>">
                        <i class="material-icons">arrow_back</i></a>
                    <?php
                } else {
                    ?>
                    <a class="disabled btn waves-effect waves-light"><i class="material-icons">arrow_back</i></a>
                    <?php
                }//后一页
                if (($page * each_page) < $lists['total']) {
                    ?>
                    <a class="btn waves-effect waves-light"
                       href="<?php echo url('Setting.MessageList', ['page' => $page + 1]); ?>">
                        <i class="material-icons">arrow_forward</i></a>
                    <?php
                } else {
                    ?>
                    <a class="disabled btn waves-effect waves-light"><i class="material-icons">arrow_forward</i></a>
                    <?php
                }
                ?>
            </td>
        </tr>
        </tbody>
    </table>