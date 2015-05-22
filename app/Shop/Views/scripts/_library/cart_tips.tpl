﻿
{{if $data || $other}}
<div id="msg_cart" class="fl"><b class="c2">该商品已成功放入购物车</b></div>
<table class="cart-goods" style="width:470px">	 
	{{if $data}}
		{{foreach from=$data item=goods}}
		<tr>
			<td width="300">
			<a href="/goods-{{$goods.goods_id}}.html" target="_blank"><img width="30" height="30" src="{{$imgBaseUrl}}/{{$goods.goods_img|replace:'.':'_60_60.'}}"/></a>
			<span><a href="/goods-{{$goods.goods_id}}.html" target="_blank" title="{{$goods.goods_name}}">{{$goods.goods_name}}</a></span></td>
			<td width="*" align="right" style="padding-right:10px;">
			<span class="c2">￥{{$goods.price}}</span> <em>×{{$goods.number}}</em><br/>
			<a class="c5"  href="javascript:;" onclick="delCartGoods({{$goods.product_id}},{{$goods.number}},'tips');return false;">删除</a></td>
		</tr>
		{{/foreach}}
	{{/if}}		
		{{if $other}}	
		{{foreach from=$other item=other}}
		<tr id="del_id_{{$other.group_id}}">
			<td width="300"><a href="/group-goods" target="_blank"><img width="30" height="30" src="{{$imgBaseUrl}}/{{$other.group_goods_img|replace:'.':'_60_60.'}}"/></a>
			<span><a href="/group-goods" target="_blank">{{$other.group_goods_name}}</a></span></td>
			<td width="*" align="right" style="padding-right:10px;">
				<span class="c2">￥{{$other.group_price}}</span> <em>×{{$other.number}}</em><br/>
				<a  class="c5" href="javascript:;" onclick="delGroupGoods({{$other.group_id}},'tips');">删除</a></td>
		</tr>
		{{/foreach}}	
	{{/if}}		
	</table>
{{/if}}

 {{if $data || $other}}
	    <div class="fr" style="padding:10px 30px 10px 0;">
		<div class="tot" >
		{{if $offers}}
		   {{foreach from=$offers item=tmp}}
			{{foreach from=$tmp item=o}}
			{{if $o.offers_type=='minus'}}
			<span class="fb">活动</span>(<span class="c2">{{$o.offers_name}}</span>)：<span class="c2">{{$o.price}}</span>元 <br/>
			{{/if}}
			{{/foreach}}
			{{/foreach}}
	    {{/if}}			
		购物车内共{{$number}}件商品总计：<b class="c2 f14">￥{{$amount|number_format:2}}</b>
		</div>
					
		<div id="checknow" style="text-align:center;clear:both;padding-top:5px">
		  <a href="/flow/index"><img src="/_static/images/shop/btn_check_right_now.jpg" /></a>&nbsp;&nbsp;
		  <a style="position:relative;top:-12px;" href="javascript:location.reload();">继续购物&raquo;&raquo;</a>
		</div>
		
	{{else}}	 
	 <div style="text-align:center;"><b class="c2">您的购物车中没有任何商品</b> </div>
{{/if}}
</div>