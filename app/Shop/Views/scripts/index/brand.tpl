<div class="brand-city">
<script type=text/javascript src="{{$imgBaseUrl}}/Public/topic/js/jquery.slides.min.js"></script>
<div class="position wbox">
	<div class="share">
		<span>分享好友：</span>
		<a href="javascript:openSina();" rel="nofollow"><img src="{{$imgBaseUrl}}/Public/img/detail_icons01.gif"></a>
		<a href="javascript:openQZone();" rel="nofollow"><img src="{{$imgBaseUrl}}/Public/img/detail_icons02.gif"></a>
		<a href="javascript:openWangyi();" rel="nofollow"><img src="{{$imgBaseUrl}}/Public/img/detail_icons04.gif"></a>
		<a href="javascript:openRenrRen();" rel="nofollow"><img src="{{$imgBaseUrl}}/Public/img/detail_icons03.gif"></a>
		<a href="javascript:openQQ();" rel="nofollow"><img src="{{$imgBaseUrl}}/Public/img/detail_icons05.gif"></a>
		<a href="javascript:openKaixin();" rel="nofollow"><img src="{{$imgBaseUrl}}/Public/img/detail_icons06.gif"></a>
	</div>
	<b><a href="/group-goods/">品牌城</a></b><span> &gt; 最值得信赖的种业商城</span>
</div>
<div class="wbox">
	<!-- 滚动图片 -->
	<div id="slides" class="banner slides" style="height: 200px;overflow: hidden;">
		{{foreach from=$list_banner item=v key=k}}
		<a href="{{$v.url}}" target="_blank"><img alt="{{$v.name}}"
		src="{{$imgBaseUrl}}/{{$v.imgUrl}}"></a>
		{{/foreach}}
	</div>

	<script>
		$(function() {
			$('#slides').slidesjs({
				start : 1,
				play : {
					auto : true,
					interval : 3000,
					swap : true,
					effect : 'fade'
				},
				pagination : {
					active : true,
					effect : "fade"
				},
				effect : {
					fade : {
						speed : 120
					}
				},
				callback : {//回调
					start : function(c, i) {
					}
				}
			});
		});
	</script>
</div>

<div class="query wbox">
	<div class="tops">
		<h3>按首字母查找</h3>
		{{include file="_library/banner-letter.tpl"}}
	</div>
</div>

<div class="brand clearfix wbox">
	<div class="bleft">
		<a href="{{$banner_left.url}}" target="_blank" title="{{$banner_left.name}}"> <img width="200" height="160" border="0" src="{{$imgBaseUrl}}/{{$banner_left.imgUrl}}" alt="{{$banner_left.name}}"> </a>
	</div>
	<div class="bright">
		<ul>
			{{foreach from=$list_banner_right item=v key=k}}
			<li>
				<a target="_blank" title="{{$v.name}}" href="{{$v.url}}">
					<img width="120" height="60" alt="{{$v.name}}"
					src="{{$imgBaseUrl}}/{{$v.imgUrl}}"></a>
			</li>
			{{/foreach}}
		</ul>
	</div>
</div>

<div class="wbox">

	{{foreach from=$list_brand item=v key=k}}
	<div class="bitem clearfix">
		<div class="tops">
			<span class="t1">{{$v.brand_name}}</span>
			<span class="t2"></span>
		</div>
		<div class="conts">
			<div class="left">
				{{if $v.adv}}
				<a href="{{$v.adv.url}}" target="_blank"> <img width="220" height="340" border="0" src="{{$imgBaseUrl}}/{{$v.adv.imgUrl}}" alt=""></a>
				{{/if}}
			</div>
			<div class="right">
				<ul>
					{{if $v.goods}}
					{{foreach from=$v.goods item=vv key=kk}}
					<li>
						<div class="pic">
							<div class="verticalPic wh180">
								<a href="/b-{{$vv.as_name}}/detail{{$vv.goods_id}}.html" title="{{$vv.goods_name}}" target="_blank"><img width="180" height="180" src="{{$imgBaseUrl}}/{{$vv.goods_img|replace:'.':'_180_180.'}}" alt="{{$vv.goods_name}}"></a>
							</div>
						</div>
						<div class="title">
							<a href="/b-{{$vv.as_name}}/detail{{$vv.goods_id}}.html" title="{{$vv.goods_name}}" target="_blank">{{$vv.goods_name}}</a>
						</div>
						<div class="other clearfix">
							<span class="oprice">市场价：<del>¥{{$vv.market_price}}</del></span>
							<span class="discount">折扣：{{$vv.saleoff}}折</span>
						</div>
						<div class="Sprice">
							¥<span>{{$vv.price}}</span>
						</div>
						<div class="buy-btn">
							<a href="/b-{{$vv.as_name}}/detail{{$vv.goods_id}}.html" title="{{$vv.goods_name}}" target="_blank">加入购物车</a>
						</div>
					</li>
					{{/foreach}}
					{{/if}}
				</ul>
			</div>
		</div>
	</div>
	{{/foreach}}
</div>
<script>
	//品牌类别展示
	$("#letter_menu ul li a").hover(function(){
		var v = $(this).attr("value");
		var small = "#small_"+v;
		var menuList = "#menuList_"+v;
		$(small).show();
		$(menuList).show();
	},function(){
		var v = $(this).attr("value");
		var small = "#small_"+v;
		var menuList = "#menuList_"+v;
		$(menuList).mouseover(function(){
			$(small).show();
			$(menuList).show();
		}).mouseout(function(){
			$(small).hide();
			$(menuList).hide();
		});
		$(small).hide();
			$(menuList).hide();
	});
</script>
<script type=text/javascript src="{{$imgBaseUrl}}/Public/js/otherPd.js"></script>
</div>