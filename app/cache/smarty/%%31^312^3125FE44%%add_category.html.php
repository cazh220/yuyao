<?php /* Smarty version 2.6.10, created on 2017-09-05 18:11:59
         compiled from add_category.html */ ?>

<div class="pageContent">
	<form method="post" action="category.php?do=categoryact" class="pageForm required-validate" onsubmit="return validateCallback(this, dialogAjaxDone);">
		<div class="pageFormContent" layoutH="56">
			<p>
				<label>上级分类</label>
				<input name="last_category" type="text" size="30" value="<?php echo $this->_tpl_vars['last_category']['cname']; ?>
" readonly="readonly"/>
				<input type="hidden" id="last_category_id" name="last_category_id" value="<?php echo $this->_tpl_vars['last_category']['cid']; ?>
">
				<input type="hidden" id="last_deepth" name="last_deepth" value="<?php echo $this->_tpl_vars['last_category']['deepth']; ?>
">
				<input type="hidden" id="ctype" name="ctype" value="<?php echo $this->_tpl_vars['last_category']['ctype']; ?>
">
				<input type="hidden" id="cid" name="cid" value="<?php echo $this->_tpl_vars['current_category']['cid']; ?>
">
			</p>
			<p>
				<label>分类名称：</label>
				<input name="category_name" class="required" type="text" size="30" value="<?php echo $this->_tpl_vars['current_category']['cname']; ?>
" alt="请输入分类名称"/>
			</p>
			<p>
				<label>分类链接：</label>
				<input name="category_link" class="required" type="text" size="30" value="<?php echo $this->_tpl_vars['current_category']['url']; ?>
" alt="请输入分类链接"/>
			</p>
			<p>
				<label>分类描述：</label>
				<input type="text" name="category_desc" size="30" value="<?php echo $this->_tpl_vars['current_category']['description']; ?>
" />
			</p>
			<p>
				<label>所属供应商：</label>
				<select name="type" class="required combox">
					<option value="">请选择</option>
					<option value="0" <?php if ($this->_tpl_vars['current_category']['ctype'] == 0 || ! $this->_tpl_vars['current_category']['ctype']): ?>selected<?php endif; ?>>供应商</option>
					<option value="1" <?php if ($this->_tpl_vars['current_category']['ctype'] == 1): ?>selected<?php endif; ?>>公司</option>
				</select>
			</p>
			<p style="width: 300px;">
				<label>操作类别：</label>
				<label style="width: 200px;">
					<input type="radio" id="o1" class="operate_type" name="type" value="0" onclick="add()"/>添加同类 
					<input type="radio" id="o1" class="operate_type" name="type" value="0" onclick="add()"/>添加子类
					<input type="radio" class="operate_type" name="type" value="1" checked onclick="edit()"/>编辑
					<input type="radio" class="operate_type" name="type" value="2" checked onclick="delete_category()"/>删除
				</label>
				<label></label>
			</p>
			
		</div>
		<div class="formBar">
			<ul>
				<!--<li><a class="buttonActive" href="javascript:;"><span>保存</span></a></li>-->
				<li><div class="buttonActive"><div class="buttonContent"><button type="submit">保存</button></div></div></li>
				<li>
					<div class="button"><div class="buttonContent"><button type="button" class="close">取消</button></div></div>
				</li>
			</ul>
		</div>
	</form>
</div>