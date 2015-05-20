<?php /* Smarty version 2.6.19, created on 2014-10-23 10:52:18
         compiled from grid/grid-list.tpl */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "inc/header.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<script>
	var grid_ids = '';
	var arr_grid_ids = new Array();
	var layout = null;
	var grid_finder = null;

	$.cookie('grid_ids', null);

	$(function() {

	layout = $('body').layout({
		applyDefaultStyles : true,
		resizable : false,
		spacing_open : 0,
		spacing_closed : 0,
		initClosed : true,
		fxName : 'fast',
		onresize_end : function() {
			$('#dgrid').datagrid({
				width : $('#lm-content').width(),
				height : $('#lm-content').height()
			}).datagrid('reload');
		}
	});

	grid_finder = $('#dgrid').datagrid({
	width : $('#lm-content').width(),
	height : $('#lm-content').height(),
	url : '/admin/<?php echo $this->_tpl_vars['mdl_name']; ?>
/get-list/',
	pageSize:30,
	checkOnSelect:true,
	selectOnCheck:true,
	idField:'<?php echo $this->_tpl_vars['pk']; ?>
',
	rownumbers:true,
	singleSelect:false,
	pagination:true,
	toolbar:'#grid-tb',
	columns : [<?php echo $this->_tpl_vars['col_model']; ?>
],
	onCheckAll:function(rows){

	$.each(rows,function(i,n){
	arr_grid_ids.push(n['<?php echo $this->_tpl_vars['pk']; ?>
']);
	});

	arr_grid_ids = arr_grid_ids.unique();
	grid_ids = arr_grid_ids.join(',');
	$.cookie('grid_ids',grid_ids,{path:'/'});

	},
	onUncheckAll:function(rows){
	$.each(rows,function(i,n){
	arr_grid_ids.remove(n['<?php echo $this->_tpl_vars['pk']; ?>
']);
	});

	arr_grid_ids = arr_grid_ids.unique();
	grid_ids = arr_grid_ids.join(',');
	$.cookie('grid_ids',grid_ids,{path:'/'});

	},
	onCheck : function(i,r){
	arr_grid_ids.push(r['<?php echo $this->_tpl_vars['pk']; ?>
']);
	arr_grid_ids = arr_grid_ids.unique();
	grid_ids = arr_grid_ids.join(',');

	$.cookie('grid_ids',grid_ids,{path:'/'});
	},
	onUncheck : function(i,r){
	arr_grid_ids.remove(r['<?php echo $this->_tpl_vars['pk']; ?>
']);
	arr_grid_ids = arr_grid_ids.unique();
	grid_ids = arr_grid_ids.join(',');

	$.cookie('grid_ids',grid_ids,{path:'/'});

	},
	onLoadSuccess: function (data) {
		
		ajaxinit();
		
		for(var i=0;i< arr_grid_ids.length;i++){
			$(this).datagrid('selectRecord',arr_grid_ids[i]);
		}
		
	}

	});

	$("#qf_value").keyup(function(evt) {
	if (evt.keyCode != 13)
	return;
	var value = $(this).val();
	var name = $("select#qf_name").val();

	$('#dgrid').datagrid('load', {
	qf_name: name,
	qf_value: value
	});

	});

	$('#btn-advsearch').click(function(){
	$('#dgrid').datagrid('load',$('#frm-advsearch').serializeObject());
	});

	$('#btn-adv-search-reset').click(function(){
	$('#frm-advsearch').find('input').val('');
	});
	
	});
	
function grid_finder_sa_after(msg,$elt){
	if(msg.status == 'succ'){
		grid_finder.datagrid('reload');
		return false;
	}
	return true;
}	

function grid_finder_del_after(msg,$elt){
	if(msg.status == 'succ'){
		grid_finder.datagrid('reload');
		return false;
	}
	
	return true;
}	

function grid_finder_del_befor($elt){
	if(grid_ids == ''){
		alert('没有选中的数据！');
		return false;		
	}
	
	$elt.attr('data','id='+grid_ids);
	
	return true;
}	
</script>

<style>
	.ui-layout-center {
		padding: 0px !important;
	}

	.ui-layout-pane {
		border: none !important;
		padding: 0px;
	}

	.ui-layout-pane-east {
		background: #E4ECF7 !important;
		border-left: 1px solid #aaa !important;
	}

	div.pq-grid-title {
		padding: 0px;
	}
	div.pq-grid-toolbar-search {
		padding: 10px;
	}

	.ui-layout-content {
		padding: 0px !important;
	}

	.ui-layout-container {
		padding: 0px !important;
	}

	.ui-layout-east dl dd {
		margin-bottom: 5px;
		margin-left:0px;
	}
	.panel {
		padding: 0px;
	}

	.panel-header, .panel-body {
		border: none !important;
	}
</style>

<div id="lm" class="ui-layout-center">
	<div id="lm-content" class="ui-layout-content">
		<table id="dgrid"></table>

		<div id="grid-tb" style="padding: 5px;">
			<form id="frm-grid-filter" onsubmit="return false;">
				<table width="100%">
					<tr>
						<td>
							<div>
								<?php if ($this->_tpl_vars['actions']): ?><?php $_from = $this->_tpl_vars['actions']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['v']):
?>
								<?php if ($this->_tpl_vars['v']['target'] == 'dwin'): ?>
								<input type="button" value="<?php echo $this->_tpl_vars['v']['label']; ?>
" onclick="winopen('<?php echo $this->_tpl_vars['v']['href']; ?>
');"/>
								<?php elseif ($this->_tpl_vars['v']['target'] == 'confirm'): ?>
								<input class="btn-ajax-confirm" befordo="grid_finder_del_befor"  afterdo="grid_finder_del_after" 
									data=""
									type="button" msg="<?php echo $this->_tpl_vars['v']['msg']; ?>
" href="<?php echo $this->_tpl_vars['v']['href']; ?>
" value="<?php echo $this->_tpl_vars['v']['label']; ?>
" />
								<?php elseif ($this->_tpl_vars['v']['target'] == 'ajax'): ?>
								<input class="btn-ajax"
									data=""
									type="button" href="<?php echo $this->_tpl_vars['v']['href']; ?>
" value="<?php echo $this->_tpl_vars['v']['label']; ?>
" />
								<?php else: ?>
								undefine
								<?php endif; ?>
								<?php endforeach; endif; unset($_from); ?><?php endif; ?>
							</div>
						</td>
						<td width="300"> 
						<?php if ($this->_tpl_vars['filter']): ?>
							Filter:<input id="qf_value" name="qf_value"/>
							<select id="qf_name" name="qf_name">
								<?php $_from = $this->_tpl_vars['filter']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['v']):
?>
								<option value="<?php echo $this->_tpl_vars['k']; ?>
"><?php echo $this->_tpl_vars['v']; ?>
</option>
								<?php endforeach; endif; unset($_from); ?>
							</select> 
						<?php endif; ?> 
						
						</td>

						<td width="40" style="text-align: right;">
							<input type="button" onclick="layout.toggle('east');" value="高级搜索"/></td>
					</tr>
				</table>
			</form>
		</div>
	</div>
</div>
<div class="ui-layout-east" id="east-layout">

	<div class="ui-layout-content">
		<form id="frm-advsearch" style="padding: 10px;" action="" onsubmit="return false;">
			<dl>
				<?php if ($this->_tpl_vars['advfilter']): ?><?php $_from = $this->_tpl_vars['advfilter']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['v']):
?>
				<dt>
					<?php echo $this->_tpl_vars['v']['title']; ?>

				</dt>
				<dd>
					<?php if ($this->_tpl_vars['v']['type'] == 'input'): ?>
					<input name="<?php echo $this->_tpl_vars['k']; ?>
"/>
					<?php endif; ?>
				</dd>
				<?php endforeach; endif; unset($_from); ?><?php endif; ?>
			</dl>
		</form>
	</div>
	<div style="padding:5px;padding-top:9px;background: #eee;text-align: center;border-top:1px solid #ddd; ">
		<input id="btn-advsearch" type="button" value="搜索"/>
		<input id="btn-adv-search-reset" type="button" type="reset" value="重置"/>
	</div>
</div>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "inc/footer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>