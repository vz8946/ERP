<table cellpadding="0" cellspacing="0" border="0" width="100%" id="discountTable" class="table_form">
<tbody>
<tr>
</tr>
<tr>
<td width="10%">折扣商品分组 *</td>
<td width="40%">
    <select name="number" onchange="makeGoodsRange(this.value)">
        <option value="0">请选择</option>
        <option value="1" {{if $offers.config.number eq 1}}selected{{/if}}>1</option>
        <option value="2" {{if $offers.config.number eq 2}}selected{{/if}}>2</option>
        <option value="3" {{if $offers.config.number eq 3}}selected{{/if}}>3</option>
        <option value="4" {{if $offers.config.number eq 4}}selected{{/if}}>4</option>
        <option value="5" {{if $offers.config.number eq 5}}selected{{/if}}>5</option>
        <option value="6" {{if $offers.config.number eq 6}}selected{{/if}}>6</option>
        <option value="7" {{if $offers.config.number eq 7}}selected{{/if}}>7</option>
        <option value="8" {{if $offers.config.number eq 8}}selected{{/if}}>8</option>
        <option value="9" {{if $offers.config.number eq 9}}selected{{/if}}>9</option>
    </select>
</td>
<td colspan="2">
<input type="checkbox" name="loop_discount" value="1" {{if $offers.config.loop_discount eq 1}}checked{{/if}}> 第N件的倍数都启用折扣
</td>
</tr>
<tr>
<td width="10%"></td>
<td id="goodsConfig" colspan="3">
{{foreach from=$offers.config.allGoods item=goods name=goods}}
{{assign var="index" value=$smarty.foreach.goods.iteration-1}}
<p>
  <input type="hidden" name="allGoods[]" id="allGoods{{$smarty.foreach.goods.iteration}}" value="{{$goods}}" /><input type="button" value="设置分组{{$smarty.foreach.goods.iteration}}商品" onclick="openAllGoodsWin('checkbox', 'allGoods{{$smarty.foreach.goods.iteration}}')" />
  第 <input type="text" size="2" name="allIndex[]" id="allIndex{{$smarty.foreach.goods.iteration}}" value="{{$offers.config.allIndex.$index}}"> 件 折扣率
  <input type="text" size="2" name="allDiscount[]" id="allDiscount{{$smarty.foreach.goods.iteration}}" value="{{$offers.config.allDiscount.$index}}"> (0-10)
</p>
{{/foreach}}
</td>
</tr>
</table>
<script>
function makeGoodsRange(num)
{
    var config = '';
    var orgNum = $('goodsConfig').getElements('p').length;
    
    if (orgNum < num) {
        for (var i = orgNum + 1; i <= num; i++)
        {
            config += '<p><input type="hidden" name="allGoods[]" id="allGoods' + i + '" value="" /><input type="button" value="设置分组' + i + '商品" onclick="openAllGoodsWin(\'checkbox\', \'allGoods' + i + '\')" /> 第 <input type="text" size="2" name="allIndex[]" id="allIndex' + i + '"> 件 折扣率 <input type="text" size="2" name="allDiscount[]" id="allDiscount' + i + '"> (0-10)</p>';
        }
        $('goodsConfig').innerHTML += config;
    } else if (orgNum > num) {
        for (var i = orgNum; i > num; i--)
        {
            $('goodsConfig').getElements('p')[i-1].destroy();
        }
    }
}

function offersSubmit()
{
    var frm = $('myForm');
    var msg = '';
    
    if (frm.uid.value.trim() == '') {
        msg += '请填写联盟ID!\n';
    }
    
    if (frm.number.value < 1) {
        msg += '请选择商品分组数量!\n';
    }
    
    $('limit_num').value = parseFloat($('limit_num').value);
    if (isNaN($('limit_num').value)) {
        $('limit_num').value = 1;
        msg += '限购买数量必须是整数!\n';
    }
    
    return msg;
}
</script>