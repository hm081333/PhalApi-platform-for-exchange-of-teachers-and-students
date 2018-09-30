<h3 class="center"><?php echo T('配置微信') ?></h3>

<fieldset>
    <legend><?php echo T('微信设置') ?></legend>
    <div class="row">
        <form method="post" onsubmit="return false;" class="col s12">
            <input name="action" value="post" type="hidden">
            <input name="name" value="wechat" type="hidden">
            <input name="service" value="Setting.Config" type="hidden">
            <div class="col s12">
                <div class="input-field">
                    <i class="material-icons prefix">account_box</i>
                    <input placeholder="公众号ID" id="app_id" name="app_id" type="text" value="<?php echo isset($setting['app_id']) ? $setting['app_id'] : ''; ?>"/>
                    <label for="app_id"><?php echo T('AppId') ?></label>
                </div>
            </div>
            <div class="col s12">
                <div class="input-field">
                    <i class="material-icons prefix">vpn_key</i>
                    <input placeholder="公众号密钥" id="app_secret" name="app_secret" type="text" value="<?php echo isset($setting['app_secret']) ? $setting['app_secret'] : ''; ?>"/>
                    <label for="app_secret"><?php echo T('AppSecret') ?></label>
                </div>
            </div>
            <div class="col s12">
                <div class="input-field">
                    <i class="material-icons prefix">vpn_key</i>
                    <input placeholder="消息密钥，用于微信消息接口" id="token" name="token" type="text" value="<?php echo isset($setting['token']) ? $setting['token'] : ''; ?>"/>
                    <label for="token"><?php echo T('Token') ?></label>
                </div>
            </div>
            <div class="col s12 center">
                <button type="submit" name="submit" class="btn waves-effect waves-light"><?php echo T('提交') ?></button>
            </div>
        </form>
</fieldset>