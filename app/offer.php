<?php 
/**
 * 报价
 */
require_once('./common.inc.php');

class offer extends Action {
	
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
		$page->params['template'] = 'offer_menu.html';
		$page->output();
	}
	
	//商品列表
	public function doOfferList()
	{
		$role_id = 2;
		$category_id 	= !empty($_GET['category_id']) ? intval($_GET['category_id']) : 0;
		$current_page 	= !empty($_GET['page']) ? intval($_GET['page']) : 1;
		
		importModule("GoodsInfo","class");
		$obj_good = new GoodsInfo;
		
		$param = array(
			'category_id'	=> $category_id,
			'page'			=> $current_page,
			'page_size'		=> 10
		);	
		
		$list = $obj_good->get_offer_list($param);
		//print_r($list);die;
		importModule("CategoryInfo","class");
		$obj_category = new CategoryInfo;
		
		$category_list = $obj_category->get_categoty_list();
		
		//导入工具类
		import('util.CategoryShow');
		$category_show = CategoryShow::category_show($category_list);
		//print_r($list);die;
		//获取总数
		$count = $list['count'];
		$page_size = 10;
		$page_num = ceil($count/$page_size);
		
		$page_info = array(
			'total'		=> $count,
			'page_num'	=> $page_num,
			'page_size'	=> $page_size,
			'current_page'=>$current_page
		);

		//用户报价输出
		foreach($list['list'] as $key => $val)
		{
			$role_price = $obj_good->get_role_good_price($role_id, $val['goods_id']);
			//var_dump($role_price);die;
			if(empty($role_price))
			{
				//取基本价
				$role_price = $obj_good->get_good_price($val['goods_id']);
			}
			
			$list['list'][$key]['price'] = $role_price;
		}
		
		//print_r($page_info);die;
		$page = $this->app->page();
		$page->value('goods',$list['list']);
		$page->value('category',$category_show);
		$page->value('page',$page_info);
		$page->params['template'] = 'offer_list.html';
		$page->output();
	}
	
	
	

}
$app->run();
	
?>
