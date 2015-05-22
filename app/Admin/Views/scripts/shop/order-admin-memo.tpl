<div style="height:200px;">
<form method="post" action="/admin/shop/order-admin-memo">
<input type="hidden" name="url" id="url" value="{{$url}}">
<input type="hidden" name="id" value="{{$data.shop_order_id}}">
<table cellpadding="0" cellspacing="0" border="0">
<tr>
<td width="30px"><br><br><br><br><br><br><br><br></td>
<td>
  订单号：{{$data.external_order_sn}}<br>
  {{if !$view}}
  备注：<input type="text" name="new_admin_memo" size="50">
  <br><input type="submit" name="submit" value="添加">
  {{/if}}
</td>
</tr>
<tr>
  <td width="30px">&nbsp;</td>
  <td>
    {{$data.admin_memo}}<br>
  </td>
</tr>
</table>
</form>
</div>
