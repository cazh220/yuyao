<?php /* Smarty version 2.6.10, created on 2017-08-16 01:00:32
         compiled from index_menu.html */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=7" />
<title>简单实用国产jQuery UI框架 - DWZ富客户端框架(J-UI.com)</title>


<link href="templates/themes/default/style.css" rel="stylesheet" type="text/css" media="screen"/>
<link href="templates/themes/css/core.css" rel="stylesheet" type="text/css" media="screen"/>
<link href="templates/themes/css/print.css" rel="stylesheet" type="text/css" media="print"/>
<link href="templates/uploadify/css/uploadify.css" rel="stylesheet" type="text/css" media="screen"/>
<!--[if IE]>
<link href="themes/css/ieHack.css" rel="stylesheet" type="text/css" media="screen"/>
<![endif]-->
<style type="text/css">
<?php echo '
	#header{height:85px}
	#leftside, #container, #splitBar, #splitBarProxy{top:90px}
'; ?>

</style>

<!--[if lt IE 9]><script src="js/speedup.js" type="text/javascript"></script><script src="js/jquery-1.11.3.min.js" type="text/javascript"></script><![endif]-->
<!--[if gte IE 9]><!--><script src="templates/js/jquery-2.1.4.min.js" type="text/javascript"></script><!--<![endif]-->

<script src="templates/js/jquery.cookie.js" type="text/javascript"></script>
<script src="templates/js/jquery.validate.js" type="text/javascript"></script>
<script src="templates/js/jquery.bgiframe.js" type="text/javascript"></script>
<script src="templates/xheditor/xheditor-1.2.2.min.js" type="text/javascript"></script>
<script src="templates/xheditor/xheditor_lang/zh-cn.js" type="text/javascript"></script>
<script src="templates/uploadify/scripts/jquery.uploadify.min.js" type="text/javascript"></script>

<script src="templates/bin/dwz.min.js" type="text/javascript"></script>
<script src="templates/js/dwz.regional.zh.js" type="text/javascript"></script>
<script src="templates/js/my.js" type="text/javascript"></script>

<script type="text/javascript">
<?php echo '
$(function(){
	DWZ.init("dwz.frag.xml", {
		loginUrl:"login_dialog.html", loginTitle:"登录",	// 弹出登录对话框
//		loginUrl:"login.html",	// 跳到登录页面
		statusCode:{ok:200, error:300, timeout:301}, //【可选】
		keys: {statusCode:"statusCode", message:"message"}, //【可选】
		pageInfo:{pageNum:"pageNum", numPerPage:"numPerPage", orderField:"orderField", orderDirection:"orderDirection"}, //【可选】
		debug:true,	// 调试模式 【true|false】
		callback:function(){
			initEnv();
			$("#themeList").theme({themeBase:"templates/themes"});
			//setTimeout(function() {$("#sidebar .toggleCollapse div").trigger("click");}, 10);
		}
	});
});
'; ?>

</script>
</head>

<body scroll="no">
	<div id="layout">
		<div id="header">
			<div class="headerNav">
				<a class="logo" href="http://j-ui.com">标志</a>
				<ul class="nav">
					<li><a href="donation.html" target="dialog" height="400" title="捐赠 & DWZ学习视频">捐赠</a></li>
					<li><a href="changepwd.html" target="dialog" width="600">设置</a></li>
					<li><a href="http://www.cnblogs.com/dwzjs" target="_blank">博客</a></li>

					<li><a href="login.html">退出</a></li>
				</ul>
				<ul class="themeList" id="themeList">
					<li theme="default"><div class="selected">蓝色</div></li>
					<li theme="green"><div>绿色</div></li>
					<!--<li theme="red"><div>红色</div></li>-->
					<li theme="purple"><div>紫色</div></li>
					<li theme="silver"><div>银色</div></li>
					<li theme="azure"><div>天蓝</div></li>
				</ul>
			</div>
		
			<div id="navMenu">
				<ul>
					<li class="selected"><a href="sidebar_1.html"><span>产品分类</span></a></li>
					<li><a href="sidebar_2.html"><span>产品信息</span></a></li>
					<li><a href="sidebar_1.html"><span>订单管理</span></a></li>
				</ul>
			</div>
		</div>

		<div id="leftside">
			<div id="sidebar_s">
				<div class="collapse">
					<div class="toggleCollapse"><div></div></div>
				</div>
			</div>
			
			<div id="sidebar">
				<div class="toggleCollapse"><h2>主菜单</h2><div>收缩</div></div>

				<div class="accordion" fillSpace="sidebar">
					<div class="accordionHeader">
						<h2><span>Folder</span>供应商分类</h2>
					</div>
					<div class="accordionContent">
						<ul class="tree treeFolder">
							<?php if ($this->_tpl_vars['category']): ?>
								<?php $_from = $this->_tpl_vars['category']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
