<?php /* Smarty version 2.6.19, created on 2014-10-22 22:17:38
         compiled from transport/change.tpl */ ?>
<form name="myForm1" id="myForm1" method="post" action="<?php echo $this -> callViewHelper('url', array());?>">
<input type="hidden" name="bill_type" value="<?php echo $this->_tpl_vars['data']['bill_type']; ?>
">
<input type="hidden" name="bill_no" value="<?php echo $this->_tpl_vars['data']['bill_no']; ?>
">
<div class="title">运输单变更</div>
<div class="content">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
    <tr>
      <td width="20%"><strong>单据类型</strong></td>
      <td><?php echo $this->_tpl_vars['billType'][$this->_tpl_vars['data']['bill_type']]; ?>
</td>
      <td width="20%"><strong>单据编号</strong></td>
      <td>
        <?php $_from = $this->_tpl_vars['data']['bill_no_array']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['bill_no'] => $this->_tpl_vars['batch_sn']):
?>
          <?php echo $this->_tpl_vars['bill_no']; ?>
<br>
        <?php endforeach; endif; unset($_from); ?>
      </td>
    </tr>
    <tr>
      <td><strong>物流公司</strong></td>
      <td>
        <select name="new_logistic_code">
          <?php $_from = $this->_tpl_vars['logisticList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['logistic']):
?>
          <option value="<?php echo $this->_tpl_vars['logistic']['logistic_code']; ?>
|<?php echo $this->_tpl_vars['logistic']['logistic_name']; ?>
|" <?php if ($this->_tpl_vars['data']['logistic_code'] == $this->_tpl_vars['logistic']['logistic_code']): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['logistic']['logistic_name']; ?>
</option>
	      <?php endforeach; endif; unset($_from); ?>
	      <option value="ems|EMS" <?php if ($this->_tpl_vars['data']['logistic_code'] == 'ems'): ?>selected<?php endif; ?>>EMS</option>
	      <option value="st|申通" <?php if ($this->_tpl_vars['data']['logistic_code'] == 'st'): ?>selected<?php endif; ?>>申通</option>
		</select>
      </td>
      <td><strong>运输单号</strong></td>
      <td><input type="text" name="new_logistic_no" id="new_logistic_no" size="20" value="<?php echo $this->_tpl_vars['data']['logistic_no']; ?>
"></td>
    </tr>
    <?php if ($this->_tpl_vars['data']['lock_name'] == $this->_tpl_vars['auth']['admin_name']): ?>
    <tr>
      <td></td>
      <td>
        <input type="button" name="dosubmit1" value="修改" onclick="if (document.getElementById('new_logistic_no').value ==''){alert('运输单号不能为空');return false;}ajax_submit($('myForm1'),'<?php echo $this -> callViewHelper('url', array());?>');"/>
      </td>
    </tr>
    <?php endif; ?>
</tbody>
</table>
</div>
</form>