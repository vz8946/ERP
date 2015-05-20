{{include file="header.tpl"}}

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
				<a class="cur" href="/tuan/next.html">下期预告</a>
			</li>
			<li>
				<a href="/tuan/prev.html">往期团购</a>
			</li>
			<li>
				<a href="/tuan/help.html">团购帮助</a>
			</li>
		</ul>
	</div>
</div>
<div class="groupon">

	{{foreach from=$datas item=vo key=k }}
	<div class="tuan_attr forecast clearfix">
		<div class="databox">
			<h1>
			<p>
				<a title="{{$vo.title}}" id="titleHref_1" href="/goods-{{$vo.gid}}.html">{{$vo.title}}</a>
			</p></h1>
			<div id="bg_1" class="tuan_price">
				<span class="price">¥<b>{{$vo.price}}</b></span>
				<span class="btn"><a class="buynow" href="/goods-{{$vo.gid}}.html" id="a_1"></a></span>
			</div>
			<div class="status clearfix">
				<div class="disc clearfix">
					<ul>
						<li>
							市场价<s>¥{{$vo.market_price}}</s>
						</li>
						<li>
							折扣<span>{{$vo.discount}}</span>
						</li>
						<li>
							节省<span>¥{{$vo.market_price-$vo.price}}</span>
						</li>
					</ul>
				</div>
				<div  class="clock" time="{{$vo.end_time}}"></div>
				<div class="peo">
					<em></em>
					<span><strong>&nbsp;&nbsp;</strong></span>
					<span class="secc">未成团，团购即将开始</span>
				</div>
			</div>
		</div>
		<div class="share">
			<h2 class="txt"></h2>
			<div style="text-align: center" class="bshare-custom">
				<div class="bsPromo bsPromo2"></div>
				<ul>
					<li>
						<a rel="nofollow" href="javascript:openSina();"><img src="{{$imgBaseUrl}}/Public/img/detail_icons01.gif"></a>
					</li>
					<li>
						<a rel="nofollow" href="javascript:openQZone();"><img src="{{$imgBaseUrl}}/Public/img/detail_icons02.gif"></a>
					</li>
					<li>
						<a rel="nofollow" href="javascript:openWangyi();"><img src="{{$imgBaseUrl}}/Public/img/detail_icons04.gif"></a>
					</li>
					<li>
						<a rel="nofollow" href="javascript:openRenrRen();"><img src="{{$imgBaseUrl}}/Public/img/detail_icons03.gif"></a>
					</li>
					<li>
						<a rel="nofollow" href="javascript:openQQ();"><img src="{{$imgBaseUrl}}/Public/img/detail_icons05.gif"></a>
					</li>
					<li>
						<a rel="nofollow" href="javascript:openKaixin();"><img src="{{$imgBaseUrl}}/Public/img/detail_icons06.gif"></a>
					</li>
				</ul>
			</div>

		</div>
		<div class="showbox">
			<div class="pic">
				<a id="picHref_1" href="/goods-{{$vo.gid}}.html"><img alt="{{$vo.title}}" src="{{$imgBaseUrl}}/{{$vo.main_img}}" width="400" height="400"></a>
			</div>
		</div>
	</div>
	{{/foreach}}

</div>

<!-- foot start-->
{{include file="footer.tpl"}}
<!-- foot end-->

<script src="{{$imgBaseUrl}}/Public/js/jquery-1.4.2.min.js" type="text/jscript"></script>
<script src="{{$imgBaseUrl}}/Public/js/global.js" type="text/jscript"></script>
<script src="{{$imgBaseUrl}}/Public/js/groupon.js" type="text/javascript"></script>

