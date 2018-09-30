<h3 class="center"><?php echo T('配置图灵') ?></h3>

<fieldset>
    <legend><?php echo T('图灵设置') ?></legend>
    <div class="row">
        <form method="post" onsubmit="return false;" class="col s12">
            <input name="action" value="post" type="hidden">
            <input name="name" value="tuling" type="hidden">
            <input name="service" value="Setting.Config" type="hidden">
            <div class="col s12">
                <div class="input-field">
                    <i class="material-icons prefix">account_box</i>
                    <input placeholder="APIkey" id="api_key" name="api_key" type="text" value="<?php echo isset($setting['api_key']) ? $setting['api_key'] : ''; ?>"/>
                    <label for="api_key"><?php echo T('APIkey') ?></label>
                </div>
            </div>
            <div class="col s12">
                <div class="input-field">
                    <i class="material-icons prefix">account_box</i>
                    <input placeholder="Secret" id="secret" name="secret" type="text" value="<?php echo isset($setting['secret']) ? $setting['secret'] : ''; ?>"/>
                    <label for="secret"><?php echo T('Secret') ?></label>
                </div>
            </div>
            <div class="col s12 center">
                <button type="submit" name="submit" class="btn waves-effect waves-light"><?php echo T('提交') ?></button>
            </div>
        </form>
</fieldset>