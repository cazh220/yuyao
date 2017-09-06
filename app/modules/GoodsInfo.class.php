<?php
/**
 * 商品处理
 */
 
class GoodsInfo
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
    
    //获取商品列表
    public function get_goods_list($param)
    {
    	if($this->db == null)
		{
    		return false;
    	}
    	
    	$where = ' a.is_delete = 0 AND a.is_show = 0';
    	if($param['category_id'])
    	{
    		$where .= " AND a.category_id = ".$param['category_id'];
    	}
    	
    	$sql = "SELECT * FROM yy_goods a LEFT JOIN yy_category b ON a.category_id = b.cid WHERE {$where}  ORDER BY goods_id DESC";
    	
    	if($param['page'] && $param['page_size'])
    	{
    		$start = ($param['page']-1)*$param['page_size'];
    		$page_size = $param['page_size'];
    		$sql .= " LIMIT {$start}, {$page_size}";
    	}
    	
    	$res = $this->db->getArray($sql);
    	return $res;
    }
    
    
    //添加商品
    public function add_new_good($param)
    {
    	if($this->db == null || empty($param))
		{
    		return false;
    	}
    	
    	$sql = "INSERT INTO yy_goods SET ";
    	foreach($param as $key => $value)
    	{
    		$sql .= $key." = '".$value."',";
    	}
    	$sql .= "create_time = NOW(), update_time = NOW()";
    	
    	try{
    		$res = $this->db->exec($sql);
    		return true;
    	}catch(exception $e){
    		$this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . ' err:'.$e->getMessage().'  sql execute false. sql = ' . $sql, date("Y-m-d H:i:s")));
    		return false;
    	}
    } 
    
    
    //编辑商品
    public function update_good($param=array(), $goods_id=0)
    {
    	if($this->db == null || empty($param) || empty($goods_id))
		{
    		return false;
    	}
    
    	$sql = "UPDATE yy_goods SET ";
    	foreach($param as $key => $value)
    	{
    		$sql .= $key." = '".$value."',";
    	}
    	$sql .= "update_time = NOW() WHERE goods_id = ".$goods_id;
    	try{
    		$res = $this->db->exec($sql);
    		return true;
    	}catch(exception $e){
    		$this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . ' err:'.$e->getMessage().'  sql execute false. sql = ' . $sql, date("Y-m-d H:i:s")));
    		return false;
    	}
    }
    
    //删除商品
   public function delete_good($goods_id=0)
   {
		if($this->db == null || empty($goods_id))
		{
    		return false;
    	}
    	
    	$sql = "UPDATE yy_goods SET is_delete = 1 WHERE cid = ".$goods_id;
    
    	try{
    		$res = $this->db->exec($sql);
    		return true;
    	}catch(exception $e){
    		$this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . ' err:'.$e->getMessage().'  sql execute false. sql = ' . $sql, date("Y-m-d H:i:s")));
    		return false;
    	}
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