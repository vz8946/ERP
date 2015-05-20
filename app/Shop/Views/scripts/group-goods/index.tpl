<link rel="stylesheet" type="text/css" href="/_static/css/list.css"/>
<link rel="stylesheet" type="text/css" href="/Public/css/sale.css"/>
<div class="position wbox">
	<b><a href="/group-goods/">组合商品</a></b><span> &gt; 最值得信赖的保健商城</span>
</div>

<div class="wbox">
	<div class="rotave">
		<ul id="adv_113_100" style="position: relative; width: 758px; height: 302px;">
			<li style="position: absolute; top: 0px; left: 0px; display: block; z-index: 4; opacity: 1; width: 758px; height: 302px;">
				<a target="_blank" href="#"><img alt="组合商品" src="/images/group.jpg"></a>
			</li>
		</ul>
	</div>
	<div class="radv218">
		<a title="基维斯蜂蜜" target="_blank" href="#"><img width="218" height="298" border="0" alt="基维斯蜂蜜" src="{{$imgBaseUrl}}/img/2012/10/31/210019_218X298.jpg"></a>
	</div>
</div>
{{strip}}
<div class="groups mar" style="width: 990px;margin: 0px auto;">
	<div class="ad" style="display:none;">
		<a href="/special-38.html" target="_blank"><img src="{{$imgBaseUrl}}/images/shop/index_ad02.jpg" /></a>
	</div>
	<ul class="clear grouplist">
		{{foreach from=$datas key=key item=data}}
		<li style="{{if $key%2 == 1}}margin-right: 0px;{{/if}}position:relative;" >
			<div style="position: absolute;left:0px;top: 0px;">
			<a style="position: absolute;left:20px;top:26px;display: block;"

			href="/groupgoods-{{$data.group_id}}.html" target="_blank"><img style="border:none;" src="{{$imgBaseUrl}}/{{$data.group_goods_img|replace:'.':'_180_180.'}}" alt="{{$data.goods_name}}"/></a>
			<p style="position: absolute;left:220px;top:24px;width: 250px;">
				<a href="/groupgoods-{{$data.group_id}}.html" target="_blank" > <span style="font-size: 1.4em;font-family: Microsoft Yahei;">{{$data.group_sale_name}}</span> </a>
			</p>

			<p style="position:absolute;left:220px;top:90px; width: 271px;background: #f5f5f5;height: 90px;text-align: left;">
				<span style="padding:5px;font-size: 12px;">&nbsp;&nbsp;市场价：¥{{$data.group_market_price}}</span>
				<span class="p1"><a href="javascript:;" onclick="addGroupCart({{$data.group_id}},'buy',1);" class="buttons btn-add-cart"></a></span>
				<span style="position: absolute;top:36px;left:18px;"><strong style="color: #fff;">¥{{$data.group_price}}</strong></span>
			</p>
			</div>
		</li>
		{{/foreach}}
		<div style="clear:both;"></div>
	</ul>
	<div style="clear:both;"></div>
	<div class="pageNav">
		{{$pageNav}}
	</div>
</div>
{{/strip}}
<script type="text/javascript">
$(function(){
		$('.grouplist').find('li').hover(function(){
			$(this).css({'border':'1px solid red'});
		},function(){
			$(this).css({'border':'1px solid #ddd'});
		});
});
</script>