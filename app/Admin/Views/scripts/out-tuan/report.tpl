<script language="javascript" type="text/javascript" src="/scripts/my97/WdatePicker.js"></script>
<script type="text/javascript">
//导出
function exportReport(){
	var f = document.getElementById('searchForm');
	f.action='/admin/out-tuan/export-report';
	f.method='post';
	f.submit();
	f.action = "/admin/out-tuan/report";
}
</script>
<div class="title">产品销售统计</div>
<div class="search">
<form name="searchForm" id="searchForm" action="/admin/out-tuan/report">
<table border="0">
    <tr>
	  <td>订单导入开始日期：</td><td><input type="text" name="fromdate" id="fromdate" size="15" value="{{$param.fromdate}}"  class="Wdate" onClick="WdatePicker()"/></td>
	  <td>订单导入结束日期：</td><td><input  type="text" name="todate" id="todate" size="15" value="{{$param.todate}}"  class="Wdate"  onClick="WdatePicker()"/></td>
	</tr>
</table>
  商品名称:<input type="text" name="goods_name_like" value="{{$param.goods_name_like}}" />&nbsp;&nbsp;&nbsp;&nbsp;
  所属网站:<select name="shop_id">
    <option value="">全部</option>
      {{foreach from=$shops item=shop}}
      <option {{if $param.shop_id eq $shop.shop_id}}selected{{/if}} value="{{$shop.shop_id}}">{{$shop.shop_name}}</option>
      {{/foreach}}
  </select>
  <input type="submit" name="dosearch" value="查询"/>&nbsp;&nbsp;&nbsp;&nbsp;
  <input type="button" onclick="exportReport()" value="导出商品销售统计" />
</form>
</div>
<div class="content">
<table cellpadding="0" cellspacing="0" border="0" class="table">
    <thead>
        <tr>
            <td>商品编码</td>
			<td>商品名称</td>
			<td>销售总金额</td>
			<td>销售量</td>
            <td>平均售价</td>
        </tr>
    </thead>
    <tbody>
    {{foreach from=$datas item=data}}
    <tr id="row{{$data.goods_id}}" >
		<td>{{$data.sn}}</td>
		<td><strong>{{$data.goods_name}}</strong></td>
		<td><strong>{{$data.totalSupplyPrice}}</strong></td>
        <td><strong>{{$data.totalAmount}}</strong></td>
        <td><strong>{{if $data.totalAmount}}{{$data.totalSupplyPrice/$data.totalAmount|string_format:"%.2f"}}{{/if}}</strong></td>
    </tr>
    {{/foreach}}
    </tbody>
</table>
</div>