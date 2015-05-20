<?php /* Smarty version 2.6.19, created on 2014-11-23 17:28:08
         compiled from stock-report/edit-district.tpl */ ?>
<form name="myForm" id="myForm" action="<?php echo $this -> callViewHelper('url', array());?>" method="post">
<div class="title"><?php if ($this->_tpl_vars['action'] == 'edit'): ?>编辑库区<?php else: ?>添加库区<?php endif; ?></div>
<div class="content">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
    <tr> 
      <td width="15%"><strong>库区名称</strong> * </td>
      <td><input type="text" name="district_name" id="district_name" size="20" value="<?php echo $this->_tpl_vars['data']['district_name']; ?>
" msg="请填写库区名称" class="required" /></td>
    </tr>
    <tr> 
      <td width="15%"><strong>库区编号</strong> * </td>
      <td><input type="text" name="district_no" id="district_no" size="10" value="<?php echo $this->_tpl_vars['data']['district_no']; ?>
" msg="请填写库区编号" class="required" /></td>
    </tr>
    <tr> 
      <td><strong>所属仓库</strong> * </td>
      <td>
        <select name="area">
          <?php $_from = $this->_tpl_vars['areas']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
?>
          <option value="<?php echo $this->_tpl_vars['key']; ?>
" <?php if ($this->_tpl_vars['data']['area'] == $this->_tpl_vars['key']): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['item']; ?>
</option>
          <?php endforeach; endif; unset($_from); ?>
        </select>
      </td>
    </tr>
    <tr> 
      <td><strong>备注</strong></td>
      <td>
		<input type="text" name="memo" id="memo" size="50" value="<?php echo $this->_tpl_vars['data']['memo']; ?>
"/>
	  </td>
    </tr>
    <tr> 
      <td><strong>是否启用</strong> * </td>
      <td>
	   <input type="radio" name="status" value="0" <?php if ($this->_tpl_vars['data']['status'] == 0 && $this->_tpl_vars['action'] == 'edit'): ?>checked<?php endif; ?>/> 是
	   <input type="radio" name="status" value="1" <?php if ($this->_tpl_vars['data']['status'] == 1 || $this->_tpl_vars['action'] == 'add'): ?>checked<?php endif; ?>/> 否
	  </td>
    </tr>
</tbody>
</table>
<div class="submit"><input type="submit" name="dosubmit" id="dosubmit" value="确定"/> <input type="reset" name="reset" value="重置" /></div>
</form>