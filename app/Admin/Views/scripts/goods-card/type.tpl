<div class="search">
  <form id="searchForm" method="get">
  提货卡名称：<input type="text" name="card_name" size="15" maxLength="50" value="{{$param.card_name}}">
  状态：
	<select name="status">
		<option value="">请选择...</option>
		<option value="0" {{if $param.status eq '0'}}selected{{/if}}>正常</option>
		<option value="1" {{if $param.status eq '1'}}selected{{/if}}>冻结</option>
	</select>
  <input type="button" name="dosearch" value="搜索" onclick="ajax_search($('searchForm'),'{{url param.dosearch=search}}','ajax_search')"/>
  </form>
</div>
<form name="myForm" id="myForm">
	<div class="title">提货卡类型列表 [<a href="/admin/goods-card/add-type">添加卡类型</a>]</div>
	<div class="content">
<div style="padding:0 5px">
</div>
		<table cellpadding="0" cellspacing="0" border="0" class="table">
			<thead>
			<tr>
			    <td>ID</td>
				<td>提货卡名称</td>
				<td>类型</td>
				<td>面值</td>
				<td>状态</td>
				<td>添加时间</td>
				<td>操作</td>
			  </tr>
		</thead>
		<tbody>
		{{foreach from=$datas item=data}}
		<tr >
		    <td valign="top">{{$data.card_type_id}}</td>
		    <td valign="top">{{$data.card_name}}</td>
			<td valign="top">{{$data.goods_num}}选1</td>
			<td valign="top">{{$data.cost}}</td>
			<td id="ajax_status{{$data.card_type_id}}">{{$data.status}}</td>
			<td valign="top">{{$data.add_time|date_format:"%Y-%m-%d %H:%M:%S"}}</td>
			<td valign="top">
			  <a href="javascript:fGo()" onclick="openDiv('/admin/goods-card/view-type/id/{{$data.card_type_id}}','ajax','查看',600,400,true)">详情</a>
			  <a href="javascript:fGo()" onclick="G('{{url param.action=edit-type param.id=$data.card_type_id}}')">编辑</a>
			  <a href="javascript:fGo()" onclick="del({{$data.card_type_id}})">删除</a>
			</td>
		  </tr>
		{{/foreach}}
		</tbody>
		</table>
	</div>
	<div class="page_nav">{{$pageNav}}</div>
</form>

<script>
function del(id)
{
    if (confirm('确定要删除吗？')) {
        window.location = '/admin/goods-card/del-type/id/' + id;
    }
}
</script>