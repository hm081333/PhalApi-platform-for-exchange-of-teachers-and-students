<h3 class="center"><?php echo T('配置百度地图') ?></h3>

<fieldset>
    <legend><?php echo T('百度地图设置') ?></legend>
    <div class="row">
        <form method="post" onsubmit="return false;" class="col s12">
            <input name="action" value="post" type="hidden">
            <input name="name" value="baidu_map" type="hidden">
            <input name="service" value="Setting.Config" type="hidden">
            <div class="col s12">
                <div class="input-field">
                    <i class="material-icons prefix">account_box</i>
                    <input id="ak" name="ak" type="text" value="<?php echo isset($setting['ak']) ? $setting['ak'] : ''; ?>"/>
                    <label for="ak"><?php echo T('AK') ?></label>
                </div>
            </div>
            <div class="col s12">
                <div class="input-field">
                    <i class="material-icons prefix">account_box</i>
                    <input id="sk" name="sk" type="text" value="<?php echo isset($setting['sk']) ? $setting['sk'] : ''; ?>"/>
                    <label for="sk"><?php echo T('SK') ?></label>
                </div>
            </div>
            <div class="col s12 center">
                <button type="submit" name="submit" class="btn waves-effect waves-light"><?php echo T('提交') ?></button>
            </div>
        </form>
</fieldset>