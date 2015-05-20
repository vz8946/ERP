<table cellpadding="0" cellspacing="0" border="0" width="100%" id="discountTable" class="table_form">
<tbody>
<tr>
<td width="50%">购物价格满: <input type="text" size="10" maxlength="10" name="price" value="{{$offers.config.price}}" /> 立减: <input type="text" size="5" maxlength="10" name="minus" value="{{$offers.config.minus}}" />
最高上限: <input type="text" size="5" maxlength="10" name="max_num" value="{{$offers.config.max_num}}" />
</td>
<td>是否叠加</td>
<td width="35%" align="left"> 
	<input type="radio" name="overlay" value="1" {{if $offers.config.overlay eq 1}}checked{{/if}}/>
	是 <input type="radio" name="overlay"  value="0"  {{if $offers.config.overlay eq 0}}checked{{/if}}/>否
 </td>
</tr>

<tr>
<td width="35%" align="left"> 允许的商品范围<input type="hidden" name="allGoods" id="allGoods" value="{{$offers.config.allGoods}}" />
  <input type="button" value="设置商品范围" onclick="openAllGoodsWin('checkbox', 'allGoods', '{{$offers.offers_type}}')" />
 </td>
<td>
不允许的商品范围
<input type="hidden" name="noallGoods" id="noallGoods" value="{{$offers.config.noallGoods}}" />
</td>
<td width="" align="left"> 
  <input type="button" value="设置商品范围" onclick="openAllGoodsWin('checkbox', 'noallGoods', '{{$offers.offers_type}}')" />
 </td>
</tr>

<tr>
<td align="left"  colspan="3">不计算组合商品价格 :
	<input type="radio" name="minus_package" value="1" {{if $offers.config.allow_package eq 1}}checked{{/if}}/>
	是
   <input type="radio" name="minus_package"  value="0"  {{if $offers.config.allow_package eq 0}}checked{{/if}}/>否
 </td>
</tr>




</table>
<script>
function offersSubmit()
{
    var frm = $('myForm');
    var msg = '';

    if (!/^[\d|\.|,]+$/.test(frm.price.value) || !/^[\d|\.|,]+$/.test(frm.minus.value)) {
        msg += '请填写正确的价格!\n';
    }
    return msg;
}
</script>