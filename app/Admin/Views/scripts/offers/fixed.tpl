<table cellpadding="0" cellspacing="0" border="0" width="100%" id="discountTable" class="table_form">
<tbody>
<tr>
  <td width="10%">数量上限</td>
  <td width="40%">
    <input type="text" name="number" size="2" value="{{if $offers.config.number eq ''}}0{{else}}{{$offers.config.number}}{{/if}}">
  </td>
  <td width="10%">仅适用新注册会员</td>
  <td>
    <input type="radio" name="only_new_member" value="1" {{if $offers.config.only_new_member eq '1'}}checked{{/if}}>是
    <input type="radio" name="only_new_member" value="0" {{if $offers.config.only_new_member ne '1'}}checked{{/if}}>否
  </td>
<tr>
<tr>
  <td width="10%">每周特价</td>
  <td width="40%" >
    <input type="checkbox" name="sale_weekly" value="1" {{if $offers.config.sale_weekly eq '1'}}checked{{/if}} disabled>
  </td>
 <td>是否免运费 *</td>
 <td>
   <input name="freight" type="radio" value="1" {{if $offers.config.freight eq 1}}checked{{/if}} />是
   <input name="freight" type="radio" value="2" {{if $offers.config.freight eq 2 || !$offers.config.freight}}checked{{/if}} />否
 </td>
</tr>
<td width="10%">
  <input type="hidden" name="allDiscount" id="allDiscount" value="{{$offers.config.allDiscount}}" />
  <input type="hidden" name="allGroupGoods" id="allGroupGoods" value="{{$offers.config.allGroupGoods}}" />
</td>
<td colspan=3>
  <input type="button" value="设置商品价格" onclick="openAllGoodsWin('text', 'allDiscount')" />
  <input type="button" value="设置组合商品价格" onclick="openAllGroupGoodsWin('text', 'allGroupGoods')" />
</td>
</tr>
</table>
<script>
function offersSubmit()
{
    var msg = '';
    return msg;
}
</script>