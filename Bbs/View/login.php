<h3 class="center"><?php echo T('用户登录'); ?></h3>

<fieldset>
    <legend><?php echo T('登录'); ?></legend>
    <div class="row">
        <form id="Login_in" method="post" onsubmit="return false;" class="col s12">
            <input name="action" value="post" type="hidden">
            <input name="service" value="User.Login" type="hidden">
            <div class="col s12">
                <div class="input-field">
                    <i class="material-icons prefix">account_box</i>
                    <input id="user_name" name="user_name" type="text">
                    <label for="user_name"><?php echo T('用户名'); ?></label>
                </div>
                <div>
                    <p class="msg right"><i class="material-icons">warning</i><?php echo T('请输入用户名'); ?></p>
                </div>
            </div>
            <div class="col s12">
                <div class="input-field">
                    <i class="material-icons prefix">vpn_key</i>
                    <input id="password" name="password" type="password">
                    <label for="password"><?php echo T('密码'); ?></label>
                </div>
                <div>
                    <p class="msg right"><i class="material-icons">warning</i><?php echo T('请输入密码'); ?></p>
                </div>
            </div>
            <div class="col s12">
                <p style="text-align: center;">
                    <input type="checkbox" id="remember" name="remember"/>
                    <label for="remember">记住我</label>
                </p>
            </div>
            <div class="col s12 center">
                <button type="submit" name="submit" class="btn waves-effect waves-light"><?php echo T('登录') ?></button>
                <button type="reset" class="btn waves-effect waves-light">清空</button>
            </div>
        </form>
        <div class="col s12 center">
            <!--<button onclick="location.href='?service=User.google_auth_login'" class="btn waves-effect waves-light">--><?php //echo T('忘记密码') ?><!--</button>-->
            <button data-target="find_password" class="btn waves-effect waves-light modal-trigger">找回密码</button>
        </div>
</fieldset>

<div id="find_password" class="modal center">
    <div class="modal-content">
        <h4>找回密码</h4>
        <p>找回途径</p>
    </div>
    <div class="modal-footer" style="text-align: center;">
        <a onclick="location.href='?service=User.forget&type=0'" class="modal-action modal-close btn waves-effect waves-light">谷歌身份验证器</a>
        <!--<a onclick="location.href='?service=User.forget&type=1'" style="float: none !important;" class="modal-action modal-close btn waves-effect waves-light">手机短信找回</a>-->
        <!--<a onclick="location.href='?service=User.forget&type=2'" style="float: none !important;" class="modal-action modal-close btn waves-effect waves-light">邮件找回</a>-->
    </div>
</div>