?>
								<li><a href="<?php echo $this->_tpl_vars['item']['url']; ?>
" target="navTab" rel="pagination1"><?php echo $this->_tpl_vars['item']['cname']; ?>
</a>
									<?php if ($this->_tpl_vars['item']['child']): ?>
									<ul>
										<?php $_from = $this->_tpl_vars['item']['child']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['keya'] => $this->_tpl_vars['itema']):
?>
										<li><a href="<?php echo $this->_tpl_vars['itema']['url']; ?>
" target="navTab" rel="pagination1"><?php echo $this->_tpl_vars['itema']['cname']; ?>
</a>
											<?php if ($this->_tpl_vars['itema']['child']): ?>
											<ul>
												<?php $_from = $this->_tpl_vars['itema']['child']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['keyb'] => $this->_tpl_vars['itemb']):
?>
												<li><a href="category.php?do=addcategory&last_cat_id=<?php echo $this->_tpl_vars['itema']['cid']; ?>
&current_cat_id=<?php echo $this->_tpl_vars['itemb']['cid']; ?>
" target="dialog" rel="dlg_page9" minable="false"><?php echo $this->_tpl_vars['itemb']['cname']; ?>
</a></li>
												<?php endforeach; endif; unset($_from); ?>
											</ul>
											<?php endif; ?>
										</li>
										<?php endforeach; endif; unset($_from); ?>
									</ul>
									<?php endif; ?>
								</li>
								<?php endforeach; endif; unset($_from); ?>
							<?php endif; ?>
						</ul>
					</div>
					<div class="accordionHeader">
						<h2><span>Folder</span>我的分类</h2>
					</div>
					<div class="accordionContent">
						

					</div>
				</div>

			</div>
			
			
		</div>
		
		<div id="container">
			<div id="navTab" class="tabsPage">
				<div class="tabsPageHeader">
					<div class="tabsPageHeaderContent"><!-- 显示左右控制时添加 class="tabsPageHeaderMargin" -->
						<ul class="navTab-tab">
							<li tabid="main" class="main"><a href="javascript:;"><span><span class="home_icon">产品分类</span></span></a></li>
						</ul>
					</div>
					<div class="tabsLeft">left</div><!-- 禁用只需要添加一个样式 class="tabsLeft tabsLeftDisabled" -->
					<div class="tabsRight tabsLeftDisabled">right</div><!-- 禁用只需要添加一个样式 class="tabsRight tabsRightDisabled" -->
					<div class="tabsMore">more</div>
				</div>
				<ul class="tabsMoreList">
					<li><a href="javascript:;">产品分类</a></li>
				</ul>
				<div class="navTab-panel tabsPageContent layoutBox">
					<div class="page unitBox">
						
						<div class="pageFormContent" layoutH="80">
							AAAAAAAAAAAAAAAA
						</div>
					</div>
					
				</div>
			</div>
		</div>

	</div>

	<div id="footer">Copyright &copy; 2010 <a href="demo_page2.html" target="dialog">DWZ团队</a></div>

</body>

</html>