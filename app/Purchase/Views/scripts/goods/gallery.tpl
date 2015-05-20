{{strip}}
<div class="position">
	<b><a href="/" title="垦丰商城">垦丰商城</a></b>&nbsp;&nbsp;
	<a href="/">首页</a>{{$cat_crumbs}}
</div>

<div class="wrap">
	<div class="list_main">
		<div class="product-right">
			<div class="product-filter">
				<div class="product-filter-title">
					<div class="lfloat greenColor"><h1 style="display: inline-block;font-weight: bold;float: left;">{{$cat_name}}</h1><span style="font-weight: bold;float: left;">&nbsp;-&nbsp;产品筛选</span></div><span class="rfloat" id="show-filter"></span>
				</div>
				<div class="product-filter-content">
					<dl>
						<dd class="filter-name">
							品牌:
						</dd>
						<dd class="filter-info">
							{{foreach from=$filter_brand item=v key=k}}
							<a href="{{$v.url}}" class="{{if $v.is_c}}all_screen{{/if}}">{{$v.brand_name}}</a>&nbsp;
							{{/foreach}}
						</dd>
					</dl>
					<dl style="border-top:1px dashed #d9d9d9">
						<dd class="filter-name">
							价格:
						</dd>
						<dd class="filter-info">
							{{foreach from=$filter_price item=v key=k}}
							<a href="{{$v.url}}" class="{{if $v.is_c}}all_screen{{/if}}">{{$v.price_name}}</a>&nbsp;
							{{/foreach}}
						</dd>
					</dl>
				</div>
			</div>
			<div class="list-bar">
				<span style="float: left;">排序：</span>
				{{foreach from=$filter_sort item=v key=k}}
				<a href="{{$v.url}}"  class="{{$v.sortclass}}" title="{{$v.sortname}}" >{{$v.sortname}}<span class="product-asc"></span></a>
				{{/foreach}}
			</div>
			<div class="product-list-list">
				{{foreach from=$list_goods item=v key=k}}
				<ul style="overflow: hidden;">
					<li class="product-list-goods-img">
						<a title="{{$v.goods_name}}" href="/b-{{$v.as_name|default:'jiankang'}}/detail{{$v.goods_id}}.html" target="_blank"><img width="168" height="168" border="0"
						alt="{{$v.goods_name}}" src="{{$imgBaseUrl}}/{{$v.goods_img|replace:'.':'_380_380.'}}"  /></a>
					</li>
					<li class="product-list-goods-info" style="height:40px; ">
						<a title="{{$v.goods_name}}" href="/b-{{$v.as_name|default:'jiankang'}}/detail{{$v.goods_id}}.html"  target="_blank" style="color:#333333;">{{$v.goods_name}}</a>
					</li>
					<li class="product-list-goods-info" style="text-align:left;">
						<span class="throughtText" style="font-style:italic;">市场价：{{$v.market_price}}元</span>
					</li>
					<li id="org_price2" class="red">
						<span>内 购 价</span>: <span style="color: red;font-weight: bold;">￥{{$v.staff_price}}</span>
					</li>	
				</ul>
				{{/foreach}}
			</div><!-- end 产品列表></div> -->
			<div class="product-split-page pagenav1">
				{{$pagenav}}
			</div>
		</div>
	</div>
	<!--*************-->
	<div class="list_side">
		<div class="mod">
			<h2 class="stitle">营养保健品</h2>
			<div class="conts">
				<ul class="keylist">
					{{foreach from=$cat_menu item=v key=k}}
					<li>
						<a href="/gallery-{{$v.cat_id}}.html" >{{$v.cat_name}}</a>
					</li>
					{{/foreach}}
				</ul>
			</div>
		</div>

		{{if $buy_relation}}
		<div class="mod">
			<h2 class="stitle2">浏览本目录的顾客购买过</h2>
			<div class="conts">
				<ul class="prolist">
					{{foreach from=$buy_relation item=v key=k}}
					<li>
						<div class="pics">
							<div class="wh160 verticalPic">
								<a href="/b-{{$v.as_name|default:'jiankang'}}/detail{{$v.goods_id}}.html" title="{{$v.goods_name}}" target="_blank" ><img alt="{{$v.goods_name}}" 
									src="{{$imgBaseUrl}}/{{$v.goods_img|replace:'.':'_380_380.'}}" height="160" width="160" /></a>
							</div>
						</div>
						<div class="txt">
							<a href="/b-{{$v.as_name|default:'jiankang'}}/detail{{$v.goods_id}}.html" title="{{$v.goods_name}}" target="_blank">{{$v.goods_name}}</a>
						</div>
						<div class="Sprice">
							¥<span>{{$v.staff_price}}</span>
						</div>
					</li>
					{{/foreach}}
				</ul>
			</div>
		</div>
		{{/if}}

		{{if $view_relation}}
		<div class="mod">
			<h2 class="stitle2">浏览本目录的顾客浏览过</h2>
			<div class="conts">
				<ul class="prolist">
					{{foreach from=$view_relation item=v key=k}}
					<li>
						<div class="pics">
							<div class="wh160 verticalPic">
								<a href="/b-{{$v.as_name|default:'jiankang'}}/detail{{$v.goods_id}}.html" title="{{$v.goods_name}}" target="_blank"><img alt="{{$v.goods_name}}" 
									src="{{$imgBaseUrl}}/{{$v.goods_img|replace:'.':'_180_180.'}}" height="160" width="160" /></a>
							</div>
						</div>
						<div class="txt">
							<a href="/b-{{$v.as_name|default:'jiankang'}}/detail{{$v.goods_id}}.html" title="{{$v.goods_name}}"  target="_blank">{{$v.goods_name}}</a>
						</div>
						<div class="Sprice">
							¥<span>{{$v.staff_price}}</span>
						</div>
					</li>
					{{/foreach}}
				</ul>
			</div>
		</div>
		{{/if}}

		{{if $similar_relation}}
		<div class="mod">
			<h2 class="stitle2">同类商品推荐</h2>
			<div class="conts">
				<ul class="prolist">
					{{foreach from=$similar_relation item=v key=k}}
					<li>
						<div class="pics">
							<div class="wh160 verticalPic">
								<a href="/b-{{$v.as_name|default:'jiankang'}}/detail{{$v.goods_id}}.html" title="{{$v.goods_name}}" target="_blank"><img alt="{{$v.goods_name}}" 
									src="{{$imgBaseUrl}}/{{$v.goods_img|replace:'.':'_180_180.'}}" height="160" width="160" /></a>
							</div>
						</div>
						<div class="txt">
							<a href="/b-{{$v.as_name|default:'jiankang'}}/detail{{$v.goods_id}}.html" title="{{$v.goods_name}}" target="_blank">{{$v.goods_name}}</a>
						</div>
						<div class="Sprice">
							¥<span>{{$v.staff_price}}</span>
						</div>
					</li>
					{{/foreach}}
				</ul>
			</div>
		</div>
		{{/if}}

		<div class="mod">
			<h2 class="stitle2"><a onclick="clearCook('/clearhistory.html',this);" href="javascript:void(0);">清空</a>历史浏览记录</h2>
			<div class="conts" id="historyBox">
				<ul class="hislist">
					{{if $history}}
					{{foreach from=$history item=v key=k}}
					<li class="clearfix">
						<div class="img">
							<div class="wh60 verticalPic">
								<a href="/b-{{$v.as_name|default:'jiankang'}}/detail{{$v.goods_id}}.html" title="{{$v.goods_name}}" target="_blank"><img src="{{$imgBaseUrl}}/{{$v.goods_img|replace:'.':'_380_380.'}}" alt="{{$v.goods_name}}" width="60" height="60"></a>
							</div>
						</div>
						<div class="txt">
							<p class="title">
								<a href="/b-{{$v.as_name|default:'jiankang'}}/detail{{$v.goods_id}}.html" title="{{$v.goods_name}}" target="_blank">{{$v.goods_name}}</a>
							</p>
							<p class="Sprice">
								￥<span>{{$v.staff_price}}</span>
							</p>
						</div>
					</li>
					{{/foreach}}
					{{else}}
					<div style="padding:10px;">暂无浏览记录！</div>
					{{/if}}
				</ul>
			</div>
		</div>
	</div>
	<!--*************-->
	<div class="clear" style="clear: both;"></div>
	
</div>

<script>$(function(){
	$('.product-list-list').find('ul').hover(function(){
		$(this).addClass('g-h');
	},function(){
		$(this).removeClass('g-h');
	});
});</script>
{{/strip}}

<script>
  	//清空浏览记录
		function clearCook(url,elt) {
			$.ajax({
				type : "GET",
				cache : false,
				url : url,
				success : function(msg) {
					$(elt).parent().parent().find('.conts').empty().html("<div style='color:#999999;padding:10px;'>暂无浏览记录！</div>");;
				}
			});
		}
</script>
