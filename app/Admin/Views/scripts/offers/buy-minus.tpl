<table cellpadding="0" cellspacing="0" border="0" width="100%" id="discountTable" class="table_form">
<tbody>
<tr>
<td width="10%"><input type="hidden" name="allGoods" id="allGoods" value="{{$offers.config.allGoods}}" /></td>
<td><input type="button" value="设置商品范围" onclick="openAllGoodsWin('checkbox', 'allGoods', '{{$offers.offers_type}}')" /></td>
</tr>
<tr>
<td width="10%"><input type="hidden" name="allMinusGoods" id="allMinusGoods" value="{{$offers.config.allMinusGoods}}" /></td>
<td><input type="button" value="设置立减商品范围" onclick="openAllGoodsWin('checkbox', 'allMinusGoods')" /></td>
</tr>
<tr>
<td width="10%">立减价格 *</td>
<td><input type="text" name="minus" value="{{$offers.config.minus}}" /></td>
</tr>
</table>
<script>
function offersSubmit()
{
    var frm = $('myForm');
    var msg = '';
    
    if (!/^[\d|\.|,]+$/.test(frm.minus.value)) {
        msg += '请填写正确的立减价格!\n';
    }
    return msg;
}
</script>