{{include file="header.tpl"}}
<link href="/styles/shop/tuan.css" rel="stylesheet" type="text/css" />
<link type="text/css" rel="stylesheet" href="/Public/css/groupon.css">

<div class="position wbox">
	<span class="none">当前位置：<a href="/">首页</a> &gt; </span><span class="p_blue">团购</span>
</div>

<div class="tuantopadv">
	<a href="#"></a>
</div>
<div class="groupon_head clearfix">
	<div class="limitbuy_nav">
		<ul>
			<li>
				<a href="/tuan/">今日团购</a>
			</li>
			<li>
				<a href="/tuan/next.html">下期预告</a>
			</li>
			<li>
				<a class="cur" href="/tuan/prev.html">往期团购</a>
			</li>
			<li>
				<a href="/tuan/help.html">团购帮助</a>
			</li>
		</ul>
	</div>
</div>

<div class="groupon">
	<div class="tuan_list clearfix">
		<ul>
			{{foreach from=$datas item=vo key=k }}
			<li>
				<div class="title">
					<p>
						<b>{{$vo.discount}}折：</b><a title="{{$vo.title}}" target="_blank" href="/goods-{{$vo.gid}}.html">{{$vo.title}}</a>
					</p>
				</div>
				<div class="picbox">
					<em><b>{{$vo.discount}}</b>折</em>
					<p class="pic">
						<a target="_blank" href="/goods-{{$vo.gid}}.html"><img alt="{{$vo.title}}" src="{{$imgBaseUrl}}/{{$vo.main_img}}" width="300" height="300"></a>
					</p>
				</div>
				<div class="buybox">
					<div class="pricebox soldout">
						<span class="price">¥<b>{{$vo.price}}</b><em></em></span>
						<span class="btn"><a target="_blank" href="/goods-{{$vo.gid}}.html" ></a></span>
					</div>
					<div class="discount">
						<p>
							市场价：<s>¥{{$vo.market_price}}</s>
						</p>
						<p>
							折扣：{{$vo.discount}}折
						</p>
						<p>
							节省：<b>¥{{$vo.market_price-$vo.price}}</b>
						</p>
					</div>
				</div>
				<div class="time">
					<span class="clock">团购日期：{{$vo.end_time}}</span>
					<span class="amount"><b>{{$vo.alt_count}}</b>人已购买</span>
				</div>
				<div class="bottomShadow"></div>
			</li>
			{{/foreach}}

		</ul>
	</div>
</div>

{{include file="footer.tpl"}}

<script src="{{$imgBaseUrl}}/Public/js/global.js" type="text/jscript"></script>
<script src="{{$imgBaseUrl}}/Public/js/groupon.js" type="text/javascript"></script>
