<div class="search">
  <form id="searchForm" method="get" action="/admin/shop/promotion-list">
  当前店铺：
  <select name="shop_id">
    <option value="">请选择...</option>
    {{foreach from=$shopDatas item=data}}
      {{if $data.shop_type ne 'tuan' && $data.shop_type ne 'jiankang' && $data.shop_type ne 'credit' && $data.shop_type ne 'distribution'}}
      <option value="{{$data.shop_id}}" {{if $data.shop_id eq $param.shop_id}}selected{{/if}}>{{$data.shop_name}}</option>
      {{/if}}
    {{/foreach}}
  </select>
  活动类型：
	<select name="type">
		<option value="">请选择...</option>
		<option value="1" {{if $param.type eq '1'}}selected{{/if}}>满送</option>
		<option value="2" {{if $param.type eq '2'}}selected{{/if}}>买送</option>
	</select>
  活动名称：<input type="text" name="promotion_name" size="15" maxLength="60" value="{{$param.promotion_name}}">
  &nbsp;&nbsp;
  <input type="checkbox" name="search_time_type[]" value="0" {{if $param.search_time_type.0}}checked{{/if}}>进行中
  <input type="checkbox" name="search_time_type[]" value="1" {{if $param.search_time_type.1}}checked{{/if}}>未开始
  <input type="checkbox" name="search_time_type[]" value="2" {{if $param.search_time_type.2}}checked{{/if}}>已结束
　&nbsp;&nbsp;
  <input type="submit" name="dosearch" value="搜索"/>
  </form>
</div>
<form name="myForm" id="myForm">
	<div class="title">店铺活动列表 [<a href="/admin/shop/promotion-add">添加店铺活动</a>]</div>
	<div class="content">
<div style="padding:0 5px">
</div>
		<table cellpadding="0" cellspacing="0" border="0" class="table">
			<thead>
			<tr>
			    <td>ID</td>
			    <td>店铺</td>
				<td>活动名称</td>
				<td>活动类型</td>
				<td>优先级</td>
				<td>开始时间</td>
				<td>结束时间</td>
				<td>状态</td>
				<td>操作</td>
			  </tr>
		</thead>
		<tbody>
		{{foreach from=$datas item=data}}
		  <tr>
		    <td valign="top">{{$data.promotion_id}}</td>
		    <td valign="top">{{$data.shop_name}}</td>
			<td valign="top">{{$data.promotion_name}}</td>
			<td valign="top">
			  {{if $data.type eq '1'}}满送
			  {{elseif $data.type eq '2'}}买送
			  {{/if}}
			</td>
			<td valign="top">{{$data.sort}}</td>
			<td valign="top">{{$data.start_time|date_format:"%Y-%m-%d %H:%M:%S"}}</td>
			<td valign="top">{{$data.end_time|date_format:"%Y-%m-%d %H:%M:%S"}}</td>
			<td valign="top">
			  {{if $data.status eq '0'}}启用
			  {{else}}<font color="red">冻结</font>
			  {{/if}}
			</td>
			<td valign="top">
			  <a href="javascript:fGo()" onclick="G('{{url param.action=promotion-edit param.id=$data.promotion_id}}')">编辑</a>
			</td>
		  </tr>
		{{/foreach}}
		</tbody>
		</table>
	</div>
	<div class="page_nav">{{$pageNav}}</div>
</form>