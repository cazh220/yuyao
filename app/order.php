<?php 
/**
 * 订单
 */
require_once('./common.inc.php');

class order extends Action {
	
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
		$page->params['template'] = 'order_menu.html';
		$page->output();
	}
	
	//加入购物清单
	public function doAddBuyList()
	{
		$operator_id = 1;
		$operator = '曹政';
		$ids = !empty($_REQUEST['ids']) ? trim($_REQUEST['ids']) : '';

		$id_set = explode(",", $ids);
		
		$order_no = date("YmdHis").rand(1000,9999);
		
		importModule("GoodsInfo","class");
		$obj_good = new GoodsInfo;
		//订单总览
		$order = array(
			'order_no'		=> $order_no,
			'operator_id'	=> $operator_id,
			'operator'		=> $operator,
			'order_status'	=> 0,
		);
		
		$order_goods = array();
		$total_num = 0;
		$total_amount = 0;
		foreach($id_set as $key => $val)
		{
			//获取商品
			$price = $obj_good->get_good_price($val);
			$order_goods[$key] = array(
				'goods_id'	=> $val,
				'goods_num'	=> 1,
			);
			$total_num++;
			$total_amount += $price;
		}
		
		$order['total_num'] = $total_num;
		$order['total_amount'] = $total_amount;
		//print_r($order);print_r($order_goods);die;
		
		importModule("OrderInfo","class");
		$obj_order = new OrderInfo;
		$res = $obj_order->add_order($order, $order_goods);
		
		if($res)
		{
			$return = array(
				'statusCode'	=> 200,
				'message'		=> '添加成功，请到我的订单确认！',
				'navTabId'		=> 'pagination1',
				'rel'			=> '',
				'callbackType'	=> '',
				'forwardUrl'	=> 'closeCurrent',
				'confirmMsg'	=> ''
			);
		}
		else
		{
			$return = array(
				'statusCode'	=> 0,
				'message'		=> '添加失败，请重新添加',
				'navTabId'		=> 'pagination1',
				'rel'			=> '',
				'callbackType'	=> '',
				'forwardUrl'	=> 'closeCurrent',
				'confirmMsg'	=> ''
			);
		}
		
		exit(json_encode($return));
		
	}
	

}
$app->run();
	
?>
