<div class="title">文章分类管理</div>
<div class="content">
    <div class="sub_title">
        [ <a href="javascript:fGo()" onclick="G('{{url param.action=addcatform}}')">添加分类</a> ]
    </div>
    <table cellpadding="0" cellspacing="0" border="0" class="table">
        <thead>
        <tr>
            <td>ID</td>
            <td>排序</td>
            <td>分类名</td>
			<td>别名</td>
            <td>文章数</td>
			<td>状态</td>
            <td>添加子分类</td>
            <td>操作</td>
        </tr>
    </thead>
    <tbody>
    {{foreach from=$catTree key=cat_id item=item}}
    <tr id="ajax_list{{$item.article_id}}">
        <td>{{$item.cat_id}}</td>
        <td><input value='{{$item.sort}}' type='text' style='width:30px'
	onchange="ajax_update('{{url param.action=ajaxupdatecat}}',{{$cat_id}},'level',this.value)"></td>
	
        <td style="padding-left:{{$item.step*30}}px;"><input value='{{$item.cat_name}}' type='text'
	onchange="ajax_update('{{url param.action=ajaxupdatecat}}',{{$cat_id}},'name',this.value)"></td>
	    <td>{{$item.asName}}</td>
        <td>{{$item.num}}</td>
		<td>{{if $item.status eq '1'}} 显示 {{else}} 隐藏 {{/if}}</td>
        <td>{{if !$item.num}}
			<a href="javascript:fGo()" onclick="G('{{url param.action=addcatform param.id=$cat_id}}')">添加子分类</a>
			{{/if}}
    </td>
        <td>
		<a href="javascript:fGo()" onclick="G('{{url param.action=editcatform param.id=$cat_id}}')">编辑</a> ||
		<a href="javascript:fGo()" onclick="reallydelete('{{url param.action=delcat}}','{{$cat_id}}','{{url param.action=listcat}}')">删除</a>
	</td>
    </tr>
    {{/foreach}}
    </tbody>
    </table>
</div>
