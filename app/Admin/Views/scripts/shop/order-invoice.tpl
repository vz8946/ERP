<div style="height:200px;">
<form method="post" action="/admin/shop/order-invoice">
<input type="hidden" name="url" id="url" value="{{$url}}">
<input type="hidden" name="id" value="{{$data.shop_order_id}}">
<table cellpadding="0" cellspacing="0" border="0">
<tr>
<td width="20px"><br><br><br><br><br><br><br><br></td>
<td>
  订单号：{{$data.external_order_sn}}<br><br>
  发票抬头：<input type="text" name="invoice" value="{{$data.invoice}}" size="40"><br><br>
  开票内容：<input type="text" name="invoice_content" value="{{$data.invoice_content}}" size="40">
</td>
</tr>
<tr>
  <td>&nbsp;</td>
  <td>
    <input type="submit" name="submit" value="修改">
  </td>
</tr>
</table>
</div>
