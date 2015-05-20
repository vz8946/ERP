<?php /* Smarty version 2.6.19, created on 2014-10-23 14:23:25
         compiled from drt/goods-data.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html', 'drt/goods-data.tpl', 11, false),)), $this); ?>
<div class="data-item">
	<table class="tbl-frm">
		<tr>
			<th width="100">数据名称：</th>
			<td><input name="source[goods_data][<?php echo $this->_tpl_vars['i']; ?>
][name]" value="<?php echo $this->_tpl_vars['k']; ?>
"/>
				<input onclick="data_item_del(this);" type="button" value="删除"/>
			</td>
		</tr>
		<tr>
			<th>
				<?php echo smarty_function_html(array('id' => "mslt-goods-".($this->_tpl_vars['i']),'tplid' => "mslt-tpl-goods-".($this->_tpl_vars['i']),'callback' => 'mslt_goods_back','remain' => 'false','name' => 'goods_ids','type' => 'mslt','mdl' => 'goods','label' => "商品"), $this);?>

			</th>
			<td>
				<div id="mslt-tpl-goods-<?php echo $this->_tpl_vars['i']; ?>
">
					<?php if ($this->_tpl_vars['r']['datasource']['goods_data']): ?>
					<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "drt/goods-source-edit.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
					<?php endif; ?>
				</div>
			</td>
		</tr>
	</table>
</div>
<script>$(function(){
	ajaxinit();
});

function mslt_goods_back(msg,name_id,ids,tplid,mslt_id){
	var j = $('#'+tplid).find('.goods-source-item').size();
	var arr_t = mslt_id.split('-');
	var i = arr_t[arr_t.length-1];
	
	$.ajax({
		url:'/admin/drt/goods-source-add',
		data:'ids='+ids+'&i='+i+'&j='+(j+1),
		method:'get',
		dataType:'html',
		success:function(msg){
			$('#'+tplid).append(msg);
		}
	});
	
	return false;	
}

</script>