<h3 class="center-align"><?php echo T($class['name'] . '区'); ?></h3>

<table>
    <thead>
    <tr class="teal darken-3">
        <th width="44%"><?php echo T('帖子'); ?></th>
        <th width="12%"><?php echo T('访问'); ?></th>
        <th width="12%"><?php echo T('回复'); ?></th>
        <th width="32%"><?php echo T('发表日期'); ?></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($rows as $row): ?>
        <tr class="green accent-1">
            <td>
                <!--如果是“置顶”的记录-->
                <?php if ($row['sticky'] == "1"): ?>
                    <i class="material-icons">stars</i>
                <?php endif; ?>
                <a class="btn-link" href="#topic_info"
                   data-topic_id="<?php echo $row['id']; ?>"><?php echo T($row['topic']); ?></a><br/><?php echo T('发帖者: ' . $row['name']) ?>
            </td>
            <td>
                <?php
                echo $row['view'];  //浏览量
                ?>
            </td>
            <td>
                <?php
                echo $row['reply'];  //回复量
                ?>
            </td>
            <td>
                <?php
                echo date('Y-m-d H:i:s', $row['add_time']);  //日期
                ?>
            </td>
        </tr>
        <!--退出while循环-->
    <?php endforeach; ?>
    </tbody>
</table>
<ul class="pagination">
    <?php if ($page > 1) : //上一页 ?>
        <li class="waves-effect">
            <a class="btn-link" href="#topic_list" data-class_id="<?php echo $class['id']; ?>" data-page="<?php echo ($page - 1); ?>">
                <i class="material-icons">chevron_left</i>
            </a>
        </li>
    <?php else: ?>
        <li class="disabled">
            <a href="javascript:;">
                <i class="material-icons">chevron_left</i>
            </a>
        </li>
    <?php endif; ?>
    <?php $total_page = ceil($total / each_page); ?>
    <?php for ($i = 1; $i <= $total_page; $i++): ?>
        <?php if ($i == $page): ?>
            <li class="active">
            <a href="javascript:;">
        <?php else: ?>
            <li class="waves-effect">
            <a class="btn-link" href="#topic_list" data-class_id="<?php echo $class['id']; ?>" data-page="<?php echo $i; ?>">
        <?php endif; ?>
        <?php echo $i; ?>
        </a>
        </li>
    <?php endfor; ?>
    <?php if (($page * each_page) < $total) : //后一页 ?>
        <li class="waves-effect">
            <a class="btn-link" href="#topic_list" data-class_id="<?php echo $class['id']; ?>" data-page="<?php echo ($page + 1); ?>">
                <i class="material-icons">chevron_right</i>
            </a>
        </li>
    <?php else: ?>
        <li class="disabled">
            <a href="javascript:;">
                <i class="material-icons">chevron_right</i>
            </a>
        </li>
    <?php endif; ?>
</ul>