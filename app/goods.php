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
		
		//获取分类
		importModule("CategoryInfo","class");
		$obj_category = new CategoryInfo;
		
		$category_list = $obj_category->get_categoty_list();
		//导入工具类
		import('util.CategoryShow');
		$category_show = CategoryShow::category_show($category_list);
		
		//获取商品
		importModule("GoodsInfo","class");
		$obj_good = new GoodsInfo;
		$good_detail = $obj_good->get_good_detail($goods_id );
		
		//print_r($category_list);die;
		$page = $this->app->page();
		$page->value('category',$category_show);
		$page->value('good',$good_detail);
		$page->params['template'] = 'edit_good.html';
		$page->output();
	}
	
	//编辑商品操作
	public function doEditGoodAct()
	{
		$goods_id 		= !empty($_POST['goods_id']) ? intval($_POST['goods_id']) : 0;
		$goods_name		= !empty($_POST['goods_name']) ? trim($_POST['goods_name']) : '';
		$category_id	= !empty($_POST['category_id']) ? intval($_POST['category_id']) : 0;
		$unit			= !empty($_POST['unit']) ? trim($_POST['unit']) : '';
		$price			= !empty($_POST['price']) ? intval($_POST['price']) : 0;
		$tax			= !empty($_POST['tax']) ? intval($_POST['tax']) : 0;
		$stock			= !empty($_POST['stock']) ? intval($_POST['stock']) : 0;
		$is_show		= !empty($_POST['is_show']) ? intval($_POST['is_show']) : 0;
		$description	= !empty($_POST['description']) ? trim($_POST['description']) : '';
		
		$param = array(
			'goods_name'	=> $goods_name,
			'category_id'	=> $category_id,
			'unit'			=> $unit,
			'price'			=> $price,
			'tax'			=> $tax,
			'stock'			=> $stock,
			'is_show'		=> $is_show,
			'description'	=> $description
		);		
		
		importModule("GoodsInfo","class");
		$obj_good = new GoodsInfo;
		
		$res = $obj_good->update_good($param, $goods_id);
		
		if($res)
		{
			//成功
			$return = array(
				'statusCode'	=> 200,
				'message'		=> '编辑成功',
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
				'message'		=> '编辑失败',
				'navTabId'		=> 'pagination1',
				'rel'			=> '',
				'callbackType'	=> '',
				'forwardUrl'	=> 'closeCurrent',
				'confirmMsg'	=> ''
			);
		}
		exit(json_encode($return));
	}
	
	//商品报价页
	public function doOfferPrice()
	{
		$goods_id = !empty($_GET['goods_id']) ? intval($_GET['goods_id']) : 0;
		//获取所有用户的商品报价
		importModule("GoodsInfo","class");
		$obj_good = new GoodsInfo;
		
		//获取所有客户用户角色
		importModule("RoleInfo","class");
		$obj_role = new RoleInfo;
		
		$roles = $obj_role->get_all_roles();
		$good_info = $obj_good->get_good_detail($goods_id);
		$base_price = !empty($good_info['price']) ? $good_info['price'] : 0;
		$goods_name = !empty($good_info['goods_name']) ? $good_info['goods_name'] : '';
		
		$offer_price = array();
		//基础报价
		$base = array(
			'goods_id'	=> $goods_id,
			'role_id'	=> $roles[0]['role_id'],
			'role_name'	=> $roles[0]['role_name'],
			'goods_name'=> $goods_name,
			'price'		=> $base_price ? $base_price : 0,
			'start_time'=> '',
			'end_time'	=> '',
			'type'		=> 'base'
		);
		
		//遍历整合报价
		if($roles)
		{
			foreach($roles as $key => $val)
			{
				if($val['role_id'] != 1)//1管理员
				{
					//查询报价
					$offer = $obj_good->get_role_price_detail($val['role_id'], $goods_id);

					if(!empty($offer))
					{
						$price  = $offer['price'] ? $offer['price'] : 0;
						$start_time = $offer['start_time'] ? $offer['start_time'] : '';
						$end_time = $offer['end_time'] ? $offer['end_time'] : '';
					}
					else
					{
						//基础报价
						$price  = $base_price ? $base_price : 0;
						$start_time = '';
						$end_time = '';
					}

					$offer_price[$key] = array(
						'goods_id'	=> $goods_id,
						'role_id'	=> $val['role_id'],
						'role_name'	=> $val['role_name'],
						'goods_name'=> $goods_name,
						'price'		=> $price,
						'start_time'=> $start_time,
						'end_time'	=> $end_time,
						'type'		=> 'client'
					);
					
				}
			}
		}
		
		array_unshift($offer_price, $base);
		$page = $this->app->page();
		$page->value('offer',$offer_price);
		$page->params['template'] = 'offer_price.html';
		$page->output();
		
	}
	
	//修改角色报价
	public function doUpdateOfferPrice()
	{
		$goods_id = !empty($_GET['goods_id']) ? intval($_GET['goods_id']) : 0;
		
		$page = $this->app->page();
		//$page->value('offer',$offer_price);
		$page->params['template'] = 'update_offer.html';
		$page->output();
	}
	
	//修改报价
	public function doUpdateRolePrice()
	{
		$goods_id = !empty($_POST['goods_id']) ? intval($_POST['goods_id']) : 0;
		$role_id = !empty($_POST['role_id']) ? intval($_POST['role_id']) : 0;
		$price = !empty($_POST['price']) ? ($_POST['price']) : 0;
		
		importModule("GoodsInfo","class");
		$obj_good = new GoodsInfo;
		
		
		$param = array(
			'goods_id'		=> $goods_id,
			'role_id'		=> $role_id,
			'price'			=> $price,
			'operator_id'	=> 1,
			'operator'		=> '曹政'
		);

		$res = $obj_good->update_role_price($param);
		
		if($res)
		{
			$return = array('status'=>1, 'msg'=>'更新成功');
		}
		else
		{
			$return = array('status'=>0, 'msg'=>'更新失败');
		}
		
		exit(json_encode($return));
	}
	
	//删除商品
	public function doDeleteGoods()
	{
		$goods_ids = $_REQUEST['ids'] ? trim($_REQUEST['ids']) : '';
		$return = array(
			'statusCode'	=> 0,
			'message'		=> '删除失败',
			'navTabId'		=> 'pagination1',
			'rel'			=> '',
			'callbackType'	=> '',
			'forwardUrl'	=> 'closeCurrent',
			'confirmMsg'	=> ''
		);
		if($goods_ids)
		{
			importModule("GoodsInfo","class");
			$obj_good = new GoodsInfo;
			$res = $obj_good->delete_goods($goods_ids);
			if($res)
			{
				$return = array(
					'statusCode'	=> 200,
					'message'		=> '删除成功',
					'navTabId'		=> 'pagination1',
					'rel'			=> '',
					'callbackType'	=> '',
					'forwardUrl'	=> 'closeCurrent',
					'confirmMsg'	=> ''
				);
			}
		}
		
		exit(json_encode($return));	
		
		
	}
	

}
$app->run();
	
?>
