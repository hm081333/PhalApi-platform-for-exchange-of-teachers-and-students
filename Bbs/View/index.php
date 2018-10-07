<div class="page page-current" id="index">
    <h3 class="center-align"><?php echo T('南洋交流平台'); ?></h3>
    <table style="table-layout: fixed;">
        <thead>
        <tr class="teal darken-3">
            <th width="40%"><?php echo T('课程'); ?></th>
            <th><?php echo T('说明'); ?></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($rows as $key => $row) : ?>
            <tr class="green accent-1">
                <td>
                    <i class="material-icons">label</i>
                    <a class="btn-link brown-text" href="<?php echo url('Topic.Topic_List', ['class_id' => $row['id']]) ?>">
                        <b><?php echo T($row['name']); ?></b>
                    </a>
                </td>
                <td>
                    <?php if (!empty($row['tips'])): ?>
                        <span class="truncate tooltipped" data-position="bottom" data-delay="50" data-tooltip="<?php echo T($row['tips']); ?>">
                            <?php echo T($row['tips']); ?>
                        </span>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <ul class="pagination">
        <?php if ($page > 1) : //上一页 ?>
            <li class="waves-effect">
                <a class="btn-link" href="<?php echo url('Default.Index', ['page' => ($page - 1)]) ?>">
                    <i class="material-icons">chevron_left</i>
                </a>
            </li>
        <?php else: ?>
            <li class="disabled">
                <a href="javascript:void(0);">
                    <i class="material-icons">chevron_left</i>
                </a>
            </li>
        <?php endif; ?>
        <?php $total_page = ceil($total / each_page); ?>
        <?php for ($i = 1; $i <= $total_page; $i++): ?>
            <?php if ($i == $page): ?>
                <li class="active">
                <a href="javascript:void(0);">
            <?php else: ?>
                <li class="waves-effect">
                <a class="btn-link" href="<?php echo url('Default.Index', ['page' => $i]) ?>">
            <?php endif; ?>
            <?php echo $i; ?>
            </a>
            </li>
        <?php endfor; ?>
        <?php if (($page * each_page) < $total) : //后一页 ?>
            <li class="waves-effect">
                <a class="btn-link" href="<?php echo url('Default.Index', ['page' => ($page + 1)]) ?>">
                    <i class="material-icons">chevron_right</i>
                </a>
            </li>
        <?php else: ?>
            <li class="disabled">
                <a href="javascript:void(0);">
                    <i class="material-icons">chevron_right</i>
                </a>
            </li>
        <?php endif; ?>
    </ul>
</div>
