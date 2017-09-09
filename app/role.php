<?php 
/**
 * 客户分类
 */
require_once('./common.inc.php');

class role extends Action {
	
	/**
	 * 默认执行的方法
	 */
	public function doDefault(){	
		//获取客户分类
		importModule("RoleInfo","class");
		$obj_role = new RoleInfo;
		$roles = $obj_role->get_all_roles();
		
		$page = $this->app->page();
		$page->value('roles',$roles);
		$page->params['template'] = 'role_list.html';
		$page->output();
	}
	
	//添加角色
	public function doAddRole()
	{
		$page = $this->app->page();
		$page->params['template'] = 'role_add.html';
		$page->output();
	}
	
	//添加jiaose
	public function doAddRoleAct()
	{
		$role_name	= !empty($_POST['role_name']) ? trim($_POST['role_name']) : '';
		
		importModule("RoleInfo","class");
		$obj_role = new RoleInfo;
		
		$res = $obj_role->add_new_role($role_name);
		
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
	
	//编辑role
	public function doEditRole()
	{
		$role_id = !empty($_GET['role_id']) ? intval($_GET['role_id']) : 0;
		importModule("RoleInfo","class");
		$obj_role = new RoleInfo;
		
		$role = $obj_role->get_role($role_id);
		
		$page = $this->app->page();
		$page->value('role',$role);
		$page->params['template'] = 'role_edit.html';
		$page->output();
	}
	
	//编辑
	public function doEditRoleAct()
	{
		$role_id = !empty($_POST['role_id']) ? intval($_POST['role_id']) : 0;
		$role_name	= !empty($_POST['role_name']) ? trim($_POST['role_name']) : '';
		
		importModule("RoleInfo","class");
		$obj_role = new RoleInfo;
		
		$res = $obj_role->edit_role($role_name, $role_id);
		
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
	
	//移除角色
	public function doRemoveRole()
	{
		$role_id = !empty($_GET['role_id']) ? intval($_GET['role_id']) : 0;
		importModule("RoleInfo","class");
		$obj_role = new RoleInfo;
		
		$res = $obj_role->remove_role($role_id);
		
		if(!$res)
		{
			//成功
			echo "<script>alert('删除失败');window.go(-1);</script>";
		}
	}
	
	

	
	
	

}
$app->run();
	
?>
