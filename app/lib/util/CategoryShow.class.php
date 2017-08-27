<?php
/**
 * 分类处理
 */
class CategoryShow{
	
	//分类数组处理
	public static function category_show($category=array())
	{
		//print_r($category);die;
		//获取一级分类
		$f_category = array();
		foreach($category as $key => $val)
		{
			if($val['parent_id'] == 0)
			{
				$f_category[] = $val;
			}
		}
		
		//print_r($f_category);die;
		//二级分类
		$s_category = array();
		foreach($f_category as $k => $v)
		{
			foreach($category as $key => $val)
			{
				if($v['cid'] == $val['parent_id'])
				{
					$f_category[$k]['child'][] = $val;
				}
			}
		}
		
		//三级分类
		foreach($f_category as $k => $v)
		{
			foreach($v['child'] as $key => $val)
			{
				foreach($category as $kk => $vv)
				{
					if($val['cid'] == $vv['parent_id'])
					{
						$f_category[$k]['child'][$key]['child'][] = $vv;
					}
				}
				
			}
		}
		
		return $f_category;
	}
}

?>