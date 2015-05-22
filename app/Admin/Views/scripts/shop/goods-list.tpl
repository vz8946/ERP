<div class="search">
  <form id="searchForm" method="get">
  当前店铺：
  <select name="shop_id">
    <option value="">请选择...</option>
    {{foreach from=$shopDatas item=data}}
      {{if $data.shop_type ne 'tuan' && $data.shop_type ne 'jiankang' && $data.shop_type ne 'credit' && $data.shop_type ne 'distribution'}}
      <option value="{{$data.shop_id}}" {{if $data.shop_id eq $param.shop_id}}selected{{/if}}>{{$data.shop_name}}</option>
      {{/if}}
    {{/foreach}}
  </select>
  商品状态：
	<select name="onsale">
		<option value="">请选择...</option>
		<option value="0" {{if $param.onsale eq '0'}}selected{{/if}}>上架</option>
		<option value="1" {{if $param.onsale eq '1'}}selected{{/if}}>下架</option>
	</select>
  SKU ID：<input type="text" name="shop_sku_id" size="10" maxLength="50" value="{{$param.shop_sku_id}}">
  商品名称：<input type="text" name="shop_goods_name" size="15" maxLength="60" value="{{$param.shop_goods_name}}">
  商品编号：<input type="text" name="goods_sn" size="10" maxLength="60" value="{{$param.goods_sn}}">
  <input type="checkbox" name="price_limit" value="1" {{if $param.price_limit eq '1'}}checked='true'{{/if}}/>限价
  <input type="button" name="dosearch" value="搜索" onclick="ajax_search($('searchForm'),'{{url param.dosearch=search}}','ajax_search')"/>
   [<a href="{{url param.action=goods-export}}?{{$smarty.server.QUERY_STRING}}" target="_blank">导出查询结果</a>]
  </form>
</div>
<form name="myForm" id="myForm">
	<div class="title">店铺商品列表</div>
	<div class="content">
<div style="padding:0 5px">
</div>
		<table cellpadding="0" cellspacing="0" border="0" class="table">
			<thead>
			<tr>
			    <td>ID</td>
			    <td>店铺</td>
				<td>商品名称</td>
				<td>商品编号</td>
				<td>分类</td>
				<td >状态</td>
				<td >SKU ID</td>
				<td>销售价</td>
                <td>保护价</td>
				<td>库存数量</td>
				<td>最后更新日期</td>
			  </tr>
		</thead>
		<tbody>
		{{foreach from=$datas item=data}}
		<tr >
		    <td valign="top">{{$data.id}}</td>
		    <td valign="top">{{$data.shop_name}}</td>
			<td valign="top">{{$data.shop_goods_name}}</td>
			<td valign="top">{{$data.goods_sn}}</td>
			<td valign="top">{{$data.category}}</td>
			<td valign="top">
			  {{if $data.onsale eq '0'}}上架
			  {{else}}下架
			  {{/if}}
			</td>
			<td valign="top">{{$data.shop_sku_id}}</td>
			<td valign="top" {{if $data.shop_price < $data.price_limit}} style="color:#ff0000;"{{/if}}>{{$data.shop_price}}</td>
            <td valign="top">{{if $data.price_limit eq 0}}无限价{{else}}{{$data.price_limit}}{{/if}}</td>
			<td valign="top">{{$data.stock_number}}</td>
			<td valign="top">{{$data.update_time|date_format:"%Y-%m-%d %H:%M:%S"}}</td>
		  </tr>
		{{/foreach}}
		</tbody>
		</table>
	</div>
	<div class="page_nav">{{$pageNav}}</div>
</form>