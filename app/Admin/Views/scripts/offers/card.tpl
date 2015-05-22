<table cellpadding="0" cellspacing="0" border="0" width="100%" id="discountTable" class="table_form">
<tbody>
<tr>
<td width="10%">卡面额 *</td>
<td width="40%"><input type="text" name="price" value="{{$offers.config.price}}" /></td>
<td width="10%"><input type="hidden" name="allGoods" id="allGoods" value="{{$offers.config.allGoods}}" /></td>
<td width="40%"><input type="button" value="设置可用卡商品" onclick="openAllGoodsWin('checkbox', 'allGoods')" /></td>
</tr>
</table>
<script>
function offersSubmit()
{
    var frm = $('myForm');
    var msg = '';
    
    if (!/^[\d|\.|,]+$/.test(frm.price.value)) {
        msg += '请填写正确的卡面额!\n';
    }
    return msg;
}
</script>