<table cellpadding="0" cellspacing="0" border="0" width="100%" id="discountTable" class="table_form">
<tbody>
<tr>
<td width="10%">联盟ID *</td>
<td width="40%">
  <input type="text" name="uid" value="{{$offers.config.uid}}" size="2" />&nbsp;&nbsp;
  下家编号 <input type="text" name="aid" value="{{$offers.config.aid}}" size="5" /> <font color="999999">区分同一联盟不同下家来源,可向技术人员索要</font>
</td>
<td width="10%">订单数量上限</td>
<td>
  <input name="order_num" id="order_num" value="{{if $offers.config.order_num == ''}}0{{else}}{{$offers.config.order_num}}{{/if}}" size="1" maxlength="3">  
  <font color="999999">0表示不限购</font>
</td>
</tr>

<tr>
<td width="10%">专享商品每组限购买数量 *</td>
<td>   
  <input name="limit_num" id="limit_num" value="{{if $offers.config.limit_num == ''}}20{{else}}{{$offers.config.limit_num}}{{/if}}" size="1" maxlength="3" readonly>
</td>
<td>是否免运费 *</td>
<td>
  <input name="freight" type="radio" value="1" {{if $offers.config.freight eq 1}}  checked="checked" {{/if}} />是   
  <input name="freight" type="radio" value="2" {{if $offers.config.freight eq 2}}  checked="checked" {{/if}} /> 否 </td>
</tr>
<tr>
</tr>
<tr>
<td width="10%">专享商品分组 *</td>
<td>
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
<td>专享价前置名称</td>
<td>
  <input name="show_name" id="show_name" value="{{$offers.config.show_name}}" size="8">
  <font color="999999">用于商品详细页面显示“XX专享价”</font>
</td>
</tr>
<tr>
<td width="10%"></td>
<td id="goodsConfig" colspan="3">
{{foreach from=$offers.config.allGoods item=goods name=goods}}
<p><input type="hidden" name="allGoods[]" id="allGoods{{$smarty.foreach.goods.iteration}}" value="{{$goods}}" /><input type="button" value="设置分组{{$smarty.foreach.goods.iteration}}商品" onclick="openAllGroupGoodsWin('text', 'allGoods{{$smarty.foreach.goods.iteration}}')" /></p>
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
            config += '<p><input type="hidden" name="allGoods[]" id="allGoods' + i + '" value="" /><input type="button" value="设置分组' + i + '商品" onclick="openAllGroupGoodsWin(\'text\', \'allGoods' + i + '\')" /></p>';
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