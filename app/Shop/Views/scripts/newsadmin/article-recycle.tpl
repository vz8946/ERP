{{include file="newsadmin/inc-header.tpl"}}

<div id="finder-tool-bar" class="opt-bar">
	<table width="100%">
		<tr>
			<td>
				<a class="easyui-splitbutton" data-options="menu:'#dm-article-batch'" href="javascript:void(0);">批操作</a>
				<div id="dm-article-batch">
					<div onclick="article_recycle_back();">还原</div>
				</div>
			</td>
			<td width="200">
				<input class="easyui-searchbox" data-options="prompt:'please input!',menu:'#sbm-article',searcher:doSearch" style="width:200px"></input>
				<div id="sbm-article" style="width:120px">
					<div name="title">标题</div>
					<div name="author">作者</div>
				</div>
			</td>
			<td width="90" align="right">
				<a class="easyui-linkbutton"
					href="javascript:void(0)" onclick="$('#win-adv-search').dialog('open');">高级搜索</a> 
			</td>
		</tr>
	</table>
</div>

<div id="win-adv-search" class="easyui-dialog" title="高级搜索" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:500px;height:400px;">
	{{include file="newsadmin/inc-article-adv-search.tpl"}}	
</div>

<table id="finder-article"></table>

{{include file="newsadmin/inc-footer.tpl"}}

<script>
var finder_article = null;
$(function() {
    finder_article = $('#finder-article').datagrid({
	    url:'/newsadmin/finder-recycle-list',
	    columns:[{{$finder_fields}}],
		rownumbers:true,
		pagination:true,	
		fit:true,
		pageSize:50,
		pageList:[50,100],
		toolbar: '#finder-tool-bar',
		onLoadSuccess:function(data){
			 $.parser.parse();
		 }
    });
});


function doSearch(value,name){
	$('#finder-article').datagrid('load',{
		qs_name:name,
		qs_value:value
	});
}

function article_recycle_back(){
	var selected_rows = $('#finder-article').datagrid('getSelections');
	var arr_id = new Array();
	$.each(selected_rows,function(i,item){
		arr_id.push(item.article_id);
	});
	if(arr_id.length<=0){
		alert('请选择要操作的数据！');
		return;
	}
	$.ajax({
		url:'/newsadmin/recycle-back/id/'+arr_id.join(','),
		dataType:'json',
		type:'get',
		success:function(msg){
			alert(msg.msg);
			if(msg.status == 'succ'){
				$('#finder-article').datagrid('reload');
			}
		}
	});
}
</script>