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
		//获取客户分类
		importModule("RoleInfo","class");
		$obj_role = new RoleInfo;
		
		$roles = $obj_role->get_all_roles();
		//获取分车
		importModule("TruckInfo","class");
		$obj_truck = new TruckInfo;
		
		$trucks = $obj_truck->get_all_trucks();
		
		//print_R($roles);print_r($trucks);die;
		
		$page = $this->app->page();
		$page->value('roles',$roles);
		$page->value('trucks',$trucks);
		$page->params['template'] = 'user_add.html';
		$page->output();
	}
	
	//添加用户
	public function doAddUserAct()
	{
		$username	= !empty($_POST['username']) ? trim($_POST['username']) : '';
		$pwd		= !empty($_POST['password']) ? trim($_POST['password']) : '';
		$realname	= !empty($_POST['realname']) ? trim($_POST['realname']) : '';
		$role_id	= !empty($_POST['role']) ? trim($_POST['role']) : 0;
		$company_name=!empty($_POST['company_name']) ? trim($_POST['company_name']) : '';
		$address	=!empty($_POST['address']) ? trim($_POST['address']) : '';
		$mobile		= !empty($_POST['mobile']) ? trim($_POST['mobile']) : '';
		$truck		= !empty($_POST['truck']) ? trim($_POST['truck']) : '';
		
		$param = array(
			'username'		=> $username,
			'password'		=> md5($pwd),
			'realname'		=> $realname,
			'role_id'		=> $role_id,
			'company_name'	=> $company_name,
			'address'		=> $address,
			'mobile'		=> $mobile,
			'truck'			=> $truck
		);
		
		importModule("UserInfo","class");
		$obj_user = new UserInfo;
		
		$res = $obj_user->add_new_user($param);
		
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
	
	//编辑用户
	public function doEditUser()
	{
		$user_id = $_GET['user_id'] ? intval($_GET['user_id']) : 0;

		//获取客户分类
		importModule("RoleInfo","class");
		$obj_role = new RoleInfo;
		$roles = $obj_role->get_all_roles();
		//获取分车
		importModule("TruckInfo","class");
		$obj_truck = new TruckInfo;
		$trucks = $obj_truck->get_all_trucks();
		
		//获取用户信息
		importModule("UserInfo","class");
		$obj_user = new UserInfo;
		
		$user = $obj_user->get_user_detail($user_id);
		//print_r($user);die;
		$page = $this->app->page();
		$page->value('roles',$roles);
		$page->value('trucks',$trucks);
		$page->value('user',$user);
		$page->params['template'] = 'user_edit.html';
		$page->output();
	}
	
	//编辑
	public function doEditUserAct()
	{
		$user_id	= !empty($_POST['user_id']) ? intval($_POST['user_id']) : 0;
		$username	= !empty($_POST['username']) ? trim($_POST['username']) : '';
		$realname	= !empty($_POST['realname']) ? trim($_POST['realname']) : '';
		$role_id	= !empty($_POST['role']) ? trim($_POST['role']) : 0;
		$company_name=!empty($_POST['company_name']) ? trim($_POST['company_name']) : '';
		$address	=!empty($_POST['address']) ? trim($_POST['address']) : '';
		$mobile		= !empty($_POST['mobile']) ? trim($_POST['mobile']) : '';
		$truck		= !empty($_POST['truck']) ? trim($_POST['truck']) : '';
		
		$param = array(
			'username'		=> $username,
			'role_id'		=> $role_id,
			'company_name'	=> $company_name,
			'address'		=> $address,
			'mobile'		=> $mobile,
			'truck'			=> $truck
		);
		
		importModule("UserInfo","class");
		$obj_user = new UserInfo;
		$res = $obj_user->edit_user($param, $user_id);
		
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
	
	//删除用户
	public function doRemoveUser()
	{
		$user_id	= !empty($_GET['user_id']) ? intval($_GET['user_id']) : 0;
		
		importModule("UserInfo","class");
		$obj_user = new UserInfo;
		$res = $obj_user->remove_user($user_id);
		
		if(!$res)
		{
			//成功
			echo "<script>alert('删除失败');window.go(-1);</script>";
		}

	}
	
	//获取管理员列表
	public function doAdminList()
	{
		importModule("UserInfo","class");
		$obj_user = new UserInfo;
		
		$list = $obj_user->get_admin_list();
		
		$page = $this->app->page();
		$page->value('users',$list);
		$page->params['template'] = 'user_list.html';
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
