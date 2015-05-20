{{include file="header.tpl"}}
<link type="text/css" rel="stylesheet" href="/Public/css/groupon.css">


<div class="tuantopadv">
	<a href="#"></a>
</div>
<div class="groupon_head clearfix" style="padding-top:20px;">
	<div class="limitbuy_nav">
		<ul>
			<li>
				<a href="/tuan/" class="cur">今日团购</a>
			</li>
		</ul>
	</div>
</div>

<div class="groupon">
	<div class="tuan_attr clearfix">
		<div class="databox">
			<h1><a title="{{$vo.title}}" id="titleHref_1" href="#">{{$vo.title}}</a></h1>
			<div class="tuan_price" id="bg_1">
				<span class="price">¥<b>{{$vo.price}}</b></span>
				<span class="btn"><a id="tuan_buy" href="javascript:void(0)" onclick="addCart({{$data.tuan_goods_id}})" class="buynow"></a></span>
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
				<div class="buynumber clearfix" style="border-bottom:1px solid #EEE2D5;">
					<span class="txt">数 量：</span>
					<a href="javascript:void();" class="reduce">-</a>
					<input type="text" id="canBuy" value="1" onblur="check(this);" autocomplete="off">
					<a href="javascript:void();" class="add">+</a>
					<span class="jian">件</span>
				</div>
				<!--
				<div time="{{$vo.end_time}}" class="clock"></div>
					-->
				<div class="peo">
					<em></em>
					<span><strong>{{$vo.alt_count}}</strong>人已购买</span>
					<span class="secc">团购活动进行中，立即去参与</span>
				</div>
			</div>
		</div>
		<div class="share">
			<h2 class="txt"></h2>
			<div class="bshare-custom" style="text-align: center">
				<div class="bsPromo bsPromo2"></div>
				<ul>
					<li>
						<a href="javascript:openSina();" rel="nofollow"><img src="{{$imgBaseUrl}}/Public/img/detail_icons01.gif"></a>
					</li>
					<li>
						<a href="javascript:openQZone();" rel="nofollow"><img src="{{$imgBaseUrl}}/Public/img/detail_icons02.gif"></a>
					</li>
					<li>
						<a href="javascript:openWangyi();" rel="nofollow"><img src="{{$imgBaseUrl}}/Public/img/detail_icons04.gif"></a>
					</li>
					<li>
						<a href="javascript:openRenrRen();" rel="nofollow"><img src="{{$imgBaseUrl}}/Public/img/detail_icons03.gif"></a>
					</li>
					<li>
						<a href="javascript:openQQ();" rel="nofollow"><img src="{{$imgBaseUrl}}/Public/img/detail_icons05.gif"></a>
					</li>
					<li>
						<a href="javascript:openKaixin();" rel="nofollow"><img src="{{$imgBaseUrl}}/Public/img/detail_icons06.gif"></a>
					</li>
				</ul>
			</div>

		</div>
		<div class="showbox">
			<div class="pic">
				<a id="picHref_1" href="/tuan/detail{{$vo.tuan_id}}.html">
					{{html type="img" src=$vo.main_img|default:$vo.goods_img w=400 h=400 alt=$vo.title}}
				</a>
			</div>
		</div>
	</div>

	<div class="logLayer" id="diyLoginLayer" style="display:none;"></div>
	<div class="tuan_detail clearfix">
		<!--tab切换菜单-->
		<div class="tabMenu">
			<ul>
				<li style="display: none;" id="atrrtabDiv">
					<a href="javascript:changeDiv('attrDiv','descDiv','attr_a','desc_a')" id="attr_a" class="cur" rel="nofollow">商品属性</a>
				</li>
				<li id="desctabDiv">
					<a class="cur" href="javascript:changeDiv('descDiv','attrDiv','desc_a','attr_a')" id="desc_a" rel="nofollow">商品描述</a>
				</li>
			</ul>
		</div>
		<!--tab切换菜单-->
		<!--商品属性-->
		<div class="proInfo" id="attrDiv" style="display: none">
			<h3>商品属性</h3>
			<div class="attr">
				<div class="cont_l"><img src="{{$imgBaseUrl}}/img/2012/12/29/740051_220X220.jpg" alt="仅售249元，抢购市场价363元汉诺金克尔松珊瑚片(140片/瓶)，为身体补充钙质，强健骨骼！">
				</div>
				<div class="cont_r">
					<h2>仅售249元，抢购市场价363元汉诺金克尔松珊瑚片(140片/瓶)，为身体补充钙质，强健骨骼！</h2>
					<ul></ul>
				</div>
			</div>
		</div>

		<!--end商品属性-->
		<!--描述-->
		<div class="proInfo" id="descDiv" style="">
			<h3>商品信息</h3>
			<div class="info_box">

				{{if $vo.tuan_goods_description}}
				{{$vo.tuan_goods_description}}
				{{else}}
				{{$vo.goods_description}}
				{{/if}}
			</div>
		</div>
		<!--end商品描述-->
	</div>

	<div class="tuan_sub">
		<!--右边 商城推荐-->
		<div class="sub_commend clearfix">
			<h2><span>商城热荐</span></h2>
			<ul>

				{{foreach from=$gooslist item=item}}
				<li>
					<div class="picbox">
						<p class="pic">
							<a href="/goods-{{$item.goods_id}}.html" target="_blank"><img width="100" height="100" src="{{$imgBaseUrl}}/{{$item.goods_img}}" alt="{{$item.goods_name}}"></a>
						</p>
					</div>
					<div class="rinfo">
						<p class="title">
							<a href="/goods-{{$item.goods_id}}.html" target="_blank" title="{{$item.goods_name}}">{{$item.goods_name}}</a>
						</p>
						<p class="price">
							¥<span>{{$item.price}}</span>
						</p>
					</div>
				</li>
				{{/foreach}}


			</ul>
		</div>
		<!--end 右边 商城推荐-->

		<div class="sub_tuan">
			<h3 id="index"><span class="txt">今日团购商品</span><span class="slide_icon"><a class="s_l" href="javascript:changeTuan(1);"></a><a class="s_r" href="javascript:changeTuan(2);"></a></span></h3>
			{{foreach from=$todTuanlist item=item key=key}}
				{{if $key < 1}}
			<div class="con" style="display: block;">
				<div class="picbox">
					<p class="pic">
						<a href="/tuan/detail{{$item.tuan_id}}.html"><img width="160" height="160" src="{{$imgBaseUrl}}/{{$item.goods_img}}" alt="{{$item.title}}"></a>
					</p>
				</div>
				<div class="title">
					<a href="/tuan/detail{{$item.tuan_id}}.html" title="{{$item.title}}">{{$item.title}}</a>
				</div>
				<div class="buybox">
					<span class="price">¥{{$item.price}}</span>
					<span class="btn"><a href="/tuan/detail{{$item.tuan_id}}.html"></a></span>
				</div>
				<div class="rebate clearfix">
					<ul>
						<li>
							市场价
							<br>
							<span><s>¥{{$item.market_price}}</s></span>
						</li>
						<li>
							节省
							<br>
							<span>¥{{math equation="x - y" x=$item.market_price y=$item.price}}</span>
						</li>
						<li class="salenum">
							已出售
							<br>
							<span>{{$item.alt_count}}件</span>
						</li>
					</ul>
				</div>
			</div>
				{{else}}
					<div class="con" style="display: none;">
				<div class="picbox">
					<p class="pic">
						<a href="/tuan/detail{{$item.tuan_id}}.html"><img width="160" height="160" src="{{$imgBaseUrl}}/{{$item.goods_img}}" alt="{{$item.title}}"></a>
					</p>
				</div>
				<div class="title">
					<a href="/tuan/detail{{$item.tuan_id}}.html" title="{{$item.title}}">{{$item.title}}</a>
				</div>
				<div class="buybox">
					<span class="price">¥{{$item.price}}</span>
					<span class="btn"><a href="/tuan/detail{{$item.tuan_id}}.html"></a></span>
				</div>
				<div class="rebate clearfix">
					<ul>
						<li>
							市场价
							<br>
							<span><s>¥{{$item.market_price}}</s></span>
						</li>
						<li>
							节省
							<br>
							<span>¥{{math equation="x - y" x=$item.market_price y=$item.price}}</span>
						</li>
						<li class="salenum">
							已出售
							<br>
							<span>{{$item.alt_count}}件</span>
						</li>
					</ul>
				</div>
			</div>
				{{/if}}

			{{/foreach}}




		</div>
		<!-- 今日团购商品-->


	</div>
	<div class="clear"></div>
</div>

<!-- foot start-->
{{include file="footer.tpl"}}
<!-- foot end-->
<script src="{{$imgBaseUrl}}/Public/js/jquery-1.4.2.min.js" type="text/jscript"></script>
<script src="{{$imgBaseUrl}}/Public/js/global.js" type="text/jscript"></script>
<script src="{{$imgBaseUrl}}/Public/js/groupon.js" type="text/javascript"></script>
<script>
	//加入购物车
	function addCart(id) {
		var productSn = '{{$vo.goods_sn}}';
		$.ajax({
			url : '/goods/check',
			data : {
				product_sn : productSn,
				number : 1
			},
			type : 'get',
			success : function(msg) {
				if (msg != '') {
					alert(msg);
					window.location.replace('/tuan/view/id/{{$vo.tuan_id}}');
				} else {
					window.location.replace('/flow/buy/product_sn/' + productSn + '/number/1');
				}
			}
		})
	}
</script>

