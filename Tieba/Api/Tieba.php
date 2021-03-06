<?php

/**
 * 默认接口服务类
 *
 * @author: dogstar <chanzonghuang@gmail.com> 2014-10-04
 */
class Api_Tieba extends PhalApi_Api
{

	public function getRules()
	{
		return array(
			'addBdussAC' => array(
				'bduss' => array('name' => 'bduss', 'type' => 'string', 'require' => true, 'desc' => 'BDUSS'),
			),
			'tiebaList' => array(),
			'refreshTieba' => array(
				'baidu_id' => array('name' => 'baidu_id', 'type' => 'int', 'require' => true, 'desc' => 'baiduid表的ID'),
			),
			'deleteTieba' => array(
				'id' => array('name' => 'id', 'type' => 'int', 'require' => true, 'desc' => 'tieba表的ID'),
			),
			'noSignTieba' => array(
				'tieba_id' => array('name' => 'tieba_id', 'type' => 'int', 'require' => true, 'desc' => 'tieba表的ID'),
				'no' => array('name' => 'no', 'type' => 'boolean', 'require' => true, 'desc' => '是否忽略签到'),
			),
			'doSignAll' => array(),
			'doSignByBaiduId' => array(
				'baidu_id' => array('name' => 'baidu_id', 'type' => 'int', 'require' => true, 'desc' => 'baiduid表的ID--签到该bduss所有贴吧'),
			),
			'doSignByTiebaId' => array(
				'tieba_id' => array('name' => 'tieba_id', 'type' => 'int', 'require' => true, 'desc' => '贴吧的ID--单独签到一个吧'),
			),
			'doSignByUserId' => array(
				'user_id' => array('name' => 'user_id', 'type' => 'int', 'require' => true, 'desc' => '会员的ID--签到会员所有贴吧'),
			),
			'deleteBaiduId' => array(
				'id' => array('name' => 'id', 'type' => 'int', 'require' => true, 'desc' => 'baiduid的ID--删除该贴吧用户'),
			),
			'checkVC' => array(
				'user' => array('name' => 'user', 'type' => 'string', 'require' => true, 'desc' => '用户名'),
			),
			'login' => array(
				'time' => array('name' => 'time', 'type' => 'string', 'require' => true, 'desc' => 'token'),
				'user' => array('name' => 'user', 'type' => 'string', 'require' => true, 'desc' => '用户名'),
				'pwd' => array('name' => 'pwd', 'type' => 'string', 'require' => true, 'desc' => '密码'),
				'p' => array('name' => 'p', 'type' => 'string', 'require' => true, 'desc' => '加密'),
				'vcode' => array('name' => 'vcode', 'type' => 'string', 'require' => true, 'desc' => '验证码'),
				'vcodestr' => array('name' => 'vcodestr', 'type' => 'string', 'require' => true, 'desc' => '验证码'),
			),
			'getVCPic' => array(
				'vcodestr' => array('name' => 'vcodestr', 'type' => 'string', 'require' => true, 'desc' => '拉取验证码'),
				'r' => array('name' => 'r', 'type' => 'string', 'require' => true, 'desc' => '未知'),
			),
			'sendCode' => array(
				'type' => array('name' => 'type', 'type' => 'string', 'require' => true, 'desc' => '未知'),
				'lstr' => array('name' => 'lstr', 'type' => 'string', 'require' => true, 'desc' => '未知'),
				'ltoken' => array('name' => 'ltoken', 'type' => 'string', 'require' => true, 'desc' => '未知'),
				'r' => array('name' => 'r', 'type' => 'string', 'require' => true, 'desc' => '未知'),
			),
			'login2' => array(
				'type' => array('name' => 'type', 'type' => 'string', 'require' => true, 'desc' => '未知'),
				'lstr' => array('name' => 'lstr', 'type' => 'string', 'require' => true, 'desc' => '未知'),
				'ltoken' => array('name' => 'ltoken', 'type' => 'string', 'require' => true, 'desc' => '未知'),
				'vcode' => array('name' => 'vcode', 'type' => 'string', 'require' => true, 'desc' => '未知'),
				'r' => array('name' => 'r', 'type' => 'string', 'require' => true, 'desc' => '未知'),
			),
			'getQRCode' => array(
				'r' => array('name' => 'r', 'type' => 'string', 'require' => true, 'desc' => '未知'),
			),
			'QRLogin' => array(
				'sign' => array('name' => 'sign', 'type' => 'string', 'require' => true, 'desc' => '未知'),
				'r' => array('name' => 'r', 'type' => 'string', 'require' => true, 'desc' => '未知'),
			),
			'getPhone' => array(
				'phone' => array('name' => 'phone', 'type' => 'string', 'require' => true, 'desc' => '手机号'),
				'r' => array('name' => 'r', 'type' => 'string', 'require' => true, 'desc' => '未知'),
			),
			'sendSms' => array(
				'phone' => array('name' => 'phone', 'type' => 'string', 'require' => true, 'desc' => '手机号'),
				'vcode' => array('name' => 'vcode', 'type' => 'string', 'require' => true, 'desc' => '未知'),
				'vcodestr' => array('name' => 'vcodestr', 'type' => 'string', 'require' => true, 'desc' => '未知'),
				'vcodesign' => array('name' => 'vcodesign', 'type' => 'string', 'require' => true, 'desc' => '未知'),
				'r' => array('name' => 'r', 'type' => 'string', 'require' => true, 'desc' => '未知'),
			),
			'login3' => array(
				'phone' => array('name' => 'phone', 'type' => 'string', 'require' => true, 'desc' => '手机号'),
				'smsvc' => array('name' => 'smsvc', 'type' => 'string', 'require' => true, 'desc' => '未知'),
				'r' => array('name' => 'r', 'type' => 'string', 'require' => true, 'desc' => '未知'),
			),
		);
	}

