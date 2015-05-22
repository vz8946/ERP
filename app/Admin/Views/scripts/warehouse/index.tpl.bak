<div class="search">
  <form id="searchForm" method="get">
  仓库编码：<input type="text" name="shop_name" size="10" maxLength="50" value="{{$params.warehouse_sn}}">
  <input type="submit" name="dosearch" value="搜索" />
  </form>
</div>
<form name="myForm" id="myForm">
	<div class="title">仓库列表 [<a href="/admin/warehouse/add">添加仓库</a>]</div>
	<div class="content">
<div style="padding:0 5px">
</div>
		<table cellpadding="0" cellspacing="0" border="0" class="table">
			<thead>
			<tr>
			    <td>仓库ID</td>
				<td>仓库编码</td>
				<td>仓库名称</td>
				<td>省</td>
				<td>市</td>
				<td>区</td>
				<td>地址</td>
				<td>添加时间</td>
				<td >操作</td>
			  </tr>
		</thead>
		<tbody>
		{{foreach from=$infos item=val}}
		<tr >
		    <td valign="top">{{$val.warehouse_id}}</td>
		    <td valign="top">{{$val.warehouse_sn}}</td>
		    <td valign="top">{{$val.warehouse_name}}</td>
		    <td valign="top">{{$val.city_id}}</td>
		    <td valign="top">{{$val.city_id}}</td>
		    <td valign="top">{{$val.district_id}}</td>
		    <td valign="top">{{$val.address}}</td>
		    <td valign="top">{{$val.created_ts}}</td>
		    <td valign="top"><a href="/admin/warehouse/edit/warehouse_id/{{$val.warehouse_id}}">编辑</a></td>
		  </tr>
		{{/foreach}}
		</tbody>
		</table>
	</div>
	<div class="page_nav">{{$pageNav}}</div>
</form>