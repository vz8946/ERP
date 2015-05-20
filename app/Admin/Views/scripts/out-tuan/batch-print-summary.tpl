<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="zh-CN" lang="zh-CN">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<style>
body {
	margin: 0;
	color: #000;
}
table, td, div {
	font: normal 12px Verdana, "Times New Roman", Times, serif;
}
div {
	margin: 0 auto;
	width: 700px;
}
.table_print {
	clear: both;
	border-right: 1px solid #333;
	border-bottom: 1px solid #333;
	text-align: left;
	width: 700px;
}
.table_print td {
	padding: 2px;
	color: #333;
	background: #fff;
	border-top: 1px solid #333;
	border-left: 1px solid #333;
	line-height: 150%;
}
.item {
	text-align:right;
	font-weight:bold;
}
h3{ font-size:14px; font-weight:bold;}
</style>
</head>
<body>
<div style="position:relative;text-align:center;padding:5px;">
	<div style="position:absolute;right:0px;top:70px;z-index:10;width:8cm;overflow:hidden"> 
		<!--<img  src="/admin/logic-area2-out-stock/barcode/code/{{$data.bill_no}}" alt="barcode" border="0"  />--> 
	</div>
	<img src="/images/admin/print_title.jpg">
	<h2><br>批次汇总单<br></h2>
	{{foreach from=$summarys item=summary}}
	<h3 style="text-align:left;"><br><br>{{$summary.shop_name}} -- {{$summary.goods_name}} -- 批次（{{$summary.batch}}） -- 货位（{{$summary.local_sn}}）</h3>
	<br>
	<table cellpadding="0" cellspacing="0" border="0" class="table_print">
		<thead>
			<tr>
				<td >商品编码</td>
				<td >商品名称</td>
				<td >单份规格</td>
				<td >单份供货价</td>
				<td >份数小计</td>
			</tr>
		</thead>
		<tbody>
			{{foreach from=$summary.summary item=d key=key}}
			<tr>
				<td>{{$d.sn}}</td>
				<td>{{$d.goods_name}}</td>
				<td>{{$d.per_number}} &times; {{$d.style}}</td>
				<td>￥{{$d.supply_price}}</td>
				<td>{{$d.ct}}</td>
			</tr>
			{{/foreach}}
			<tr>
				<td colspan="5" align="center"><!--<strong>订单销售金额合计：</strong> {{$summary.totalPrice}}  &nbsp;&nbsp;&nbsp;--><strong>此批次供货金额合计：</strong> {{$summary.totalSupplyPrice}} </td>
			</tr>
		</tbody>
	</table>
	{{/foreach}}
</div>
<div style="text-align:center;padding:30px 0;"><strong>打印时间：</strong>{{$summary.print_time}}&nbsp;&nbsp;&nbsp;&nbsp;<strong>操作人：</strong>{{$summary.admin_name}}</div>
</body>
</html>