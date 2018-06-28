<h3 class="center"><?php echo T('查询快递'); ?></h3>
<table>
    <thead>
    <tr class="teal darken-3">
        <th><?php echo T('单号'); ?></th>
        <th><?php echo T('物流公司'); ?></th>
        <th><?php echo T('说明'); ?></th>
        <th><?php echo T('状态'); ?></th>
        <th><?php echo T('上次查看时间'); ?></th>
        <th><?php echo T('操作'); ?></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($rows as $key => $row) : ?>
        <tr class="green accent-1">
            <td style="color: blueviolet;">
                <?php echo $row['sn']; ?>
            </td>
            <td>
                <?php echo $row['log_name']; ?>
            </td>
            <td>
                <?php echo $row['memo']; ?>
            </td>
            <td>
                <!--0：在途，即货物处于运输过程中；
                1：揽件，货物已由快递公司揽收并且产生了第一条跟踪信息；
                2：疑难，货物寄送过程出了问题；
                3：签收，收件人已签收；
                4：退签，即货物由于用户拒签、超区等原因退回，而且发件人已经签收；
                5：派件，即快递正在进行同城派件；
                6：退回，货物正处于退回发件人的途中；-->
                <?php
                switch ($row['state']):
                    case 0:
                        echo '在途';
                        break;
                    case 1:
                        echo '揽件';
                        break;
                    case 2:
                        echo '疑难';
                        break;
                    case 3:
                        echo '签收';
                        break;
                    case 4:
                        echo '退签';
                        break;
                    case 5:
                        echo '派件';
                        break;
                    case 6:
                        echo '退回';
                        break;
                endswitch;
                ?>
            </td>
            <td>
                <?php echo $row['last_time'] ? date('Y-m-d H:i:s', $row['last_time']) : ''; ?>
            </td>
            <td class="center">
                <a class="delivery_id btn-floating waves-effect waves-light" data-service="Default.DeliveryView"
                   data-id="<?php echo $row['id']; ?>">
                    <i class="material-icons">insert_chart</i>
                </a>
                <a class="delete btn-floating waves-effect waves-light" data-id="<?php echo $row['id']; ?>"
                   data-service="Default.DelDelivery">
                    <i class="material-icons">delete</i>
                </a>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<div class="row valign-wrapper">
    <div class="col s8">
        <ul class="pagination">
            <?php if ($page > 1) : //上一页 ?>
                <li class="waves-effect">
                    <a href="./?service=Default.deliveryList&page=<?php echo($page - 1); ?>">
                        <i class="material-icons">chevron_left</i>
                    </a>
                </li>
            <?php else: ?>
                <li class="disabled">
                    <a href="#!">
                        <i class="material-icons">chevron_left</i>
                    </a>
                </li>
            <?php endif; ?>
            <?php $total_page = ceil($total / each_page); ?>
            <?php for ($i = 1; $i <= $total_page; $i++): ?>
                <?php if ($i == $page): ?>
                    <li class="active">
                        <a href="#!">
                            <?php echo $i; ?>
                        </a>
                    </li>
                <?php else: ?>
                    <li class="waves-effect">
                        <a href="./?service=Default.deliveryList&page=<?php echo $i; ?>">
                            <?php echo $i; ?>
                        </a>
                    </li>
                <?php endif; ?>
            <?php endfor; ?>
            <?php if (($page * each_page) < $total) : //后一页 ?>
                <li class="waves-effect">
                    <a href="./?service=Default.deliveryList&page=<?php echo($page + 1); ?>">
                        <i class="material-icons">chevron_right</i>
                    </a>
                </li>
            <?php else: ?>
                <li class="disabled">
                    <a href="#!">
                        <i class="material-icons">chevron_right</i>
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </div>
    <div class="col s4">
        <button class="btn right waves-effect waves-light modal-trigger" data-target="addDelivery">
            <?php echo T('添加'); ?>
        </button>
    </div>
</div>

<!-- 物流信息弹框 -->
<div id="delivery" class="modal"></div>
<!-- 添加快递弹窗 -->
<div id="addDelivery" class="modal" style="min-height: 70%;">
    <div class="modal-content center">
        <h4>添加查询运单</h4>
        <p>测试功能</p>
        <form method="post" onsubmit="return false;">
            <input type="hidden" name="service" value="Default.addDelivery">
            <div class="input-field">
                <select name="code">
                    <option value="" disabled selected>请选择对应的物流公司</option>
                    <?php
                    foreach ($logss as $log):?>
                        <option value="<?php echo $log['code']; ?>"><?php echo $log['name']; ?></option>
                    <?php endforeach; ?>
                </select>
                <label>物流选择</label>
            </div>
            <div class="input-field">
                <input id="sn" name="sn" type="text" class="validate">
                <label for="sn">运单号</label>
            </div>
            <div class="input-field">
                <input id="memo" name="memo" type="text" class="validate">
                <label for="memo">备注</label>
            </div>
            <button type="submit" class="btn waves-effect waves-light"><?php echo T('添加'); ?></button>
        </form>
    </div>
</div>