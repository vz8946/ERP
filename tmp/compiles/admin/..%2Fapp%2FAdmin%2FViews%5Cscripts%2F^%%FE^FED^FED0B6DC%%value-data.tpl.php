<?php /* Smarty version 2.6.19, created on 2014-10-23 14:23:25
         compiled from drt/value-data.tpl */ ?>
<div class="data-item">
	<table class="tbl-frm">
		<tr>
			<th width="100">数据名称：</th>
			<td>
			<input name="source[value_data][<?php echo $this->_tpl_vars['i']; ?>
][name]" value="<?php echo $this->_tpl_vars['k']; ?>
"/>
			<input onclick="data_item_del(this);" type="button" value="删除"/>
			</td>
		</tr>
		<tr>
			<th>值：</th>
			<td>
				<input name="source[value_data][<?php echo $this->_tpl_vars['i']; ?>
][value]" value="<?php echo $this->_tpl_vars['v']; ?>
"/>
			</td>
		</tr>
	</table>
</div>