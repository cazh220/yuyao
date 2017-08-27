<?php 
/**
 * 分类模块
 */
require_once('./common.inc.php');

class category extends Action {
	
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
		//print_r($category_show);die;
		$page = $this->app->page();
		$page->value('category',$category_show);
		$page->params['template'] = 'index_menu.html';
		$page->output();
	}
	
	//添加节点
	public function doAddCategory()
	{
		$last_cat_id = $_GET['last_cat_id'];
		$current_cat_id = $_GET['current_cat_id'];
		
		//获取上级分类名称
		importModule("CategoryInfo","class");
		$obj_category = new CategoryInfo;
		$last_detail = $obj_category->get_category_info($last_cat_id);
		//获取当前分类信息
		$current_detail = $obj_category->get_category_info($current_cat_id);
		//print_r($current_detail);die;
		$page = $this->app->page();
		$page->value('last_category',$last_detail);
		$page->value('current_category',$current_detail);
		$page->params['template'] = 'add_category.html';
		$page->output();
	}

}
$app->run();
	
?>
