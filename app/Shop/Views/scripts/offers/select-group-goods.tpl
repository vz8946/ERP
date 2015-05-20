{{if !$param.do && $param.do neq 'splitPage'}}
{{/if}}
<div class="gift_select" id="ajax_search">
<table width="100%" border="0"   border="0" id="select-goods">
        <thead>
        <tr>
          <td width="12%" align="center">赠品</td>
          <td colspan="2">商品名称</td>
        </tr>
        </thead>
        <tbody>
        {{foreach from=$goodsMessage item=goods name=goods}}
        <tr id="ajax_list{{$goods.goods_id}}">
       		<td align="center"><a href="javascript:;" id="selectButton_{{$smarty.foreach.goods.iteration}}" onclick="checkPackageGroupGoods(this.id, '{{$goods.group_id}}', '{{$boxid}}', {{$expire}}, '{{$index}}', '{{$offer_id}}')"><em>选择</em></a></td>
            <td><img src="{{$imgBaseUrl}}/{{$goods.group_goods_img|replace:'.':'_60_60.'}}" height="46" width="46"  title="{{$goods.group_goods_name}}" />  
            {{$goods.group_goods_name}}</td>
            <td>{{$goods.group_price}}元</td>

        </tr>
        {{/foreach}}
        </tbody>
    </table>    
<div class="mr10">{{$pageNav}}</div>
</div>
{{if !$param.do}}
{{/if}}