<?php 
/**
 * index入口文件
 */
require_once('./common.inc.php');

class index extends Action {
	
	/**
	 * 默认执行的方法
	 */
	public function doDefault(){	

		$page = $this->app->page();
		//$page->value('user_id',$user_id);
		$page->params['template'] = 'index_menu.html';
		$page->output();
	}

}
$app->run();
	
?>
