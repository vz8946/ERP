<?php /* Smarty version 2.6.19, created on 2014-11-23 17:28:16
         compiled from stock-report/edit-position.tpl */ ?>
<form name="myForm" id="myForm" action="<?php echo $this -> callViewHelper('url', array());?>" method="post">
<div class="title"><?php if ($this->_tpl_vars['action'] == 'edit'): ?>编辑库位<?php else: ?>添加库位<?php endif; ?></div>
<div class="content">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
    <tr> 
      <td width="15%"><strong>所属仓库</strong> * </td>
      <td>
        <select name="area" onchange="changeArea(this.value)" <?php if ($this->_tpl_vars['action'] == 'edit'): ?>disabled<?php endif; ?>>
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
      <td width="15%"><strong>所属库区</strong> * </td>
      <td id="districtBox">
        <select name="district_id" id="district_id" onchange="changeDistrict()" <?php if ($this->_tpl_vars['action'] == 'edit'): ?>disabled<?php endif; ?>>
          <?php $_from = $this->_tpl_vars['districts']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
?>
          <option value="<?php echo $this->_tpl_vars['key']; ?>
" <?php if ($this->_tpl_vars['data']['district_id'] == $this->_tpl_vars['key']): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['item']; ?>
</option>
          <?php endforeach; endif; unset($_from); ?>
        </select>
        <?php if ($this->_tpl_vars['action'] == 'edit'): ?>
          <input type="hidden" name="district_id" value="<?php echo $this->_tpl_vars['data']['district_id']; ?>
">
        <?php endif; ?>
      </td>
    </tr>
    <tr> 
      <td><strong>库位编号</strong> * </td>
      <td><input type="text" name="position_no" id="position_no" size="20" value="<?php echo $this->_tpl_vars['data']['position_no']; ?>
" msg="请填写库位编号" class="required" /></td>
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
<script>
function changeArea(area)
{
    new Request({
        url:'/admin/stock-report/get-district-box/area/' + area,
	    onSuccess:function(msg){
            $('districtBox').innerHTML = msg;
            changeDistrict();
		},
		onError:function() {
			alert("网络繁忙，请稍后重试");
		}
	}).send();
}
function changeDistrict()
{
    if ($('district_id') == null) {
        $('position_no').value = '';
        return;
    }
    
    var district_id = $('district_id').value;
    new Request({
        url:'/admin/stock-report/get-district-no/district_id/' + district_id,
	    onSuccess:function(msg){
            $('position_no').value = msg;
		},
		onError:function() {
			alert("网络繁忙，请稍后重试");
		}
	}).send();
}
</script>