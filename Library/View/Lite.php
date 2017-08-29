<?php
/**
 *  Lite.php
 *  视图控制器
 *
 *  Created by SteveAK on 04/27/16
 *  Copyright (c) 2016 SteveAK. All rights reserved.
 *  Contact email(aer_c@qq.com) or qq(7579476)
 */

class View_Lite
{
	//项目名称
	protected $item = '';

	//模板赋值参数
	protected $param = array();

	//视图类型
	protected $type = '';

	public function __construct($item = '', $type = '')
	{
		if (!empty($item)) {
			$this->item = $item;
		}

		if (!empty($type)) {
			$this->type = $type;
		}
	}

	/**
	 * 渲染模板
	 * @param  string $name html文件名称
	 * @param  array $param 参数
	 */
	public function show($name, $param = array())
	{
		$this->load($name, $param);
		exit();
	}

	/**
	 * 模板赋值
	 * @param  array $param 参数 $K => $v
	 */
	public function assign($param = array())
	{
		foreach ($param as $k => $v) {
			$this->param[$k] = $v;
		}
		return true;
	}

	/**
	 * 装载模板
	 * @param  string $name html文件名称
	 * @param  array $param 参数
	 */
	public function load($name, $param = array())
	{
		if (empty($this->type)) {
			$view = API_ROOT . '/' . $this->item . '/View/' . $name . '.php';
		} else {
			$view = API_ROOT . '/' . $this->item . '/View/' . $this->type . '/' . $name . '.php';
		}

		//合并参数
		$param = array_merge($this->param, $param);

		//将数组键名作为变量名，如果有冲突，则覆盖已有的变量
		extract($param, EXTR_OVERWRITE);

		//开启缓冲区
		ob_start();
		ob_implicit_flush(false);

		defined('index') ? require PUB_ROOT . 'static/header/header.php' : require PUB_ROOT . 'static/header/header_admin.php';

		//检查文件是否存在
		file_exists($view) ? require $view : exit($view . ' 模板文件不存在');

		require PUB_ROOT.'static/header/footer.php';

		//获取当前缓冲区内容
		//$content = ob_get_contents(); // 仅输出
		//return $content;
		$content = ob_get_clean(); // 输出并清空关闭
		//$content = Common_Function::higrid_compress_html($content);
		echo $content;
	}

	public function post($name, $param = array())
	{
		//if (empty($this->type)) {
		//	$view = API_ROOT . '/' . $this->item . '/View/inc/' . $name . '.php';
		//} else {
		//	$view = API_ROOT . '/' . $this->item . '/View/' . $this->type . '/inc/' . $name . '.php';
		//}

		$view = API_ROOT . '/' . $this->item . '/View/' . $name . '.php';
		//合并参数
		$param = array_merge($this->param, $param);

		//将数组键名作为变量名，如果有冲突，则覆盖已有的变量
		extract($param, EXTR_OVERWRITE);

		//开启缓冲区
		ob_start();
		ob_implicit_flush(false);

		//检查文件是否存在
		file_exists($view) ? require $view : exit($view . ' 模板文件不存在');

		//获取当前缓冲区内容
		//$content = ob_get_contents(); // 仅输出
		$content = ob_get_clean(); // 输出并清空关闭
		return $content;
	}
}