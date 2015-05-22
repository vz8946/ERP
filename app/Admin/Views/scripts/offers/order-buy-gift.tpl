<table cellpadding="0" cellspacing="0" border="0" width="100%" id="discountTable" class="table_form">
<tbody>
<tr>
<td width="10%"><input type="hidden" name="allGoods" id="allGoods" value="{{$offers.config.allGoods}}" /></td>
<td><input type="button" value="设置商品范围" onclick="openAllGoodsWin('checkbox', 'allGoods', '{{$offers.offers_type}}')" /></td>
</tr>
<tr>
<td width="10%">设置赠品ID</td>
<td><input type="text" name="product_id" value="{{$offers.config.product_id}}" /> (产品ID)</td>
</tr>
</table>
<script>
function offersSubmit()
{
    var msg = '';
    return msg;
}
</script>