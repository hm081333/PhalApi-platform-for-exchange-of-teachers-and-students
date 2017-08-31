</main>
<!-- 正文内容结束 -->
<!-- 页脚信息 -->
<footer class="page-footer cyan darken-4">

	<div class="row container"><!--row行，可用col-->
		<div class="col s12 m4">
			<!--col定义总长度为12，字母代表设备，s为手机分辨率的百分之85，m为平板分辨率的百分之85，l为电脑分辨率的百分之70。此语句为：此div在电脑上浏览时宽度为分辨率宽度百分之70的y一半，在手机上浏览时宽度为分辨率宽度的百分之85-->
			<h5 class="white-text"><?php echo T('页脚'); ?></h5>
			<p class="white-text"><?php echo T('没说明'); ?></p>
		</div>

		<div class="col s12 m4"><!--类似上面 offset为抵消-在电脑上浏览时距离左边物体两个单位-->
			<h5 class="white-text"><?php echo T('友情链接'); ?></h5>
			<ul>
				<li><a class="white-text" href="#!"><?php echo T('暂时没有友情链接'); ?></a></li>
				<li>
					<a class="white-text">
						<?php echo T('IP：'); ?>
						<a id="ip" class="white-text" href="http://www.ip.cn/index.php?ip=<?php echo client_ip ?>"><?php echo client_ip; ?></a>
					</a>
				</li>
				<li>
					<a class="white-text">
						<?php echo T('IP所在地：'); ?>
						<a id="ip_address" class="white-text">
						</a>
					</a>
				</li>
			</ul>
		</div>

		<div class="col s12 m4">
			<h5 class="white-text"><?php echo T('联系我'); ?></h5>
			<ul>
				<li><a style="width: 127px;" class="white-text btn waves-effect waves-light"
					   onclick="javascript:window.open('mailto:522751485@qq.com')"><i class="tiny material-icons">mail</i>Email</a></li>
				<li><a style="width: 127px;" class="white-text btn waves-effect waves-light"
					   onclick="javascript:window.open('http://sighttp.qq.com/authd?IDKEY=2370447117525914b38fc589aa94b53b4d3a892de4c76039')">QQ</a>
				</li>
				<!--<li><a style="width: 127px;" class="white-text btn waves-effect waves-light"
					   href="javascript:window.open('http://wpa.qq.com/msgrd?v=3&uin=3449769816&site=qq&menu=yes')">XXX</a>
				</li>-->
			</ul>
		</div>
	</div>

	<div class="footer-copyright"><!--版权说明版面-->
		<div class="container"><!--版权说明容器-->
			© 2016 Copyright LYi-Ho.
			<a class="grey-text text-lighten-4" onmouseover="this.style.cursor='pointer';" style="cursor:hand"
			   onclick="javascript:window.open('http://materializecss.com/')">design by Materialize</a>
			<!--			<a class="grey-text text-lighten-4 center" href="./admin/">后台</a>-->
			<a class="grey-text text-lighten-4 right" onmouseover="this.style.cursor='pointer';" style="cursor:hand"
			   onclick="javascript:window.open('https://design.google.com/icons/')">material icons library</a>
		</div>
	</div>
</footer>

<a href="#!" class="cd-top"></a><!--返回顶部按钮-->

</body>
</html>
<!--局域网获取IP方法-->
<!--<script src="http://pv.sohu.com/cityjson?ie=utf-8"></script>-->
<script src="./Public/static/js/diy.js"></script>

