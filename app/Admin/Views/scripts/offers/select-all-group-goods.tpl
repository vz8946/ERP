<form name="discountForm" id="distcountForm">
<div style="height: 30px; float:right; padding: 0 20px 0 0; margin: 0px"><input type="button" onclick="if (storeDiscount('{{$discountinput}}')) closeAllGroupGoodsWin();" value="设置并关闭" /></div>
<div style="height: 330px; overflow: auto; clear: both">
<table cellpadding="0" cellspacing="0" border="0" width="100%" id="discountTable" class="table_form">
<tbody>
<tr style="display:{{if $offersType eq 'fixed' or $offersType eq 'exclusive'}}none{{/if}}">
<td width="10%">所有商品</td>
<td><div style="float:left">{{if $intype eq 'text'}}<input type="text" name="discount" size="10" maxlength="3" value="{{$discount}}" />{{else if $intype eq 'checkbox'}}<input type="checkbox" name="discount" value="1" {{if $discount>0}}checked=true{{/if}} />{{/if}}</div></td>
</tr>
<tr style="display:{{if $offersType eq 'fixed' or $offersType eq 'exclusive'}}none{{/if}}">
<td width="10%">类别商品</td>
<td><div class="tree_div" style="width:100px; height: 50px; float:left; background-color:#f5f5f5; border:1px solid Silver; overflow:auto;">{{$goodsCatInput}}</div></td>
</tr>
<tr>
<td width="10%">个体商品</td>
<td>
<input type="button" value=" 添加 " onclick="openGroupGoodsWin('{{$intype}}', '{{$offersType}}')" />
<div id="discountGoods" style="padding-left:5px; align:left">
{{foreach from=$goodsDiscount item=goodsDiscount name=goodsDiscount}}
    {{if $goodsDiscount.discount}}
        {{if $show_limit eq '1'}}
            <p><a href="javascript:fGo()" onclick="removeDiscountGoods(this)" title="删除"><img src="/images/admin/delete.png" border="0" /></a>
                <span style="padding-right:8px">{{$goodsDiscount.group_goods_name}}</span>
                <span style="padding-right:8px">{{$goodsDiscount.group_price}}</span>
                {{if $intype eq 'text'}}
                    <input type="text" name="goodsDiscount[{{$goodsDiscount.group_id}}]" value="{{$goodsDiscount.discount}}" size="15" id="{{$goodsDiscount.group_id}}" onchange="changePrice('{{$goodsDiscount.group_id}}', '{{$goodsDiscount.price_limit}}')" />
                {{elseif $intype eq 'checkbox'}}
                    <input type="checkbox" name="goodsDiscount[{{$goodsDiscount.group_id}}]" value="1" checked=true id="{{$goodsDiscount.group_id}}" onchange="changePrice('{{$goodsDiscount.group_id}}', '{{$goodsDiscount.price_limit}}')" />
                {{/if}}保护价:{{if $goodsDiscount.price_limit eq 0}}不限价{{else}}{{$goodsDiscount.price_limit}}{{/if}}
            </p>
        {{else}}
            <p><a href="javascript:fGo()" onclick="removeDiscountGoods(this)" title="删除"><img src="/images/admin/delete.png" border="0" /></a><span style="padding-right:8px">{{$goodsDiscount.group_goods_name}}</span><span style="padding-right:8px">{{$goodsDiscount.group_price}}</span>{{if $intype eq 'text'}}<input type="text" name="goodsDiscount[{{$goodsDiscount.group_id}}]" value="{{$goodsDiscount.discount}}" size="15" />{{elseif $intype eq 'checkbox'}}<input type="checkbox" name="goodsDiscount[{{$goodsDiscount.group_id}}]" value="1" checked=true />{{/if}}</p>
        {{/if}}
    {{/if}}
{{/foreach}}
</div>
</td>
</tr>
</table>
</div>
</form>