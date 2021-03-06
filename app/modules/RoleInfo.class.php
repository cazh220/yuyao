<?php
/**
 * 角色用户处理
 */
 
class RoleInfo
{
	/**
	 * 应用程序对象
	 * @var Application
	 */
	private $app = null;
	
	/**
	 * 数据库操作对象
	 * @var OrmQuery
	 */
	private $db = null;
	
	/**
     * 构造函数，获取数据库连接对象
     *
     */
    public function __construct(){
        global $app;
        $this->app = $app;
        $this->db = $app->orm($app->cfg['db'])->query();

        mysql_query("set names utf8");
    }
    
    //获取所有角色用户
    public function get_all_roles()
    {
    	if($this->db == null)
		{
    		return false;
    	}
    	
    	$sql = "SELECT * FROM yy_role WHERE is_delete = 0";
    	
    	$res = $this->db->getArray($sql);
    	return $res;
    }
    
    //添加新角色
    public function add_new_role($role_name='')
    {
    	if($this->db == null || empty($role_name))
		{
    		return false;
    	}
    	
    	$sql = "INSERT INTO yy_role(role_name, create_time, update_time)VALUES('".$role_name."', NOW(), NOW())";
    	
    	try{
    		$res = $this->db->exec($sql);
    	}catch(exception $e){
    		$this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . ' err:'.$e->getMessage().'  sql execute false. sql = ' . $sql, date("Y-m-d H:i:s")));
    		return false;
    	} 
    	
    	return true;
    }
    
    //获取角色
    public function get_role($role_id=0)
    {
    	if($this->db == null || empty($role_id))
		{
    		return false;
    	}
    	
    	$sql = "SELECT * FROM yy_role WHERE role_id = ".$role_id;
    	
    	$res = $this->db->getRow($sql);
    	
    	return $res ? $res : array();
    }
    
    //编辑
    public function edit_role($role_name='', $role_id=0)
    {
    	if($this->db == null || empty($role_id) || empty($role_name))
		{
    		return false;
    	}
    	
    	$sql = "UPDATE yy_role SET role_name = '".$role_name."' WHERE role_id = ".$role_id;
    	
    	try{
    		$res = $this->db->exec($sql);
    	}catch(exception $e){
    		$this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . ' err:'.$e->getMessage().'  sql execute false. sql = ' . $sql, date("Y-m-d H:i:s")));
    		return false;
    	} 
    	
    	return true;
    }
    
    //删除角色
    public function remove_role($role_id='')
    {
    	if($this->db == null || empty($role_id))
		{
    		return false;
    	}
    	
    	$sql = "UPDATE yy_role SET is_delete = 1 WHERE role_id = ".$role_id;
    	
    	try{
    		$res = $this->db->exec($sql);
    	}catch(exception $e){
    		$this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . ' err:'.$e->getMessage().'  sql execute false. sql = ' . $sql, date("Y-m-d H:i:s")));
    		return false;
    	} 
    	
    	return true;
    }

	/**
	 * 数据更新失败记录日志，并标识操作失败
	 *
	 * @param 	Array 	$data
	 * @return 	bool	false
	 */
	private function _log($data){
	    $log = $this->app->log();
	    $log->reset()->setPath("modules/Category")->setData($data)->write();
	    
	    return false;
	}
}
?>