	public function login3()
	{
		$result = Domain_Tieba::login3($this->phone, $this->smsvc);
		if ($result) {
			return $result;
		}
		throw new PhalApi_Exception_Error(T('获取二维码失败'));
	}

	public function sendSms()
	{
		$result = Domain_Tieba::sendSms($this->phone, $this->vcode, $this->vcodestr, $this->vcodesign);
		if ($result) {
			return $result;
		}
		throw new PhalApi_Exception_Error(T('获取二维码失败'));
	}

	public function getPhone()
	{
		$result = Domain_Tieba::getPhone($this->phone);
		if ($result) {
			return $result;
		}
		throw new PhalApi_Exception_Error(T('获取二维码失败'));
	}

	public function getQRCode()
	{
		$result = Domain_Tieba::getQRCode();
		if ($result) {
			return $result;
		}
		throw new PhalApi_Exception_Error(T('获取二维码失败'));
	}

	public function qRLogin()
	{
		$result = Domain_Tieba::qRLogin($this->sign);
		if ($result) {
			return $result;
		}
		throw new PhalApi_Exception_Error(T('登录失败'));
	}

	public function checkVC()
	{
		$result = Domain_Tieba::checkVC($this->user);
		if ($result) {
			return $result;
		}
		throw new PhalApi_Exception_Error(T('登陆失败'));
	}

	public function sendCode()
	{
		$result = Domain_Tieba::sendCode($this->type, $this->lstr, $this->ltoken);
		if ($result) {
			return $result;
		}
		throw new PhalApi_Exception_Error(T('发送失败'));
	}

	public function getVCPic()
	{
		//直接输出图片
		header('content-type:image/jpeg');
		echo Domain_Tieba::getVCPic($this->vcodestr);
		exit();
	}

	public function time()
	{
		$result = Domain_Tieba::serverTime();
		if ($result) {
			return $result;
		}
		throw new PhalApi_Exception_Error(T('获取Token失败'));
	}

	public function login()
	{
		$result = Domain_Tieba::login($this->time, $this->user, $this->pwd, $this->p, $this->vcode, $this->vcodestr);
		if ($result) {
			return $result;
		}
		throw new PhalApi_Exception_Error(T('登陆失败'));
	}

	public function login2()
	{
		$result = Domain_Tieba::login2($this->type, $this->lstr, $this->ltoken, $this->vcode);
		if ($result) {
			return $result;
		}
		throw new PhalApi_Exception_Error(T('登陆失败'));
	}

	public function addBduss()
	{
		DI()->view->show('add_bduss');
	}

	public function addBdussAC()
	{
		$user_id = $_SESSION['user_id'];
		return Domain_Tieba::addBduss($user_id, $this->bduss);
	}

	public function tiebaList()
	{
		$user_id = $_SESSION['user_id'];
		$baiduid_model = new Model_BaiduId();
		$tieba_model = new Model_Tieba();
		$baiduids = $baiduid_model->getListByWhere(array('user_id' => $user_id), 'id, name', 'id asc');
		$tiebas = array();
		foreach ($baiduids as $baiduid) {
			//$tiebas[$baiduid['id']] = array();
			$tiebas[$baiduid['id']]['name'] = $baiduid['name'];
			$tiebas[$baiduid['id']]['tieba'] = $tieba_model->getListByWhere(array('user_id' => $user_id, 'baidu_id' => $baiduid['id']), '*', 'id asc');
		}
		DI()->view->assign(array('tiebas' => $tiebas));
		DI()->view->show('tieba_list');
	}

	public function refreshTieba()
	{
		$result = Domain_Tieba::scanTiebaByPid($this->baidu_id);
		DI()->response->setMsg(T('刷新成功'));
		return true;
	}

	public function refreshAllTieba()
	{
		$user_id = $_SESSION['user_id'];
		Domain_Tieba::scanTiebaByUser($user_id);
		DI()->response->setMsg(T('刷新成功'));
		return true;
	}

	public function deleteTieba()
	{
		Domain_Tieba::deleteTieba($this->id);
		DI()->response->setMsg(T('删除成功'));
	}

	public function noSignTieba()
	{
		$no = intval($this->no);
		Domain_Tieba::noSignTieba($this->tieba_id, $no);
		DI()->response->setMsg(T('操作成功'));
	}

	public function doSignAll()
	{
		Domain_Tieba::doSignAll();
		DI()->response->setMsg(T('签到成功'));
	}

	public function doSignByBaiduId()
	{
		Domain_Tieba::doSignByBaiduId($this->baidu_id);
		DI()->response->setMsg(T('签到成功'));
	}

	public function doSignByTiebaId()
	{
		Domain_Tieba::doSignByTiebaId($this->tieba_id);
		DI()->response->setMsg(T('签到成功'));
	}

	public function doSignByUserId()
	{
		Domain_Tieba::doSignByUserId($this->user_id);
		DI()->response->setMsg(T('签到成功'));
	}

	public function deleteBaiduId()
	{
		$tieba_model = new Model_Tieba();
		$baiduid_model = new Model_BaiduId();
		$del_tieba = $tieba_model->deleteByWhere(array('baidu_id' => $this->id));
		$del_baiduid = $baiduid_model->delete($this->id);
		if ($del_tieba === false || $del_baiduid === false) {
			throw new PhalApi_Exception_Error(T('删除失败'));
		}
		DI()->response->setMsg(T('删除成功'));
		return true;
	}


}
