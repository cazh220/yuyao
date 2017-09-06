<?php 
/**
 * 商品
 */
require_once('./common.inc.php');

class goods extends Action {
	
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
		$page->params['template'] = 'goods_menu.html';
		$page->output();
	}
	
	//商品列表
	public function doGoodsList()
	{
		$category_id 	= !empty($_GET['category_id']) ? intval($_GET['category_id']) : 0;
		$page 			= !empty($_GET['page']) ? intval($_GET['page']) : 1;
		
		importModule("GoodsInfo","class");
		$obj_good = new GoodsInfo;
		
		$param = array(
			'category_id'	=> $category_id,
			'page'			=> $page,
			'page_size'		=> 10
		);	
		
		$list = $obj_good->get_goods_list($param);
		//print_r($list);die;
		$page = $this->app->page();
		$page->value('goods',$list);
		$page->params['template'] = 'goods_list.html';
		$page->output();
	}
	
	//添加商品
	public function doAddGood()
	{
		importModule("CategoryInfo","class");
		$obj_category = new CategoryInfo;
		
		$category_list = $obj_category->get_categoty_list();
		
		//导入工具类
		import('util.CategoryShow');
		$category_show = CategoryShow::category_show($category_list);
		
		$page = $this->app->page();
		$page->value('category',$category_show);
		$page->params['template'] = 'add_good.html';
		$page->output();
	}
	
	//添加商品
	public function doAddGoodAct()
	{
		//模拟用户
		$operator_id = 1;
		$operator = '曹政';
		$goods_name	= $_POST['goods_name'] ? trim($_POST['goods_name']) : '';
		$goods_code = $_POST['goods_code'] ? trim($_POST['goods_code']) : '';
		$category_id= $_POST['category_id'] ? intval($_POST['category_id']) : 0;
		$unit		= $_POST['unit'] ? trim($_POST['unit']) : '';
		$price 		= $_POST['price'] ? floatval($_POST['price']) : 0;
		$tax 		= $_POST['tax'] ? intval($_POST['tax']) : 0;
		$stock		= $_POST['stock'] ? intval($_POST['stock']) : 0;
		$is_show	= $_POST['is_show'] ? intval($_POST['is_show']) : 0;
		$descrition	= $_POST['description'] ? trim($_POST['description']) : '';
		
		
		$param = array(
			'goods_name'	=> $goods_name,
			'goods_code'	=> $goods_code,
			'category_id'	=> $category_id,
			'unit'			=> $unit,
			'price'			=> $price,
			'tax'			=> $tax,
			'stock'			=> $stock,
			'is_show'		=> $is_show,
			'description'	=> $descrition,
			'operator_id'	=> $operator_id,
			'operator'		=> $operator
		);
		
		importModule("GoodsInfo","class");
		$obj_good = new GoodsInfo;
		
		$res = $obj_good->add_new_good($param);
		
		if($res)
		{
			//成功
			$return = array(
				'statusCode'	=> 200,
				'message'		=> '添加成功',
				'navTabId'		=> 'pagination1',
				'rel'			=> '',
				'callbackType'	=> 'closeCurrent',
				'forwardUrl'	=> '',
				'confirmMsg'	=> ''
			);
		}
		else
		{
			//失败
			$return = array(
				'statusCode'	=> 0,
				'message'		=> '添加失败',
				'navTabId'		=> 'pagination1',
				'rel'			=> '',
				'callbackType'	=> '',
				'forwardUrl'	=> 'closeCurrent',
				'confirmMsg'	=> ''
			);
		}
		exit(json_encode($return));

	}
	
	//编辑商品页面
	public function doEditGood()
	{
		$goods_id = !empty($_GET['goods_id']) ? intval($_GET['goods_id']) : 0;
		
		//获取商品
		
		$page = $this->app->page();
		$page->params['template'] = 'edit_good.html';
		$page->output();
	}
	

}
$app->run();
	
?>
