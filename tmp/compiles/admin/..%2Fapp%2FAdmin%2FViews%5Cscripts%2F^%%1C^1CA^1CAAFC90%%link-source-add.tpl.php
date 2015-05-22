<?php /* Smarty version 2.6.19, created on 2014-10-23 14:21:16
         compiled from drt/link-source-add.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html', 'drt/link-source-add.tpl', 5, false),array('modifier', 'default', 'drt/link-source-add.tpl', 28, false),)), $this); ?>
<div class="link-source-item source-item">
	<table width="100%">
		<tr>
			<td rowspan="6">
				<?php echo smarty_function_html(array('type' => 'pic','id' => "pic-link-".($this->_tpl_vars['j'])."-".($this->_tpl_vars['i']),'name' => "source[link_data][".($this->_tpl_vars['i'])."][".($this->_tpl_vars['j'])."][img]",'value' => $this->_tpl_vars['vv']['img']), $this);?>

			</td>
		</tr>
		<tr>
			<td>名称：<input size="30" name="source[link_data][<?php echo $this->_tpl_vars['i']; ?>
][<?php echo $this->_tpl_vars['j']; ?>
][title]" value="<?php echo $this->_tpl_vars['vv']['title']; ?>
"/></td>
		</tr>
		<tr>
			<td>URL：<input size="30" name="source[link_data][<?php echo $this->_tpl_vars['i']; ?>
][<?php echo $this->_tpl_vars['j']; ?>
][url]" value="<?php echo $this->_tpl_vars['vv']['url']; ?>
"/></td>
		</tr>
		<tr>
			<td>备注：<input size="30" name="source[link_data][<?php echo $this->_tpl_vars['i']; ?>
][<?php echo $this->_tpl_vars['j']; ?>
][memo]" value="<?php echo $this->_tpl_vars['vv']['memo']; ?>
"/></td>
		</tr>
		<tr>
			<td>
				颜色：<input size="7" name="source[link_data][<?php echo $this->_tpl_vars['i']; ?>
][<?php echo $this->_tpl_vars['j']; ?>
][color]" value="<?php echo $this->_tpl_vars['vv']['color']; ?>
"/>
				&nbsp;
				排序：<input size="4" name="source[link_data][<?php echo $this->_tpl_vars['i']; ?>
][<?php echo $this->_tpl_vars['j']; ?>
][ord]" value="<?php echo $this->_tpl_vars['vv']['ord']; ?>
"/>
			</td>
		</tr>
		<tr>
			<td>
				可用：<?php echo smarty_function_html(array('type' => 'slt','opt' => $this->_tpl_vars['opt_enable'],'name' => "source[link_data][".($this->_tpl_vars['i'])."][".($this->_tpl_vars['j'])."][enable]",'value' => $this->_tpl_vars['vv']['enable']), $this);?>

				&nbsp;
				新窗口：<?php echo smarty_function_html(array('type' => 'slt','opt' => $this->_tpl_vars['opt_enable'],'name' => "source[link_data][".($this->_tpl_vars['i'])."][".($this->_tpl_vars['j'])."][is_new_win]",'value' => ((is_array($_tmp=@$this->_tpl_vars['vv']['is_new_win'])) ? $this->_run_mod_handler('default', true, $_tmp, 'N') : smarty_modifier_default($_tmp, 'N'))), $this);?>

				&nbsp;
				<input type="button" value="删除" onclick="source_item_del(this);"/>
			</td>
		</tr>
	</table>
</div>