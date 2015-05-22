{{include file="newsadmin/inc-header.tpl"}}

<div id="finder-tool-bar" class="opt-bar">
	<table width="100%">
		<tr>
			<td>
				<a class="easyui-linkbutton" data-options="plain:true" onclick="win_open('/newsadmin/add','win-article-new','新的文章',900,600,true);" href="javascript:void(0);">新的文章</a>
				<a class="easyui-splitbutton" data-options="menu:'#dm-article-batch'" href="javascript:void(0);">批操作</a>
				<div id="dm-article-batch">
					<div onclick="article_del_batch();">批量删除</div>
					<div onclick="add_to_tag();">加入标签</div>
					<div onclick="remove_from_tag();">移除标签</div>
					{{if $loginer_name == 'guoyao-newsadmin'}}
					<div onclick="audit('AUDITED');">通过审核</div>
					<div onclick="audit('PENDING');">设为待审核</div>
					{{/if}}
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
	    url:'/newsadmin/finder-list',
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

function article_del_after(msg,$elt){
	if(msg.status == 'succ'){
		$('#finder-article').datagrid('reload');
	}else{
		alert(msg.msg);
	}
	return false;
}

function doSearch(value,name){
	$('#finder-article').datagrid('load',{
		qs_name:name,
		qs_value:value
	});
}

function article_del_batch(){
	if(!confirm('确定要删除吗？')) return;
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
		url:'/newsadmin/del/id/'+arr_id.join(','),
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

function add_to_tag(){
	var selected_rows = $('#finder-article').datagrid('getSelections');
	var arr_id = new Array();
	$.each(selected_rows,function(i,item){
		arr_id.push(item.article_id);
	});
	
	if(arr_id.length<=0){
		alert('请选择要操作的数据！');
		return;
	}
	
	dlg_open('/newsadmin/add-to-tag/article_ids/'+arr_id.join(','),'add-to-tag-dlg','加入标签',400,150,true);	
}

function remove_from_tag(){
	var selected_rows = $('#finder-article').datagrid('getSelections');
	var arr_id = new Array();
	$.each(selected_rows,function(i,item){
		arr_id.push(item.article_id);
	});
	
	if(arr_id.length<=0){
		alert('请选择要操作的数据！');
		return;
	}
	
	dlg_open('/newsadmin/remove-from-tag/article_ids/'+arr_id.join(','),'remove-from-tag-dlg','从标签移除',400,150,true);	
}

function audit(status){
	
	if(!confirm('确定要执行此操作吗？')) return;
	
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
		url:'/newsadmin/audit/status/'+status+'/id/'+arr_id.join(','),
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