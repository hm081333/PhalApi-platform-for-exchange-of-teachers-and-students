<h3 class="center"><?php echo T('配置短信') ?></h3>

<fieldset>
    <legend><?php echo T('短信设置') ?></legend>
    <div class="row">
        <form method="post" onsubmit="return false;" class="col s12">
            <input name="action" value="post" type="hidden">
            <input name="name" value="sms" type="hidden">
            <input name="service" value="Setting.Config" type="hidden">
            <div class="col s12">
                <p>
                    短信类型：
                    <input class="with-gap" name="sms_type" value="0" type="radio" id="smsbao"<?php echo (!isset($setting['sms_type']) || $setting['sms_type'] == 0) ? ' checked' : '' ?>/>
                    <label for="smsbao">短信宝</label>
                    <input class="with-gap" name="sms_type" value="1" type="radio" id="yunpian"<?php echo (isset($setting['sms_type']) && $setting['sms_type'] == 1) ? ' checked' : '' ?>/>
                    <label for="yunpian">云片</label>
                </p>
            </div>
            <div class="col s12">
                <div class="input-field">
                    <i class="material-icons prefix">account_box</i>
                    <input placeholder="用于短信宝" id="user_name" name="username" type="text" value="<?php echo isset($setting['username']) ? $setting['username'] : ''; ?>"/>
                    <label for="user_name"><?php echo T('用户名') ?></label>
                </div>
            </div>
            <div class="col s12">
                <div class="input-field">
                    <i class="material-icons prefix">vpn_key</i>
                    <input placeholder="用于短信宝" id="password" name="password" type="password" value="<?php echo isset($setting['password']) ? $setting['password'] : ''; ?>"/>
                    <label for="password"><?php echo T('密码') ?></label>
                </div>
            </div>
            <div class="col s12">
                <div class="input-field">
                    <i class="material-icons prefix">vpn_key</i>
                    <input placeholder="用于云片" id="api_key" name="api_key" type="text" value="<?php echo isset($setting['api_key']) ? $setting['api_key'] : ''; ?>"/>
                    <label for="api_key"><?php echo T('ApiKey') ?></label>
                </div>
            </div>
            <div class="col s6">
                <div class="input-field">
                    <i class="material-icons prefix">vpn_key</i>
                    <input placeholder="短信开头的签名" id="sign_name" name="sign_name" type="text" value="<?php echo isset($setting['sign_name']) ? $setting['sign_name'] : ''; ?>"/>
                    <label for="sign_name"><?php echo T('短信签名') ?></label>
                </div>
            </div>
            <div class="col s6">
                使用统一签名
                <div class="switch">
                    <label>
                        Off
                        <input type="checkbox" name="use_sign" value="1"<?php echo isset($setting['use_sign']) ? ' checked' : '' ?>>
                        <span class="lever"></span>
                        On
                    </label>
                </div>
            </div>
            <div class="col s12 center">
                <button type="submit" name="submit" class="btn waves-effect waves-light"><?php echo T('提交') ?></button>
            </div>
        </form>
</fieldset>