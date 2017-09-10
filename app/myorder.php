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
		/*
		importModule("CategoryInfo","class");
		$obj_category = new CategoryInfo;
		
		$category_list = $obj_category->get_categoty_list();
		
		//导入工具类
		import('util.CategoryShow');
		$category_show = CategoryShow::category_show($category_list);
		*/
		$page = $this->app->page();
		//$page->value('category',$category_show);
		//$page->value('main','myorder');
		$page->params['template'] = 'myorder_menu.html';
		$page->output();
	}
	
	
	//我的订单
	public function doMyOrder()
	{
		$user_id 		= !empty($_SESSION['user_id']) ? $_SESSION['user_id'] : 1;
		$role_id 		= !empty($_SESSION['role_id']) ? $_SESSION['role_id'] : 4;
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
	
	//从我的待确认订单移除商品
	public function doRemoveGood()
	{
		$goods_id 	= !empty($_GET['goods_id']) ? intval($_GET['goods_id']) : 0;
		$order_id	= !empty($_GET['order_id']) ? intval($_GET['order_id']) : 0;
		
		importModule("OrderInfo","class");
		$obj_order = new OrderInfo;
		if($goods_id && $order_id)
		{
			$res = $obj_order->remove_goods($goods_id, $order_id);
		}
		
		if(empty($res))
		{
			//失败
			/*
			$return = array(
				'statusCode'	=> 0,
				'message'		=> '添加失败',
				'navTabId'		=> 'pagination1',
				'rel'			=> '',
				'callbackType'	=> '',
				'forwardUrl'	=> 'closeCurrent',
				'confirmMsg'	=> ''
			);*/
			exit(json_encode(array('status'=>1, 'message'=>'移除失败')));
		}

	}
	
	//订单确认
	public function doConfirmOrder()
	{
		$goods_num_arr 	= !empty($_POST['goods_nums']) ? $_POST['goods_nums'] : array();
		$order_id_arr  	= !empty($_POST['order_id']) ? $_POST['order_id'] : array();
		$goods_id_arr  	= !empty($_POST['goods_id']) ? $_POST['goods_id'] : array();
		$offer_price_arr= !empty($_POST['offer_price']) ? $_POST['offer_price'] : array();
		
		$order_id = isset($order_id_arr[0]) ? $order_id_arr[0] : 0;
		$order_goods = array();
		$total_num = 0;
		$total_amount = 0;
		if($goods_id_arr)
		{
			foreach($goods_id_arr as $key => $val)
			{
				$order_goods[$key] = array(
					'goods_id'	=> $val['goods_id'],
					'order_id'	=> $order_id,
					'goods_num'	=> $goods_num_arr[$key]
				);
				$total_num += $goods_num_arr[$key];
				$total_amount += $goods_num_arr[$key]*$offer_price_arr[$key];
			}
		}
		
		$order = array(
			'order_id'		=> $order_id,
			'total_num'		=> $total_num,
			'total_amount'	=> $total_amount
		);
		//print_r($order);print_r($order_goods);die;
		importModule("OrderInfo","class");
		$obj_order = new OrderInfo;
		$res = $obj_order->confirm_order($order, $order_goods);
		
		if(empty($res))
		{
			//失败
			$return = array(
				'statusCode'	=> 0,
				'message'		=> '确认订单失败',
				'navTabId'		=> 'pagination1',
				'rel'			=> '',
				'callbackType'	=> '',
				'forwardUrl'	=> 'closeCurrent',
				'confirmMsg'	=> ''
			);
		}
		else
		{
			//成功
			$return = array(
				'statusCode'	=> 200,
				'message'		=> '确认订单成功',
				'navTabId'		=> 'pagination1',
				'rel'			=> '',
				'callbackType'	=> '',
				'forwardUrl'	=> 'closeCurrent',
				'confirmMsg'	=> ''
			);
		}
		
		exit(json_encode($return));
	}
	
	//获取已确认订单
	public function doCompleteOrder(){	
		$user_id 		= !empty($_SESSION['user_id']) ? $_SESSION['user_id'] : 1;
		$role_id 		= !empty($_SESSION['role_id']) ? $_SESSION['role_id'] : 4;
		//获取待确认清单
		importModule("OrderInfo","class");
		$obj_order = new OrderInfo;
		$param = array(
			'user_id'	=> $user_id,
			'order_status'	=> 1
		);

		$order_list = $obj_order->get_order($param); 
		importModule("GoodsInfo","class");
		$obj_good = new GoodsInfo;
		/*
		print_r($order_list);die;
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
		}*/
		
		//print_r($order_list);die;
		$page = $this->app->page();
		$page->value('list',$order_list);
		$page->params['template'] = 'my_complete_list.html';
		$page->output();
	}
	

}
$app->run();
	
?>
