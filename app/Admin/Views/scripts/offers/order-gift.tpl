<table cellpadding="0" cellspacing="0" border="0" width="100%" id="discountTable" class="table_form">
<tbody>
<tr>
<td width="10%">订单最小金额</td>
<td width="40%"><input type="text" name="min_price" size="11" value="{{$offers.config.min_price}}" /></td>
<td width="10%">订单最大金额</td>
<td width="40%"><input type="text" name="max_price" size="11" value="{{$offers.config.max_price}}" />不填写默认到无限大</td>
</tr>
</table>
<table cellpadding="0" cellspacing="0" border="0" width="100%" id="discountTable" class="table_form">
<tbody>
<tr>
<td width="10%">联盟ID</td>
<td>
  <input type="text" name="uid" value="{{$offers.config.uid}}" size="2"/>&nbsp;&nbsp;
  下家编号 <input type="text" name="aid" value="{{$offers.config.aid}}" size="5" /> <font color="999999">区分同一联盟不同下家来源,可向技术人员索要</font>
</td>
<td width="10%">仅适用新注册会员</td>
  <td width="40%">
    <input type="radio" name="only_new_member" value="1" {{if $offers.config.only_new_member eq '1'}}checked{{/if}}>是
    <input type="radio" name="only_new_member" value="0" {{if $offers.config.only_new_member ne '1'}}checked{{/if}}>否
  </td>
</tr>
<tr>

<td>购买指定商品</td>
<td>
<input type="hidden" name="allGoods" id="allGoods" value="{{$offers.config.allGoods}}"/>
<input type="button" value="设置商品范围" onclick="openAllGoodsWin('checkbox', 'allGoods')" />
<input type="hidden" name="allGroupGoods" id="allGroupGoods" value="{{$offers.config.allGroupGoods}}"/>
<input type="button" value="设置组合商品范围" onclick="openAllGroupGoodsWin('checkbox', 'allGroupGoods')" />
</td>
<td>赠送类型:</td> 
<td>
{{assign var="type" value=$offers.config.give_type|default:'goods'}}
<label><input type="radio"  {{if $type eq 'goods'}}checked{{/if}}   onclick="changeType(this.value)"   name="give_type" value="goods" />&nbsp;单品</label>   
<label><input name="give_type" {{if $type eq 'group'}}checked {{/if}}  onclick="changeType(this.value)"     value="group" type="radio"/>套组</label>
</td>
</tr>

<tr  id="giftConfig-box"  {{if $type neq 'goods'}}style="display:none"{{/if}}>
<td width="10%">赠送单品：<select name="number" onchange="makeGiftRange(this.value,'goods')">
        <option value="0">不设置</option>
        <option value="1" {{if $offers.config.number eq 1}}selected{{/if}}>1</option>
        <option value="2" {{if $offers.config.number eq 2}}selected{{/if}}>2</option>
        <option value="3" {{if $offers.config.number eq 3}}selected{{/if}}>3</option>
        <option value="4" {{if $offers.config.number eq 4}}selected{{/if}}>4</option>
        <option value="5" {{if $offers.config.number eq 5}}selected{{/if}}>5</option>
        <option value="6" {{if $offers.config.number eq 6}}selected{{/if}}>6</option>
        <option value="7" {{if $offers.config.number eq 7}}selected{{/if}}>7</option>
        <option value="8" {{if $offers.config.number eq 8}}selected{{/if}}>8</option>
        <option value="9" {{if $offers.config.number eq 9}}selected{{/if}}>9</option>
    </select></td>
<td id="giftConfig"  width="90%" colspan="3">
  {{foreach from=$offers.config.allGift item=gift name=gift}}
  {{assign var="index" value=$smarty.foreach.gift.iteration-1}}
  <p>
      <input type="hidden" name="allGift[]" id="allGift{{$smarty.foreach.gift.iteration}}" value="{{$gift}}" />
      <input type="button" value="设置赠品{{$smarty.foreach.gift.iteration}}范围" onclick="openAllGoodsWin('checkbox', 'allGift{{$smarty.foreach.gift.iteration}}')" /> 
           金额：<input type="text" size="2" name="allPrice[]" id="allPrice{{$smarty.foreach.gift.iteration}}" value="{{$offers.config.allPrice.$index}}">   
   </p> 
  {{/foreach}}  
