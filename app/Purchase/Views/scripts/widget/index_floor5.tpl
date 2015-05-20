<div id="wgt-index_floor5" class="widget"><div class="floor">
	<div class="col-l fl w2">
		<div class="col-l-1 fl" style="width: 222px;overflow: hidden;">
			<h3 style="background: #FF3939;"><span><img alt="" src="http://img.1jiankang.com/upload/uploadify/20130909_a228dba7822d7899.gif"/></span>传统滋补系列</h3>
			<div class="adv"><a href="/search.html?module=shop&keyword=%E9%98%BF%E8%83%B6?  &bid=47"><img alt="东阿阿胶" src="http://img.1jiankang.com/upload/uploadify/20130924_6e63896df3cc8353.jpg" /></a></div>
			<div class="keys">
								<a style="background: #FF3939;" href="/search.html?keyword=%E9%98%BF%E8%83%B6">阿胶</a>
								<a style="background: #FF3939;" href="/search.html?keyword=%E5%8F%82">参类</a>
								<a style="background: #FF3939;" href="/gallery-207.html">蜂产品</a>
								<a style="background: #FF3939;" href="/gallery-203.html">冬虫夏草</a>
								<a style="background: #FF3939;" href="/search.html?keyword=%E7%81%B5%E8%8A%9D">灵芝</a>
								<a style="background: #FF3939;" href="/search.html?keyword=%E9%BA%A6%E5%8D%A2%E5%8D%A1">麦卢卡</a>
								<a style="background: #FF3939;" href="/search.html?keyword=%E4%B8%9C%E9%98%BF%E9%98%BF%E8%83%B6">东阿阿胶</a>
								<a style="background: #FF3939;" href="/search.html?keyword=%E5%9B%BD%E8%83%B6%E5%A0%82">国胶堂</a>
								<a style="background: #FF3939;" href="/search.html?keyword=%E6%AD%A3%E5%AE%98%E5%BA%84">正官庄</a>
								<a style="background: #FF3939;" href="/search.html?keyword=%E5%A5%BD%E5%BD%93%E5%AE%B6">好当家</a>
							</div>
		</div>
		<div class="col-l-2 fl tab floor-tab" style="width: 668px;padding-left:10px;">
			<ul class="tab-h">
				<li><a href="#">阿胶</a></li>				<li><a href="#">参类</a></li>				<li><a href="#">蜂产品</a></li>							</ul>
			<div class="tab-c" style="border-color: #FF3939;">
				<div>
					<ul class="floor-goods">
						{{foreach from = $indextag.45.details item = tag }}
								<li>
								<div class="pi">
										<a href="/b-{{$tag.as_name}}/detail{{$tag.goods_id}}.html" target="_blank">
										<img _src="{{$tag.goods_img|replace:'.':'_180_180.'}}"  alt="{{$tag.goods_name}}" height="175"/>
									</a>
								</div>
								<div class="pa fs1 fb c1" title="{{$tag.goods_alt}} ">
								 <!--  {{$tag.goods_alt}} -->
								</div>
								<div class="pt">
									<a class="c3" href="/b-{{$tag.as_name}}/detail{{$tag.goods_id}}.html" target="_blank">{{$tag.goods_name}}</a>
								</div>
								<div class="pp">
									<span class="pmp fl">￥{{$tag.market_price}}</span>
									<span class="prp fr c2 fs3">￥{{$tag.staff_price}}</span>
								</div>										
							</li>
						{{/foreach}}
											</ul>								
				</div>
				<div>
					<ul class="floor-goods">
						{{foreach from = $indextag.46.details item = tag }}
								<li>
								<div class="pi">
										<a href="/b-{{$tag.as_name}}/detail{{$tag.goods_id}}.html" target="_blank">
										<img _src="{{$tag.goods_img|replace:'.':'_180_180.'}}"  alt="{{$tag.goods_name}}" height="175"/>
									</a>
								</div>
								<div class="pa fs1 fb c1" title="{{$tag.goods_alt}} ">
								 <!--  {{$tag.goods_alt}} -->
								</div>
								<div class="pt">
									<a class="c3" href="/b-{{$tag.as_name}}/detail{{$tag.goods_id}}.html" target="_blank">{{$tag.goods_name}}</a>
								</div>
								<div class="pp">
									<span class="pmp fl">￥{{$tag.market_price}}</span>
									<span class="prp fr c2 fs3">￥{{$tag.staff_price}}</span>
								</div>										
							</li>
						{{/foreach}}
											</ul>								
				</div>
				<div>
					<ul class="floor-goods">
						{{foreach from = $indextag.47.details item = tag }}
								<li>
								<div class="pi">
										<a href="/b-{{$tag.as_name}}/detail{{$tag.goods_id}}.html" target="_blank">
										<img _src="{{$tag.goods_img|replace:'.':'_180_180.'}}"  alt="{{$tag.goods_name}}" height="175"/>
									</a>
								</div>
								<div class="pa fs1 fb c1" title="{{$tag.goods_alt}} ">
								 <!--  {{$tag.goods_alt}} -->
								</div>
								<div class="pt">
									<a class="c3" href="/b-{{$tag.as_name}}/detail{{$tag.goods_id}}.html" target="_blank">{{$tag.goods_name}}</a>
								</div>
								<div class="pp">
									<span class="pmp fl">￥{{$tag.market_price}}</span>
									<span class="prp fr c2 fs3">￥{{$tag.staff_price}}</span>
								</div>										
							</li>
						{{/foreach}}
											</ul>								
				</div>
				<div>
					<ul class="floor-goods">
											</ul>								
				</div>
			</div>
		</div>
	</div>
	<div class="col-r fr w3">
		<div class="goods-hot">
			<h3 style="background: #FF3939;"><i></i>本周热销排行</h3>
			<ul>
			
			{{foreach from = $indextag.39.details key=k item = tag }}
				<li>
					<div class="p-detail" {{if  $k > 0 }}  style="display: none;" {{/if}}>
						<div class="pi">
								<a href="/b-{{$tag.as_name}}/detail{{$tag.goods_id}}.html" target="_blank"><img _src="{{$tag.goods_img|replace:'.':'_180_180.'}}"  alt="{{$tag.goods_name}}" height="175"/></a>
						</div>
						<div class="pt">
							<a class="c3" href="/b-{{$tag.as_name}}/detail{{$tag.goods_id}}.html" target="_blank">{{$tag.goods_name}}</a>
						</div>
						<div class="pp">
							<span class="prp c2 fs3">￥{{$tag.staff_price}}</span>
						</div>										
					</div>
					<div class="p-title" {{if  $k eq 0 }}  style="display: none;" {{/if}} >{{$tag.goods_name}}</div>
				</li>	
			{{/foreach}}	
			</ul>
			
			<div style="height: 180px;overflow: hidden;">
				<a href="/b-zhengguanzhuang/detail1569.html"><img alt="正官庄"  _src="http://img.1jiankang.com/upload/uploadify/20130916_01f99731c9205353.jpg"/></a>
			</div>
			
		</div>
	</div>
</div>
<style>
#wgt-index_floor5 .tab-h-c a{
	background: #FF3939;
}	
</style>
</div>