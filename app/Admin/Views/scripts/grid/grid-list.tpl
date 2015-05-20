{{include file="inc/header.tpl"}}

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
	url : '/admin/{{$mdl_name}}/get-list/',
	pageSize:30,
	checkOnSelect:true,
	selectOnCheck:true,
	idField:'{{$pk}}',
	rownumbers:true,
	singleSelect:false,
	pagination:true,
	toolbar:'#grid-tb',
	columns : [{{$col_model}}],
	onCheckAll:function(rows){

	$.each(rows,function(i,n){
	arr_grid_ids.push(n['{{$pk}}']);
	});

	arr_grid_ids = arr_grid_ids.unique();
	grid_ids = arr_grid_ids.join(',');
	$.cookie('grid_ids',grid_ids,{path:'/'});

	},
	onUncheckAll:function(rows){
	$.each(rows,function(i,n){
	arr_grid_ids.remove(n['{{$pk}}']);
	});

	arr_grid_ids = arr_grid_ids.unique();
	grid_ids = arr_grid_ids.join(',');
	$.cookie('grid_ids',grid_ids,{path:'/'});

	},
	onCheck : function(i,r){
	arr_grid_ids.push(r['{{$pk}}']);
	arr_grid_ids = arr_grid_ids.unique();
	grid_ids = arr_grid_ids.join(',');

	$.cookie('grid_ids',grid_ids,{path:'/'});
	},
	onUncheck : function(i,r){
	arr_grid_ids.remove(r['{{$pk}}']);
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
								{{if $actions}}{{foreach from=$actions item=v key=k}}
								{{if $v.target == 'dwin'}}
								<input type="button" value="{{$v.label}}" onclick="winopen('{{$v.href}}');"/>
								{{elseif $v.target == 'confirm'}}
								<input class="btn-ajax-confirm" befordo="grid_finder_del_befor"  afterdo="grid_finder_del_after" 
									data=""
									type="button" msg="{{$v.msg}}" href="{{$v.href}}" value="{{$v.label}}" />
								{{elseif $v.target == 'ajax'}}
								<input class="btn-ajax"
									data=""
									type="button" href="{{$v.href}}" value="{{$v.label}}" />
								{{else}}
								undefine
								{{/if}}
								{{/foreach}}{{/if}}
							</div>
						</td>
						<td width="300"> 
						{{if $filter}}
							Filter:<input id="qf_value" name="qf_value"/>
							<select id="qf_name" name="qf_name">
								{{foreach from=$filter item=v key=k}}
								<option value="{{$k}}">{{$v}}</option>
								{{/foreach}}
							</select> 
						{{/if}} 
						
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
				{{if $advfilter}}{{foreach from=$advfilter item=v key=k}}
				<dt>
					{{$v.title}}
				</dt>
				<dd>
					{{if $v.type == 'input'}}
					<input name="{{$k}}"/>
					{{/if}}
				</dd>
				{{/foreach}}{{/if}}
			</dl>
		</form>
	</div>
	<div style="padding:5px;padding-top:9px;background: #eee;text-align: center;border-top:1px solid #ddd; ">
		<input id="btn-advsearch" type="button" value="搜索"/>
		<input id="btn-adv-search-reset" type="button" type="reset" value="重置"/>
	</div>
</div>

{{include file="inc/footer.tpl"}}
