<?php /* Smarty version 2.6.19, created on 2014-10-23 14:21:16
         compiled from drt/link-data.tpl */ ?>
<div class="data-item">
	<table class="tbl-frm">
		<tr>
			<th width="100">数据名称：</th>
			<td><input name="source[link_data][<?php echo $this->_tpl_vars['i']; ?>
][name]" value="<?php echo $this->_tpl_vars['k']; ?>
"/>
				<input onclick="data_item_del(this);" type="button" value="删除"/></td>
		</tr>
		<tr>
			<th>
				数据源
				<a id="btn-link-source-add-<?php echo $this->_tpl_vars['i']; ?>
" class="btn-ajax-load" alc="#link-source-container-<?php echo $this->_tpl_vars['i']; ?>
"
					data="i=<?php echo $this->_tpl_vars['i']; ?>
" befordo="link_source_add_befor" append="true" 
					href="/admin/drt/link-source-add" >[ + ]</a>
				：
			</th>
			<td>
				<div  id="link-source-container-<?php echo $this->_tpl_vars['i']; ?>
" class="link-source-container">
					<?php $_from = $this->_tpl_vars['v']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['kk'] => $this->_tpl_vars['vv']):
?>
					<?php $this->assign('j', $this->_tpl_vars['kk']); ?>
					<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "drt/link-source-add.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
					<?php endforeach; endif; unset($_from); ?>
				</div>
			</td>
		</tr>
	</table>
</div>

<script>

<?php if (! $this->_tpl_vars['r']['datasource']['link_data']): ?>
$(function(){
	ajaxinit();
	$('#btn-link-source-add-<?php echo $this->_tpl_vars['i']; ?>
').click();
});
<?php endif; ?>

function link_source_add_befor($elt){
	var alc = $elt.attr('alc');	
	var count = $(alc).find('.link-source-item').size();
	var data = $elt.attr('data')+'&j='+(count+1);
	$elt.attr('data',data);
	return true;
}	

</script>

<style>
.source-item{
	padding: 5px;
	border: 1px solid #eee;
	background: #F5F5F5;
	width: 400px;
	display: inline-block;
}	

</style>