{{if !$param.do}}
<div class="search">
	<form id="searchForm">
		<div style="clear:both; padding-top:5px">
		标题：<input type="text" name="name" size="40" maxLength="50" value="{{$param.name}}">

        类型：<select name="type">
          <option value="">请选择</option>
			  <option value="1" {{if $param.type eq '1'}}selected{{/if}}>文章资讯</option>
			  <option value="2" {{if $param.type eq '2'}}selected{{/if}}>广告位</option>
			  <option value="3" {{if $param.type eq '3'}}selected{{/if}}>友情链接</option>
			  <option value="4" {{if $param.type eq '4'}}selected{{/if}}>爆款类别</option>
			  <option value="5" {{if $param.type eq '5'}}selected{{/if}}>促销中心类别</option>
			  <option value="6" {{if $param.type eq '6'}}selected{{/if}}>巨便宜类别</option>
			  <option value="7" {{if $param.type eq '7'}}selected{{/if}}>首页排行装修</option>
        </select>
		<input type="button" name="dosearch" value="搜索" onclick="ajax_search($('searchForm'),'{{url param.do=search}}','ajax_search')"/>
		</div>
	</form>
</div>
<div id="ajax_search">
{{/if}}
	<div class="title">数据字典管理</div>
	<div class="content">
		<div class="sub_title">[ <a href="javascript:fGo()" onclick="G('{{url param.action=adddataform}}')">添加数据字典</a> ]</div>
		<table cellpadding="0" cellspacing="0" border="0" class="table">
			<thead>
			<tr>
				<td>ID</td>
				<td>名称</td>
				<td>数据码</td>
				<td>类型</td>
				<td>操作</td>
			</tr>
		</thead>
		<tbody>
		{{foreach from=$data item=item}}
		<tr id="ajax_list{{$item.id}}">
			<td>{{$item.id}}</td>
			<td><input value='{{$item.name}}' type='text' style='width:230px' onchange="ajax_update('{{url param.action=ajaxupdatedata}}',{{$item.id}},'name',this.value)"></td>
			<td><input value='{{$item.code}}' type='text' style='width:120px' onchange="ajax_update('{{url param.action=ajaxupdatedata}}',{{$item.id}},'code',this.value)"></td>
			<td>
				<select name="type"  onchange="ajax_update('{{url param.action=ajaxupdatedata}}',{{$item.id}},'type',this.value)">
		          <option value="1" {{if $item.type eq '1'}}selected{{/if}}>文章资讯</option>
		          <option value="2" {{if $item.type eq '2'}}selected{{/if}}>广告位</option>
		          <option value="3" {{if $item.type eq '3'}}selected{{/if}}>友情链接</option>
		          <option value="4" {{if $item.type eq '4'}}selected{{/if}}>爆款类别</option>
	          	  <option value="5" {{if $item.type eq '5'}}selected{{/if}}>促销中心类别</option>
	          	  <option value="6" {{if $item.type eq '6'}}selected{{/if}}>巨便宜类别</option>
	          	  <option value="7" {{if $item.type eq '7'}}selected{{/if}}>首页排行装修</option>
		        </select>
			</td>
			<td>
			<a href="javascript:fGo()" onclick="reallydelete('{{url param.action=deldata}}','{{$item.id}}','{{url param.action=listdata}}')">删除</a>
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