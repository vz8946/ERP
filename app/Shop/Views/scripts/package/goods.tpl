<link rel="stylesheet" type="text/css" href="/styles/shop/package.css"/>
<div class="tckcon clear">
<div class="l"><a href="/goods/show/id/{{$data.goods_id}}" target="_blank"><img id="simage" src="{{$imgBaseUrl}}/{{$data.goods_img|replace:'.':'_180_180.'}}"/></a></div><!--tck_left end-->

<div class="r">
<h3>{{$data.goods_name}} {{$data.goods_style}}</h3>
商品编码：{{$data.goods_sn}}<br />
现价：￥{{$data.price}}
<p>{{if $data.onsale==0 }}
<input type="button" id="cartImg"  value=" 放入礼包 "  onclick="checkPackageGoods('cartImg', '{{$data.goods_sn}}')" /><a href="/goods/show/id/{{$data.goods_id}}"  target="_blank">查看商品详情</a>
{{else}}该商品已下架{{/if}}</p>
</div><!--tck_rig end-->

</div>