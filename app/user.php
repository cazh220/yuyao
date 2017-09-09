<?php 
/**
 * 用户
 */
require_once('./common.inc.php');

class user extends Action {
	
	/**
	 * 默认执行的方法
	 */
	public function doDefault(){	

		$page = $this->app->page();
		$page->value('main','myorder');
		$page->params['template'] = 'user_menu.html';
		$page->output();
	}
	
	//用户列表
	public function doUserList()
	{
		importModule("UserInfo","class");
		$obj_user = new UserInfo;
		
		$list = $obj_user->get_user_list();
		
		$page = $this->app->page();
		$page->value('users',$list);
		$page->params['template'] = 'user_list.html';
		$page->output();
	}
	
	//添加用户
	public function doAddUser()
	{
		$page = $this->app->page();
		$page->params['template'] = 'user_add.html';
		$page->output();
	}
	
	//获取订单商品明细
	public function doOrderGoods()
	{
		$order_id	= !empty($_GET['order_id']) ? $_GET['order_id'] : 0;
		
		importModule("OrderInfo","class");
		$obj_order = new OrderInfo;
		
		$order_goods = $obj_order->get_order_goods($order_id);
		//print_r($order_goods);
		
		$page = $this->app->page();
		$page->value('order_goods',$order_goods);
		//$page->value('main','myorder');
		$page->params['template'] = 'order_goods.html';
		$page->output();
	}
	

}
$app->run();
	
?>
