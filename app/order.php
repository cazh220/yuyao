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
		$role_id = 4;
		$ids = !empty($_REQUEST['ids']) ? trim($_REQUEST['ids']) : '';
		$id_set = explode(",", $ids);
		
		importModule("OrderInfo","class");
		$obj_order = new OrderInfo;
		
		importModule("GoodsInfo","class");
		$obj_good = new GoodsInfo;
		//检查是否待处理的订单
		$order = $obj_order->get_unconfirm_order($operator_id);
		//print_r($order);die;
		if($order)
		{
			//添加到购物商品表
			$order_goods = array();
			$total_num = 0;
			$total_amount = 0;
			foreach($id_set as $key => $val)
			{
				//获取商品实际价格
				$offer_price = $obj_good->get_role_good_price($role_id, $val);
				if(empty($offer_price))
				{
					$offer_price = $obj_good->get_good_price($val);
				}
				
				$order_goods[$key] = array(
					'goods_id'	=> $val,
					'goods_num'	=> 1,
					'order_id'	=> $order['order_id'],
					'good_price'	=> $offer_price
				);
				
				$total_num++;
				$total_amount += $offer_price;
			}
			$_order = array(
				'total_amount'	=> number_format($order['total_amount']+$total_amount, 2, '.', ''),
				'total_num'		=> $order['total_num']+$total_num,
				'order_id'		=> $order['order_id']
			);
			//更新订单
			$res = $obj_order->add_new_order_goods($_order, $order_goods);
		}
		else
		{
			$order_no = date("YmdHis").rand(1000,9999);
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
				//$price = $obj_good->get_good_price($val);
				//获取商品实际价格
				$offer_price = $obj_good->get_role_good_price($role_id, $val);
				if(empty($offer_price))
				{
					$offer_price = $obj_good->get_good_price($val);
				}
				$order_goods[$key] = array(
					'goods_id'	=> $val,
					'goods_num'	=> 1,
					'good_price'	=> $offer_price
				);
				$total_num++;
				$total_amount += $offer_price;
			}
			
			$order['total_num'] = $total_num;
			$order['total_amount'] = $total_amount;
			//print_r($order);print_r($order_goods);die;

			$res = $obj_order->add_order($order, $order_goods);
		}
		

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
	
	//获取所有用户订单
	public function doOrderList()
	{
		$current_page	= !empty($_GET['current_page']) ? intval($_GET['current_page']) : 1;
		importModule("OrderInfo","class");
		$obj_order = new OrderInfo;
		
		$param = array(
			'current_page'	=> $current_page,
			'page_size'		=> 10
		);
		
		$res = $obj_order->get_order_list($param);
		
		$pageinfo = array(
			'total'	=> $res['count'],
			'page_size'	=> 10,
			'current_page'	=> $current_page,
			'page_count'	=> ceil($res['count']/10)
		);
		//print_r($pageinfo);die;
		$page = $this->app->page();
		$page->value('order',$res['list']);
		$page->value('page_info',$pageinfo);
		$page->params['template'] = 'order_list.html';
		$page->output();
	}
	
	//获取分车订单
	public function doTruckOrder()
	{
		$current_page	= !empty($_GET['current_page']) ? intval($_GET['current_page']) : 1;
		//获取货车订单
		importModule("SendInfo","class");
		$obj_send = new SendInfo;
		$param = array(
			'current_page'	=> $current_page,
			'page_size'		=> 10
		);
		//var_dump($param);die;
		$order = $obj_send->get_assign_order(1, $param);
		
		$pageinfo = array(
			'total'	=> $order['count'],
			'page_size'	=> 10,
			'current_page'	=> $current_page,
			'page_count'	=> ceil($order['count']/10)
		);

		$page = $this->app->page();
		$page->value('order',$order['list']);
		$page->value('page_info',$pageinfo);
		$page->params['template'] = 'truck_order_list.html';
		$page->output();
	}
	

}
$app->run();
	
?>
