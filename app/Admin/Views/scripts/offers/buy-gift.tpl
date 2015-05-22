<table cellpadding="0" cellspacing="0" border="0" width="100%" id="discountTable" class="table_form">
<tbody>
<tr>
<td width="10%">联盟ID</td>
<td>
  <input type="text" name="uid" value="{{$offers.config.uid}}" size="2"/>&nbsp;&nbsp;
  下家编号 <input type="text" name="aid" value="{{$offers.config.aid}}" size="5" /> <font color="999999">区分同一联盟不同下家来源,可向技术人员索要</font>
</td>
<td width="10%">是否免运费 *</td>
<td>
  <input name="freight" type="radio" value="1" {{if $offers.config.freight eq 1}}checked{{/if}} />是
  <input name="freight" type="radio" value="2" {{if $offers.config.freight eq 2 || !$offers.config.freight}}checked{{/if}} />否
</td>
<tr>
  <td width="10%">商品数量下限</td>
  <td width="40%"><input type="text" name="number" size="2" value="{{if $offers.config.number}}{{$offers.config.number}}{{else}}1{{/if}}" /></td>
  <td colspan="2"><input type="checkbox" name="loop_gift" value="1" {{if $offers.config.loop_gift eq 1}}checked{{/if}}> 第N件的倍数都启用买赠</td>
</tr>
<tr>
<td width="10%">
  <input type="hidden" name="allGoods" id="allGoods" value="{{$offers.config.allGoods}}" />
  <input type="hidden" name="allGroupGoods" id="allGroupGoods" value="{{$offers.config.allGroupGoods}}"/>
</td>
<td colspan="3">
  <input type="button" value="设置商品范围" onclick="openAllGoodsWin('checkbox', 'allGoods', '{{$offers.offers_type}}')" />
  <input type="button" value="设置组合商品范围" onclick="openAllGroupGoodsWin('checkbox', 'allGroupGoods', '{{$offers.offers_type}}')" />
</td>
</tr>
<tr>
<td width="10%">
  <input type="hidden" name="allGift" id="allGift" value="{{$offers.config.allGift}}" />
  <input type="hidden" name="allGiftGroup" id="allGiftGroup" value="{{$offers.config.allGiftGroup}}" />
</td>
<td colspan="3">
  <input type="button" value="设置赠品商品范围" onclick="openAllGoodsWin('text', 'allGift')" />
  <input type="button" value="设置赠品组合商品范围" onclick="openAllGroupGoodsWin('text', 'allGiftGroup')" />
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