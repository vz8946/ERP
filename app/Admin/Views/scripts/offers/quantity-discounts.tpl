<table cellpadding="0" cellspacing="0" border="0" width="100%" id="discountTable" class="table_form">
<tbody>
<tr>
<td width="10%"><input type="hidden" name="allGoods" id="allGoods" value="{{$offers.config.allGoods}}" /></td>
<td><input type="button" value="设置商品范围" onclick="openAllGoodsWin('checkbox', 'allGoods', '{{$offers.offers_type}}')" /></td>
<td>是否免运费 *</td>
<td>
  <input name="freight" type="radio" value="1" {{if $offers.config.freight eq 1}}checked{{/if}} />是
  <input name="freight" type="radio" value="2" {{if $offers.config.freight eq 2 || !$offers.config.freight}}checked{{/if}} />否
</td>
</tr>
<tr>
<td width="10%">设置折扣组一</td>
<td><input type="text" name="discount" value="{{$offers.config.discount}}" /></td>
<td width="10%">数量限制(小于等于)</td>
<td><input type="text" name="buynum" value="{{$offers.config.buynum}}" /></td>
</tr>
<tr>
<td width="10%">设置折扣组二</td>
<td><input type="text" name="discount2" value="{{$offers.config.discount2}}" /></td>
<td width="10%">数量限制(小于等于)</td>
<td><input type="text" name="buynum2" value="{{$offers.config.buynum2}}" /></td>
</tr>
<td colspan="2">同一商品是否叠加数量：<input type="radio" name="overlay" value="1" {{if $offers.config.overlay eq 1}}checked{{/if}}/>是 <input type="radio" name="overlay"  value="0"  {{if $offers.config.overlay eq 0}}checked{{/if}}/>否</td>
</table>
<script>
function offersSubmit()
{
    var msg = '';
    return msg;
}
</script>