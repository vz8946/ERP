{{foreach from=$datas item=data}}
<li class="clear" onmouseover="document.getElementById('history-goods-{{$data.goods_id}}').style.display=''" onmouseout="document.getElementById('history-goods-{{$data.goods_id}}').style.display='none'">
<a href="/goods-{{$data.goods_id}}.html" target="_blank"><img src="{{$imgBaseUrl}}/{{$data.goods_img|replace:'.':'_60_60.'}}"  alt="{{$data.goods_name}}" ></a>
<div class="fr"><p><a href="/goods-{{$data.goods_id}}.html" target="_blank">{{$data.goods_name}}</a></p>
￥{{$data.price}}
<a onclick="addGalleryCart({{$data.goods_sn}},1)" href="javascript:void(0);" class="buttons" style="display:none" id="history-goods-{{$data.goods_id}}">+ 加入购物车</a></div></li>
{{/foreach}}