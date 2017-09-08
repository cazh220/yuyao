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
			$sql = "INSERT INTO yy_order_goods SET order_id = ".$order_id.", goods_id = ".$val['goods_id'].", goods_num = ".$val['goods_num'];
			
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