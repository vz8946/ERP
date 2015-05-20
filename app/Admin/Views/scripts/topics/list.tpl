{{if !$param.do}}
<div class="search">
	<form id="searchForm">
		<div style="clear:both; padding-top:5px">

		标题：<input type="text" name="title" size="20" maxLength="50" value="{{$param.title}}">

        是否显示：<select name="is_view">
          <option value="null">请选择</option>
          <option value="1" {{if $param.is_view eq '1'}}selected{{/if}}>显示</option>
          <option value="0" {{if $param.is_view eq '0'}}selected{{/if}}>不显示</option>
        </select>


		<input type="button" name="dosearch" value="搜索" onclick="ajax_search($('searchForm'),'{{url param.do=search}}','ajax_search')"/>
		</div>
	</form>
</div>
<div id="ajax_search">
{{/if}}
	<div class="title">专题管理</div>
	<div class="content">
		<div class="sub_title">[ <a href="javascript:fGo()" onclick="G('{{url param.action=add}}')">添加专题</a> ]</div>
		<table cellpadding="0" cellspacing="0" border="0" class="table">
			<thead>
			<tr>
				<td>ID</td>
				<td>排序</td>
				<td>标题</td>
				<td>是否显示</td>
				<td>操作</td>
			</tr>
		</thead>
		<tbody>
		{{foreach from=$data item=item}}
		<tr id="ajax_list{{$item.id}}">
			<td>{{$item.id}}</td>
			<td><input type="text" name="update" size="2" value="{{$item.sort}}" style="text-align:center;" 
			onchange="ajax_update('{{url param.action=ajaxupdate}}',{{$item.id}},'sort',this.value)"></td>
			<td>{{$item.name}}</td>
			<td  >
            <a href="javascript:void(0);"  id="label_isdis{{$item.id}}" onclick="ajax_status('/admin/topics/toggle-isdis','{{$item.id}}','{{$item.isDisplay}}','label_isdis');">
	        {{if $item.isDisplay eq 1}}显示{{elseif $item.isDisplay eq 0 }}不显示 {{/if}}</a>
			</td>
			<td>
			<a href="javascript:fGo()" onclick="G('{{url param.action=edit param.id=$item.id param.pkid=id}}')">编辑</a>||
			<a href="javascript:fGo()" onclick="reallydelete('{{url param.action=del param.pkid=id}}','{{$item.id}}','{{url param.action=list}}')">删除</a>
		</td>
		</tr>
		{{/foreach}}
		</tbody>
		</table>
	</div>
	<div class="page_nav">{{$pageNav}}</div>
</div>
<script type="text/javascript">
function changHot(articleID, st){
	articleID = parseInt(articleID);
	st = parseInt(st);
	if(st!=1 && st!=0){ st = 0; }
	new Request({
		url:'/admin/article/is-hot/article_id/'+articleID+'/st/'+st,
		onSuccess:function(msg){
			if(msg == 'ok'){
				location.reload();
			}else{
				alert(msg);
			}
		},
		onFailure:function(){
			alert('网络繁忙，请稍后重试');
		}
	}).send();
}
</script>