</td>
</tr>

<tr id="groupConfig-box"  {{if $type neq 'group'}}style="display:none"{{/if}} >
<td  width="10%">赠送套装:<select name="group_number" onchange="makeGiftRange(this.value,'suit')">
        <option value="0">不设置</option>
        <option value="1" {{if $offers.config.group_number eq 1}}selected{{/if}}>1</option>
        <option value="2" {{if $offers.config.group_number eq 2}}selected{{/if}}>2</option>
        <option value="3" {{if $offers.config.group_number eq 3}}selected{{/if}}>3</option>
        <option value="4" {{if $offers.config.group_number eq 4}}selected{{/if}}>4</option>
        <option value="5" {{if $offers.config.group_number eq 5}}selected{{/if}}>5</option>
        <option value="6" {{if $offers.config.group_number eq 6}}selected{{/if}}>6</option>
        <option value="7" {{if $offers.config.group_number eq 7}}selected{{/if}}>7</option>
        <option value="8" {{if $offers.config.group_number eq 8}}selected{{/if}}>8</option>
        <option value="9" {{if $offers.config.group_number eq 9}}selected{{/if}}>9</option>
    </select></td>
<td id="groupConfig"  width="90%" colspan="3">
{{foreach from=$offers.config.allGroup item=gift name=gift}}
  {{assign var="index" value=$smarty.foreach.gift.iteration-1}}  <p>
      <input type="hidden" name="allGroup[]" id="allGroup{{$smarty.foreach.gift.iteration}}" value="{{$gift}}" />
      <input type="button" value="设置套装赠品{{$smarty.foreach.gift.iteration}}范围" onclick="openAllGroupGoodsWin('checkbox', 'allGroup{{$smarty.foreach.gift.iteration}}')" /> 
           金额：<input type="text" size="2" name="allGroupPrice[]" id="allGroupPrice{{$smarty.foreach.gift.iteration}}"  readonly value="{{$offers.config.allGroupPrice.$index}}">   
   </p> 
  {{/foreach}}  
</td>
</tr>
</table>
<script>

String.prototype.replaceAll = function(s1,s2) { 
    return this.replace(new RegExp(s1,"gm"),s2); 
}


function changeType(give_type){	
	if(give_type == 'goods')
	{
		$('giftConfig-box').setStyle('display','');
		$('groupConfig-box').setStyle('display','none');
	}else{
		$('giftConfig-box').setStyle('display','none');
		$('groupConfig-box').setStyle('display','');
	}
}

function offersSubmit()
{
    var msg = '';
    return msg;
}
function makeGiftRange(num,type)
{
    var config = '';
    if(type=='goods')
    	{
    	   var ob = $('giftConfig');
    	    var  tpm = '<p><input type="hidden" name="allGift[]" id="allGift#i#" value="" /><input type="button" value="设置赠品#i#范围" onclick="openAllGoodsWin(\'checkbox\', \'allGift#i#\')" /> 金额：<input type="text" size="2" name="allPrice[]" id="allPrice#i#"></p>';
    	   
     }else{
    	 var ob = $('groupConfig'); 
    	 var tpm= '<p><input type="hidden" name="allGroup[]" id="allGroup#i#" value="" /><input type="button" value="设置套装赠品#i#范围" onclick="openAllGroupGoodsWin(\'checkbox\', \'allGroup#i#\')" /> 金额：<input type="text" size="2" value="0" readonly name="allGroupPrice[]" id="allGroupPrice#i#"></p>';
    	 	
    }
    var orgNum = ob.getElements('p').length;
    if (orgNum < num) {
        for (var i = orgNum + 1; i <= num; i++)
        {
            config += tpm.replaceAll('#i#',i);
        }
        ob.innerHTML += config;
    } else if (orgNum > num) {
        for (var i = orgNum; i > num; i--)
        {
        	ob.getElements('p')[i-1].destroy();
        }
    }
}
</script>