<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<div class="search">
  <form id="searchForm" method="get">
	
<span style="float:left;line-height:18px;">开始日期：</span>
<span style="float:left;width:150px;line-height:18px;"><input type="text" name="fromdate" id="fromdate" size="15" value="{{$param.fromdate}}"  class="Wdate" onClick="WdatePicker()"/></span>
<span style="float:left;line-height:18px;">结束日期：</span>
<span style="float:left;width:300px;line-height:18px;">
<input  type="text" name="todate" id="todate" size="15" value="{{$param.todate}}"  class="Wdate"  onClick="WdatePicker()"/>
<input type="button" value="清除日期" onclick="$('fromdate').value='';$('todate').value=''"/>
</span>
  当前店铺：
  <select name="shop_id" id="shop_id">
    <option value="">请选择...</option>
    {{foreach from=$shopDatas item=data}}
      {{if $data.shop_type ne 'tuan' && $data.shop_type ne '99vk' && $data.shop_type ne '99nc' && $data.shop_type ne '99ty'}}
      <option value="{{$data.shop_id}}" {{if $data.shop_id eq $param.shop_id}}selected{{/if}}>{{$data.shop_name}}</option>
      {{/if}}
    {{/foreach}}
  </select>
  <br><br>
  商品编号：<input type="text" name="goods_sn" size="10" maxLength="60" value="{{$param.goods_sn}}">
  商品名称：<input type="text" name="shop_goods_name" size="10" maxLength="60" value="{{$param.shop_goods_name }}">
  <input type="button" name="dosearch" value="搜索" onclick="ajax_search($('searchForm'),'{{url param.dosearch=search}}','ajax_search')"/>
  [<a href="{{url param.action=goods-sale-export}}?{{$smarty.server.QUERY_STRING}}" target="_blank">导出查询结果</a>]
  </form>
</div>
<form name="myForm" id="myForm">
	<div class="title">商品销售数据</div>
	<div class="content">
<div style="padding:0 5px">
</div>
		<table cellpadding="0" cellspacing="0" border="0" class="table">
			<thead>
			<tr>
                <td>商品编号</td>
				<td>商品名称</td>
				<td>商品单价</td>
                 {{if $viewcost eq '1'}}<td>成本价</td>{{/if}}
				<td>销售数量</td>
				<td>平均售价</td>
                <td>总金额</td>
			  </tr>
		</thead>
		<tbody>
		{{foreach from=$goodsList item=goods}}
		<tr >
            <td valign="top">{{$goods.goods_sn}}</td>
			<td valign="top">{{$goods.goods_name}}</a></td>
			<td valign="top">{{$goods.price}}</td>
           {{if  $viewcost eq '1'}}<td valign="top">{{$goods.cost}}</td>{{/if}}
            <td valign="top">{{$goods.number}}</td>
			<td valign="top">{{$goods.avg_price}} </td>
            <td valign="top">{{$goods.total_amount}}</td>
		  </tr>
		{{/foreach}}
		</tbody>
		</table>
	</div>
	<div class="page_nav">{{$pageNav}}</div>
</form>