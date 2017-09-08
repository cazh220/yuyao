<?php 
/**
 * 订单
 */
require_once('./common.inc.php');

class myorder extends Action {
	
	/**
	 * 默认执行的方法
	 */
	public function doDefault(){	
		//获取分类列表
		importModule("CategoryInfo","class");
		$obj_category = new CategoryInfo;
		
		$category_list = $obj_category->get_categoty_list();
		
		//导入工具类
		import('util.CategoryShow');
		$category_show = CategoryShow::category_show($category_list);
		
		$page = $this->app->page();
		$page->value('category',$category_show);
		$page->value('main','myorder');
		$page->params['template'] = 'myorder_menu.html';
		$page->output();
	}
	
	
	//我的订单
	public function doMyOrder()
	{
		$user_id 		= !empty($_SESSION['user_id']) ? $_SESSION['user_id'] : 1;
		$role_id 		= !empty($_SESSION['role_id']) ? $_SESSION['role_id'] : 1;
		//获取待确认清单
		importModule("OrderInfo","class");
		$obj_order = new OrderInfo;
		$param = array(
			'user_id'	=> $user_id,
		);
		
		$order_list = $obj_order->get_unconfirm_order_goods($param); 
		
		importModule("GoodsInfo","class");
		$obj_good = new GoodsInfo;
		if($order_list)
		{
			foreach($order_list as $key => $val)
			{
				//获取实时报价
				$role_price = $obj_good->get_role_good_price($role_id, $val['goods_id']);
				if(empty($role_price))
				{
					//取基本价
					$role_price = $obj_good->get_good_price($val['goods_id']);
				}
				$order_list[$key]['offer_price'] = $role_price;
			}
		}
		
		//print_r($order_list);die;
		$page = $this->app->page();
		$page->value('list',$order_list);
		$page->params['template'] = 'myorder_list.html';
		$page->output();
	}
	

}
$app->run();
	
?>
