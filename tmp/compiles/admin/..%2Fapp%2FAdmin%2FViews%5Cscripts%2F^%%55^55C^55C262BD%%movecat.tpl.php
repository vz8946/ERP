<?php /* Smarty version 2.6.19, created on 2014-10-27 14:10:07
         compiled from category/movecat.tpl */ ?>
<div class="title">分类管理 ---> 展示分类移动</div>
<div class="search">
	<form name="myForm" id="myForm" method="post" action="/admin/category/movecat">
	把<?php echo $this->_tpl_vars['fromcatSelect']; ?>
的商品移动到  <?php echo $this->_tpl_vars['tocatSelect']; ?>
 <font color="#FF0000"> (必须是末级分类)</font>
	<input type="submit" name="dosubmit" id="dosubmit" value="确定移动"/> 
	</form>
</div>