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
    text-700px: left;
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
{{foreach from=$datas item=data name=data}}
<div style="position:relative;text-align:center;padding:5px;">
<img src="/images/admin/print_title.jpg">
<h2>销　售 单</h2>
<br><img src="/admin/transport/barcode/no/{{$data.order_sn}}">
<br><br>
<br><br>
<table cellpadding="0" cellspacing="5" border="0" width="100%">
  <tr>
    <td width="50%" style="text-align:left;">
      送货方式：<b>款到发货</b> 已收货款：<b>{{$data.order_price}}</b>元
    </td>
    <td style="text-align:right;">
      订购渠道：{{$data.shop_name}}
    </td>
  </tr>
  <tr>
    <td style="text-align:left;">
      物流公司：{{$data.ship.logistic_name}}
    </td>
    <td style="text-align:right;">
      订单编号：<b>{{$data.order_sn}}</b>
    </td>
  </tr>
  <tr>
    <td style="text-align:left;">
      物流单号：{{$data.ship.logistics_no}}
    </td>
  </tr>
</table>
<br>
<table cellpadding="0" cellspacing="5" border="0" width="100%">
  <tr>
    <td style="text-align:left;">
      收货人姓名：{{$data.ship.name}}　　　　　　电话：{{$data.ship.phone}}　　　　　　手机：{{$data.ship.mobile}}
    </td>
  </tr>
  <tr>
    <td style="text-align:left;">
      地址：{{$data.ship.addr}}
    </td>
  </tr>
  <tr>
    <td style="text-align:left;">
      是否开票：{{$data.invoice_info}}
    </td>
  </tr>
  <tr>
    <td style="text-align:left;">
      物流备注：
    </td>
  </tr>
</table>
<br>
<table cellpadding="0" cellspacing="5" border="0" width="100%">
<tr><td style="text-align:left;">本次发货情况：</td></tr>
</table>
<table cellpadding="0" cellspacing="0" border="0" class="table_print">
<thead>
<tr>
    <td >商品编码</td>
    <td >商品名称</td>
    <td >商品规格</td>
    <td >数量</td>
    <td >单价</td>
    </tr>
</thead>
<tbody>
<tr>
<td>{{$data.product.sn}}</td>
<td>{{$data.product.name}}</td>
<td>{{$data.product.style}}&nbsp;</td>
<td>{{$data.product.amount}}</td>
<td>
{{$data.product.sale_price}}
</td>
</tr>
<tr>
<td colspan="4" style="text-align:right;">
小计：
</td>
<td>
{{$data.order_price}}
</td>
</tr>
{{if $data.ship.fee > 0}}
<!--
<tr>
<td colspan="4" style="text-align:right;">
渠道销售费：
</td>
<td>
{{$data.ship.fee|string_format:'%.2f'}}
</td>
</tr>
-->
{{/if}}
<tr>
<td colspan="4" style="text-align:right;">
订单金额总计：
</td>
<td>
{{$data.order_price|string_format:'%.2f'}}
</td>
</tr>
</tbody>
</table>
<table cellpadding="0" cellspacing="5" border="0" width="100%">
<tr><td style="font-style:italic">感谢您在垦丰商城购物，如需退换货请填写背面的退换货单并拨打客服热线400-603-3883。我们期待您的再次光临</td></tr>
</table>
</div>
{{if $datas|@count ne $smarty.foreach.data.iteration}}
<div style="PAGE-BREAK-AFTER:always"></div>
{{/if}}
{{/foreach}}
</body>
</html>