<?php /* Smarty version 2.6.19, created on 2014-11-12 08:34:52
         compiled from operation/reason.tpl */ ?>

<div class="title">退货原因管理</div>

<div class="content">
<table cellpadding="0" cellspacing="0" border="0" class="table">
    <thead>
        <tr>
            <td>ID</td>
			<td>原因</td>
			<td>顺序</td>
        </tr>
    </thead>
    <tbody>
    <?php $_from = $this->_tpl_vars['reasonlist']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['data']):
?>
    <tr id="row<?php echo $this->_tpl_vars['data']['id']; ?>
" >
    	<td><?php echo $this->_tpl_vars['data']['reason_id']; ?>
</td>
		<td>  <input type="text" name="update" size="30" value="<?php echo $this->_tpl_vars['data']['label']; ?>
"  onchange="ajax_update('<?php echo $this -> callViewHelper('url', array(array('action'=>'ajaxupdate','type'=>'reason',),""));?>',<?php echo $this->_tpl_vars['data']['reason_id']; ?>
,'label',this.value)"></td>
		<td> <input type="text" name="update" size="3" value="<?php echo $this->_tpl_vars['data']['sort']; ?>
"  onchange="ajax_update('<?php echo $this -> callViewHelper('url', array(array('action'=>'ajaxupdate','type'=>'reason',),""));?>',<?php echo $this->_tpl_vars['data']['reason_id']; ?>
,'sort',this.value)"></td>
    </tr>
    <?php endforeach; endif; unset($_from); ?>
    </tbody>
</table>
</div>