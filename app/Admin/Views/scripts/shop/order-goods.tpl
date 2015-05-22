<div style="height:400px;">
<form method="post" action="/admin/shop/order-goods">
<input type="hidden" name="url" id="url" value="{{$url}}">
<input type="hidden" name="id" value="{{$order.shop_order_id}}">
<table cellpadding="0" cellspacing="0" border="0">
<tr>
<td width="30px"><br><br><br></td>
<td>
  订单号：{{$order.external_order_sn}}
</td>
</tr>
<tr>
  <td>&nbsp;</td>
  <td>
    <table cellpadding="0" cellspacing="0" border="0" class="table">
      <thead>
        <tr>
          <td><b>商品名称</b></td>
          <td><b>商品编号</b></td>
          <td><b>数量</b></td>
        </tr>
      </thead>
      <tbody>
        {{foreach from=$order.goods item=goods}}
        <tr>
          <td>{{$goods.shop_goods_name}}</td>
          <td>{{$goods.goods_sn}}</td>
          <td><input type="text" name="number_{{$goods.id}}" size="1" value="{{$goods.number}}"></td>
        </tr>
        {{/foreach}}
        <tr>
          <td>添加商品</td>
          <td><input type="text" name="goods_sn_0" size="5"></td>
          <td><input type="text" name="number_0" size="1"></td>
        </tr>
      </tbody>
    </table>
  </td>
</tr>
<tr>
  <td>&nbsp;</td>
  <td><br><input type="submit" name="submit" value="修改"></td>
</tr>
</table>
</form>
</div>
