{{if !$param.do}}
<div class="search">
	<form id="searchForm">
		<div style="clear:both; padding-top:5px">
		分类:
		<select name="ncId">
			<option value="">请选择...</option>
			{{foreach from=$catTree key=cat_id item=item}}
			{{if $item.leaf}}
			<option value={{$cat_id}} style="padding-left:{{$item.step*20-20}}px" {{if $param.cat_id==$cat_id}}selected="selected"{{/if}}>
			{{$item.cat_name}}（{{$item.num}}）
			</option>
			{{else}}
			<optgroup label='{{$item.cat_name}}' style="padding-left:{{$item.step*20-20}}px"></optgroup>
			{{/if}}
			{{/foreach}}
		</select>
		标题：<input type="text" name="title" size="20" maxLength="50" value="{{$param.title}}">

        是否推荐：<select name="isTop">
          <option value="null">请选择</option>
          <option value="1" {{if $param.is_hot eq '1'}}selected{{/if}}>是</option>
          <option value="0" {{if $param.is_hot eq '0'}}selected{{/if}}>否</option>
        </select>

		<input type="button" name="dosearch" value="搜索" onclick="ajax_search($('searchForm'),'{{url param.do=search}}','ajax_search')"/>
		</div>
	</form>
</div>
<div id="ajax_search">
{{/if}}
	<div class="title">资讯管理</div>
	<div class="content">
		<div class="sub_title">[ <a href="javascript:fGo()" onclick="G('{{url param.action=addform}}')">添加资讯</a> ]</div>
		<table cellpadding="0" cellspacing="0" border="0" class="table">
			<thead>
			<tr>
				<td>ID</td>
				<td>分类</td>
				<td>标题</td>
				<td>展示位置</td>
				<td>添加日期</td>
				<td>是否推荐置顶</td>
			    <td>添加人</td>
				<td>操作</td>
			</tr>
		</thead>
		<tbody>
		{{foreach from=$data item=item}}
		<tr id="ajax_list{{$item.id}}">
			<td>{{$item.id}}</td>
			<td>{{$item.ncName}}</td>
			<td>
				<a href="{{$newsBaseUrl}}/{{$item.asName}}/news-{{$item.id}}.html" title="{{$item.title}}" target="_blank">{{$item.title}}</a>
			</td>
			<td title="{{$item.position}}"><div style="width:350px;height:30px;overflow:hidden;margin:0;padding:0;">{{$item.position}}</div></td>
			<td >{{if $item.addTime}}{{$item.addTime|date_format:'%Y-%m-%d'}}{{else}}{{/if}}</td>
			<td>{{if $item.isTop eq 1}}<font style="color:#009966; cursor:pointer;" onclick="changHot({{$item.id}}, 0);">是</font>{{elseif $item.isTop eq 0}}<font style="color:#FF3300; cursor:pointer;" onclick="changHot({{$item.id}}, 1);">否</font>{{/if}}</td>
			<td>{{$item.lauthor}}</td>
			<td>
			<a href="javascript:fGo()" onclick="G('{{url param.action=editform param.id=$item.id}}')">编辑</a>||
			<a href="javascript:fGo()" onclick="reallydelete('{{url param.action=del}}','{{$item.id}}','{{url param.action=list}}')">删除</a>
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
		url:'/admin/news/is-hot/article_id/'+articleID+'/st/'+st,
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