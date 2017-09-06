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
		$page = $this->app->page();
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
	

}
$app->run();
	
?>
