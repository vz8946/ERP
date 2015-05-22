{{include file="newsadmin/inc-header.tpl"}}

<div id="finder-tool-bar" class="opt-bar">
	<a class="easyui-linkbutton" onclick="dlg_open('/newsadmin/cat-add','cat-dlg','新的类别',500,460,true)" href="javascript:void(0);">新的分类</a>
	<a class="easyui-linkbutton" onclick="$('#finder-seo-cat').treegrid('collapseAll');" href="javascript:void(0);">折叠所有</a>
	<a class="easyui-linkbutton" onclick="$('#finder-seo-cat').treegrid('expandAll');" href="javascript:void(0);">展开所有</a>
	<a class="easyui-linkbutton" onclick="add_to_tag();" href="javascript:void(0);">加入到标签</a>
	<a class="easyui-linkbutton" onclick="remove_from_tag();" href="javascript:void(0);">从标签移除</a>
</div>

<table id="finder-seo-cat" data-options="fit:true"></table>

{{include file="newsadmin/inc-footer.tpl"}}

<script>
$(function() {
    $('#finder-seo-cat').treegrid({
	    url:'/newsadmin/cat-tree-finder-list',
	    idField:'cat_id',
	    treeField:'cat_name',
	    singleSelect:false,
	    toolbar: '#finder-tool-bar',
	    columns:[{{$finder_fields}}],
		onLoadSuccess:function(data){
			 $.parser.parse();
		 }
    });
});

function cat_del_after(msg,$elt){
	if(msg.status == 'succ'){
		$('#finder-seo-cat').treegrid('reload');
	}else{
		alert(msg.msg);
	}
}


function add_to_tag(){
	var selected_rows = $('#finder-seo-cat').treegrid('getSelections');
	var arr_id = new Array();
	$.each(selected_rows,function(i,item){
		arr_id.push(item.cat_id);
	});
	
	if(arr_id.length<=0){
		alert('请选择要操作的数据！');
		return;
	}
	
	dlg_open('/newsadmin/cat-add-to-tag/cat_ids/'+arr_id.join(','),'add-to-tag-dlg','加入标签',400,150,true);	
}

function remove_from_tag(){

	var selected_rows = $('#finder-seo-cat').treegrid('getSelections');
	var arr_id = new Array();
	$.each(selected_rows,function(i,item){
		arr_id.push(item.cat_id);
	});
	
	if(arr_id.length<=0){
		alert('请选择要操作的数据！');
		return;
	}
	
	dlg_open('/newsadmin/cat-remove-from-tag/cat_ids/'+arr_id.join(','),'remove-from-tag-dlg','从标签移除',400,150,true);	
}

</script>