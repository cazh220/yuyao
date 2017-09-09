<?php
/**
 * 用户处理
 */
 
class UserInfo
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
    
    //获取所有普通用户
    public function get_user_list()
    {
    	if($this->db == null)
		{
    		return false;
    	}
    	
    	$sql = "SELECT * FROM yy_users WHERE is_delete = 0 AND type = 0";
    	
    	$res = $this->db->getArray($sql);
    	return $res;
    }
    
    //添加新用户
    public function add_new_user($param=array())
    {
    	if($this->db == null || empty($param))
		{
    		return false;
    	}
    	
    	$sql = "INSERT INTO yy_users SET ";
    	foreach($param as $key => $val)
    	{
    		$sql .= $key." = '".$val."',";
    	}
    	$sql .= "create_time = NOW(), update_time = NOW()";
    	//echo $sql;die;
    	try{
    		$res = $this->db->exec($sql);
    	}catch(exception $e){
    		$this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . ' err:'.$e->getMessage().'  sql execute false. sql = ' . $sql, date("Y-m-d H:i:s")));
    		return false;
    	} 
    	
    	return true;
    }
    
    //获取用户详情
    public function get_user_detail($user_id = 0)
    {
    	if($this->db == null || empty($user_id))
		{
    		return false;
    	}
    	
    	$sql = "SELECT * FROM yy_users WHERE user_id = {$user_id}";
    	
    	$res = $this->db->getRow($sql);

    	return !empty($res) ? $res : array();
    }
    
    //更新用户信息
    public function edit_user($param=array(), $user_id=0)
    {
    	if($this->db == null || empty($user_id) || empty($param))
		{
    		return false;
    	}
    	
    	$sql = "UPDATE yy_users SET ";
    	foreach($param as $key => $val)
    	{
    		$sql .= $key." = '".$val."',";
    	}
    	
    	$sql .= ' update_time = NOW() WHERE user_id = '.$user_id;
    	//echo $sql;die;
    	try{
    		$res = $this->db->exec($sql);
    	}catch(exception $e){
    		$this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . ' err:'.$e->getMessage().'  sql execute false. sql = ' . $sql, date("Y-m-d H:i:s")));
    		return false;
    	} 
    	
    	return true;
    }
    
    //移除用户
    public function remove_user($user_id=0)
    {
    	if($this->db == null || empty($user_id))
		{
    		return false;
    	}
    	
    	$sql = "UPDATE yy_users SET is_delete = 1 WHERE user_id = ".$user_id;
    	
    	try{
    		$res = $this->db->exec($sql);
    	}catch(exception $e){
    		$this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . ' err:'.$e->getMessage().'  sql execute false. sql = ' . $sql, date("Y-m-d H:i:s")));
    		return false;
    	} 
    	
    	return true;
    }
    
    //获取管理员
    public function get_admin_list()
    {
    	if($this->db == null)
		{
    		return false;
    	}
    	
    	$sql = "SELECT * FROM yy_users WHERE is_delete = 0 AND type = 1";
    	
    	$res = $this->db->getArray($sql);
    	return $res;
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