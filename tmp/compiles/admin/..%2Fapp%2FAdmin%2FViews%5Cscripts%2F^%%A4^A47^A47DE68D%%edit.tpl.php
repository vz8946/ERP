<?php /* Smarty version 2.6.19, created on 2014-10-28 14:17:57
         compiled from supplier/edit.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'supplier/edit.tpl', 58, false),)), $this); ?>
<script type="text/javascript" src="/scripts/kindeditor/kindeditor-min.js"></script>
<script type="text/javascript" src="/scripts/kindeditor/lang/zh_CN.js"></script>
<form name="myForm" id="myForm" action="<?php echo $this -> callViewHelper('url', array(array('action'=>$this->_tpl_vars['action'],)));?>" method="post">
<div class="title"><?php if ($this->_tpl_vars['action'] == 'edit'): ?>编辑供货商<?php else: ?>添加供货商<?php endif; ?></div>
<div class="content">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table_form">
<tbody>
    <tr> 
      <td width="15%"><strong>供货商名称</strong> * </td>
      <td>
     <?php if ($this->_tpl_vars['action'] == 'edit'): ?> <?php echo $this->_tpl_vars['data']['supplier_name']; ?>
 <?php else: ?> 
      <input type="text" name="supplier_name" size="30" value="<?php echo $this->_tpl_vars['data']['supplier_name']; ?>
" msg="请填写供货商名称" class="required" />  <?php endif; ?>
      </td>
	   <td width="15%"><strong>公司名称</strong> * </td>
      <td><input type="text" name="company" size="30" value="<?php echo $this->_tpl_vars['data']['company']; ?>
" msg="请填写公司名称" class="required" /></td>
    </tr>
    <tr> 
      <td width="15%"><strong>公司法人代表</strong></td>
      <td><input type="text" name="corporate" size="30" value="<?php echo $this->_tpl_vars['data']['corporate']; ?>
"  /></td>
      <td width="15%"><strong>公司注册号</strong></td>
      <td><input type="text" name="registration_num" size="30" value="<?php echo $this->_tpl_vars['data']['registration_num']; ?>
"  /></td>
    </tr>
    <tr> 
      <td width="15%"><strong>地址</strong></td>
      <td><input type="text" name="addr" size="30" value="<?php echo $this->_tpl_vars['data']['addr']; ?>
"  /></td>
      <td width="15%"><strong>公司类型</strong></td>
      <td><input type="text" name="business_type" size="30" value="<?php echo $this->_tpl_vars['data']['business_type']; ?>
"  /></td>
    </tr>
   
    <tr> 
      <td width="15%"><strong>联系人</strong></td>
      <td><input type="text" name="contact" size="30" value="<?php echo $this->_tpl_vars['data']['contact']; ?>
"  /></td>
	  <td width="15%"><strong>电话</strong></td>
      <td><input type="text" name="tel" size="30" value="<?php echo $this->_tpl_vars['data']['tel']; ?>
"  /></td>
    </tr>
    <tr> 
      <td width="15%"><strong>手机:</strong></td>
      <td><input type="text" name="mobile" size="30" value="<?php echo $this->_tpl_vars['data']['mobile']; ?>
" /></td>
	  <td width="15%"><strong>EMAIL:</strong></td>
      <td><input type="text" name="email" size="30" value="<?php echo $this->_tpl_vars['data']['email']; ?>
" /></td>
    </tr>
    <tr>
      <td width="15%"><strong>备注</strong></td>
      <td colspan="3">
	  
	  <textarea name="supplier_desc" id="supplier_desc" rows="20" style="width:680px; height:260px;"><?php echo $this->_tpl_vars['data']['supplier_desc']; ?>
</textarea>
		<script type="text/javascript">
			KindEditor.ready(function(K) {
				K.create('textarea[name="supplier_desc"]', {
							allowFileManager : true
						});
			});
		</script>
	  </td>
    </tr>
    <tr> 
      <td width="15%"><strong>有效期开始时间</strong></td>
      <td><input type="text" name="start_time" size="30" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['data']['start_time'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y-%m-%d") : smarty_modifier_date_format($_tmp, "%Y-%m-%d")); ?>
"  />
        如:2012-04-10</td>
	  <td width="15%"><strong>结束时间</strong>  </td>
      <td><input type="text" name="end_time" size="30" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['data']['end_time'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y-%m-%d") : smarty_modifier_date_format($_tmp, "%Y-%m-%d")); ?>
"  />
      如:2018-10-25</td>
    </tr>
	 <tr> 
      <td width="15%"><strong>银行</strong></td>
      <td><input type="text" name="bank_name" size="30" value="<?php echo $this->_tpl_vars['data']['bank_name']; ?>
"/></td>
	  <td width="15%"><strong>应收银行帐户</strong>  </td>
      <td><input type="text" name="bank_account" size="30" value="<?php echo $this->_tpl_vars['data']['bank_account']; ?>
"  /></td>
    </tr>
	<tr> 
	  <td width="15%"><strong>银行帐号</strong>  </td>
      <td colspan="3"><input type="text" name="bank_sn" size="55" value="<?php echo $this->_tpl_vars['data']['bank_sn']; ?>
"  /></td>
    </tr>

    <tr> 
      <td><strong>是否启用</strong></td>
      <td colspan="3">
	   <input type="radio" name="status" value="0" <?php if ($this->_tpl_vars['data']['status'] == 0 && $this->_tpl_vars['action'] == 'edit'): ?>checked<?php endif; ?>/> 是
	   <input type="radio" name="status" value="1" <?php if ($this->_tpl_vars['data']['status'] == 1 || $this->_tpl_vars['action'] == 'add'): ?>checked<?php endif; ?>/> 否
	  </td>
    </tr>
</tbody>
</table>
</div>
<div class="submit"><input type="submit" name="dosubmit" id="dosubmit" value="确定" /> <input type="reset" name="reset" value="重置" /></div>
</form>