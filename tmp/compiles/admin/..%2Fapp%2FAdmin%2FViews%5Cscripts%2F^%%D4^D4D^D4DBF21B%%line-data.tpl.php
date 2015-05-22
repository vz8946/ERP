<?php /* Smarty version 2.6.19, created on 2014-10-23 14:24:06
         compiled from drt/line-data.tpl */ ?>
<div class="data-item">
	<table class="tbl-frm">
		<tr>
			<th width="100">数据名称：</th>
			<td>
			<input name="source[line_data][<?php echo $this->_tpl_vars['i']; ?>
][name]" value="<?php echo $this->_tpl_vars['v']['name']; ?>
"/>
			<input onclick="data_item_del(this);" type="button" value="删除"/>
			</td>
		</tr>
		<tr>
			<th>配置：</th>
			<td>高度：
				<input name="source[line_data][<?php echo $this->_tpl_vars['i']; ?>
][h]" value="<?php echo $this->_tpl_vars['v']['h']; ?>
" size='5'/>
				&nbsp;宽度：
				<input name="source[line_data][<?php echo $this->_tpl_vars['i']; ?>
][w]" value="<?php echo $this->_tpl_vars['v']['w']; ?>
" size='5'/>
				&nbsp;颜色：
				<input name="source[line_data][<?php echo $this->_tpl_vars['i']; ?>
][c]" value="<?php echo $this->_tpl_vars['v']['c']; ?>
" size="8"/>
			</td>
		</tr>
	</table>
</div>