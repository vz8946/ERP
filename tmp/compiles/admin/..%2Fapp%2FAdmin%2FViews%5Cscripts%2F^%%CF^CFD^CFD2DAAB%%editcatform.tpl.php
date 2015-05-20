<?php /* Smarty version 2.6.19, created on 2014-10-24 17:03:55
         compiled from article/editcatform.tpl */ ?>
<form name="myForm" id="myForm" action="<?php echo $this -> callViewHelper('url', array(array('action'=>$this->_tpl_vars['action'],)));?>" method="post">
<input type='hidden' name='cat[cat_id]' value='<?php echo $this->_tpl_vars['cat']['cat_id']; ?>
'>
<div class="title">编辑分类</div>
<div class="content">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
    <tr>
      <td width="10%"><strong>名称</strong> * </td>
      <td><input type="text" name='cat[cat_name]' value='<?php echo $this->_tpl_vars['cat']['cat_name']; ?>
' size="30" msg="请填写名称" class="required" /></td>
    </tr>
    <tr>
      <td width="10%"><strong>类别</strong> * </td>
      <td><select name='cat[parent_id]'>
		<option value=''>--顶级目录--</option>
		<?php $_from = $this->_tpl_vars['catTree']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['catID'] => $this->_tpl_vars['item']):
?>
		<?php if (( ! $this->_tpl_vars['item']['num'] && ! $this->_tpl_vars['item']['deny'] )): ?>
		<option value=<?php echo $this->_tpl_vars['catID']; ?>
 style="padding-left:<?php echo $this->_tpl_vars['item']['step']*20-20; ?>
px" <?php if ($this->_tpl_vars['catID'] == $this->_tpl_vars['cat']['parent_id']): ?>selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['item']['cat_name']; ?>
</option>
		<?php else: ?>
		<optgroup label='<?php echo $this->_tpl_vars['item']['cat_name']; ?>
<?php if ($this->_tpl_vars['item']['num']): ?>（<?php echo $this->_tpl_vars['item']['num']; ?>
）<?php endif; ?>' style="padding-left:<?php echo $this->_tpl_vars['item']['step']*20-20; ?>
px"></optgroup>
		<?php endif; ?>
		<?php endforeach; endif; unset($_from); ?>
	</select></td>
    </tr>
    <tr>
      <td width="10%"><strong>meta标题</strong> * </td>
      <td><input type="text" name="cat[meta_title]" size="30" value="<?php echo $this->_tpl_vars['cat']['meta_title']; ?>
" /></td>
    </tr>
    <tr>
      <td width="10%"><strong>meta关键字</strong> * </td>
      <td><input type="text" name="cat[meta_keywords]" size="30" value="<?php echo $this->_tpl_vars['cat']['meta_keywords']; ?>
" /></td>
    </tr>
    <tr>
      <td width="10%"><strong>meta描述</strong> * </td>
      <td><textarea name="cat[meta_description]" rows="3" cols="39" id="meta_description" style="width:330px; height:45px;"><?php echo $this->_tpl_vars['cat']['meta_description']; ?>
</textarea></td>
    </tr>
</tbody>
</table>
</div>
<div class="submit"><input type="submit" name="dosubmit" id="dosubmit" value="确定" /> <input type="reset" name="reset" value="重置" /></div>
</form>