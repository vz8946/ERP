{{include file="newsadmin/inc-header.tpl"}}

<div id="finder-tool-bar" class="opt-bar">
	<table width="100%">
		<tr>
			<td>
				<a class="easyui-linkbutton" data-options="plain:true" onclick="dlg_open('/newsadmin/tag-add','tag-dlg','新的标签',500,450,true);" href="javascript:void(0);">新的标签</a>
				<a class="easyui-splitbutton" data-options="menu:'#dm-tag-batch'" href="javascript:void(0);">批操作</a>
				<div id="dm-tag-batch">
					<div onclick="tag_del_batch();">批量删除</div>
				</div>
			</td>
			<td width="200">
				<input class="easyui-searchbox" data-options="prompt:'please input!',menu:'#sbm-tag',searcher:doSearch" style="width:200px"></input>
				<div id="sbm-tag" style="width:120px">
					<div name="name">标识</div>
					<div name="title">标题</div>
				</div>
			</td>
		</tr>
	</table>
</div>

<div id="win-adv-search" class="easyui-dialog" title="高级搜索" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:500px;height:400px;">
	{{include file="newsadmin/inc-tag-adv-search.tpl"}}	
</div>

<table id="finder-tag"></table>

{{include file="newsadmin/inc-footer.tpl"}}

<script>
var finder_tag = null;
$(function() {
    finder_tag = $('#finder-tag').datagrid({
	    url:'/newsadmin/finder-list-tag',
	    columns:[{{$finder_fields}}],
		rownumbers:true,
		pagination:true,	
		fit:true,
		pageSize:50,
		pageList:[50,100],
		toolbar:'#finder-tool-bar',
		onLoadSuccess:function(data){
			 $.parser.parse();
		 }
    });
});

function tag_del_after(msg,$elt){
	if(msg.status == 'succ'){
		$('#finder-tag').datagrid('reload');
	}else{
		alert(msg.msg);
	}
	return false;
}

function doSearch(value,name){
	$('#finder-tag').datagrid('load',{
		qs_name:name,
		qs_value:value
	});
}

function tag_del_batch(){
	if(!confirm('确定要删除吗？')) return;
	var selected_rows = $('#finder-tag').datagrid('getSelections');
	var arr_id = new Array();
	$.each(selected_rows,function(i,item){
		arr_id.push(item.tag_id);
	});
	
	if(arr_id.length<=0){
		alert('请选择要删除的数据！');
		return;
	}
	
	$.ajax({
		url:'/newsadmin/tag-del/id/'+arr_id.join(','),
		dataType:'json',
		type:'get',
		success:function(msg){
			if(msg.status == 'succ'){
				$('#finder-tag').datagrid('reload');
			}else{
				alert(msg.msg);
			}
		}
	});
}

</script>