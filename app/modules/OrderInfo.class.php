<?php
/**
 * 订单处理
 */
 
class OrderInfo
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
    
    //添加订单
    public function add_order($order=array(), $order_goods=array())
    {
    	if($this->db == null || empty($order) || empty($order_goods))
		{
    		return false;
    	}

		$sql = "INSERT INTO yy_order SET ";
		foreach($order as $key => $val)
		{
			$sql .= $key." = '".$val."',";
		}
		$sql .= " create_time = NOW(), update_time = NOW()";
		
		$this->db->exec("START TRANSACTION");
		
		try{
    		$res = $this->db->exec($sql);
    	}catch(exception $e){
    		$this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . ' err:'.$e->getMessage().'  sql execute false. sql = ' . $sql, date("Y-m-d H:i:s")));
    		$this->db->exec("ROLLBACK");
			return false;
    	}
		
		if(!$res)
		{
			$this->db->exec("ROLLBACK");
			return false;
		}
		
		$order_id = $this->db->getLastId();
		
		foreach($order_goods as $key => $val)
		{
			$sql = "INSERT INTO yy_order_goods SET order_id = ".$order_id.", goods_id = ".$val['goods_id'].", goods_num = ".$val['goods_num'].", good_price = ".$val['good_price'];
			
			try{
	    		$res = $this->db->exec($sql);
	    	}catch(exception $e){
	    		$this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . ' err:'.$e->getMessage().'  sql execute false. sql = ' . $sql, date("Y-m-d H:i:s")));
	    		$this->db->exec("ROLLBACK");
				return false;
	    	}
			
			if(!$res)
			{
				$this->db->exec("ROLLBACK");
				return false;
			}
		}
		
		$this->db->exec("COMMIT");
		return true;

    }
    
    //获取待确认的订单商品
    public function get_unconfirm_order_goods($param=array())
    {
    	if($this->db == null || empty($param['user_id']))
		{
    		return false;
    	}
    	
    	$sql = "SELECT * FROM yy_order a LEFT JOIN yy_order_goods b ON a.order_id = b.order_id LEFT JOIN yy_goods c ON b.goods_id = c.goods_id WHERE a.is_delete = 0 AND a.order_status = 0 AND a.operator_id = ".$param['user_id'];
    	
    	$res = $this->db->getArray($sql);
    	return $res ? $res : array();  	
    }
    
    //获取的订单
    public function get_order($param=array())
    {
    	if($this->db == null || empty($param['user_id']))
		{
    		return false;
    	}
    	
    	$sql = "SELECT * FROM yy_order WHERE is_delete = 0 AND order_status = ".$param['order_status']." AND operator_id = ".$param['user_id'];//echo $sql;die;
    	
    	$res = $this->db->getArray($sql);
    	return $res ? $res : array();  	
    }
    
    //移除订单内商品
    public function remove_goods($goods_id=0, $order_id=0)
    {
    	if($this->db == null || empty($goods_id) || empty($order_id))
		{
    		return false;
    	}
    	
    	$sql = "DELETE FROM yy_order_goods WHERE goods_id = {$goods_id} AND order_id = {$order_id}";
    	
    	try{
    		$res = $this->db->exec($sql);
    	}catch(exception $e){
    		$this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . ' err:'.$e->getMessage().'  sql execute false. sql = ' . $sql, date("Y-m-d H:i:s")));
    		$this->db->exec("ROLLBACK");
			return false;
    	}
    	
    	return true;
    }
    
    //更新订单信息
    public function confirm_order($order=array(), $order_goods=array())
    {
    	if($this->db == null || empty($order) || empty($order_goods))
		{
    		return false;
    	}
    	
    	$this->db->exec("START TRANSACTION");
    	foreach($order_goods as $key => $val)
    	{
    		$sql = "UPDATE yy_order_goods SET goods_num = ".$val['goods_num']." WHERE goods_id = ".$val['goods_id']." AND order_id = ".$val['order_id'];
    		
    		try{
	    		$res = $this->db->exec($sql);
	    	}catch(exception $e){
	    		$this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . ' err:'.$e->getMessage().'  sql execute false. sql = ' . $sql, date("Y-m-d H:i:s")));
	    		$this->db->exec("ROLLBACK");
				return false;
	    	}
    		
    	}
    	
    	//更改订单状态
    	$sql  = "UPDATE yy_order SET order_status = 1, total_num = ".$order['total_num'].", total_amount = ".$order['total_amount']." WHERE order_id = ".$order['order_id'];
    	try{
    		$res = $this->db->exec($sql);
    	}catch(exception $e){
    		$this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . ' err:'.$e->getMessage().'  sql execute false. sql = ' . $sql, date("Y-m-d H:i:s")));
    		$this->db->exec("ROLLBACK");
			return false;
    	}
    	
    	//减库存
    	foreach($order_goods as $key => $val)
    	{
    		$sql = "UPDATE yy_goods SET stock = stock - ".$val['goods_num']." WHERE goods_id = ".$val['goods_id'];
    		try{
	    		$res = $this->db->exec($sql);
	    	}catch(exception $e){
	    		$this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . ' err:'.$e->getMessage().'  sql execute false. sql = ' . $sql, date("Y-m-d H:i:s")));
	    		$this->db->exec("ROLLBACK");
				return false;
	    	}
    	}

    	$this->db->exec("COMMIT");
		return true;
    }
    
    //检查订单信息
    public function get_unconfirm_order($user_id = 0)
    {
    	if($this->db == null || empty($user_id))
		{
    		return false;
    	}
    	
    	$sql = "SELECT * FROM yy_order WHERE operator_id = {$user_id} AND order_status = 0 LIMIT 1";
    	try{
    		$order = $this->db->getArray($sql);
    	}catch(exception $e){
    		echo $e->getMessage();die;
    	}
    	
    	return isset($order[0]) ? $order[0] : array();
    }
    
    //添加新商品
    public function add_new_order_goods($order=array(), $order_goods=array())
    {
    	if($this->db == null || empty($order) || empty($order_goods))
		{
    		return false;
    	}
    	
    	$this->db->exec("START TRANSACTION");
    	foreach($order_goods as $key => $val)
    	{
    		$sql = "INSERT INTO yy_order_goods SET order_id = ".$val['order_id'].", goods_id = ".$val['goods_id'].", goods_num = 1, good_price = ".$val['good_price'];
    		try{
	    		$res = $this->db->exec($sql);
	    	}catch(exception $e){
	    		$this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . ' err:'.$e->getMessage().'  sql execute false. sql = ' . $sql, date("Y-m-d H:i:s")));
	    		$this->db->exec("ROLLBACK");
				return false;
	    	}
    	}	
    	
    	//更新订单
    	$sql = "UPDATE yy_order SET total_amount = ".$order['total_amount'].", total_num = ".$order['total_num']." WHERE order_id = ".$order['order_id'];
    	
    	try{
    		$res = $this->db->exec($sql);
    	}catch(exception $e){
    		$this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . ' err:'.$e->getMessage().'  sql execute false. sql = ' . $sql, date("Y-m-d H:i:s")));
    		$this->db->exec("ROLLBACK");
			return false;
    	}
    	
    	
    	$this->db->exec("COMMIT");
		return true;
    }
    
    //获取订单商品明显
    public function get_order_goods($order_id=0)
    {
    	if($this->db == null || empty($order_id))
		{
    		return false;
    	}
    	
    	$sql = "SELECT * FROM yy_order a LEFT JOIN yy_order_goods b ON a.order_id = b.order_id LEFT JOIN yy_goods c ON b.goods_id = c.goods_id WHERE a.order_id = {$order_id}";
    	//echo $sql;die;
    	$res = $this->db->getArray($sql);
    	
    	return $res ? $res : array();
    }
    
    //获取订单
    public function get_order_list($param=array())
    {
    	if($this->db == null || empty($param))
		{
    		return false;
    	}
    	$where = '';
    	$sql = "SELECT * FROM yy_order WHERE is_delete = 0 {$where} ORDER BY order_id DESC";
    	
    	$sql_count = "SELECT count(*) as count FROM yy_order WHERE is_delete = 0 {$where}";
    	$res_count = $this->db->getValue($sql);
    	
    	if($param['current_page'] && $param['page_size'])
    	{
    		$start = ($param['current_page']-1)*$param['page_size'];
    		$page_size = $param['page_size'];
    		$sql .= " LIMIT $start,$page_size";
    	}
    	
    	$res = $this->db->getArray($sql);
    	
    	$return = array('list'=>$res, 'count'=>$res_count ? $res_count : 0);
    	return $return ? $return : array();
    }
    
    //获取订单详情
    public function get_order_detail($order_id=0)
    {
    	if($this->db == null || empty($order_id))
		{
    		return false;
    	}
    	
    	$sql = "SELECT * FROM yy_order WHERE order_id = {$order_id}";
    	
    	$res = $this->db->getRow($sql);
    	
    	return $res ? $res : array();
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