<table cellpadding="0" cellspacing="0" border="0" width="100%" id="discountTable" class="table_form">
<tr>
<td width="10%">折扣比率 *</td>
<td width="40%">
  <input type="text" name="discount" size="4" maxlength="2" value="{{$offers.config.discount}}" /> (0-10)
  &nbsp;&nbsp;&nbsp;
  <input type="checkbox" name="to_market_price" value="1" {{if $offers.config.to_market_price}}checked{{/if}}>按市场价
</td>
<td width="10%">是否免运费 *</td>
<td>
  <input name="freight" type="radio" value="1" {{if $offers.config.freight eq 1}}checked{{/if}} />是
  <input name="freight" type="radio" value="2" {{if $offers.config.freight eq 2 || !$offers.config.freight}}checked{{/if}} />否
</td>
</tr>
<tr>
<td width="10%"><input type="hidden" name="allDiscount" id="allDiscount" value="{{$offers.config.allDiscount}}" /></td>
<td><input type="button" value="设置商品折扣" onclick="openAllGoodsWin('checkbox', 'allDiscount')" /></td>
<td colspan="2">&nbsp;</td>
</tr>
</table>
<script>
function offersSubmit()
{
    var msg = '';
    return msg;
}
</script>