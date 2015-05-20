<div class="tj">
<h2><a href="goods-{{$goods.goods_id}}.html" title="{{$goods.goods_name}}">{{$goods.goods_name}}</a></h2>
		<a href="goods-{{$goods.goods_id}}.html"  title="{{$goods.goods_name}}" target="_blank"><img src="{{$imgBaseUrl}}/{{$goods.goods_img|replace:'.':'_100_100.'}}"  alt="{{$goods.goods_name}}"/></a>
        <div class="r"><strong class="blue">抢购价</strong>
        <p class="red"><strong>{{$goods.price}} </strong>({{$goods.discount}}折)</p>
		市场价：<em>{{$goods.market_price}}</em></div>
<div class="bottom"><a href="goods-{{$goods.goods_id}}.html"  target="_blank"></a><p id="countdown"></p></div>
<script type="text/javascript">
	function countdown(enddate){var t=new Date();var tT=new Date(enddate);var sg=(tT.getTime()-t.getTime());var lH=sg/3600000;var fH=Math.floor(lH);var lM=(lH-fH)*60;var fM=Math.floor(lM);var lS=(lM-fM)*60;var fS=Math.floor(lS);var fC=Math.floor((lS-fS)*1000);var sM=Math.floor(fC/10);var showTime = document.getElementById('countdown').innerHTML='剩余'+'<span>'+fH +'</span>'+'小时'+'<span>'+fM+'</span>'+'分'+'<span>'+fS+'</span>'+'秒';window.setTimeout("countdown('" + enddate + "')",100);} 
	countdown('{{$goods.to_date}}');
</script>
</div>