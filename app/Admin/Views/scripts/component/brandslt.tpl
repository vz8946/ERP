{{include file="inc/header.tpl"}}

<link rel="stylesheet" type="text/css" href="/Public/js/esyui/default/easyui.css">
<link rel="stylesheet" type="text/css" href="/Public/js/esyui/icon.css">
<script type="text/javascript" src="/Public/js/esyui/jquery.easyui.min.js"></script>

<script src="/Public/js/layout/jquery.layout-latest.js"></script>

<script type="text/javascript" src="/Public/js/jquery.cookie.js"></script>
<script type="text/javascript" src="/Public/js/mypropotype.js"></script>

<script>

	var grid_ids = '';
	if($.cookie('grid_ids')){
		grid_ids = $.cookie('grid_ids').trim(',');
	}
	
	var arr_grid_ids = new Array();
	arr_grid_ids = grid_ids.split(',');
	arr_grid_ids = arr_grid_ids.unique();
	
	var mslt_id = $.cookie('mslt_id');
	
	var layout = null;
	
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
				});
			}
		});
		
		
		$('#dgrid').datagrid({
			width : $('#lm-content').width(),
			height : $('#lm-content').height(),
			url : '/admin/component/get-list/mdl/{{$params.mdl}}',
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
		
		$('#btn-mslt-ok').click(function(){
			$.ajax({
			   type: "GET",
			   url: "/admin/component/get-slt-data/mdl/{{$params.mdl}}",
			   data: "ids="+grid_ids,
			   dataType:'json',
			   success: function(msg){
			   		window.opener.msltback(mslt_id,grid_ids,msg);
		   			window.close();
			   }
			});
		});
		
	}); 
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
	}
	.panel {
		padding: 0px;
	}
</style>

<div id="lm" class="ui-layout-center">
	<div id="lm-content" class="ui-layout-content">
	<table id="dgrid"></table>

	<div id="grid-tb" style="padding: 5px;">
		<form id="frm-grid-filter" onsubmit="return false;">
			<table width="100%">
				<tr>
					{{if $filter}}
					<td> Filter:
					<input id="qf_value" name="qf_value"/>
					<select id="qf_name" name="qf_name">
						{{foreach from=$filter item=v key=k}}
						<option value="{{$k}}">{{$v}}</option>
						{{/foreach}}
					</select></td>
					{{/if}}

					<td width="160" style="text-align: right;">
					<input onclick="layout.toggle('east');" type="button" value="高级搜索"/>
					</td>
				</tr>
			</table>
		</form>
	</div>
	</div>
	<div style="padding: 5px;padding-top:9px;text-align: center;">
		<input id="btn-mslt-ok" type="button" value="确定"/>
		&nbsp;
		<input type="button" onclick="window.close();" value="关闭"/>
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
