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
<div style="position:absolute;right:0px;top:70px;z-index:10;width:7cm;overflow:hidden">{{$data.barcode}}</div>
<img src="/images/admin/print_title.jpg">
<br><br>
<div style="float:left;text-align:left;font-weight:bold; line-height:150%;">
<table cellpadding="10" cellspacing="10" border="0" width="100%">
    <tr>
        <td width="50%">
          <h4>箱号：{{$info.box_sn}}</h4>    备注：{{$info.remark}}
        </td>
        <td>
          SKU总类数：{{$product_count}}
        </td>
        <td>SKU总数：{{$sum_product}}</td>
        <td>&nbsp;</td>
    </tr>
</table>
</div>
<table cellpadding="0" cellspacing="0" border="0" class="table_print" align="center">
<thead>
<tr>
    <td>产品条码</td>
    <td>产品编码</td>
    <td>产品名称</td>
	<td>数量</td>
    </tr>
</thead>
<tbody>
{{if $product_infos}}
    {{foreach from=$product_infos item=product}}
    <tr>
        <td>{{$product.barcode}}&nbsp;</td>
        <td>{{$product.product_sn}}&nbsp;</td>
        <td>{{$product.product_name}}&nbsp;</td>
        <td>{{$product.number}}&nbsp;</td>
    </tr>
    {{/foreach}}
    {{/if}}
</tbody>
</table>
</div>

</body>
</html>