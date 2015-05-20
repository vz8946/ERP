<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="zh-CN" lang="zh-CN">
<head>
<title>垦丰电商ERP系统</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<link href="/styles/admin/style.css" type="text/css" rel="stylesheet"/>
<link href="/images/admin/alertImg/alertbox.css" type="text/css" rel="stylesheet"/>
<style>
body {
    margin: 0;
    color: #000;
}
table, td, div {
    font: normal 12px  Verdana, "Times New Roman", Times, serif;
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
</style>
</head>
<body>
<div style="position:relative;text-align:center;padding:5px;">
<img src="/images/admin/print_title.jpg">
<h2>订单商品发货统计</h2>
<br>
<div style="font-size:12px">
  店铺名：{{$printData.shopName}}&nbsp;&nbsp;
  时间区间：{{$printData.startDate}} - {{$printData.endDate}}&nbsp;&nbsp;
  商品品种：{{$goodsInfo|@count}}
  总商品数：{{$printData.totalGoodsNumber}}&nbsp;&nbsp;
  总单数：{{$printData.totalOrderNumber}}
</div>
<br>
<table cellpadding="0" cellspacing="0" border="0" class="table_print">
<thead>
  <tr>
    <td>商品名 商品规格</td>
    <td>商品编号</td>
    <td>待发货数量</td>
    <td>已发货数量</td>
  </tr>
</thead>
<tbody>
{{foreach from=$goodsInfo item=goods}}
  <tr>
    <td>{{$goods.goodsName}}&nbsp;</td>
    <td>{{$goods.goodsSN}}</td>
    <td>{{if $goods.wait}}{{$goods.wait}}{{else}}0{{/if}}</td>
    <td>{{if $goods.sent}}{{$goods.sent}}{{else}}0{{/if}}</td>
{{/foreach}}
</tbody>
</table>
</div>
<div style="text-align:center;padding:30px 0;"><strong>打印时间：</strong>{{$smarty.now|date_format:"%Y-%m-%d %H:%M:%S"}}&nbsp;&nbsp;&nbsp;&nbsp;<strong>操作人：</strong>{{$auth.admin_name}}</div>
</body>
</html>