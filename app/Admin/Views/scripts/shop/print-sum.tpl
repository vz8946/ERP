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
    width: 740px;
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
<!--<img src="/images/admin/print_title.jpg">-->
<h2>商品销售拣货单</h2>
<br>
<div style="font-size:12px">
  商品品种：{{$goodsInfo|@count}}
  总商品数：{{$printData.totalGoodsNumber}}&nbsp;&nbsp;
  总单数：{{$printData.totalOrderNumber}}
</div>
<br>
<table cellpadding="0" cellspacing="0" border="0" class="table_print">
<thead>
  <tr>
    <td width="400px">商品名</td>
    <td>商品编码</td>
    <!--<td>批次</td>-->
    <td>商品总数量</td>
    <td>货位</td>
    <!--<td>订单中商品件数（订单数）</td>-->
    <!--<td>订单号（商品数量）</td>-->
  </tr>
</thead>
<tbody>
{{foreach from=$goodsInfo item=goodsInfo1}}
{{foreach from=$goodsInfo1 key=key item=goods}}
  <tr>
    <td>{{$goods.info.product_name}} ({{$goods.info.goods_style}})</td>
    <td>{{$goods.goodsSN}}</td>
    <!--<td>{{if $key}}{{$key}}{{else}}无批次{{/if}}</td>-->
    <td style="text-align:center">{{$goods.goodsNumber}}</td>
    <td>{{$goods.localSN|default:'&nbsp;'}}</td>
    <!--<td>
      {{foreach from=$goods.orderInfo item=number key=order_number}}
        商品数量{{$order_number}}件 ({{$number}})<br>
      {{/foreach}}
    </td>-->
    <!--<td>{{$goods.orderSN}}</td>-->
{{/foreach}}
{{/foreach}}
</tbody>
</table>
<br>
<table cellpadding="0" cellspacing="0" border="0" class="table_print">
<thead>
<tr>
<td>订单编号</td>
<td>送货方式</td>
<td>物流公司</td>
<td>物流单号</td>
<td>发票抬头</td>
<td>发票内容</td>
<td>开票金额</td>
</tr>
</thead>
{{foreach from=$datas item=data}}
<tr>
  <td>{{$data.external_order_sn}}</td>
  <td>{{if $data.is_cod}}货到付款{{else}}款到发货{{/if}}</td>
  <td>{{$data.logistic_name}}</td>
  <td>{{$data.logistic_no|default:'&nbsp;'}}</td>
  <td>{{if $data.invoice}}{{$data.invoice}}{{else}}&nbsp;{{/if}}</td>
  <td>&nbsp;</td>
  <td>{{if $data.invoice}}{{$data.amount}}{{else}}&nbsp;{{/if}}</td>
</tr>
{{/foreach}}
</table>
</div>
<div style="text-align:left;padding:30px 0;"><br>
拣货人：________________　　　　　　QC：________________　　　　　　打印时间：{{$smarty.now|date_format:"%Y-%m-%d %H:%M:%S"}}
<br>
<br>
<table cellpadding="0" cellspacing="0" border="0" class="table_print">
<thead>
<tr>
<td>订单编号</td>
<td>送货方式</td>
<td>物流公司</td>
<td>物流单号</td>
<td>产品信息</td>
</tr>
</thead>
{{foreach from=$datas item=data}}
<tr>
<td>{{$data.external_order_sn}}</td>
<td>{{if $data.is_cod}}货到付款{{else}}款到发货{{/if}}</td>
<td>{{$data.logistic_name}}</td>
<td>{{$data.logistic_no}}</td>
<td>
{{foreach from=$goodsArray[$data.external_order_sn] item=goods}}
【{{$goods.goodsSN}}】{{$goods.goodsName}}【{{$goods.goodsNumber}}】 <br>
{{/foreach}}
</td>
</tr>
{{/foreach}}
</table>
</div>
</body>
</html>