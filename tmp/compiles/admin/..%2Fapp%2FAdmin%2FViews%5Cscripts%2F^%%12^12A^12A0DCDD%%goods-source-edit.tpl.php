<?php /* Smarty version 2.6.19, created on 2014-10-23 14:23:25
         compiled from drt/goods-source-edit.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html', 'drt/goods-source-edit.tpl', 5, false),)), $this); ?>
<?php $_from = $this->_tpl_vars['v']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['kk'] => $this->_tpl_vars['vv']):
?>
<div class="goods-source-item source-item">
	<table width="100%">
		<tr>
			<td rowspan="6" width="100"><?php echo smarty_function_html(array('type' => 'pic','name' => "source[goods_data][".($this->_tpl_vars['i'])."][".($this->_tpl_vars['j']).($this->_tpl_vars['kk'])."][img]",'id' => "pic-goods-img-".($this->_tpl_vars['i'])."-".($this->_tpl_vars['j'])."-".($this->_tpl_vars['kk']),'value' => $this->_tpl_vars['vv']['img']), $this);?>
 </td>
		</tr>
		<tr>
			<td>
			<input type="hidden" name="source[goods_data][<?php echo $this->_tpl_vars['i']; ?>
][<?php echo $this->_tpl_vars['j']; ?>
<?php echo $this->_tpl_vars['kk']; ?>
][goods_id]" value="<?php echo $this->_tpl_vars['vv']['goods_id']; ?>
"/>
			名称：
			<input size="30" name="source[goods_data][<?php echo $this->_tpl_vars['i']; ?>
][<?php echo $this->_tpl_vars['j']; ?>
<?php echo $this->_tpl_vars['kk']; ?>
][goods_name]" value="<?php echo $this->_tpl_vars['vv']['goods_name']; ?>
"/>
			</td>
		</tr>
		<tr>
			<td>功效：
			<input size="30" name="source[goods_data][<?php echo $this->_tpl_vars['i']; ?>
][<?php echo $this->_tpl_vars['j']; ?>
<?php echo $this->_tpl_vars['kk']; ?>
][goods_alt]" value="<?php echo $this->_tpl_vars['vv']['goods_alt']; ?>
"/>
			</td>
		</tr>
		<tr>
			<td>URL：
			<input size="30" name="source[goods_data][<?php echo $this->_tpl_vars['i']; ?>
][<?php echo $this->_tpl_vars['j']; ?>
<?php echo $this->_tpl_vars['kk']; ?>
][url]" value="<?php echo $this->_tpl_vars['vv']['url']; ?>
"/>
			</td>
		</tr>
		<tr>
			<td>
				市场价：<input name="source[goods_data][<?php echo $this->_tpl_vars['i']; ?>
][<?php echo $this->_tpl_vars['j']; ?>
<?php echo $this->_tpl_vars['kk']; ?>
][market_price]" size="5" value="<?php echo $this->_tpl_vars['vv']['market_price']; ?>
"/>
				&nbsp;&nbsp;
				销售价：<input name="source[goods_data][<?php echo $this->_tpl_vars['i']; ?>
][<?php echo $this->_tpl_vars['j']; ?>
<?php echo $this->_tpl_vars['kk']; ?>
][price]" size="5" value="<?php echo $this->_tpl_vars['vv']['price']; ?>
"/>
			</td>
		</tr>
		<tr>
			<td>
				排序：
				<input name="source[goods_data][<?php echo $this->_tpl_vars['i']; ?>
][<?php echo $this->_tpl_vars['j']; ?>
<?php echo $this->_tpl_vars['kk']; ?>
][ord]" size="5" value="<?php echo $this->_tpl_vars['vv']['ord']; ?>
"/>&nbsp;
				可用：<?php echo smarty_function_html(array('type' => 'slt','opt' => $this->_tpl_vars['opt_enable'],'name' => "source[goods_data][".($this->_tpl_vars['i'])."][".($this->_tpl_vars['j']).($this->_tpl_vars['kk'])."][enable]",'value' => $this->_tpl_vars['vv']['enable']), $this);?>

				&nbsp;&nbsp;
				操作： <input onclick="source_item_del(this);" type="button" value="删除"/>
			</td>
		</tr>
	</table>
</div>
<?php endforeach; endif; unset($_from); ?>