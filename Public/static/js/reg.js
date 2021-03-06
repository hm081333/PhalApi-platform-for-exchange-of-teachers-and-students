window.onload = function () {
	// var allgood = true;
	var findid = document.getElementById('Register');
	var aInput = findid.getElementsByTagName('input');
	var oName = aInput[2];
	var psw = aInput[3];
	var psw2 = aInput[4];
	var email = aInput[5];
	var nime = aInput[6];
	// var login = aInput[7];
	oName.ok = false;
	psw.ok = false;
	psw2.ok = false;
	email.ok = false;
	nime.ok = false;
	var aP = findid.getElementsByTagName('p');
	var oName_msg = aP[0];
	var psw_msg = aP[1];
	var psw2_msg = aP[2];
	var email_msg = aP[3];
	var nime_msg = aP[4];
	//用户名验证
	oName.onfocus = function () {
		oName_msg.style.display = "inline";
		oName_msg.innerHTML = '<i class="material-icons">warning</i>用户名需唯一';
	};
	oName.onblur = function () {
		if (this.value == "") {
			oName_msg.innerHTML = '<i class="material-icons">clear</i>不能为空';
			oName.ok = false;
		} else {
			oName_msg.innerHTML = '<i class="material-icons">done</i>初检通过了';
			oName.ok = true;
		}
	};

	//邮箱验证
	email.onfocus = function () {
		email_msg.style.display = "inline";
		email_msg.innerHTML = '<i class="material-icons">warning</i>请输入可用的邮箱';
	};
	email.onblur = function () {
		var re = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
		email_msg.innerHTML = "";
		if (this.value == "") {
			email_msg.innerHTML = '<i class="material-icons">clear</i>不能为空';
		} else if (!re.test(this.value)) {
			email_msg.innerHTML = '<i class="material-icons">clear</i>邮箱格式不正确';
		} else {
			email_msg.innerHTML = '<i class="material-icons">done</i>OK';
			email.ok = true;
		}
	};
	//密码验证
	psw.onfocus = function () {
		psw_msg.style.display = "block";
		psw_msg.innerHTML = '<i class="material-icons">warning</i>密码应为6-16个字符';
	};
	psw.onkeyup = function () {
		if (this.value.length >= 6) {
			psw2.removeAttribute("disabled");
			psw2_msg.style.display = "inline";
			psw2_msg.innerHTML = '<i class="material-icons">warning</i>再次输入密码';
		}
	};
	psw.onblur = function () {
		psw_msg.innerHTML = "";
		if (this.value == "") {
			psw_msg.innerHTML = '<i class="material-icons">clear</i>不能为空';
		} else {
			if ((psw2.value) && this.value != psw2.value) {
				psw_msg.innerHTML = '<i class="material-icons">clear</i>两次密码不相同';
				psw.ok = false;
			} else if (this.value.length <= 5 || this.value.length > 16) {
				psw_msg.innerHTML = '<i class="material-icons">clear</i>密码应为6-16个字符';
			} else {
				psw_msg.innerHTML = '<i class="material-icons">done</i>OK';
				psw.ok = true;
			}
		}
	};
	//确认密码
	psw2.onfocus = function () {
		psw2_msg.innerHTML = '<i class="material-icons">warning</i>请确认密码';
	};
	psw2.onkeyup = function () {
		psw2_msg.innerHTML = "";
	};
	psw2.onblur = function () {
		if (this.value == "") {
			psw2_msg.innerHTML = '<i class="material-icons">clear</i>不能为空';
		} else if (this.value != psw.value) {
			psw.value = "";
			psw2.value = "";
			psw2_msg.innerHTML = '<i class="material-icons">clear</i>两次输入不一致!';
		} else {
			psw2_msg.innerHTML = '<i class="material-icons">done</i>OK';
			psw2.ok = true;
		}
	};

	//真实名验证
	nime.onfocus = function () {
		nime_msg.style.display = "inline";
		nime_msg.innerHTML = '<i class="material-icons">warning</i>请输入姓名';
	};
	nime.onblur = function () {
		if (this.value == "") {
			nime_msg.innerHTML = '<i class="material-icons">clear</i>不能为空';
			nime.ok = false;
		} else {
			nime_msg.innerHTML = '<i class="material-icons">done</i>欢迎加入';
			nime.ok = true;
		}
	};

	//提交验证
	/*login.onclick = function () {
		for (var i = 0; i < 5; i++) {
			if (!aInput[i].ok) {
				aInput[i].onfocus();
				aInput[i].value = "";
				return allgood = false;
			}
		}
		return allgood;
	};*/

	/*$("#email").change(function(){
		var email = $("#email").val();
		console.log(email);
	});*/


	$('#Register').submit(function ()//提交表单
	{
		$.ajax({
			type: 'POST',
			data: $("#Register").serialize(),
			success: function (d) {
				if (d.ret == 200) {
					Materialize.toast(d.msg, 2000, 'rounded', function () {
						if (d.data == 'admin') {
							location.reload();
						} else {
							history.back();
						}
					});
				} else {
					Materialize.toast(d.msg, 2000, 'rounded');
				}
			}
		});
	});
};
