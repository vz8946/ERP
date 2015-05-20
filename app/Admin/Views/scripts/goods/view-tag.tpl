<div class="title">商品单页标签管理   [ <a href="javascript:fGo()" onclick="G('{{url param.action=add-view-tag}}')">添加新标签</a> ]</div>
<form name="searchForm" id="searchForm" action="/admin/goods/goods-tag">
<div class="search">
标题：<input type="text" name="title" size="15" maxLength="80" value="{{$param.title}}"/>
联盟ID：<input type="text" name="union_id" size="10" maxLength="50" value="{{$param.union_id}}"/>
<input type="submit" name="dosearch" value="查询"/>
</div>
</form>
<div class="content">
    <table cellpadding="0" cellspacing="0" border="0" class="table">
        <thead>
        <tr>
            <td>标签ID</td>
			<td>标签名</td>
            <td>联盟ID</td>
            <td>联盟下家参数</td>
            <td>商品名前缀</td>
            <td>商品名后缀</td>
            <td>操作</td>
        </tr>
    </thead>
    <tbody>
    {{foreach from=$taglist item=data}}
    <tr id="ajax_list{{$data.goods_id}}">
        <td>{{$data.id}}</td>
        <td>  
		<input type="text" name="title" size="20" value="{{$data.title}}"  onchange="ajax_update('{{url param.action=ajaxupdate  param.type=viewtag}}',{{$data.id}},'title',this.value)">
		 </td>
         
        <td>  
		<input type="text" name="union_id" size="8" value="{{$data.union_id}}"  onchange="ajax_update('{{url param.action=ajaxupdate  param.type=viewtag}}',{{$data.id}},'union_id',this.value)">
		 </td>
        <td>  
		<input type="text" name="union_param" size="30" value="{{$data.union_param}}"  onchange="ajax_update('{{url param.action=ajaxupdate  param.type=viewtag}}',{{$data.id}},'union_param',this.value)">
		 </td>
        <td>  
		<input type="text" name="mark" size="30" value="{{$data.mark}}"  onchange="ajax_update('{{url param.action=ajaxupdate  param.type=viewtag}}',{{$data.id}},'mark',this.value)">
		 </td>
         
        <td>
		<input type="text" name="tag" size="30" value="{{$data.tag}}"  onchange="ajax_update('{{url param.action=ajaxupdate  param.type=viewtag}}',{{$data.id}},'tag',this.value)">
		</td>
        <td>
		 <a href="javascript:fGo()" onclick="G('/admin/goods/edit-view-tag/id/{{$data.id}}/type/{{$data.type}}')">编辑</a> || 
         <a href="javascript:fGo()" onclick="delViewTag({{$data.id}})">删除</a>
        </td>
        </td>
    </tr>
    {{/foreach}}

    </tbody>
    </table>
</div>
<div class="page_nav">{{$pageNav}}</div>

<script type="text/javascript">

//删除一条记录
function delViewTag(id){
	if(confirm("确认要删除吗？")){
		id=parseInt(id);
		if(id<1){alert('参数错误！');return;}
		new Request({
			url:'/admin/goods/del-view-tag/id/'+id,
			method:'get',
			onSuccess:function(data){
				if(data=='ok'){
					alert("删除成功");
					window.location.reload();
				}else{
					alert("删除失败，请稍后重试");
				}
			},
			onFailure:function(){
				alert("网路繁忙，请稍后重试");
			}
		}).send();
	}
}
</script>
